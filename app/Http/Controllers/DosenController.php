<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DosenController extends Controller
{
    public function index()
    {
        $dosens = Dosen::orderBy('id', 'desc')->get();
        return view('dosen.index', compact('dosens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'nik'    => 'required|string|max:20|unique:dosen,nik',
            'bidang' => 'required|string|max:255',
        ]);

        $user = \App\Models\User::whereRaw('LOWER(TRIM(name)) = ?', [strtolower(trim($request->name))])->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User untuk dosen ini belum ada di tabel users!');
        }

        Dosen::create([
            'userId' => $user->id,
            'name'   => $request->name,
            'nik'    => $request->nik,
            'bidang' => $request->bidang,
        ]);

        return redirect()->back()->with('success', 'Dosen berhasil ditambahkan!');
    }


    public function update(Request $request, Dosen $dosen)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'nik'    => 'required|string|max:20|unique:dosen,nik,' . $dosen->id,
            'bidang' => 'required|string|max:255',
        ]);

        $dosen->update([
            'name'   => $request->name,
            'nik'    => $request->nik,
            'bidang' => $request->bidang,
        ]);

        return redirect()->back()->with('success', 'Data dosen berhasil diubah!');
    }

    public function destroy(Dosen $dosen)
    {
        try {
            $dosen->delete();
            return redirect()->back()->with('success', 'Data dosen berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data dosen!');
        }
    }
}
