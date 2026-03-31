{{-- Header & Tombol Tambah --}}

{{-- Filter Bar --}}
<div class="bg-white rounded-2xl shadow-sm p-5 mb-6">
    <div class="grid grid-cols-3 gap-4 items-end w-full">
        <div class="w-full">
            <label class="block text-sm font-medium text-[#2d3a32] mb-1">Nama Dosen</label>
            <input type="text" id="filterNama" placeholder="Cari nama dosen..."
                class="w-full border border-[#d8e4d8] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#3ea76a] focus:outline-none" />
        </div>

        <div class="w-full">
            <label class="block text-sm font-medium text-[#2d3a32] mb-1">NIK</label>
            <input type="text" id="filterNik" placeholder="Cari NIP..."
                class="w-full border border-[#d8e4d8] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#3ea76a] focus:outline-none" />
        </div>

        <div class="flex justify-end items-end">
            <button id="openModalBtn"
                class="bg-[#3ea76a] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-[#2d8c5d] transition">
                + Tambah Dosen
            </button>
        </div>
    </div>
</div>

{{-- DataTable --}}
<div class="bg-white rounded-2xl shadow-md p-6 overflow-x-auto">
    <table id="dosenTable" class="display text-sm min-w-[950px]">
        <thead>
            <tr class="text-[#2d3a32] border-b border-[#e8f0e8]">
                <th>No</th>
                <th>Nama Dosen</th>
                <th>NIP</th>
                <th>Bidang</th>
                <th>Tanggal Terdaftar</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dosens as $index => $dsn)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $dsn->name }}</td>
                    <td>{{ $dsn->nik }}</td>
                    <td>{{ $dsn->bidang }}</td>
                    <td>{{ $dsn->created_at?->format('d M Y') ?? '-' }}</td>
                    <td class="text-center">
                        <div class="flex justify-center gap-3">
                            <button type="button" class="text-blue-500 hover:text-blue-700 transition"
                                onclick="openEditModal('{{ $dsn->userId }}', '{{ $dsn->name }}', '{{ $dsn->nik }}', '{{ $dsn->bidang }}')">
                                ✏️
                            </button>
                            <button type="button" class="text-red-500 hover:text-red-700 transition"
                                onclick="confirmDelete('{{ route('koordinator.users.destroy', $dsn->userId) }}', '{{ $dsn->nik }}')">
                                🗑️
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-gray-500 py-4">Tidak ada data dosen</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal Tambah --}}
<div id="addModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-white w-[400px] rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-green-700 text-white px-5 py-3 font-semibold text-lg">
            Tambah Dosen
        </div>

        <form action="{{ route('koordinator.users.store') }}" method="POST" class="p-5 space-y-4">
            @csrf
            <input type="hidden" name="roleId" value="1">
            <input type="hidden" name="positionId" value="2">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Dosen</label>
                <input type="text" name="name" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">NIP</label>
                <input type="text" name="nip" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bidang</label>
                <input type="text" name="bidang" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none">
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
            Edit Dosen
        </div>

        <form id="editForm" method="POST" class="p-5 space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="roleId" value="1">
            <input type="hidden" name="positionId" value="2">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Dosen</label>
                <input type="text" name="name" id="editName" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">NIP</label>
                <input type="text" name="nip" id="editNik" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bidang</label>
                <input type="text" name="bidang" id="editBidang" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
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
const addModal = document.getElementById('addModal');
const openModalBtn = document.getElementById('openModalBtn');
const closeModalBtn = document.getElementById('closeModalBtn');

openModalBtn.addEventListener('click', () => {
    addModal.classList.remove('hidden', 'opacity-0');
    addModal.classList.add('flex');
});

closeModalBtn.addEventListener('click', () => {
    addModal.classList.add('hidden', 'opacity-0');
    addModal.classList.remove('flex');
});

addModal.addEventListener('click', (e) => {
    if (e.target === addModal) {
        addModal.classList.add('hidden', 'opacity-0');
        addModal.classList.remove('flex');
    }
});

$(document).ready(function () {
    const table = $('#dosenTable').DataTable({
        pageLength: 5,
        lengthChange: true,
        lengthMenu: [5, 10, 25, 50, 100],
        language: {
            search: "",
            searchPlaceholder: "Cari dosen...",
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

    $('#filterNik').on('keyup', function () {
        table.column(2).search(this.value).draw();
    });
});

const editModal = document.getElementById('editModal');
const closeEditModalBtn = document.getElementById('closeEditModalBtn');
const editForm = document.getElementById('editForm');

function openEditModal(id, name, nik, bidang) {
    Swal.fire({
        title: `Edit ${name}?`,
        text: "Apakah Anda yakin ingin mengubah data ini?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, ubah',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            editModal.classList.remove('hidden', 'opacity-0');
            editModal.classList.add('flex');
            document.getElementById('editName').value = name;
            document.getElementById('editNik').value = nik;
            document.getElementById('editBidang').value = bidang;
            editForm.action = `/dashboard/koordinator/users/${id}`;
        }
    });
}

closeEditModalBtn?.addEventListener('click', () => {
    editModal.classList.add('hidden', 'opacity-0');
    editModal.classList.remove('flex');
});

editModal?.addEventListener('click', (e) => {
    if (e.target === editModal) {
        editModal.classList.add('hidden', 'opacity-0');
        editModal.classList.remove('flex');
    }
});

editForm?.addEventListener('submit', (e) => {
    e.preventDefault();

    Swal.fire({
        title: 'Menyimpan...',
        text: 'Mohon tunggu, sedang memperbarui data',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading(),
    });

    setTimeout(() => {
        e.target.submit();
    }, 700);
});

function confirmDelete(actionUrl, nik) {
    Swal.fire({
        title: `Hapus ${nik}?`,
        text: "Data ini tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e3342f',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Menghapus...',
                text: 'Sedang menghapus data',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading(),
            });

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = actionUrl;
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

@if (session('success'))
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: '{{ session('success') }}',
    showConfirmButton: false,
    timer: 1800
});
@endif

@if (session('error'))
Swal.fire({
    icon: 'error',
    title: 'Gagal!',
    text: '{{ session('error') }}',
});
@endif

@if ($errors->any())
Swal.fire({
    icon: 'warning',
    title: 'Validasi Gagal!',
    html: `{!! implode('<br>', $errors->all()) !!}`,
    confirmButtonText: 'Oke'
});
@endif
</script>

<style>
#dosenTable tbody tr:nth-child(odd) { background-color: #fafdfa; }
#dosenTable tbody tr:nth-child(even) { background-color: #ffffff; }
#dosenTable tbody tr:hover { background-color: #f1f8f4; }
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
