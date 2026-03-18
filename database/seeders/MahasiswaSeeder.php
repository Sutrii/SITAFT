<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create dosen/koordinator user
        User::create([
            'name' => 'Dr. Ahmad Supriyan, M.T.',
            'email' => 'ahmad.supriyan@unsyiah.ac.id',
            'nip' => '198505101012051001',
            'password' => Hash::make('password123'),
            'roleId' => 2,
            'positionId' => 1,
        ]);

        // Create mahasiswa users
        User::create([
            'name' => 'Muhammad Rizki Pratama',
            'email' => 'rizki.pratama@student.unsyiah.ac.id',
            'nip' => '2206110001',
            'password' => Hash::make('password123'),
            'roleId' => 1,
            'positionId' => 3,
        ]);

        User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti.nurhaliza@student.unsyiah.ac.id',
            'nip' => '2206110002',
            'password' => Hash::make('password123'),
            'roleId' => 1,
            'positionId' => 3,
        ]);

        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@student.unsyiah.ac.id',
            'nip' => '2206110003',
            'password' => Hash::make('password123'),
            'roleId' => 1,
            'positionId' => 3,
        ]);
    }
}

