{{-- Header & Tombol Tambah --}}
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold text-[#2d3a32]">Data Skripsi Mahasiswa</h2>

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
            <label class="block text-sm font-medium text-[#2d3a32] mb-1">Nama Mahasiswa</label>
            <input type="text" id="filterNama" placeholder="Cari nama mahasiswa..."
                class="w-full border border-[#d8e4d8] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#3ea76a] focus:outline-none" />
        </div>

        <div class="w-full">
            <label class="block text-sm font-medium text-[#2d3a32] mb-1">NIM</label>
            <input type="text" id="filterNim" placeholder="Cari NIM..."
                class="w-full border border-[#d8e4d8] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#3ea76a] focus:outline-none" />
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
                <th class="py-3 px-2 text-left">Judul Skripsi</th>
                <th class="py-3 px-2 text-left">Bidang</th>
                <th class="py-3 px-2 text-left">Tanggal Dibuat</th>
                <th class="py-3 px-2 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($skripsis as $index => $skripsi)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $skripsi->nama_mahasiswa }}</td>
                    <td>{{ $skripsi->judul_skripsi }}</td>
                    <td>{{ $skripsi->bidang }}</td>
                    <td>{{ $skripsi->created_at?->format('d M Y') ?? '-' }}</td>
                    <td class="text-center">
                        <div class="flex justify-center gap-3">
                            {{-- Tombol Edit --}}
                            <button type="button" class="text-blue-500 hover:text-blue-700 transition"
                            onclick="openEditModal('{{ $skripsi->id }}', '{{ $skripsi->nama_mahasiswa }}', '{{ $skripsi->judul_skripsi }}', '{{ $skripsi->bidang }}')">
                                ✏️
                            </button>

                            {{-- Tombol Hapus --}}
                            <button type="button" class="text-red-500 hover:text-red-700 transition"
                                onclick="confirmDelete('{{ route('skripsi.destroy', $skripsi->id) }}', '{{ $skripsi->judul_skripsi }}')">
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
            Tambah Skripsi
        </div>

        <form action="{{ route('skripsi.store') }}" method="POST" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Mahasiswa</label>
                <select name="nama_mahasiswa" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-400">
                    <option value="">-- Pilih Mahasiswa --</option>
                    @foreach ($mahasiswas as $m)
                        <option value="{{ $m->name }}">{{ $m->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Skripsi</label>
                <input type="text" name="judul_skripsi" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-400">
            </div>

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
            Edit Skripsi
        </div>

        <form id="editForm" method="POST" class="p-5 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Mahasiswa</label>
                <select name="nama_mahasiswa" id="editNama" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
                    <option value="">-- Pilih Mahasiswa --</option>
                    @foreach ($mahasiswas as $m)
                        <option value="{{ $m->name }}">{{ $m->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Skripsi</label>
                <input type="text" name="judul_skripsi" id="editJudul" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
            </div>

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
    const table = $('#skripsiTable').DataTable({
        scrollX: false,
        autoWidth: false,
        pageLength: 5,
        lengthChange: true,
        lengthMenu: [5, 10, 25, 50, 100],
        language: {
            search: "",
            searchPlaceholder: "Cari skripsi...",
            lengthMenu: "Tampilkan _MENU_ data",
            paginate: { previous: "←", next: "→" },
            info: "Menampilkan _START_–_END_ dari _TOTAL_ data"
        }
    });

    $('#filterNama').on('keyup', function () {
        table.column(1).search(this.value).draw();
    });
    $('#filterNim').on('keyup', function () {
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

const editModal = document.getElementById('editModal');
const closeEditModalBtn = document.getElementById('closeEditModalBtn');
const editForm = document.getElementById('editForm');

function openEditModal(id, namaMahasiswa, judul, bidang) {
    Swal.fire({
        title: `Edit "${judul}"?`,
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

            document.getElementById('editNama').value = namaMahasiswa;
            document.getElementById('editJudul').value = judul;
            document.getElementById('editBidang').value = bidang;
            editForm.action = `/skripsi/${id}`;
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
        text: 'Sedang memperbarui data skripsi',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading(),
    });
    setTimeout(() => e.target.submit(), 700);
});

// ✅ Konfirmasi Hapus
function confirmDelete(actionUrl, judul) {
    Swal.fire({
        title: `Hapus "${judul}"?`,
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
                text: 'Sedang menghapus data skripsi',
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
#skripsiTable tbody tr:nth-child(odd) { background-color: #fafdfa; }
#skripsiTable tbody tr:nth-child(even) { background-color: #ffffff; }
#skripsiTable tbody tr:hover { background-color: #f1f8f4; }
table.dataTable.no-footer { border-bottom: none; }
table.dataTable tbody td, table.dataTable thead th { padding: 0.75rem 1rem !important; }
/* Search Bar DataTables */
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
</style>
