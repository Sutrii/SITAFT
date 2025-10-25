{{-- Filter Bar --}}
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold text-[#2d3a32]">Jadwal Kosong Dosen</h2>
</div>

<div class="bg-white rounded-2xl shadow-sm p-5 mb-6">
    <div class="grid grid-cols-3 gap-4 items-end w-full">
        {{-- Pilih Dosen --}}
        <div>
            <label class="block text-sm font-medium text-[#2d3a32] mb-1">Pilih Dosen</label>
            <select id="filterDosen"
                class="w-full border border-[#d8e4d8] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#3ea76a] focus:outline-none">
                <option value="">Semua Dosen</option>
                <option>Dr. Ir. Hasan Yudie Sastra, DEA</option>
                <option>Ir. Ilyas, MT</option>
                <option>Ir. Awal Aflizal Zubir, S.T., M.Sc</option>
                <option>Ir. Riski Arifin, S.T., M.T.</option>
            </select>
        </div>

        {{-- Pilih Hari --}}
        <div>
            <label class="block text-sm font-medium text-[#2d3a32] mb-1">Hari</label>
            <select id="filterHari"
                class="w-full border border-[#d8e4d8] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#3ea76a] focus:outline-none">
                <option value="">Semua Hari</option>
                <option>Senin</option>
                <option>Selasa</option>
                <option>Rabu</option>
                <option>Kamis</option>
                <option>Jumat</option>
            </select>
        </div>

        {{-- Pilih Jam --}}
        <div>
            <label class="block text-sm font-medium text-[#2d3a32] mb-1">Jam</label>
            <select id="filterJam"
                class="w-full border border-[#d8e4d8] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#3ea76a] focus:outline-none">
                <option value="">Semua Jam</option>
                <option>08.00 - 08.50</option>
                <option>09.40 - 10.30</option>
                <option>10.30 - 11.20</option>
                <option>13.00 - 14.00</option>
            </select>
        </div>
    </div>
</div>

{{-- DataTable --}}
<div class="bg-white rounded-2xl shadow-md p-6 overflow-x-auto">
    <table id="dosenTable" class="display text-sm min-w-[800px]">
        <thead>
            <tr class="text-[#2d3a32] border-b border-[#e8f0e8]">
                <th class="py-3 px-2 text-left">No</th>
                <th class="py-3 px-2 text-left">Nama Dosen</th>
                <th class="py-3 px-2 text-left">Hari</th>
                <th class="py-3 px-2 text-left">Jam</th>
                <th class="py-3 px-2 text-left">Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Ir. Ilyas, MT</td>
                <td>Selasa</td>
                <td>10.30 - 11.20</td>
                <td><span class="bg-[#e5f5e8] text-[#3ea76a] px-3 py-1 rounded-full text-xs font-medium">Kosong</span></td>
            </tr>
            <tr>
                <td>2</td>
                <td>Ir. Ilyas, MT</td>
                <td>Selasa</td>
                <td>13.00 - 14.00</td>
                <td><span class="bg-[#e5f5e8] text-[#3ea76a] px-3 py-1 rounded-full text-xs font-medium">Kosong</span></td>
            </tr>
            <tr>
                <td>3</td>
                <td>Dr. Ir. Hasan Yudie Sastra, DEA</td>
                <td>Senin</td>
                <td>09.40 - 10.30</td>
                <td><span class="bg-[#e5f5e8] text-[#3ea76a] px-3 py-1 rounded-full text-xs font-medium">Kosong</span></td>
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
    const table = $('#dosenTable').DataTable({
        scrollX: false,
        autoWidth: false,
        pageLength: 5,
        lengthChange: true,
        lengthMenu: [5, 10, 25, 50, 100],
        language: {
            search: "",
            searchPlaceholder: "Cari jadwal atau dosen...",
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
            { width: "35%", targets: 1 },
            { width: "20%", targets: 2 },
            { width: "20%", targets: 3 },
            { width: "20%", targets: 4 }
        ]
    });

    $('#filterDosen, #filterHari, #filterJam').on('change', function () {
        let dosen = $('#filterDosen').val().toLowerCase();
        let hari = $('#filterHari').val().toLowerCase();
        let jam = $('#filterJam').val().toLowerCase();

        table.rows().every(function () {
            const data = this.data();
            const matchDosen = !dosen || data[1].toLowerCase().includes(dosen);
            const matchHari = !hari || data[2].toLowerCase().includes(hari);
            const matchJam = !jam || data[3].toLowerCase().includes(jam);
            this.visible(matchDosen && matchHari && matchJam);
        });
    });
});
</script>

<style>
/* Zebra Row */
#dosenTable tbody tr:nth-child(odd) {
    background-color: #fafdfa;
}
#dosenTable tbody tr:nth-child(even) {
    background-color: #ffffff;
}

/* Hover Row */
#dosenTable tbody tr:hover {
    background-color: #f1f8f4;
}

/* Tabel Rapi */
table.dataTable.no-footer {
    border-bottom: none;
}
table.dataTable tbody td, table.dataTable thead th {
    padding: 0.75rem 1rem !important;
}

/* Dropdown "Tampilkan X data" */
.dataTables_length select {
    border: 1px solid #d8e4d8;
    border-radius: 8px;
    padding: 8px 40px 8px 14px;
    font-size: 0.875rem;
    color: #2d3a32;
    background-color: #fff;
    outline: none;
    cursor: pointer;
    transition: all 0.2s ease;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg fill='none' stroke='%236b7d6f' stroke-width='1.5' viewBox='0 0 24 24'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M8.25 9.75L12 13.5l3.75-3.75'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 8px center;
    background-size: 14px;
}

/* Search Bar */
.dataTables_filter input {
    border: 1px solid #d8e4d8 !important;
    border-radius: 9999px !important;
    padding: 0.5rem 1rem 0.5rem 2.5rem !important;
    font-size: 0.875rem;
    color: #2d3a32;
    background-color: #ffffff;
    background-image: url('data:image/svg+xml,%3Csvg fill="none" stroke="%236b7d6f" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"%3E%3Cpath stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 18a7.5 7.5 0 006.15-3.35z"/%3E%3C/svg%3E');
    background-repeat: no-repeat;
    background-position: 0.75rem center;
    background-size: 1rem;
    width: 14rem !important;
}

/* Pagination & Info */
.dataTables_wrapper .dataTables_paginate {
    margin-top: 1rem;
}
.dataTables_wrapper .dataTables_info {
    color: #6b7d6f;
    font-size: 0.875rem;
}

/* Responsive layout */
@media (max-width: 640px) {
    .dataTables_length_wrapper,
    .dataTables_filter_wrapper {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
}
/* Pastikan tabel ngisi container penuh */
#dosenTable {
    width: 100% !important;
    min-width: unset !important;
}

/* Header & body sejajar */
table.dataTable thead th,
table.dataTable tbody td {
    white-space: nowrap;
}

/* Hapus gap horizontal DataTables */
.dataTables_wrapper {
    overflow-x: visible !important;
}

/* Kolom padding rapih */
#dosenTable th,
#dosenTable td {
    padding: 0.75rem 1rem !important;
    text-align: left;
}

/* Tambah biar zebra rapih */
#dosenTable tbody tr:nth-child(odd) {
    background-color: #fafdfa !important;
}
#dosenTable tbody tr:nth-child(even) {
    background-color: #ffffff !important;
}
</style>
