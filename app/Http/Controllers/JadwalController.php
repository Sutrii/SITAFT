<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\JadwalDosen; 
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Skripsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\User;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwals = Jadwal::with([
                'mahasiswa',
                'skripsi.dosen1',
                'skripsi.dosen2',
                'dosen1',
                'dosen2'
            ])
            ->orderBy('id', 'desc')
            ->get();

        $accPendaftaran = \App\Models\PendaftaranSeminar::where('status', 'acc')->get();
        $mahasiswaIds = $accPendaftaran->pluck('mahasiswa_id')->unique();
        $skripsiIds = $accPendaftaran->pluck('skripsi_id')->unique();

        $mahasiswas = Mahasiswa::whereIn('id', $mahasiswaIds)->orderBy('name')->get()->map(function($m) use ($accPendaftaran) {
            $latest = $accPendaftaran->where('mahasiswa_id', $m->id)->sortByDesc('created_at')->first();
            $m->jenis_seminar = $latest ? $latest->jenis_seminar : null;
            return $m;
        });
        $dosens = Dosen::orderBy('name')->get();
        $skripsis = Skripsi::whereIn('id', $skripsiIds)->orderBy('judul_skripsi')->get();

        return view('dashboard.koordinator.jadwal.index', compact('jadwals', 'mahasiswas', 'dosens', 'skripsis'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->roleId == 1 && Auth::user()->positionId == 3) {
            return back()->with('error', 'Anda tidak memiliki izin untuk menambah jadwal.');
        }

        $request->validate([
            'skripsiId' => 'required|exists:skripsi,id',
            'mahasiswaId' => 'required|exists:mahasiswa,id',
            'dosenId1' => 'required|exists:dosen,id',
            'dosenId2' => 'required|exists:dosen,id|different:dosenId1',
            'tanggal_seminar' => 'required|date',
            'jam_seminar' => 'required|string',
            'ruang' => 'nullable|string|max:255',
            'status' => 'required|in:Seminar Proposal,Seminar Hasil,Sidang Akhir',
        ]);

        [$mulai, $selesai] = explode(' - ', $request->jam_seminar);
        
        $jamMulai = str_replace('.', ':', trim($mulai)) . ':00';
        $jamSelesai = str_replace('.', ':', trim($selesai)) . ':00';

        $jadwal_seminar = Carbon::parse($request->tanggal_seminar . ' ' . $jamMulai)->format('Y-m-d H:i:s');
        $jadwal_seminar_selesai = Carbon::parse($request->tanggal_seminar . ' ' . $jamSelesai)->format('Y-m-d H:i:s');

        Jadwal::create([
            'skripsiId' => $request->skripsiId,
            'mahasiswaId' => $request->mahasiswaId,
            'dosenId1' => $request->dosenId1,
            'dosenId2' => $request->dosenId2,
            'jadwal_seminar' => $jadwal_seminar,
            'jadwal_seminar_selesai' => $jadwal_seminar_selesai,
            'ruang' => $request->ruang,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Jadwal tugas akhir berhasil ditambahkan!');
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        if (Auth::user()->roleId == 1 && Auth::user()->positionId == 3) {
            return back()->with('error', 'Anda tidak memiliki izin untuk mengubah jadwal.');
        }

        $request->validate([
            'skripsiId' => 'required|exists:skripsi,id',
            'mahasiswaId' => 'required|exists:mahasiswa,id',
            'dosenId1' => 'required|exists:dosen,id',
            'dosenId2' => 'required|exists:dosen,id|different:dosenId1',
            'tanggal_seminar' => 'required|date',
            'jam_seminar' => 'required|string',
            'ruang' => 'nullable|string|max:255',
            'status' => 'required|in:Seminar Proposal,Seminar Hasil,Sidang Akhir',
        ]);

        [$mulai, $selesai] = explode(' - ', $request->jam_seminar);
        
        $jamMulai = str_replace('.', ':', trim($mulai)) . ':00';
        $jamSelesai = str_replace('.', ':', trim($selesai)) . ':00';

        $jadwal_seminar = Carbon::parse($request->tanggal_seminar . ' ' . $jamMulai)->format('Y-m-d H:i:s');
        $jadwal_seminar_selesai = Carbon::parse($request->tanggal_seminar . ' ' . $jamSelesai)->format('Y-m-d H:i:s');

        $jadwal->update([
            'skripsiId' => $request->skripsiId,
            'mahasiswaId' => $request->mahasiswaId,
            'dosenId1' => $request->dosenId1,
            'dosenId2' => $request->dosenId2,
            'jadwal_seminar' => $jadwal_seminar,
            'jadwal_seminar_selesai' => $jadwal_seminar_selesai,
            'ruang' => $request->ruang,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Jadwal berhasil diperbarui!');
    }

    public function destroy(Jadwal $jadwal)
    {
        if (Auth::user()->roleId == 1 && Auth::user()->positionId == 3) {
            return back()->with('error', 'Anda tidak memiliki izin untuk menghapus jadwal.');
        }

        try {
            $jadwal->delete();
            return back()->with('success', 'Jadwal berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus jadwal!');
        }
    }

    public function sharedView($title, $from, $to, Request $request)
    {
        $status = $request->query('status');

        $query = Jadwal::with([
            'mahasiswa',
            'skripsi.dosen1',
            'skripsi.dosen2',
            'dosen1',
            'dosen2'
        ])
        ->whereDate('jadwal_seminar', '>=', $from)
        ->whereDate('jadwal_seminar', '<=', $to)
        ->orderBy('jadwal_seminar', 'desc');

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $jadwals = $query->get();

        return view('dashboard.koordinator.jadwal.shared', compact('jadwals', 'title', 'from', 'to', 'status'));
    }

    public function import(Request $request)
    {
        if (Auth::user()->roleId == 1 && Auth::user()->positionId == 3) {
            return back()->with('error', 'Anda tidak memiliki izin untuk import jadwal.');
        }

        $request->validate([
            'file'   => 'required|mimes:xlsx,xls',
            'status' => 'required|in:Seminar Proposal,Seminar Hasil,Sidang Akhir',
        ]);

        $file   = $request->file('file');
        $status = $request->status;

        $spreadsheet = IOFactory::load($file);
        $sheet       = $spreadsheet->getActiveSheet();
        $rows        = $sheet->toArray(null, true, true, true);

        for ($i = 2; $i <= count($rows); $i++) {

            $row = $rows[$i];

            $nim           = trim($row['A']);
            $namaExcel     = trim($row['B']);
            $pemb1Excel    = trim($row['C']);
            $pemb2Excel    = trim($row['D']);
            $peng1Excel    = trim($row['E']);
            $peng2Excel    = trim($row['F']);
            $tanggalExcel  = trim($row['G']);
            $waktuExcel    = trim($row['H']);
            $ruang         = trim($row['I']);
            $judulExcel    = trim($row['J']);

            if (!$nim) {
                continue;
            }

            $mahasiswa = Mahasiswa::where('nim', $nim)->first();

            if (!$mahasiswa) {
                $user = User::whereRaw('LOWER(TRIM(name)) = ?', [strtolower(trim($namaExcel))])->first();

                if (!$user) {
                    $user = User::create([
                        'name'       => $namaExcel,
                        'email'      => null,
                        'password'   => null,
                        'roleId'     => 1,
                        'positionId' => 3,
                    ]);
                }

                $mahasiswa = Mahasiswa::create([
                    'userId' => $user->id,
                    'name'   => $namaExcel,
                    'nim'    => $nim,
                ]);
            }

            $skripsi = Skripsi::whereRaw('LOWER(TRIM(nama_mahasiswa)) = ?', [
                strtolower(trim($mahasiswa->name))
            ])->first();

            if (!$skripsi) {
                $pemb1 = Dosen::where('name', $pemb1Excel)->first();
                $pemb2 = Dosen::where('name', $pemb2Excel)->first();

                if (!$pemb1) {
                    $pemb1 = $this->findOrCreateDosen($pemb1Excel);
                }
                if (!$pemb2) {
                    $pemb2 = $this->findOrCreateDosen($pemb2Excel);
                }

                if (!$pemb1 || !$pemb2) {
                    continue;
                }

                $skripsi = Skripsi::create([
                    'nama_mahasiswa'      => $mahasiswa->name,
                    'judul_skripsi'       => $judulExcel,
                    'dosen_pembimbing_1'  => $pemb1->id,
                    'dosen_pembimbing_2'  => $pemb2->id,
                    'bidang'              => null,
                ]);
            } 

            if (!$skripsi->dosen1 || $skripsi->dosen1->name !== $pemb1Excel) {
                continue;
            }
            if (!$skripsi->dosen2 || $skripsi->dosen2->name !== $pemb2Excel) {
                continue;
            }

            $penguji1 = Dosen::where('name', $peng1Excel)->first();
            if (!$penguji1) {
                $penguji1 = $this->findOrCreateDosen($peng1Excel);
            }

            $penguji2 = Dosen::where('name', $peng2Excel)->first();
            if (!$penguji2) {
                $penguji2 = $this->findOrCreateDosen($peng2Excel);
            }

            try {
                $tanggalClean = preg_replace('/^[^,]+,\s*/', '', $tanggalExcel);
                $tanggal      = Carbon::parse($tanggalClean)->format('Y-m-d');
            } catch (\Exception $e) {
                continue;
            }

            $times = explode('-', $waktuExcel);
            if (count($times) != 2) {
                continue;
            }

            $mulai   = str_replace('.', ':', trim($times[0]));
            $selesai = str_replace('.', ':', trim(str_replace('WIB', '', $times[1])));

            $datetimeMulai   = $tanggal . ' ' . $mulai . ':00';
            $datetimeSelesai = $tanggal . ' ' . $selesai . ':00';

            $cekExisting = Jadwal::where([
                'skripsiId' => $skripsi->id,
                'mahasiswaId' => $mahasiswa->id,
                'dosenId1' => $penguji1->id,
                'dosenId2' => $penguji2->id,
            ])
            ->where('jadwal_seminar', $datetimeMulai)
            ->where('jadwal_seminar_selesai', $datetimeSelesai)
            ->first();
            
            if ($cekExisting) {
                continue;
            }

            Jadwal::create([
                'skripsiId'              => $skripsi->id,
                'mahasiswaId'            => $mahasiswa->id,
                'dosenId1'               => $penguji1->id,
                'dosenId2'               => $penguji2->id,
                'jadwal_seminar'         => $datetimeMulai,
                'jadwal_seminar_selesai' => $datetimeSelesai,
                'ruang'                  => $ruang,
                'status'                 => $status,
            ]);
        }

        return back()->with('success', 'Import jadwal berhasil!');
    }

    private function findOrCreateMahasiswa(string $nim, string $nama)
    {
        $nim = trim((string) $nim);
        $nama = trim($nama);

        if ($nim === '' && $nama === '') {
            return null;
        }

        $mahasiswa = \App\Models\Mahasiswa::where('nim', $nim)->first();
        if ($mahasiswa) return $mahasiswa;

        $user = \App\Models\User::whereRaw('LOWER(TRIM(name)) = ?', [strtolower($nama)])->first();
        if (!$user) {
            $user = \App\Models\User::create([
                'name'       => $nama,
                'email'      => null,
                'password'   => null,
                'roleId'     => 1,
                'positionId' => 3,
            ]);
        }

        return \App\Models\Mahasiswa::create([
            'userId' => $user->id,
            'name'   => $nama,
            'nim'    => $nim,
        ]);
    }

    private function findOrCreateDosen(string $nama)
    {
        $nama = trim($nama);
        if ($nama === '') return null;

        $dosen = \App\Models\Dosen::whereRaw('LOWER(TRIM(name)) = ?', [strtolower($nama)])->first();
        if ($dosen) return $dosen;

        $user = \App\Models\User::whereRaw('LOWER(TRIM(name)) = ?', [strtolower($nama)])->first();
        if (!$user) {
            $user = \App\Models\User::create([
                'name'       => $nama,
                'email'      => null,
                'password'   => null,
                'roleId'     => 1,
                'positionId' => 2,
            ]);
        }

        return \App\Models\Dosen::create([
            'userId' => $user->id,
            'name'   => $nama,
            'nik'    => null,
            'bidang' => null,
        ]);
    }

    private function autoAssignPenguji(Skripsi $skripsi, string $mulai, string $selesai)
    {
        $pemb1 = $skripsi->dosen_pembimbing_1;
        $pemb2 = $skripsi->dosen_pembimbing_2;

        $hari = Carbon::parse($mulai)->locale('id')->dayName;
        $seminarStart = Carbon::parse($mulai)->format('H:i');
        $seminarEnd   = Carbon::parse($selesai)->format('H:i');

        // Get available time slots from JadwalDosen - hanya dosen yang punya jadwal terdaftar
        $jadwalKosongUserId = JadwalDosen::where('hari', $hari)
            ->where('status', 'Kosong')
            ->get()
            ->filter(function ($jd) use ($seminarStart, $seminarEnd) {

                [$mulai, $selesai] = explode(' - ', $jd->jam);

                $jamMulai = str_replace('.', ':', trim($mulai));
                $jamSelesai = str_replace('.', ':', trim($selesai));

                return !(
                    $seminarEnd < $jamMulai ||
                    $seminarStart > $jamSelesai
                );
            })
            ->pluck('userId')
            ->toArray();

        // Jika tidak ada slot kosong sama sekali, return error
        if (empty($jadwalKosongUserId)) {
            return [
                'dosenId1' => null,
                'dosenId2' => null,
                'error' => 'Tidak ada dosen yang memiliki jadwal kosong pada hari/jam ini'
            ];
        }

        // Prioritas 1: Cari dosen dengan bidang yang sama DAN punya jadwal kosong
        $bidangDosens = Dosen::where('bidang', $skripsi->bidang)
            ->whereNotIn('id', [$pemb1, $pemb2])
            ->whereIn('userId', $jadwalKosongUserId)
            ->get();

        if ($bidangDosens->count() >= 2) {
            return [
                'dosenId1' => $bidangDosens[0]->id,
                'dosenId2' => $bidangDosens[1]->id
            ];
        }

        // Prioritas 2: Jika bidang tidak cukup, ambil dari dosen lain yang punya jadwal kosong
        $sisaBidang = $bidangDosens->count() > 0 ? $bidangDosens->count() : 0;
        $kurang = 2 - $sisaBidang;

        $dosenLainUserId = array_diff($jadwalKosongUserId, $bidangDosens->pluck('userId')->toArray());
        
        $dosenLain = Dosen::whereNotIn('id', [$pemb1, $pemb2])
            ->whereNotIn('id', $bidangDosens->pluck('id')->toArray())
            ->whereIn('userId', $dosenLainUserId)
            ->take($kurang)
            ->get();

        $hasilAkhir = $bidangDosens->merge($dosenLain);

        if ($hasilAkhir->count() >= 2) {
            return [
                'dosenId1' => $hasilAkhir[0]->id,
                'dosenId2' => $hasilAkhir[1]->id
            ];
        }

        // Jika masih kurang dari 2 orang, cukup return yang ada
        if ($hasilAkhir->count() == 1) {
            return [
                'dosenId1' => $hasilAkhir[0]->id,
                'dosenId2' => null,
                'error' => 'Hanya ada 1 dosen yang tersedia'
            ];
        }

        return [
            'dosenId1' => null,
            'dosenId2' => null,
            'error' => 'Tidak cukup dosen yang tersedia'
        ];
    }

    public function autoPengujiBySkripsi($skripsiId)
    {
        $skripsi = Skripsi::with(['dosen1', 'dosen2'])->findOrFail($skripsiId);

        $mulai = now()->format('Y-m-d 00:00');
        $selesai = now()->format('Y-m-d 00:30');

        $hasil = $this->autoAssignPenguji($skripsi, $mulai, $selesai);

        return response()->json([
            'penguji1' => Dosen::find($hasil['dosenId1']),
            'penguji2' => Dosen::find($hasil['dosenId2']),
        ]);
    }

    public function getAvailableSlots(Request $request, $skripsiId)
    {
        $status = $request->query('status');
        $skripsi = Skripsi::with(['dosen1', 'dosen2', 'penguji1', 'penguji2'])->findOrFail($skripsiId);

        if (!$skripsi->dosen1 || !$skripsi->dosen2) {
             return response()->json(['error' => 'Data pembimbing tidak lengkap', 'slots' => []]);
        }
        
        $isSeminarHasil = ($status === 'Seminar Hasil' || $status === 'Sidang Akhir') && $skripsi->penguji1 && $skripsi->penguji2;

        $allDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $masterSlots = [
            '08.00 - 08.50', '08.50 - 09.40', '09.40 - 10.30', '10.30 - 11.20', 
            '11.20 - 12.10', '12.10 - 13.00', '13.00 - 14.00', '14.00 - 14.50', 
            '14.50 - 15.30', '15.30 - 16.30', '16.30 - 17.20', '17.20 - 18.10'
        ];

        $validSlots = [];

        foreach ($allDays as $hari) {
            $slotsP1 = JadwalDosen::where('userId', $skripsi->dosen1->userId)
                ->where('hari', $hari)
                ->where('status', 'Kosong')
                ->pluck('jam')->toArray();

            $slotsP2 = JadwalDosen::where('userId', $skripsi->dosen2->userId)
                ->where('hari', $hari)
                ->where('status', 'Kosong')
                ->pluck('jam')->toArray();

            $commonSlots = array_values(array_intersect($slotsP1, $slotsP2));

            if ($isSeminarHasil) {
                $slotsPeng1 = JadwalDosen::where('userId', $skripsi->penguji1->userId)
                    ->where('hari', $hari)->where('status', 'Kosong')->pluck('jam')->toArray();
                $slotsPeng2 = JadwalDosen::where('userId', $skripsi->penguji2->userId)
                    ->where('hari', $hari)->where('status', 'Kosong')->pluck('jam')->toArray();

                $commonSlots = array_values(array_intersect($commonSlots, $slotsPeng1, $slotsPeng2));
            }

            $consecutivePairs = [];
            for ($i = 0; $i < count($masterSlots) - 1; $i++) {
                $slotA = $masterSlots[$i];
                $slotB = $masterSlots[$i+1];
                if (in_array($slotA, $commonSlots) && in_array($slotB, $commonSlots)) {
                    $consecutivePairs[] = [$slotA, $slotB];
                }
            }

            foreach ($consecutivePairs as $pair) {
                if ($isSeminarHasil) {
                    $jamMulai = explode(' - ', $pair[0])[0];
                    $jamSelesai = explode(' - ', $pair[1])[1];
                    $combinedJam = $jamMulai . ' - ' . $jamSelesai;

                    $validSlots[] = [
                        'hari' => $hari,
                        'jam' => $combinedJam,
                        'penguji1' => $skripsi->penguji1,
                        'penguji2' => $skripsi->penguji2,
                        'available_penguji' => collect([$skripsi->penguji1, $skripsi->penguji2])
                    ];
                } else {
                    $otherDosensUserIdA = JadwalDosen::where('hari', $hari)
                        ->where('jam', $pair[0])
                        ->where('status', 'Kosong')
                        ->whereNotIn('userId', [$skripsi->dosen1->userId, $skripsi->dosen2->userId])
                        ->pluck('userId')->toArray();
                    
                    $otherDosensUserIdB = JadwalDosen::where('hari', $hari)
                        ->where('jam', $pair[1])
                        ->where('status', 'Kosong')
                        ->whereNotIn('userId', [$skripsi->dosen1->userId, $skripsi->dosen2->userId])
                        ->pluck('userId')->toArray();

                    $validPengujiIds = array_values(array_intersect($otherDosensUserIdA, $otherDosensUserIdB));

                    if (count($validPengujiIds) < 2) continue;

                    $bidangDosens = Dosen::where('bidang', $skripsi->bidang)
                        ->whereIn('userId', $validPengujiIds)
                        ->get();
                    
                    $dosenLainIds = array_diff($validPengujiIds, $bidangDosens->pluck('userId')->toArray());
                    $dosenLain = Dosen::whereIn('userId', $dosenLainIds)->get();

                    $hasil = $bidangDosens->merge($dosenLain);

                    if ($hasil->count() >= 2) {
                        $jamMulai = explode(' - ', $pair[0])[0];
                        $jamSelesai = explode(' - ', $pair[1])[1];
                        $combinedJam = $jamMulai . ' - ' . $jamSelesai;

                        $validSlots[] = [
                            'hari' => $hari,
                            'jam' => $combinedJam,
                            'penguji1' => $hasil[0],
                            'penguji2' => $hasil[1],
                            'available_penguji' => $hasil
                        ];
                    }
                }
            }
        }

        if (empty($validSlots)) {
            $msg = $isSeminarHasil 
                ? "Tidak ditemukan 2 jadwal beruntun kosong bersamaan untuk 4 Dosen (Pembimbing 1, Pembimbing 2, Penguji 1, Penguji 2)."
                : "Tidak ditemukan 2 jadwal beruntun kosong bersamaan untuk Pembimbing 1, Pembimbing 2, beserta Penguji di minggu ini.";
            return response()->json([
                'slots' => [],
                'error' => $msg
            ]);
        }

        return response()->json([
            'slots' => $validSlots
        ]);
    }

    public function getPengujiFromProposal($skripsiId)
    {
        $proposal = Jadwal::where('skripsiId', $skripsiId)
            ->where('status', 'Seminar Proposal')
            ->first();

        if (!$proposal) {
            return response()->json(['exists' => false]);
        }

        return response()->json([
            'exists' => true,
            'penguji1' => [
                'id' => $proposal->dosenId1,
                'name' => $proposal->dosen1->name ?? '-'
            ],
            'penguji2' => [
                'id' => $proposal->dosenId2,
                'name' => $proposal->dosen2->name ?? '-'
            ],
        ]);
    }

    public function getJadwalByMahasiswa($mahasiswaId)
    {
        $jadwal = Jadwal::where('mahasiswaId', $mahasiswaId)
            ->whereIn('status', ['Seminar Proposal', 'Seminar Hasil'])
            ->orderBy('jadwal_seminar', 'desc')
            ->first();

        if (!$jadwal) {
            return response()->json([
                'exists' => false
            ]);
        }

        return response()->json([
            'exists' => true,
            'jadwal_seminar' => $jadwal->jadwal_seminar,
            'jadwal_seminar_selesai' => $jadwal->jadwal_seminar_selesai,
            'status' => $jadwal->status,
        ]);
    }
}
