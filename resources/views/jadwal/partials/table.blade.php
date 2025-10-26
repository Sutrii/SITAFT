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
        <div class="w-full">
            <label class="block text-sm font-medium text-[#2d3a32] mb-1">Jenis Seminar</label>
            <select id="filterStatus"
                class="w-full border border-[#d8e4d8] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#3ea76a] focus:outline-none">
                <option value="">Semua</option>
                <option>Seminar Proposal</option>
                <option>Seminar Hasil</option>
                <option>Sidang Akhir</option>
            </select>
        </div>
    </div>
</div>

{{-- DataTable --}}
<div class="bg-white rounded-2xl shadow-md p-6">
    <div class="overflow-x-auto">
        <table id="jadwalTable" class="display text-sm min-w-[950px]">
            <thead class="sticky top-0 bg-white z-10">
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
                        <td>
                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                @if ($jadwal->status == 'Seminar Proposal') bg-green-100 text-green-600
                                @elseif ($jadwal->status == 'Seminar Hasil') bg-blue-100 text-blue-600
                                @else bg-yellow-100 text-yellow-600 @endif">
                                {{ $jadwal->status }}
                            </span>
                        </td>
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
                        <td colspan="9" class="text-center text-gray-500 py-4">Tidak ada data ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Tambah Data --}}
