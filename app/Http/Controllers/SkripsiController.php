<?php

namespace App\Http\Controllers;

use App\Models\Skripsi;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkripsiController extends Controller
{
    public function index()
    {
        $skripsis = Skripsi::orderBy('id', 'desc')->get();
        $mahasiswas = Mahasiswa::orderBy('name')->get();
        $dosens = Dosen::orderBy('name')->get();
        return view('skripsi.index', compact('skripsis', 'mahasiswas', 'dosens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_mahasiswa' => 'required|string|max:255',
            'judul_skripsi'  => 'required|string|max:255',
            'bidang'         => 'required|string|max:255',
            'dosen_pembimbing_1' => 'required|exists:dosens,id',
            'dosen_pembimbing_2' => 'required|exists:dosens,id',
        ]);

        Skripsi::create([
            'nama_mahasiswa' => $request->nama_mahasiswa,
            'judul_skripsi'  => $request->judul_skripsi,
            'bidang'         => $request->bidang,
            'dosen_pembimbing_1' => $request->dosen_pembimbing_1,
            'dosen_pembimbing_2' => $request->dosen_pembimbing_2,
        ]);

        return redirect()->back()->with('success', 'Data skripsi berhasil ditambahkan!');
    }

    public function update(Request $request, Skripsi $skripsi)
    {
        $request->validate([
            'nama_mahasiswa' => 'required|string|max:255',
            'judul_skripsi'  => 'required|string|max:255',
            'bidang'         => 'required|string|max:255',
            'dosen_pembimbing_1' => 'required|string|max:255',
            'dosen_pembimbing_2' => 'required|string|max:255',
        ]);

        $skripsi->update([
            'nama_mahasiswa' => $request->nama_mahasiswa,
            'judul_skripsi'  => $request->judul_skripsi,
            'bidang'         => $request->bidang,
            'dosen_pembimbing_1' => $request->dosen_pembimbing_1,
            'dosen_pembimbing_2' => $request->dosen_pembimbing_2,
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
