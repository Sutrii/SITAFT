<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;

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

        $user = \App\Models\User::whereRaw('LOWER(TRIM(name)) = ?', [strtolower(trim($request->name))])->first();

        if (!$user) {
            $user = \App\Models\User::create([
                'name'        => $request->name,
                'email'       => null,
                'password'    => null,
                'roleId'      => 1,
                'positionId'  => 3,
            ]);
        }

        Mahasiswa::create([
            'userId' => $user->id,
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

        if ($mahasiswa->user) {
            $mahasiswa->user->update([
                'name' => $request->name,
            ]);
        }

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
