<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UsersTemplateExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'Nama',
            'Nomor Induk',
            'Role',
            'Posisi',
            'Email',
            'Password'
        ];
    }

    public function array(): array
    {
        return [
            ['Ucup Surucup', '2010114001', 'Viewer', 'Mahasiswa', 'ucup@student.ac.id', ''],
            ['Budi Raharjo', '198001012010121001', 'Viewer', 'Dosen', 'budi@university.ac.id', ''],
            ['Admin TA Central', '198505052015041002', 'Admin', 'Koordinator', 'admin_ta@university.ac.id', 'strongpass123']
        ];
    }
}
