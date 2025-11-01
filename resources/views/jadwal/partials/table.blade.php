{{-- Header & Tombol Tambah --}}
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold text-[#2d3a32]">Jadwal Tugas Akhir</h2>

    <button id="openModalBtn"
        class="bg-[#3ea76a] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-[#2d8c5d] transition">
        + Tambah Jadwal
    </button>
</div>

{{-- Filter Bar --}}
<div class="bg-white rounded-2xl shadow-sm p-5 mb-6">
    <div class="grid grid-cols-3 gap-4 items-end w-full">
        <div class="w-full">
            <label class="block text-sm font-medium text-[#2d3a32] mb-1">Nama Mahasiswa</label>
            <input type="text" id="filterNama" placeholder="Cari nama mahasiswa..."
                class="w-full border border-[#d8e4d8] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#3ea76a] focus:outline-none" />
        </div>

        <div class="w-full flex items-end gap-2">
            <div class="flex-1">
                <label class="block text-sm font-medium text-[#2d3a32] mb-1">Jenis Seminar</label>
                <select id="filterStatus"
                    class="w-full border border-[#d8e4d8] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#3ea76a] focus:outline-none">
                    <option value="">Semua Jenis</option>
                    <option value="Seminar Proposal">Seminar Proposal</option>
                    <option value="Seminar Hasil">Seminar Hasil</option>
                    <option value="Sidang Akhir">Sidang Akhir</option>
                </select>
            </div>

            <button id="shareLinkBtn"
                class="h-[38px] px-4 ml-2.5 flex items-center gap-1 bg-[#3ea76a] text-white rounded-lg text-sm font-medium hover:bg-[#2d8c5d] transition">
                🔗 Share Link
            </button>
        </div>
    </div>
</div>

{{-- DataTable --}}
<div class="bg-white rounded-2xl shadow-md p-6 overflow-x-auto">
    <table id="jadwalTable" class="display text-sm min-w-[950px]">
        <thead>
            <tr class="text-[#2d3a32] border-b border-[#e8f0e8]">
                <th>No</th>
                <th>Mahasiswa</th>
                <th>Judul Skripsi</th>
                <th>Dosen 1</th>
                <th>Dosen 2</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Status</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($jadwals as $index => $jadwal)
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
                    <td class="text-center">
                        <div class="flex justify-center gap-3">
                            <button type="button" class="text-blue-500 hover:text-blue-700 transition"
                                onclick="openEditModal('{{ $jadwal->id }}', '{{ $jadwal->mahasiswaId }}', '{{ $jadwal->skripsiId }}', '{{ $jadwal->dosenId1 }}', '{{ $jadwal->dosenId2 }}', '{{ $jadwal->jadwal_seminar }}', '{{ $jadwal->status }}')">
                                ✏️
                            </button>
                            <button type="button" class="text-red-500 hover:text-red-700 transition"
                                onclick="confirmDelete('{{ route('jadwal.destroy', $jadwal->id) }}', '{{ $jadwal->mahasiswa->name ?? '-' }}')">
                                🗑️
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-gray-500 py-4">Tidak ada data jadwal</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal Tambah --}}
<div id="addModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-white w-[400px] rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-green-700 text-white px-5 py-3 font-semibold text-lg">
            Tambah Jadwal
        </div>

        <form action="{{ route('jadwal.store') }}" method="POST" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mahasiswa</label>
                <select name="mahasiswaId" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">-- Pilih Mahasiswa --</option>
                    @foreach ($mahasiswas as $m)
                        <option value="{{ $m->id }}">{{ $m->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Skripsi</label>
                <select name="skripsiId" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">-- Pilih Skripsi --</option>
                    @foreach ($skripsis as $s)
                        <option value="{{ $s->id }}">{{ $s->judul_skripsi }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dosen Penguji 1</label>
                    <select name="dosenId1" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">-- Pilih Dosen --</option>
                        @foreach ($dosens as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dosen Penguji 2</label>
                    <select name="dosenId2" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">-- Pilih Dosen --</option>
                        @foreach ($dosens as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal & Jam Seminar</label>
                <input type="datetime-local" name="jadwal_seminar" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="Seminar Proposal">Seminar Proposal</option>
                    <option value="Seminar Hasil">Seminar Hasil</option>
                    <option value="Sidang Akhir">Sidang Akhir</option>
                </select>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" id="closeModalBtn"
                    class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 transition">Batal</button>
                <button type="submit"
                    class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div id="editModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-white w-[400px] rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-blue-700 text-white px-5 py-3 font-semibold text-lg">
            Edit Jadwal
        </div>

        <form id="editForm" method="POST" class="p-5 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mahasiswa</label>
                <select name="mahasiswaId" id="editMahasiswa" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    @foreach ($mahasiswas as $m)
                        <option value="{{ $m->id }}">{{ $m->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Skripsi</label>
                <select name="skripsiId" id="editSkripsi" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    @foreach ($skripsis as $s)
                        <option value="{{ $s->id }}">{{ $s->judul_skripsi }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dosen Penguji 1</label>
                    <select name="dosenId1" id="editDosen1" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        @foreach ($dosens as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dosen Penguji 2</label>
                    <select name="dosenId2" id="editDosen2" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        @foreach ($dosens as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal & Jam Seminar</label>
                <input type="datetime-local" name="jadwal_seminar" id="editTanggal" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="editStatus" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option>Seminar Proposal</option>
                    <option>Seminar Hasil</option>
                    <option>Sidang Akhir</option>
                </select>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" id="closeEditModalBtn"
                    class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 transition">Batal</button>
                <button type="submit"
                    class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">Update</button>
            </div>
        </form>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(() => {
    const table = $('#jadwalTable').DataTable({
        pageLength: 5,
        lengthChange: true,
        lengthMenu: [5, 10, 25, 50, 100],
        language: {
            search: "",
            searchPlaceholder: "Cari jadwal...",
            lengthMenu: "Tampilkan _MENU_ data",
            paginate: { previous: "←", next: "→" },
            info: "Menampilkan _START_–_END_ dari _TOTAL_ data"
        },
        dom:
            "<'flex justify-between items-center mb-4 flex-wrap gap-3'<'dataTables_length_wrapper'l><'dataTables_filter_wrapper'f>>" +
            "tr" +
            "<'flex justify-between items-center mt-3 flex-wrap gap-3'<'dataTables_info_wrapper'i><'dataTables_pagination_wrapper'p>>"
    });

    $('#filterNama').on('keyup', function () {
        table.column(1).search(this.value).draw();
    });

    $('#filterStatus').on('change', function () {
        table.column(7).search(this.value).draw();
    });

    // 🔗 Tombol Share Link
    $('#shareLinkBtn').on('click', () => {
        const link = window.location.href;
        navigator.clipboard.writeText(link).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Link disalin!',
                text: 'URL halaman ini telah disalin ke clipboard.',
                timer: 1800,
                showConfirmButton: false
            });
        }).catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal menyalin!',
                text: 'Browser kamu tidak mendukung fitur salin otomatis.',
                timer: 2000,
                showConfirmButton: false
            });
        });
    });

});
</script>

<style>
#jadwalTable tbody tr:nth-child(odd) { background-color: #fafdfa; }
#jadwalTable tbody tr:nth-child(even) { background-color: #ffffff; }
#jadwalTable tbody tr:hover { background-color: #f1f8f4; }
table.dataTable.no-footer { border-bottom: none; }
table.dataTable tbody td, table.dataTable thead th { padding: 0.75rem 1rem !important; }
.dataTables_length select {
    border: 1px solid #d8e4d8; border-radius: 8px;
    padding: 8px 40px 8px 14px; font-size: 0.875rem;
    color: #2d3a32; background-color: #fff;
    outline: none; cursor: pointer; transition: all 0.2s ease;
}
.dataTables_filter input {
    border: 1px solid #d8e4d8 !important;
    border-radius: 9999px !important;
    padding: 0.5rem 1rem 0.5rem 2.5rem !important;
    font-size: 0.875rem; color: #2d3a32; background-color: #ffffff;
    background-image: url('data:image/svg+xml,%3Csvg fill="none" stroke="%236b7d6f" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"%3E%3Cpath stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 18a7.5 7.5 0 006.15-3.35z"/%3E%3C/svg%3E');
    background-repeat: no-repeat; background-position: 0.75rem center;
    background-size: 1rem; width: 14rem !important;
}
.dataTables_wrapper .dataTables_paginate { margin-top: 1rem; }
.dataTables_wrapper .dataTables_info { color: #6b7d6f; font-size: 0.875rem; }
</style>
