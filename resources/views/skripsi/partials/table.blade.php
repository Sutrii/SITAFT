{{-- Header & Tombol Tambah --}}
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold text-[#2d3a32]">Data Skripsi Mahasiswa</h2>
    <div class="flex items-center gap-3">
        <button
            class="border border-[#3ea76a] text-[#3ea76a] hover:bg-[#e5f5e8] px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition-all">
            + Tambah Data
        </button>
    </div>
</div>

{{-- Filter Bar --}}
<div class="bg-white rounded-2xl shadow-sm p-5 mb-6">
    <div class="grid grid-cols-3 gap-4 items-end w-full">
        {{-- Kolom 1 - Nama Mahasiswa --}}
        <div class="w-full">
            <label class="block text-sm font-medium text-[#2d3a32] mb-1">Nama Mahasiswa</label>
            <input type="text" id="filterNama" placeholder="Cari nama mahasiswa..."
                class="w-full border border-[#d8e4d8] rounded-lg px-3 py-2 text-sm
                       focus:ring-2 focus:ring-[#3ea76a] focus:outline-none" />
        </div>

        {{-- Kolom 2 - NIM --}}
        <div class="w-full">
            <label class="block text-sm font-medium text-[#2d3a32] mb-1">NIM</label>
            <input type="text" id="filterNim" placeholder="Cari NIM..."
                class="w-full border border-[#d8e4d8] rounded-lg px-3 py-2 text-sm
                       focus:ring-2 focus:ring-[#3ea76a] focus:outline-none" />
        </div>
    </div>
</div>

{{-- DataTable --}}
<div class="bg-white rounded-2xl shadow-md p-6 overflow-x-auto">
    <table id="skripsiTable" class="display text-sm min-w-[850px]">
        <thead>
            <tr class="text-[#2d3a32] border-b border-[#e8f0e8]">
                <th class="py-3 px-2 text-left">No</th>
                <th class="py-3 px-2 text-left">Nama Mahasiswa</th>
                <th class="py-3 px-2 text-left">NIM</th>
                <th class="py-3 px-2 text-left">Judul Skripsi</th>
                <th class="py-3 px-2 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Riyanda Ilham</td>
                <td>2105012345</td>
                <td>Implementasi Sistem Penjadwalan TA</td>
                <td class="text-center flex justify-center gap-2">
                    <button class="text-[#3ea76a] hover:text-[#2d3a32] transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.862 4.487l1.687 1.688m-2.122-2.122a1.5 1.5 0 112.122 2.122L7.5 17.25H4.5v-3l11.928-11.928z" />
                        </svg>
                    </button>
                    <button class="text-red-500 hover:text-red-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td>Andi Saputra</td>
                <td>2105012346</td>
                <td>Optimasi Algoritma Klasifikasi</td>
                <td class="text-center flex justify-center gap-2">
                    <button class="text-[#3ea76a] hover:text-[#2d3a32] transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.862 4.487l1.687 1.688m-2.122-2.122a1.5 1.5 0 112.122 2.122L7.5 17.25H4.5v-3l11.928-11.928z" />
                        </svg>
                    </button>
                    <button class="text-red-500 hover:text-red-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

{{-- Scripts --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function () {
    const table = $('#skripsiTable').DataTable({
        scrollX: false,
        autoWidth: false,
        pageLength: 5,
        lengthChange: true,
        lengthMenu: [5, 10, 25, 50, 100],
        language: {
            search: "",
            searchPlaceholder: "Cari mahasiswa atau judul...",
            lengthMenu: "Tampilkan _MENU_ data",
            paginate: { previous: "←", next: "→" },
            info: "Menampilkan _START_–_END_ dari _TOTAL_ data"
        },
        dom:
            "<'flex justify-between items-center mb-4 flex-wrap gap-3'<'dataTables_length_wrapper'l><'dataTables_filter_wrapper'f>>" +
            "tr" +
            "<'flex justify-between items-center mt-3 flex-wrap gap-3'<'dataTables_info_wrapper'i><'dataTables_pagination_wrapper'p>>",
        columnDefs: [
            { width: "5%", targets: 0 },
            { width: "25%", targets: 1 },
            { width: "15%", targets: 2 },
            { width: "35%", targets: 3 },
            { width: "20%", targets: 4, orderable: false, searchable: false }
        ]
    });

    // Filter manual via input
    $('#filterNama').on('keyup', function () {
        table.column(1).search(this.value).draw();
    });
    $('#filterNim').on('keyup', function () {
        table.column(2).search(this.value).draw();
    });

    // Reset filter
    $('#resetFilter').on('click', function () {
        $('#filterNama').val('');
        $('#filterNim').val('');
        table.columns().search('').draw();
    });
});
</script>

<style>
/* Zebra Row */
#skripsiTable tbody tr:nth-child(odd) { background-color: #fafdfa; }
#skripsiTable tbody tr:nth-child(even) { background-color: #ffffff; }

/* Hover Row */
#skripsiTable tbody tr:hover { background-color: #f1f8f4; }

/* Tabel Rapi */
table.dataTable.no-footer { border-bottom: none; }
table.dataTable tbody td, table.dataTable thead th { padding: 0.75rem 1rem !important; }

/* Dropdown "Tampilkan X data" */
.dataTables_length select {
    border: 1px solid #d8e4d8; border-radius: 8px;
    padding: 8px 40px 8px 14px; font-size: 0.875rem;
    color: #2d3a32; background-color: #fff;
    outline: none; cursor: pointer; transition: all 0.2s ease;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg fill='none' stroke='%236b7d6f' stroke-width='1.5' viewBox='0 0 24 24'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M8.25 9.75L12 13.5l3.75-3.75'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 8px center; background-size: 14px;
}

/* Search Bar */
.dataTables_filter input {
    border: 1px solid #d8e4d8 !important;
    border-radius: 9999px !important;
    padding: 0.5rem 1rem 0.5rem 2.5rem !important;
    font-size: 0.875rem; color: #2d3a32; background-color: #ffffff;
    background-image: url('data:image/svg+xml,%3Csvg fill="none" stroke="%236b7d6f" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"%3E%3Cpath stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 18a7.5 7.5 0 006.15-3.35z"/%3E%3C/svg%3E');
    background-repeat: no-repeat; background-position: 0.75rem center;
    background-size: 1rem; width: 14rem !important;
}

/* Pagination & Info */
.dataTables_wrapper .dataTables_paginate { margin-top: 1rem; }
.dataTables_wrapper .dataTables_info { color: #6b7d6f; font-size: 0.875rem; }

/* Responsive layout */
@media (max-width: 640px) {
    .dataTables_length_wrapper, .dataTables_filter_wrapper {
        flex-direction: column; align-items: flex-start; gap: 0.5rem;
    }
}

/* Header & body sejajar */
table.dataTable thead th, table.dataTable tbody td { white-space: nowrap; }

/* Pastikan tabel ngisi container penuh */
#skripsiTable { width: 100% !important; min-width: unset !important; }

/* Hilangkan gap horizontal */
.dataTables_wrapper { overflow-x: visible !important; }

/* Kolom padding */
#skripsiTable th, #skripsiTable td {
    padding: 0.75rem 1rem !important;
    text-align: left;
}
</style>
