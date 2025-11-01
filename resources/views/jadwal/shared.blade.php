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
        body { font-family: 'Inter', sans-serif; background: #f9fafb; padding: 40px; }
        h2 { color: #2d3a32; margin-bottom: 20px; }
        .dataTables_wrapper .dataTables_paginate { margin-top: 1rem; }
        table.dataTable.no-footer { border-bottom: none; }
        table.dataTable tbody td, table.dataTable thead th {
            padding: 0.75rem 1rem !important;
        }
    </style>
</head>
<body>
    <h2>{{ str_replace('-', ' ', $title) }}</h2>
    <p style="color:#6b7d6f;">Periode: {{ $from }} s.d {{ $to }}</p>

    <table id="sharedTable" class="display text-sm w-full">
        <thead>
            <tr>
                <th>No</th>
                <th>Mahasiswa</th>
                <th>Judul Skripsi</th>
                <th>Dosen 1</th>
                <th>Dosen 2</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($jadwals as $index => $jadwal)
                @php
                    $tanggal = \Carbon\Carbon::parse($jadwal->jadwal_seminar)->format('Y-m-d');
                    $jam = \Carbon\Carbon::parse($jadwal->jadwal_seminar)->format('H:i');
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $jadwal->mahasiswa->name ?? '-' }}</td>
                    <td>{{ $jadwal->skripsi->judul_skripsi ?? '-' }}</td>
                    <td>{{ $jadwal->dosen1->name ?? '-' }}</td>
                    <td>{{ $jadwal->dosen2->name ?? '-' }}</td>
                    <td>{{ $tanggal }}</td>
                    <td>{{ $jam }}</td>
                    <td>{{ $jadwal->status }}</td>
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
