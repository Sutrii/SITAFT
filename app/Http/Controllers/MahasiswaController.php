<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswas = Mahasiswa::orderBy('id', 'desc')->get();
        return view('mahasiswa.index', compact('mahasiswas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nim'  => 'required|string|max:15|unique:mahasiswa,nim',
        ]);

        Mahasiswa::create([
            'userId' => Auth::id(),
            'name'   => $request->name,
            'nim'    => $request->nim,
        ]);

        return redirect()->back()->with('success', 'Mahasiswa berhasil ditambahkan!');
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nim'  => 'required|string|max:15|unique:mahasiswa,nim,' . $mahasiswa->id,
        ]);

        $mahasiswa->update([
            'name' => $request->name,
            'nim'  => $request->nim,
        ]);

        return redirect()->back()->with('success', 'Data mahasiswa berhasil diubah!');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        try {
            $mahasiswa->delete();
            return redirect()->back()->with('success', 'Data mahasiswa berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data mahasiswa!');
        }
    }
}
