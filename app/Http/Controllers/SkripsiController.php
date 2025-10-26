<?php

namespace App\Http\Controllers;

use App\Models\Skripsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkripsiController extends Controller
{
    public function index()
    {
        // ambil data terbaru duluan
        $skripsis = Skripsi::orderBy('id', 'desc')->get();
        return view('skripsi.index', compact('skripsis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_mahasiswa' => 'required|string|max:255',
            'judul_skripsi'  => 'required|string|max:255',
            'bidang'         => 'required|string|max:255',
        ]);

        Skripsi::create([
            'nama_mahasiswa' => $request->nama_mahasiswa,
            'judul_skripsi'  => $request->judul_skripsi,
            'bidang'         => $request->bidang,
        ]);

        return redirect()->back()->with('success', 'Data skripsi berhasil ditambahkan!');
    }

    public function update(Request $request, Skripsi $skripsi)
    {
        $request->validate([
            'nama_mahasiswa' => 'required|string|max:255',
            'judul_skripsi'  => 'required|string|max:255',
            'bidang'         => 'required|string|max:255',
        ]);

        $skripsi->update([
            'nama_mahasiswa' => $request->nama_mahasiswa,
            'judul_skripsi'  => $request->judul_skripsi,
            'bidang'         => $request->bidang,
        ]);

        return redirect()->back()->with('success', 'Data skripsi berhasil diperbarui!');
    }

    public function destroy(Skripsi $skripsi)
    {
        try {
            $skripsi->delete();
            return redirect()->back()->with('success', 'Data skripsi berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data skripsi!');
        }
    }
}
