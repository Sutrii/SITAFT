{{-- Header & Tombol Tambah --}}
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold text-[#2d3a32]">Jadwal Dosen</h2>
    <button id="openModalBtn"
        class="border border-[#3ea76a] text-[#3ea76a] hover:bg-[#e5f5e8] px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition-all">
        + Tambah Data
    </button>
</div>

{{-- Filter Bar --}}
<div class="bg-white rounded-2xl shadow-sm p-5 mb-6">
    <div class="grid grid-cols-3 gap-4 items-end w-full">
        {{-- Pilih Dosen --}}
        <div>
            <label class="block text-sm font-medium text-[#2d3a32] mb-1">Pilih Dosen</label>
            <select id="filterDosen"
                class="w-full border border-[#d8e4d8] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#3ea76a] focus:outline-none">
                <option value="">Semua Dosen</option>
                @foreach ($dosens as $dosen)
                    <option value="{{ $dosen->name }}">{{ $dosen->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Pilih Hari --}}
        <div>
            <label class="block text-sm font-medium text-[#2d3a32] mb-1">Hari</label>
            <select id="filterHari"
                class="w-full border border-[#d8e4d8] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#3ea76a] focus:outline-none">
                <option value="">Semua Hari</option>
                @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $hari)
                    <option>{{ $hari }}</option>
                @endforeach
            </select>
        </div>

        {{-- Pilih Jam --}}
        <div>
            <label class="block text-sm font-medium text-[#2d3a32] mb-1">Jam</label>
            <select id="filterJam"
                class="w-full border border-[#d8e4d8] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#3ea76a] focus:outline-none">
                <option value="">Semua Jam</option>
                @foreach (['08.00 - 08.50', '09.40 - 10.30', '10.30 - 11.20', '13.00 - 14.00'] as $jam)
                    <option>{{ $jam }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

{{-- DataTable --}}
<div class="bg-white rounded-2xl shadow-md p-6 overflow-x-auto">
    <table id="jadwalTable" class="display text-sm min-w-[900px]">
        <thead>
            <tr class="text-[#2d3a32] border-b border-[#e8f0e8]">
                <th>No</th>
                <th>Nama Dosen</th>
                <th>Hari</th>
                <th>Jam</th>
                <th>Status</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($jadwals as $index => $jadwal)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $jadwal->dosen->name ?? '-' }}</td>
                    <td>{{ $jadwal->hari }}</td>
                    <td>{{ $jadwal->jam }}</td>
                    <td>
                        <span
                            class="px-3 py-1 rounded-full text-xs font-medium {{ $jadwal->status === 'Kosong' ? 'bg-[#e5f5e8] text-[#3ea76a]' : 'bg-red-100 text-red-600' }}">
                            {{ $jadwal->status }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="flex justify-center gap-3">
                            {{-- Tombol Edit --}}
                            <button type="button" class="text-blue-500 hover:text-blue-700 transition"
                                onclick="openEditModal('{{ $jadwal->id }}', '{{ $jadwal->userId }}', '{{ $jadwal->hari }}', '{{ $jadwal->jam }}', '{{ $jadwal->status }}')">
                                ✏️
                            </button>
                            {{-- Tombol Hapus --}}
                            <button type="button" class="text-red-500 hover:text-red-700 transition"
                                onclick="confirmDelete('{{ route('jadwal-dosen.destroy', $jadwal->id) }}', '{{ $jadwal->dosen->name ?? '-' }}')">
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

{{-- Modal Tambah Data --}}
<div id="addModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-white w-[400px] rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-green-700 text-white px-5 py-3 font-semibold text-lg">
            Tambah Jadwal Dosen
        </div>
        <form action="{{ route('jadwal-dosen.store') }}" method="POST" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dosen</label>
                <select name="userId" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-400">
                    <option value="">-- Pilih Dosen --</option>
                    @foreach ($dosens as $dosen)
                        <option value="{{ $dosen->userId }}">{{ $dosen->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
                <select name="hari" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-400">
                    <option>Senin</option>
                    <option>Selasa</option>
                    <option>Rabu</option>
                    <option>Kamis</option>
                    <option>Jumat</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jam</label>
                <select name="jam" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-400">
                    <option>08.00 - 08.50</option>
                    <option>09.40 - 10.30</option>
                    <option>10.30 - 11.20</option>
                    <option>13.00 - 14.00</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-400">
                    <option value="Kosong">Kosong</option>
                    <option value="Terisi">Terisi</option>
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
            Edit Jadwal Dosen
        </div>
        <form id="editForm" method="POST" class="p-5 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dosen</label>
                <select name="userId" id="editUserId" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
                    @foreach ($dosens as $dosen)
                        <option value="{{ $dosen->userId }}">{{ $dosen->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
                <select name="hari" id="editHari" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
                    <option>Senin</option><option>Selasa</option><option>Rabu</option><option>Kamis</option><option>Jumat</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jam</label>
                <select name="jam" id="editJam" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
                    <option>08.00 - 08.50</option><option>09.40 - 10.30</option><option>10.30 - 11.20</option><option>13.00 - 14.00</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="editStatus" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
                    <option value="Kosong">Kosong</option>
                    <option value="Terisi">Terisi</option>
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

{{-- Scripts --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const modal = document.getElementById('addModal');
const openModalBtn = document.getElementById('openModalBtn');
const closeModalBtn = document.getElementById('closeModalBtn');

openModalBtn.addEventListener('click', () => modal.classList.replace('hidden', 'flex'));
closeModalBtn.addEventListener('click', () => modal.classList.replace('flex', 'hidden'));
modal.addEventListener('click', (e) => { if (e.target === modal) modal.classList.replace('flex', 'hidden'); });

$(document).ready(function () {
    const table = $('#jadwalTable').DataTable({
        scrollX: false, autoWidth: false, pageLength: 5,
        language: {
            search: "", searchPlaceholder: "Cari jadwal dosen...",
            lengthMenu: "Tampilkan _MENU_ data",
            paginate: { previous: "←", next: "→" },
            info: "Menampilkan _START_–_END_ dari _TOTAL_ data"
        }
    });
    $('#filterDosen, #filterHari, #filterJam').on('change', function () {
        table.columns(1).search($('#filterDosen').val());
        table.columns(2).search($('#filterHari').val());
        table.columns(3).search($('#filterJam').val());
        table.draw();
    });
});

@if (session('success'))
Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', showConfirmButton: false, timer: 1800 });
@endif
@if (session('error'))
Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session('error') }}' });
@endif

const editModal = document.getElementById('editModal');
const closeEditModalBtn = document.getElementById('closeEditModalBtn');
const editForm = document.getElementById('editForm');

function openEditModal(id, userId, hari, jam, status) {
    Swal.fire({
        title: "Edit Jadwal?",
        text: "Apakah Anda yakin ingin mengubah data ini?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, ubah',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            editModal.classList.replace('hidden', 'flex');
            document.getElementById('editUserId').value = userId;
            document.getElementById('editHari').value = hari;
            document.getElementById('editJam').value = jam;
            document.getElementById('editStatus').value = status;
            editForm.action = `/jadwal-dosen/${id}`;
        }
    });
}
closeEditModalBtn.addEventListener('click', () => editModal.classList.replace('flex', 'hidden'));
editModal.addEventListener('click', (e) => { if (e.target === editModal) editModal.classList.replace('flex', 'hidden'); });

function confirmDelete(actionUrl, dosen) {
    Swal.fire({
        title: `Hapus jadwal ${dosen}?`,
        text: "Data ini tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e3342f',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = actionUrl;
            form.innerHTML = `@csrf @method('DELETE')`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>

<style>
#jadwalTable tbody tr:nth-child(odd) { background-color: #fafdfa; }
#jadwalTable tbody tr:nth-child(even) { background-color: #ffffff; }
#jadwalTable tbody tr:hover { background-color: #f1f8f4; }
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
