<?php

namespace App\Http\Controllers;

use App\Models\JadwalDosen;
use App\Models\Dosen;
use Illuminate\Http\Request;

class JadwalDosenController extends Controller
{
    public function index()
    {
        $jadwals = JadwalDosen::with('dosen')->orderBy('id', 'desc')->get();
        $dosens = Dosen::orderBy('name', 'asc')->get();

        return view('jadwal-dosen.index', compact('jadwals', 'dosens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'userId' => 'required|exists:dosen,userId',
            'hari'   => 'required|string|max:50',
            'jam'    => 'required|string|max:50',
            'status' => 'required|in:Kosong,Terisi',
        ]);

        JadwalDosen::create([
            'userId' => $request->userId,
            'hari'   => $request->hari,
            'jam'    => $request->jam,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Jadwal dosen berhasil ditambahkan!');
    }

    public function update(Request $request, JadwalDosen $jadwal)
    {
        $request->validate([
            'userId' => 'required|exists:dosen,userId',
            'hari'   => 'required|string|max:50',
            'jam'    => 'required|string|max:50',
            'status' => 'required|in:Kosong,Terisi',
        ]);

        $jadwal->update([
            'userId' => $request->userId,
            'hari'   => $request->hari,
            'jam'    => $request->jam,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Data jadwal dosen berhasil diperbarui!');
    }

    public function destroy(JadwalDosen $jadwal)
    {
        try {
            $jadwal->delete();
            return redirect()->back()->with('success', 'Data jadwal dosen berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data jadwal dosen!');
        }
    }
}
