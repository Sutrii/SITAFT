<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Mahasiswa;
use App\Models\Skripsi;
use App\Models\PendaftaranSeminar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MahasiswaDashboardController extends Controller
{
    /**
     * Display mahasiswa dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        $registrationStatus = [
            'seminar_proposal' => null,
            'seminar_hasil' => null,
            'sidang_akhir' => null,
        ];

        if ($mahasiswa) {
            $pendaftarans = \App\Models\PendaftaranSeminar::where('mahasiswa_id', $mahasiswa->id)->get();

            foreach ($pendaftarans as $pendaftaran) {
                switch ($pendaftaran->jenis_seminar) {
                    case 'seminar_proposal':
                        $registrationStatus['seminar_proposal'] = $pendaftaran;
                        break;
                    case 'seminar_hasil':
                        $registrationStatus['seminar_hasil'] = $pendaftaran;
                        break;
                    case 'sidang_akhir':
                        $registrationStatus['sidang_akhir'] = $pendaftaran;
                        break;
                }
            }
        }

        return view('dashboard.mahasiswa.index', compact('mahasiswa', 'registrationStatus'));
    }

    /**
     * Display daftar seminar page with 3 seminar options
     * Detects if mahasiswa has already registered for each seminar type
     */
    public function daftarSeminar()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        // Check registration status for each seminar type
        $registrationStatus = [
            'seminar_proposal' => null,
            'seminar_hasil' => null,
            'sidang_akhir' => null,
        ];

        if ($mahasiswa) {
            $pendaftarans = \App\Models\PendaftaranSeminar::where('mahasiswa_id', $mahasiswa->id)->get();

            foreach ($pendaftarans as $pendaftaran) {
                switch ($pendaftaran->jenis_seminar) {
                    case 'seminar_proposal':
                        $registrationStatus['seminar_proposal'] = $pendaftaran;
                        break;
                    case 'seminar_hasil':
                        $registrationStatus['seminar_hasil'] = $pendaftaran;
                        break;
                    case 'sidang_akhir':
                        $registrationStatus['sidang_akhir'] = $pendaftaran;
                        break;
                }
            }
        }

        $dosens = \App\Models\Dosen::all();

        return view('dashboard.mahasiswa.daftar-seminar', compact('mahasiswa', 'registrationStatus', 'dosens'));
    }

    /**
     * Store Seminar Proposal Registration
     */
    public function storeSeminarProposal(Request $request)
    {
        $request->validate([
            'nip' => 'required',
            'nama' => 'required',
            'pembimbing_1' => 'required|exists:dosen,id',
            'pembimbing_2' => 'required|exists:dosen,id',
            'judul_skripsi' => 'required|string',
            'file_krs' => 'nullable|mimes:pdf|max:10240',
            'file_pengesahan' => 'required|mimes:pdf|max:10240',
            'file_draft_proposal' => 'required|mimes:pdf|max:10240',
            'no_hp' => 'required|string',
            'no_registrasi' => 'required|string'
        ]);

        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            $mahasiswa = Mahasiswa::create([
                'userId' => $user->id,
                'name' => $request->nama,
                'nim' => $request->nip, // map nip to nim
            ]);
        } else {
            $mahasiswa->update([
                'name' => $request->nama,
                'nim' => $request->nip, // map nip to nim
            ]);
        }

        // Handle File Uploads
        $filePaths = [];
        if ($request->hasFile('file_krs')) {
            $filePaths['krs'] = $request->file('file_krs')->store('persyaratan/krs', 'public');
        }
        if ($request->hasFile('file_pengesahan')) {
            $filePaths['pengesahan'] = $request->file('file_pengesahan')->store('persyaratan/pengesahan', 'public');
        }
        if ($request->hasFile('file_draft_proposal')) {
            $filePaths['draft_proposal'] = $request->file('file_draft_proposal')->store('persyaratan/draft_proposal', 'public');
        }

        // Create or Update Skripsi Data
        $skripsi = Skripsi::firstOrCreate(
            ['nama_mahasiswa' => $request->nama],
            [
                'judul_skripsi' => $request->judul_skripsi,
                'dosen_pembimbing_1' => $request->pembimbing_1,
                'dosen_pembimbing_2' => $request->pembimbing_2,
            ]
        );

        // Update just in case the existing Skripsi differs
        $skripsi->update([
            'judul_skripsi' => $request->judul_skripsi,
            'dosen_pembimbing_1' => $request->pembimbing_1,
            'dosen_pembimbing_2' => $request->pembimbing_2,
        ]);

        // Insert Registration into pendaftaran_seminars
        PendaftaranSeminar::create([
            'mahasiswa_id' => $mahasiswa->id,
            'skripsi_id' => $skripsi->id,
            'nomor_registrasi' => $request->no_registrasi,
            'no_hp' => $request->no_hp,
            'jenis_seminar' => 'seminar_proposal',
            'status' => 'pending',
            'file_persyaratan' => json_encode($filePaths),
        ]);

        return back()->with('success', 'Pendaftaran seminar proposal berhasil disubmit.');
    }

    /**
     * Display download berita acara page
     */
    public function downloadBeritaAcara()
    {
        return view('dashboard.mahasiswa.download-berita-acara');
    }
}
