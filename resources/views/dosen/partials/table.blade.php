{{-- Header & Tombol Tambah --}}
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold text-[#2d3a32]">Data Dosen</h2>

    {{-- Tombol buka modal --}}
    <button id="openModalBtn"
        class="bg-[#3ea76a] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-[#2d8c5d] transition">
        + Tambah Data
    </button>
</div>

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
            <input type="text" id="filterNik" placeholder="Cari NIK..."
                class="w-full border border-[#d8e4d8] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#3ea76a] focus:outline-none" />
        </div>
    </div>
</div>

{{-- DataTable --}}
<div class="bg-white rounded-2xl shadow-md p-6 overflow-x-auto">
    <table id="dosenTable" class="display text-sm min-w-[900px]">
        <thead>
            <tr class="text-[#2d3a32] border-b border-[#e8f0e8]">
                <th class="py-3 px-2 text-left">No</th>
                <th class="py-3 px-2 text-left">Nama Dosen</th>
                <th class="py-3 px-2 text-left">NIK</th>
                <th class="py-3 px-2 text-left">Bidang</th>
                <th class="py-3 px-2 text-left">Tanggal Terdaftar</th>
                <th class="py-3 px-2 text-center">Aksi</th>
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
                            {{-- Tombol Edit --}}
                            <button type="button" class="text-blue-500 hover:text-blue-700 transition"
                            onclick="openEditModal('{{ $dsn->id }}', '{{ $dsn->name }}', '{{ $dsn->nik }}', '{{ $dsn->bidang }}')">
                                ✏️
                            </button>

                            {{-- Tombol Hapus --}}
                            <button type="button" class="text-red-500 hover:text-red-700 transition"
                                onclick="confirmDelete('{{ route('dosen.destroy', $dsn->id) }}', '{{ $dsn->nik }}')">
                                🗑️
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
            <tr>
                <td></td>
                <td></td>
                <td class="text-center text-gray-500">Tidak ada data ditemukan</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- 🌟 Modal Tambah Data --}}
<div id="addModal"
    class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-white w-[400px] rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-green-700 text-white px-5 py-3 font-semibold text-lg">
            Tambah Dosen
        </div>

        <form action="{{ route('dosen.store') }}" method="POST" class="p-5 space-y-4">
            @csrf
            {{-- 🔹 Nama Dosen --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Dosen</label>
                <input type="text" name="name" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-400">
            </div>

            {{-- 🔹 NIK --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                <input type="text" name="nik" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-400">
            </div>

            {{-- 🔹 Bidang --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bidang</label>
                <input type="text" name="bidang" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-400">
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

{{-- 🌟 Modal Edit Data --}}
<div id="editModal"
    class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-white w-[400px] rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-blue-700 text-white px-5 py-3 font-semibold text-lg">
            Edit Dosen
        </div>

        <form id="editForm" method="POST" class="p-5 space-y-4">
            @csrf
            @method('PUT')

            {{-- 🔹 Nama Dosen --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Dosen</label>
                <input type="text" name="name" id="editName" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
            </div>

            {{-- 🔹 NIK --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                <input type="text" name="nik" id="editNik" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
            </div>

            {{-- 🔹 Bidang --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bidang</label>
                <input type="text" name="bidang" id="editBidang" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
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

{{-- Scripts --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const modal = document.getElementById('addModal');
const openModalBtn = document.getElementById('openModalBtn');
const closeModalBtn = document.getElementById('closeModalBtn');

openModalBtn.addEventListener('click', () => {
    modal.classList.remove('hidden', 'opacity-0');
    modal.classList.add('flex');
});

closeModalBtn.addEventListener('click', () => {
    modal.classList.add('hidden', 'opacity-0');
    modal.classList.remove('flex');
});

modal.addEventListener('click', (e) => {
    if (e.target === modal) {
        modal.classList.add('hidden', 'opacity-0');
        modal.classList.remove('flex');
    }
});

$(document).ready(function () {
    const table = $('#dosenTable').DataTable({
        scrollX: false,
        autoWidth: false,
        pageLength: 5,
        language: {
            search: "",
            searchPlaceholder: "Cari dosen...",
            lengthMenu: "Tampilkan _MENU_ data",
            paginate: { previous: "←", next: "→" },
            info: "Menampilkan _START_–_END_ dari _TOTAL_ data"
        }
    });

    $('#filterNama').on('keyup', function () {
        table.column(1).search(this.value).draw();
    });
    $('#filterNik').on('keyup', function () {
        table.column(2).search(this.value).draw();
    });
});

// ✅ SweetAlert Notifikasi
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

// ✅ Modal Edit
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
            editForm.action = `/data-dosen/${id}`;
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
        text: 'Sedang memperbarui data dosen',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading(),
    });
    setTimeout(() => e.target.submit(), 700);
});

// ✅ Konfirmasi Hapus
function confirmDelete(actionUrl, nik) {
    Swal.fire({
        title: `Hapus Dosen ${nik}?`,
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
                text: 'Sedang menghapus data dosen',
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
</script>

<style>
#dosenTable tbody tr:nth-child(odd) { background-color: #fafdfa; }
#dosenTable tbody tr:nth-child(even) { background-color: #ffffff; }
#dosenTable tbody tr:hover { background-color: #f1f8f4; }
table.dataTable.no-footer { border-bottom: none; }
table.dataTable tbody td, table.dataTable thead th { padding: 0.75rem 1rem !important; }
</style>
