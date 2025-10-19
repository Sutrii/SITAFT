{{-- Filter Bar dan DataTable Jadwal Tugas Akhir --}}
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold text-[#2d3a32]">Jadwal Tugas Akhir</h2>
    <div class="flex items-center gap-3">
        <button
            class="border border-[#3ea76a] text-[#3ea76a] hover:bg-[#e5f5e8] px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition-all">
            + Tambah Jadwal
        </button>
    </div>
</div>

{{-- Filter Bar --}}
<div class="bg-white rounded-2xl shadow-sm p-5 mb-6 w-full">
    <div class="grid grid-cols-4 gap-4 items-end w-full">
        {{-- Kolom 1 - Tanggal Mulai --}}
        <div class="w-full">
            <label class="block text-sm font-medium text-[#2d3a32] mb-1">Tanggal Mulai</label>
            <input type="date"
                class="w-full border border-[#d8e4d8] rounded-lg px-3 py-2 text-sm 
                       focus:ring-2 focus:ring-[#3ea76a] focus:outline-none" />
        </div>

        {{-- Kolom 2 - Tanggal Berakhir --}}
        <div class="w-full">
            <label class="block text-sm font-medium text-[#2d3a32] mb-1">Tanggal Berakhir</label>
            <input type="date"
                class="w-full border border-[#d8e4d8] rounded-lg px-3 py-2 text-sm 
                       focus:ring-2 focus:ring-[#3ea76a] focus:outline-none" />
        </div>

        {{-- Kolom 3 - Jenis Seminar --}}
        <div class="w-full">
            <label class="block text-sm font-medium text-[#2d3a32] mb-1">Jenis Seminar</label>
            <select
                class="w-full border border-[#d8e4d8] rounded-lg px-3 py-2 text-sm 
                       focus:ring-2 focus:ring-[#3ea76a] focus:outline-none">
                <option value="">Semua</option>
                <option>Seminar Proposal</option>
                <option>Seminar Hasil</option>
                <option>Sidang Akhir</option>
            </select>
        </div>

        {{-- Kolom 4 - Tombol Bagikan --}}
        <div class="w-full">
            <label class="block text-sm font-medium text-transparent mb-1 select-none">_</label>
            <button
                class="w-full bg-[#3ea76a] hover:bg-[#359e61] text-white px-4 py-2 
                       rounded-lg text-sm font-medium shadow-sm transition-all flex items-center justify-center gap-2">
                📤 Bagikan Jadwal
            </button>
        </div>
    </div>
</div>

{{-- DataTable --}}
<div class="bg-white rounded-2xl shadow-md p-6 overflow-x-auto"> {{-- ✅ Scroll horizontal di wrapper --}}
    <table id="jadwalTable" class="display text-sm min-w-[900px]"> {{-- ✅ Min width agar scroll aktif --}}
        <thead>
            <tr class="text-[#2d3a32] border-b border-[#e8f0e8]">
                <th class="py-3 px-2 text-left">No</th>
                <th class="py-3 px-2 text-left">Nama Mahasiswa</th>
                <th class="py-3 px-2 text-left">Judul Skripsi</th>
                <th class="py-3 px-2 text-left">Dosen Penguji 1</th>
                <th class="py-3 px-2 text-left">Dosen Penguji 2</th>
                <th class="py-3 px-2 text-left">Tanggal</th>
                <th class="py-3 px-2 text-left">Jam</th>
                <th class="py-3 px-2 text-left">Status</th>
                <th class="py-3 px-2 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr class="hover:bg-[#f8fcf9] transition">
                <td>1</td>
                <td>Riyanda Ilham</td>
                <td>Implementasi Sistem Penjadwalan TA</td>
                <td>Dr. Budi Santoso</td>
                <td>Ir. Rina Wijaya</td>
                <td>2025-10-22</td>
                <td>09:00 – 10:30</td>
                <td>
                    <span class="bg-[#e5f5e8] text-[#3ea76a] px-2 py-1 rounded-full text-xs font-medium">
                        Seminar Proposal
                    </span>
                </td>
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
                <td>Optimasi Algoritma Klasifikasi</td>
                <td>Prof. Agus Mahendra</td>
                <td>Dr. Rina Wijaya</td>
                <td>2025-10-26</td>
                <td>13:30 – 15:00</td>
                <td>
                    <span class="bg-[#e0ebff] text-[#376ad9] px-2 py-1 rounded-full text-xs font-medium">
                        Seminar Hasil
                    </span>
                </td>
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

{{-- Datatables Scripts --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function () {
        $('#jadwalTable').DataTable({
            scrollX: true,
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
                "<'flex justify-between items-center mt-3 flex-wrap gap-3'<'dataTables_info_wrapper'i><'dataTables_pagination_wrapper'p>>"
        });
    });
</script>

<style>
/* 🌿 Area atas tabel (dropdown & search bar sejajar) */
.dataTables_length_wrapper,
.dataTables_filter_wrapper {
    display: flex;
    align-items: center;
    gap: 1rem;
}

/* 🌿 Dropdown “Tampilkan n data” */
.dataTables_length {
    font-size: 0.875rem;
    color: #2d3a32;
    font-weight: 500;
}

.dataTables_length select {
    border: 1px solid #d8e4d8;
    border-radius: 8px;
    padding: 8px 40px 8px 14px; /* 👉 tambahkan right padding lebih besar */
    font-size: 0.875rem;
    background-color: #fff;
    color: #2d3a32;
    outline: none;
    cursor: pointer;
    transition: all 0.2s ease;
    appearance: none; /* ✅ hilangkan style default browser */
    background-image: url("data:image/svg+xml,%3Csvg fill='none' stroke='%236b7d6f' stroke-width='1.5' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M8.25 9.75L12 13.5l3.75-3.75'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 8px center; /* ✅ posisi panah */
    background-size: 14px;
}

.dataTables_length select:hover {
    border-color: #3ea76a;
}

.dataTables_length select:focus {
    border-color: #3ea76a;
    box-shadow: 0 0 0 2px rgba(62, 167, 106, 0.2);
}

/* 🌿 Search bar */
.dataTables_filter {
    margin: 0 !important;
}

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

/* 🌿 Responsif di layar kecil */
@media (max-width: 640px) {
    .dataTables_length_wrapper,
    .dataTables_filter_wrapper {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
}
</style>
