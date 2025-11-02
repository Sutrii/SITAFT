<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id', 'desc')->get();
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'roleId' => 'required|in:1,2',
            'positionId' => 'required|in:1,2,3',
        ];

        // Kondisi lama tetap + tambahan Viewer + Mahasiswa
        if (
            $request->positionId == 1 ||
            ($request->roleId == 1 && $request->positionId == 3)
        ) {
            $rules['email'] = 'required|email|unique:users,email';
            $rules['password'] = 'required|min:6';
        }

        $validated = $request->validate($rules);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'password' => isset($validated['password']) ? Hash::make($validated['password']) : null,
            'roleId' => $validated['roleId'],
            'positionId' => $validated['positionId'],
        ]);

        return redirect()->back()->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'roleId' => 'required|in:1,2',
            'positionId' => 'required|in:1,2,3',
        ];

        if (
            $request->positionId == 1 ||
            ($request->roleId == 1 && $request->positionId == 3)
        ) {
            $rules['email'] = 'required|email|unique:users,email,' . $user->id;
            if ($request->filled('password')) {
                $rules['password'] = 'min:6';
            }
        }

        $validated = $request->validate($rules);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'roleId' => $validated['roleId'],
            'positionId' => $validated['positionId'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->back()->with('success', 'Data pengguna berhasil diubah!');
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
