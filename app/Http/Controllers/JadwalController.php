<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Skripsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwals = Jadwal::with(['mahasiswa', 'skripsi', 'dosen1', 'dosen2'])
            ->orderBy('id', 'desc')->get();

        $mahasiswas = Mahasiswa::orderBy('name')->get();
        $dosens = Dosen::orderBy('name')->get();
        $skripsis = Skripsi::orderBy('judul_skripsi')->get();

        return view('jadwal.index', compact('jadwals', 'mahasiswas', 'dosens', 'skripsis'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->roleId == 1 && Auth::user()->positionId == 3) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menambah jadwal.');
        }

        $request->validate([
            'skripsiId' => 'required|exists:skripsi,id',
            'mahasiswaId' => 'required|exists:mahasiswa,id',
            'dosenId1' => 'required|exists:dosen,id',
            'dosenId2' => 'required|exists:dosen,id',
            'jadwal_seminar' => 'required|date',
            'status' => 'required|in:Seminar Proposal,Seminar Hasil,Sidang Akhir',
        ]);

        Jadwal::create($request->all());

        return redirect()->back()->with('success', 'Jadwal tugas akhir berhasil ditambahkan!');
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        if (Auth::user()->roleId == 1 && Auth::user()->positionId == 3) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengubah jadwal.');
        }

        $request->validate([
            'skripsiId' => 'required|exists:skripsi,id',
            'mahasiswaId' => 'required|exists:mahasiswa,id',
            'dosenId1' => 'required|exists:dosen,id',
            'dosenId2' => 'required|exists:dosen,id',
            'jadwal_seminar' => 'required|date',
            'status' => 'required|in:Seminar Proposal,Seminar Hasil,Sidang Akhir',
        ]);

        $jadwal->update($request->all());

        return redirect()->back()->with('success', 'Jadwal berhasil diperbarui!');
    }

    public function destroy(Jadwal $jadwal)
    {
        if (Auth::user()->roleId == 1 && Auth::user()->positionId == 3) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menghapus jadwal.');
        }

        try {
            $jadwal->delete();
            return redirect()->back()->with('success', 'Jadwal berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus jadwal!');
        }
    }

    public function sharedView($title, $from, $to)
    {
        $jadwals = Jadwal::with(['mahasiswa', 'skripsi', 'dosen1', 'dosen2'])
            ->whereDate('jadwal_seminar', '>=', $from)
            ->whereDate('jadwal_seminar', '<=', $to)
            ->orderBy('jadwal_seminar', 'asc')
            ->get();

        return view('jadwal.shared', compact('jadwals', 'title', 'from', 'to'));
    }
}
