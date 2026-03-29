<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id', 'desc')->get();
        return view('dashboard.koordinator.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'roleId' => 'required|in:1,2',
            'positionId' => 'required|in:1,2,3',
        ];

        if (in_array($request->positionId, [1, 2, 3])) {
            $rules['nip'] = 'required|string|max:30|unique:users,nip';
        }

        if ($request->positionId == 1 || ($request->roleId == 1 && $request->positionId == 3)) {
            $rules['email'] = 'required|email|unique:users,email';
            $rules['password'] = 'required|min:6';
        }

        $validated = $request->validate($rules);

        $user = User::create([
            'name' => $validated['name'],
            'nip' => $validated['nip'] ?? null,
            'email' => $validated['email'] ?? null,
            'password' => !empty($validated['password']) ? Hash::make($validated['password']) : null,
            'roleId' => $validated['roleId'],
            'positionId' => $validated['positionId'],
        ]);

        if ($validated['positionId'] == 2) {
            Dosen::create([
                'userId' => $user->id,
                'name' => $user->name,
                'nik' => $user->nip,
            ]);
        } elseif ($validated['positionId'] == 3) {
            Mahasiswa::create([
                'userId' => $user->id,
                'name' => $user->name,
                'nim' => $user->nip,
            ]);
        }

        return back()->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'roleId' => 'required|in:1,2',
            'positionId' => 'required|in:1,2,3',
        ];

        if (in_array($request->positionId, [1, 2, 3])) {
            $rules['nip'] = 'required|string|max:30|unique:users,nip,' . $user->id;
        }

        if ($request->positionId == 1 || ($request->roleId == 1 && $request->positionId == 3)) {
            $rules['email'] = 'required|email|unique:users,email,' . $user->id;
            if ($request->filled('password')) {
                $rules['password'] = 'min:6';
            }
        }

        $validated = $request->validate($rules);

        $updateData = [
            'name' => $validated['name'],
            'nip' => $validated['nip'] ?? null,
            'email' => $validated['email'] ?? null,
            'roleId' => $validated['roleId'],
            'positionId' => $validated['positionId'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        if ($validated['positionId'] == 2) {
            Dosen::updateOrCreate(
                ['userId' => $user->id],
                ['name' => $user->name, 'nik' => $user->nip]
            );
        } elseif ($validated['positionId'] == 3) {
            Mahasiswa::updateOrCreate(
                ['userId' => $user->id],
                ['name' => $user->name, 'nim' => $user->nip]
            );
        }

        return back()->with('success', 'Data pengguna berhasil diubah!');
    }


    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->back()->with('success', 'Data pengguna berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data pengguna!');
        }
    }
}
