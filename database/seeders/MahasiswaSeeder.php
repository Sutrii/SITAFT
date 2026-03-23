<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Koordinator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 2 Koordinator Users
        $koordinators = [
            [
                'name' => 'Dr. Ahmad Supriyan, M.T.',
                'email' => 'ahmad.supriyan@unsyiah.ac.id',
                'nip' => '198505101012051001',
                'roleId' => 2,
                'positionId' => 1,
            ],
            [
                'name' => 'Dr. Diana Kusuma, S.T., M.Eng.',
                'email' => 'diana.kusuma@unsyiah.ac.id',
                'nip' => '198708152014042002',
                'roleId' => 2,
                'positionId' => 1,
            ]
        ];

        foreach ($koordinators as $k) {
            $user = User::create([
                'name' => $k['name'],
                'email' => $k['email'],
                'nip' => $k['nip'],
                'password' => Hash::make('password123'),
                'roleId' => $k['roleId'],
                'positionId' => $k['positionId'],
            ]);

            // Profil Koordinator otomatis dibuat
            Koordinator::create([
                'userId' => $user->id,
                'name' => $k['name'],
                'nip' => $k['nip'],
            ]);
        }

        // 3 Mahasiswa Users
        $mahasiswas = [
            [
                'name' => 'Muhammad Rizki Pratama',
                'email' => 'rizki.pratama@student.unsyiah.ac.id',
                'nim' => '2206110001',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@student.unsyiah.ac.id',
                'nim' => '2206110002',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@student.unsyiah.ac.id',
                'nim' => '2206110003',
            ]
        ];

        foreach ($mahasiswas as $m) {
            $user = User::create([
                'name' => $m['name'],
                'email' => $m['email'],
                'nip' => $m['nim'], // Aplikasi memetakan nim di kolom nip
                'password' => Hash::make('password123'),
                'roleId' => 1,
                'positionId' => 3,
            ]);

            // Profil Mahasiswa otomatis dibuat
            Mahasiswa::create([
                'userId' => $user->id,
                'name' => $m['name'],
                'nim' => $m['nim'],
            ]);
        }
    }
}
