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
            'nama_mahasiswa'      => 'required|string|max:255',
            'judul_skripsi'       => 'required|string|max:255',
            'bidang'              => 'required|string|max:255',
            'dosen_pembimbing_1'  => 'required|exists:dosen,id',
            'dosen_pembimbing_2'  => 'required|exists:dosen,id|different:dosen_pembimbing_1',
        ]);

        Skripsi::create([
            'nama_mahasiswa'      => $request->nama_mahasiswa,
            'judul_skripsi'       => $request->judul_skripsi,
            'bidang'              => $request->bidang,
            'dosen_pembimbing_1'  => $request->dosen_pembimbing_1,
            'dosen_pembimbing_2'  => $request->dosen_pembimbing_2,
        ]);

        return back()->with('success', 'Data skripsi berhasil ditambahkan!');
    }

    public function update(Request $request, Skripsi $skripsi)
    {
        $request->validate([
            'nama_mahasiswa'      => 'required|string|max:255',
            'judul_skripsi'       => 'required|string|max:255',
            'bidang'              => 'required|string|max:255',
            'dosen_pembimbing_1'  => 'required|exists:dosen,id',
            'dosen_pembimbing_2'  => 'required|exists:dosen,id|different:dosen_pembimbing_1',
        ]);

        $skripsi->update([
            'nama_mahasiswa'      => $request->nama_mahasiswa,
            'judul_skripsi'       => $request->judul_skripsi,
            'bidang'              => $request->bidang,
            'dosen_pembimbing_1'  => $request->dosen_pembimbing_1,
            'dosen_pembimbing_2'  => $request->dosen_pembimbing_2,
        ]);

        return back()->with('success', 'Data skripsi berhasil diperbarui!');
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

    public function getById($id)
    {
        $skripsi = Skripsi::with(['dosen1', 'dosen2'])->find($id);

        if (!$skripsi) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        return response()->json([
            'id' => $skripsi->id,
            'judul_skripsi' => $skripsi->judul_skripsi,
            'bidang' => $skripsi->bidang,
            'dosen1' => [
                'id' => $skripsi->dosen1->id ?? null,
                'name' => $skripsi->dosen1->name ?? '-'
            ],
            'dosen2' => [
                'id' => $skripsi->dosen2->id ?? null,
                'name' => $skripsi->dosen2->name ?? '-'
            ],
        ]);
    }

    public function getByMahasiswa($id)
    {
        $mhs = Mahasiswa::find($id);

        if (!$mhs) {
            return response()->json(['error' => 'Mahasiswa tidak ditemukan'], 404);
        }

        $skripsi = Skripsi::with(['dosen1', 'dosen2'])
            ->whereRaw('LOWER(TRIM(nama_mahasiswa)) = ?', [strtolower(trim($mhs->name))])
            ->first();

        if (!$skripsi) {
            return response()->json(['error' => 'Mahasiswa ini belum memiliki skripsi'], 404);
        }

        return response()->json([
            'id'    => $skripsi->id,
            'judul' => $skripsi->judul_skripsi,
            'dosen1' => $skripsi->dosen1->name ?? '-',
            'dosen2' => $skripsi->dosen2->name ?? '-',
        ]);
    }
}
