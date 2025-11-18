<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
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

        $mahasiswas = Mahasiswa::orderBy('name')->get();
        $dosens = Dosen::orderBy('name')->get();
        $skripsis = Skripsi::orderBy('judul_skripsi')->get();

        return view('jadwal.index', compact('jadwals', 'mahasiswas', 'dosens', 'skripsis'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->roleId == 1 && Auth::user()->positionId == 3) {
            return back()->with('error', 'Anda tidak memiliki izin untuk menambah jadwal.');
        }

        $validated = $request->validate([
            'skripsiId' => 'required|exists:skripsi,id',
            'mahasiswaId' => 'required|exists:mahasiswa,id',
            'dosenId1' => 'required|exists:dosen,id',
            'dosenId2' => 'required|exists:dosen,id|different:dosenId1',
            'jadwal_seminar' => 'required|date',
            'jadwal_seminar_selesai' => 'required|date|after_or_equal:jadwal_seminar',
            'ruang' => 'nullable|string|max:255',
            'status' => 'required|in:Seminar Proposal,Seminar Hasil,Sidang Akhir',
        ]);

        Jadwal::create($validated);

        return back()->with('success', 'Jadwal tugas akhir berhasil ditambahkan!');
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        if (Auth::user()->roleId == 1 && Auth::user()->positionId == 3) {
            return back()->with('error', 'Anda tidak memiliki izin untuk mengubah jadwal.');
        }

        $validated = $request->validate([
            'skripsiId' => 'required|exists:skripsi,id',
            'mahasiswaId' => 'required|exists:mahasiswa,id',
            'dosenId1' => 'required|exists:dosen,id',
            'dosenId2' => 'required|exists:dosen,id|different:dosenId1',
            'jadwal_seminar' => 'required|date',
            'jadwal_seminar_selesai' => 'required|date|after_or_equal:jadwal_seminar',
            'ruang' => 'nullable|string|max:255',
            'status' => 'required|in:Seminar Proposal,Seminar Hasil,Sidang Akhir',
        ]);

        $jadwal->update($validated);

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
        ->orderBy('jadwal_seminar', 'asc');

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $jadwals = $query->get();

        return view('jadwal.shared', compact('jadwals', 'title', 'from', 'to', 'status'));
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
}
