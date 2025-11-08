<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ str_replace('-', ' ', $title) }}</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f5;
            padding: 40px;
            color: #2d3a32;
        }

        .header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 30px;
            border-bottom: 3px solid #3ea76a;
            padding-bottom: 12px;
        }

        .header img {
            width: 90px;
            height: 90px;
            object-fit: contain;
        }

        .header-text h2 {
            margin: 0;
            font-size: 1.75rem;
            color: #2d3a32;
            font-weight: 700;
        }

        .header-text p {
            margin: 4px 0 0 0;
            color: #6b7d6f;
            font-weight: 500;
        }

        table {
            border-collapse: collapse !important;
            width: 100%;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        table thead {
            background-color: #3ea76a;
            color: white;
        }

        table thead th {
            padding: 12px 16px;
            text-align: left;
            font-weight: 600;
            font-size: 0.95rem;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9fcfa;
        }

        table tbody tr:hover {
            background-color: #e8f5ee;
        }

        table tbody td {
            padding: 10px 16px;
            border-bottom: 1px solid #e4efe6;
            font-size: 0.9rem;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #c5d8ca;
            border-radius: 6px;
            padding: 6px 10px;
            font-size: 0.9rem;
        }

        .dataTables_wrapper .dataTables_paginate {
            margin-top: 1.2rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            background: #fff !important;
            border: 1px solid #d6e8da !important;
            border-radius: 6px;
            margin: 0 2px;
            color: #3ea76a !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #3ea76a !important;
            color: #fff !important;
            border: none !important;
        }

        /* Buat print version */
        @media print {
            body { background: white; padding: 0; }
            .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_paginate,
            .dataTables_info { display: none; }
            table { box-shadow: none; }
        }
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-top: 10px; /* jarak antara tabel dan bagian tampilkan/cari */
            margin-bottom: 20px;
        }

        /* Kalau mau biar keduanya sejajar lebih elegan */
        .dataTables_wrapper .dataTables_length {
            float: left;
        }
        .dataTables_wrapper .dataTables_filter {
            float: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('assets/images/unsyiah.png') }}" alt="Logo Unsyiah">
        <div class="header-text">
            <h2>{{ str_replace('-', ' ', $title) }}</h2>
            <p>Periode: {{ $from }} s.d {{ $to }}</p>
        </div>
    </div>

    <table id="sharedTable" class="display">
        <thead>
            <tr>
                <th>No</th>
                <th>Mahasiswa</th>
                <th>Judul Skripsi</th>
                <th>Dosen Penguji 1</th>
                <th>Dosen Penguji 2</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($jadwals as $index => $jadwal)
                @php
                    $tanggal = \Carbon\Carbon::parse($jadwal->jadwal_seminar)->translatedFormat('d F Y');
                    $jam = \Carbon\Carbon::parse($jadwal->jadwal_seminar)->format('H:i');

                    // Style warna berdasarkan status
                    $status = $jadwal->status;
                    $badgeColor = match($status) {
                        'Seminar Proposal' => 'background-color:#fde8e8; color:#b91c1c;', // Merah lembut
                        'Seminar Hasil' => 'background-color:#e0e7ff; color:#3730a3;', // Biru lembut
                        'Sidang Akhir' => 'background-color:#dcfce7; color:#166534;', // Hijau lembut
                        default => 'background-color:#f3f4f6; color:#374151;'
                    };
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $jadwal->mahasiswa->name ?? '-' }}</td>
                    <td>{{ $jadwal->skripsi->judul_skripsi ?? '-' }}</td>
                    <td>{{ $jadwal->dosen1->name ?? '-' }}</td>
                    <td>{{ $jadwal->dosen2->name ?? '-' }}</td>
                    <td>{{ $tanggal }}</td>
                    <td>{{ $jam }}</td>
                    <td data-status="{{ $jadwal->status }}">
                        <span style="padding:4px 10px; border-radius:20px; font-size:0.85rem; font-weight:600; {{ $badgeColor }}">
                            {{ $jadwal->status }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <script>
        $(document).ready(() => {
            $('#sharedTable').DataTable({
                pageLength: 10,
                language: {
                    search: "",
                    searchPlaceholder: "Cari jadwal...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    paginate: { previous: "←", next: "→" },
                    info: "Menampilkan _START_–_END_ dari _TOTAL_ data"
                }
            });
        });
    </script>
</body>
</html>
