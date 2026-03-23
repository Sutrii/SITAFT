<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Dosen;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data 5 Dosen Pembimbing / Penguji
        $dosens = [
            [
                'name' => 'Prof. Dr. Ir. Budi Wibowo, M.T.',
                'email' => 'budi.wibowo@unsyiah.ac.id',
                'nik' => '197001011995121001',
                'bidang' => 'Manajemen Kualitas',
            ],
            [
                'name' => 'Dr. Eng. Siti Aisyah, S.T., M.T.',
                'email' => 'siti.aisyah@unsyiah.ac.id',
                'nik' => '197505152001122001',
                'bidang' => 'Sistem Informasi Industri',
            ],
            [
                'name' => 'Rahmat Hidayat, S.T., M.Sc.',
                'email' => 'rahmat.hidayat@unsyiah.ac.id',
                'nik' => '198211232008121002',
                'bidang' => 'Ergonomi',
            ],
            [
                'name' => 'Ir. Dewi Susanti, M.Eng.',
                'email' => 'dewi.susanti@unsyiah.ac.id',
                'nik' => '198007202005012001',
                'bidang' => 'Logistik dan Rantai Pasok',
            ],
            [
                'name' => 'Dr. Anton Setiawan, M.T.',
                'email' => 'anton.setiawan@unsyiah.ac.id',
                'nik' => '197808082006041005',
                'bidang' => 'Sistem Produksi',
            ]
        ];

        foreach ($dosens as $d) {
            $user = User::create([
                'name' => $d['name'],
                'email' => $d['email'],
                'nip' => $d['nik'], // Aplikasi SITAFT memetakan NIK/NIP di kolom nip
                'password' => Hash::make('password123'),
                'roleId' => 3, // Dosen
                'positionId' => 2, // Posisi fungsional (bebas, disesuaikan)
            ]);

            // Profil Dosen otomatis dibuat
            Dosen::create([
                'userId' => $user->id,
                'name' => $d['name'],
                'nik' => $d['nik'],
                'bidang' => $d['bidang'],
            ]);
        }
    }
}
