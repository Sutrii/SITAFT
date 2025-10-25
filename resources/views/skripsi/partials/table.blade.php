{{-- Filter Bar dan DataTable Skripsi --}}
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold text-[#2d3a32]">Data Skripsi Mahasiswa</h2>
    <div class="flex items-center gap-3">
        <button
            class="border border-[#3ea76a] text-[#3ea76a] hover:bg-[#e5f5e8] px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition-all">
            + Tambah Data
        </button>
    </div>
</div>

{{-- 🔍 Filter Bar --}}
<div class="bg-white rounded-2xl shadow-sm p-5 mb-6 w-full">
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

{{-- 📋 DataTable --}}
<div class="bg-white rounded-2xl shadow-md p-4">
    <table id="skripsiTable" class="display text-sm w-full">
        <thead>
            <tr class="text-[#2d3a32] border-b border-[#e8f0e8]">
                <th class="py-2 px-3 text-left w-[60px]">No</th>
                <th class="py-2 px-3 text-left w-[220px]">Nama Mahasiswa</th>
                <th class="py-2 px-3 text-left w-[140px]">NIM</th>
                <th class="py-2 px-3 text-left">Judul Skripsi</th>
                <th class="py-2 px-3 text-center w-[100px]">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr class="hover:bg-[#f8fcf9] transition">
                <td>1</td>
                <td>Riyanda Ilham</td>
                <td>2105012345</td>
                <td>Implementasi Sistem Penjadwalan TA</td>
                <td class="text-center flex justify-center gap-2">
                    <button class="text-[#3ea76a] hover:text-[#2d3a32]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.862 4.487l1.687 1.688m-2.122-2.122a1.5 1.5 0 112.122 2.122L7.5 17.25H4.5v-3l11.928-11.928z" />
                        </svg>
                    </button>
                    <button class="text-red-500 hover:text-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </td>
            </tr>
            <tr class="hover:bg-[#f8fcf9] transition">
                <td>2</td>
                <td>Andi Saputra</td>
                <td>2105012346</td>
                <td>Optimasi Algoritma Klasifikasi</td>
                <td class="text-center flex justify-center gap-2">
                    <button class="text-[#3ea76a] hover:text-[#2d3a32]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.862 4.487l1.687 1.688m-2.122-2.122a1.5 1.5 0 112.122 2.122L7.5 17.25H4.5v-3l11.928-11.928z" />
                        </svg>
                    </button>
                    <button class="text-red-500 hover:text-red-700">
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

{{-- DataTables --}}
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
                "<'flex justify-between items-center mt-3 flex-wrap gap-3'<'dataTables_info_wrapper'i><'dataTables_pagination_wrapper'p>>"
        });

        // Filter Nama dan NIM
        $('#filterNama').on('keyup', function () {
            table.column(1).search(this.value).draw();
        });

        $('#filterNim').on('keyup', function () {
            table.column(2).search(this.value).draw();
        });

        // Reset Filter
        $('#resetFilter').on('click', function () {
            $('#filterNama').val('');
            $('#filterNim').val('');
            table.columns().search('').draw();
        });
    });
</script>

<style>
    /* Table alignment dan spacing fix */
    table.dataTable {
        border-collapse: collapse !important;
        width: 100% !important;
    }
    table.dataTable th,
    table.dataTable td {
        padding: 0.75rem 1rem !important;
        vertical-align: middle !important;
    }
    table.dataTable thead th {
        background-color: #f9fbf9 !important;
        font-weight: 600 !important;
    }
    .dataTables_wrapper {
        width: 100%;
        overflow-x: hidden;
    }
    .dataTables_length_wrapper,
    .dataTables_filter_wrapper {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .dataTables_filter input {
        border: 1px solid #d8e4d8 !important;
        border-radius: 9999px !important;
        padding: 0.5rem 1rem 0.5rem 2.5rem !important;
        background-image: url('data:image/svg+xml,%3Csvg fill="none" stroke="%236b7d6f" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"%3E%3Cpath stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 18a7.5 7.5 0 006.15-3.35z"/%3E%3C/svg%3E');
        background-repeat: no-repeat;
        background-position: 0.75rem center;
        background-size: 1rem;
    }
</style>
