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
}