<div id="addModal"
    class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-white w-[450px] rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-green-700 text-white px-5 py-3 font-semibold text-lg">
            Tambah Jadwal
        </div>

        <form action="{{ route('jadwal.store') }}" method="POST" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">Mahasiswa</label>
                <select name="mahasiswaId" required class="w-full border rounded-lg px-3 py-2">
                    <option value="">-- Pilih Mahasiswa --</option>
                    @foreach ($mahasiswas as $m)
                        <option value="{{ $m->id }}">{{ $m->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Judul Skripsi</label>
                <select name="skripsiId" required class="w-full border rounded-lg px-3 py-2">
                    <option value="">-- Pilih Skripsi --</option>
                    @foreach ($skripsis as $s)
                        <option value="{{ $s->id }}">{{ $s->judul_skripsi }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium mb-1">Dosen Penguji 1</label>
                    <select name="dosenId1" required class="w-full border rounded-lg px-3 py-2">
                        <option value="">-- Pilih Dosen --</option>
                        @foreach ($dosens as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Dosen Penguji 2</label>
                    <select name="dosenId2" required class="w-full border rounded-lg px-3 py-2">
                        <option value="">-- Pilih Dosen --</option>
                        @foreach ($dosens as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Tanggal & Jam Seminar</label>
                <input type="datetime-local" name="jadwal_seminar" required
                    class="w-full border rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Status</label>
                <select name="status" required class="w-full border rounded-lg px-3 py-2">
                    <option>Seminar Proposal</option>
                    <option>Seminar Hasil</option>
                    <option>Sidang Akhir</option>
                </select>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" id="closeModalBtn"
                    class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 transition">Batal</button>
                <button type="submit"
                    class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit Data --}}
<div id="editModal"
    class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-white w-[450px] rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-blue-700 text-white px-5 py-3 font-semibold text-lg">
            Edit Jadwal
        </div>

        <form id="editForm" method="POST" class="p-5 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-1">Mahasiswa</label>
                <select name="mahasiswaId" id="editMahasiswa" required class="w-full border rounded-lg px-3 py-2">
                    <option value="">-- Pilih Mahasiswa --</option>
                    @foreach ($mahasiswas as $m)
                        <option value="{{ $m->id }}">{{ $m->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Judul Skripsi</label>
                <select name="skripsiId" id="editSkripsi" required class="w-full border rounded-lg px-3 py-2">
                    <option value="">-- Pilih Skripsi --</option>
                    @foreach ($skripsis as $s)
                        <option value="{{ $s->id }}">{{ $s->judul_skripsi }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium mb-1">Dosen Penguji 1</label>
                    <select name="dosenId1" id="editDosen1" required class="w-full border rounded-lg px-3 py-2">
                        <option value="">-- Pilih Dosen --</option>
                        @foreach ($dosens as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Dosen Penguji 2</label>
                    <select name="dosenId2" id="editDosen2" required class="w-full border rounded-lg px-3 py-2">
                        <option value="">-- Pilih Dosen --</option>
                        @foreach ($dosens as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Tanggal & Jam Seminar</label>
                <input type="datetime-local" name="jadwal_seminar" id="editTanggal" required
                    class="w-full border rounded-lg px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Status</label>
                <select name="status" id="editStatus" required class="w-full border rounded-lg px-3 py-2">
                    <option>Seminar Proposal</option>
                    <option>Seminar Hasil</option>
                    <option>Sidang Akhir</option>
                </select>
            </div>

            <div class="flex justify-end gap-2">
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
$(document).ready(() => {
    const table = $('#jadwalTable').DataTable({
        scrollX: false,
        autoWidth: false,
        pageLength: 5,
        language: {
            search: "",
            searchPlaceholder: "Cari jadwal...",
            lengthMenu: "Tampilkan _MENU_ data",
            paginate: { previous: "←", next: "→" },
            info: "Menampilkan _START_–_END_ dari _TOTAL_ data"
        }
    });

    $('#filterNama').on('keyup', function () {
        table.column(1).search(this.value).draw();
    });

    $('#filterStatus').on('change', function () {
        table.column(7).search(this.value).draw();
    });
});

const modal = document.getElementById('addModal');
const openModalBtn = document.getElementById('openModalBtn');
const closeModalBtn = document.getElementById('closeModalBtn');
openModalBtn.addEventListener('click', () => modal.classList.replace('hidden', 'flex'));
closeModalBtn.addEventListener('click', () => modal.classList.replace('flex', 'hidden'));
modal.addEventListener('click', (e) => { if (e.target === modal) modal.classList.replace('flex', 'hidden'); });

const editModal = document.getElementById('editModal');
const closeEditModalBtn = document.getElementById('closeEditModalBtn');
const editForm = document.getElementById('editForm');

function openEditModal(id, mahasiswaId, skripsiId, dosen1, dosen2, waktu, status) {
    Swal.fire({
        title: 'Edit Jadwal?',
        text: 'Apakah Anda yakin ingin mengubah data ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, ubah'
    }).then((res) => {
        if (res.isConfirmed) {
            editModal.classList.replace('hidden', 'flex');

            document.getElementById('editMahasiswa').value = mahasiswaId;
            document.getElementById('editSkripsi').value = skripsiId;
            document.getElementById('editDosen1').value = dosen1;
            document.getElementById('editDosen2').value = dosen2;
            document.getElementById('editTanggal').value = waktu.replace(' ', 'T');
            document.getElementById('editStatus').value = status;

            editForm.action = `/jadwal/${id}`;
        }
    });
}

closeEditModalBtn.addEventListener('click', () => editModal.classList.replace('flex', 'hidden'));

function confirmDelete(url, nama) {
    Swal.fire({
        title: `Hapus jadwal ${nama}?`,
        text: 'Data ini tidak bisa dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e3342f',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((res) => {
        if (res.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            form.innerHTML = `@csrf @method('DELETE')`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>

<style>
.overflow-x-auto {
    width: 100%;
    overflow-x: auto;
    overflow-y: hidden;
}

.overflow-x-auto::-webkit-scrollbar {
    height: 8px;
}
.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 9999px;
}
.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

#jadwalTable thead th {
    position: sticky;
    top: 0;
    background: #ffffff;
    z-index: 10;
    box-shadow: 0 1px 0 #e8f0e8;
}

#jadwalTable {
    width: 100% !important;
    table-layout: auto !important;
}

.dataTables_wrapper {
    overflow-x: hidden !important;
}

#jadwalTable th,
#jadwalTable td {
    white-space: nowrap;
    padding: 0.75rem 1rem !important;
    text-align: left;
}

#jadwalTable td:nth-child(3) {
    white-space: normal !important;
    word-break: break-word;
    max-width: 300px; 
}

#jadwalTable tbody tr:nth-child(odd) { background-color: #fafdfa; }
#jadwalTable tbody tr:nth-child(even) { background-color: #ffffff; }
#jadwalTable tbody tr:hover { background-color: #f1f8f4; }

.dataTables_length select {
    border: 1px solid #d8e4d8;
    border-radius: 8px;
    padding: 8px 40px 8px 14px;
    font-size: 0.875rem;
    background-color: #fff;
    color: #2d3a32;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg fill='none' stroke='%236b7d6f' stroke-width='1.5' viewBox='0 0 24 24'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M8.25 9.75L12 13.5l3.75-3.75'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 8px center;
    background-size: 14px;
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
</style>
