<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Identifier (NIP / NIM / NIK)
            $nomorInduk = rtrim(trim($row['nomor_induk'] ?? ''), '.0');
            // Jika kosong, lompat ke baris berikutnya
            if(empty($row['nama']) || empty($nomorInduk)) {
                continue; 
            }

            // Parsing Posisi (Mahasiswa / Dosen / Koordinator)
            $posisiStr = strtolower(trim($row['posisi'] ?? ''));
            $positionId = 3; // Default ke Mahasiswa
            if ($posisiStr === 'koordinator') {
                $positionId = 1;
            } else if ($posisiStr === 'dosen') {
                $positionId = 2;
            }

            // Parsing Role (Admin / Viewer)
            $roleStr = strtolower(trim($row['role'] ?? ''));
            $roleId = 2; // Default Viewer
            if ($roleStr === 'admin') {
                $roleId = 1;
            }
            
            // Password Default (auto-generate dari Nomor Induk jika dikosongkan)
            $passwordStr = trim($row['password'] ?? '');
            if(empty($passwordStr)) {
                $passwordStr = $nomorInduk;
            }

            // Proses Penyimpanan / Update
            $user = User::updateOrCreate(
                ['nip' => $nomorInduk],
                [
                    'name' => trim($row['nama']),
                    'email' => trim($row['email'] ?? null) ?: null,
                    'password' => Hash::make($passwordStr),
                    'roleId' => $roleId,
                    'positionId' => $positionId,
                ]
            );

            // Sinkronisasi otomatis ke tabel sub-profil terkait
            if ($positionId == 2) {
                Dosen::updateOrCreate(
                    ['userId' => $user->id],
                    ['name' => $user->name, 'nik' => $user->nip]
                );
            } elseif ($positionId == 3) {
                Mahasiswa::updateOrCreate(
                    ['userId' => $user->id],
                    ['name' => $user->name, 'nim' => $user->nip]
                );
            }
        }
    }
}
