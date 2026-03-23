{{-- Header & Tombol Tambah --}}
<div class="flex justify-end items-end">
    <button id="openModalBtn"
        class="bg-[#3ea76a] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-[#2d8c5d] transition">
        + Tambah Jadwal
    </button>
</div>

{{-- Filter Bar --}}
<div class="bg-white rounded-2xl shadow-sm p-5 mb-6">
    <div class="grid grid-cols-3 gap-4 items-end w-full">
        <div>
            <label class="block text-sm font-medium text-[#2d3a32] mb-1">Dosen</label>
            <select id="filterDosen"
                class="w-full border border-[#d8e4d8] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#3ea76a] focus:outline-none">
                <option value="">Semua Dosen</option>
                @foreach ($dosens as $dosen)
                    <option value="{{ $dosen->name }}">{{ $dosen->name }}</option>
                @endforeach
            </select>
        </div>

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

        <div>
            <label class="block text-sm font-medium text-[#2d3a32] mb-1">Jam</label>
            <select id="filterJam"
                class="w-full border border-[#d8e4d8] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#3ea76a] focus:outline-none">
                <option value="">Semua Jam</option>
                @foreach (['08.00 - 08.50', '09.40 - 10.30', '10.30 - 11.20', '13.00 - 14.00', '14.00 - 14.50', '14.50 - 15.40', '15.40 - 16.30', '16.30 - 17.20', '17.20 - 18.10'] as $jam)
                    <option>{{ $jam }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

{{-- DataTable --}}
<div class="bg-white rounded-2xl shadow-md p-6 overflow-x-auto">
    <table id="jadwalTable" class="display text-sm min-w-[950px]">
        <thead>
            <tr class="text-[#2d3a32] border-b border-[#e8f0e8]">
                <th>No</th>
                <th>Dosen</th>
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
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $jadwal->status === 'Kosong' ? 'bg-[#e5f5e8] text-[#3ea76a]' : 'bg-red-100 text-red-600' }}">
                            {{ $jadwal->status }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="flex justify-center gap-3">
                            <button class="text-blue-500 hover:text-blue-700 transition"
                                onclick="openEditModal('{{ $jadwal->id }}', '{{ $jadwal->userId }}', '{{ $jadwal->hari }}', '{{ $jadwal->jam }}', '{{ $jadwal->status }}')">
                                ✏️
                            </button>
                            <button class="text-red-500 hover:text-red-700 transition"
                                onclick="confirmDelete('{{ route('koordinator.jadwal-dosen.destroy', $jadwal->id) }}', '{{ $jadwal->dosen->name ?? '-' }}')">
                                🗑️
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-gray-500 py-4">Tidak ada data jadwal</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal Tambah --}}
<div id="addModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-white w-[500px] rounded-2xl shadow-lg overflow-hidden max-h-[90vh] flex flex-col">
        <div class="bg-gradient-to-r from-green-500 to-green-700 text-white px-5 py-3 font-semibold text-lg flex-shrink-0">
            Tambah Jadwal Dosen (Bulk)
        </div>
        <form action="{{ route('koordinator.jadwal-dosen.store') }}" method="POST" class="p-5 overflow-y-auto w-full">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Dosen</label>
                <select name="userId" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-400">
                    <option value="">-- Pilih Dosen --</option>
                    @foreach ($dosens as $dosen)
                        <option value="{{ $dosen->userId }}">{{ $dosen->name }}</option>
                    @endforeach
                </select>
            </div>

            <div id="jadwal-rows" class="space-y-3">
                <div class="jadwal-row bg-gray-50 border border-gray-200 p-3 rounded-lg flex flex-col gap-2 relative">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-widest sesi-label">Sesi 1</span>
                        <button type="button" class="text-red-500 hover:text-red-700 text-xs font-medium hidden remove-row">Hapus Sesi</button>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Hari</label>
                            <select name="jadwal[0][hari]" required
                                class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm focus:ring-2 focus:ring-green-400">
                                <option>Senin</option><option>Selasa</option><option>Rabu</option><option>Kamis</option><option>Jumat</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Jam</label>
                            <select name="jadwal[0][jam]" required
                                class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm focus:ring-2 focus:ring-green-400">
                                <option>08.00 - 08.50</option><option>08.50 - 09.40</option><option>09.40 - 10.30</option><option>10.30 - 11.20</option><option>11.20 - 12.10</option><option>12.10 - 13.00</option><option>13.00 - 14.00</option><option>14.00 - 14.50</option><option>14.50 - 15.30</option><option>15.30 - 16.30</option><option>16.30 - 17.20</option><option>17.20 - 18.10</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                        <select name="jadwal[0][status]" required
                            class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm focus:ring-2 focus:ring-green-400">
                            <option value="Kosong">Kosong (Tersedia)</option>
                            <option value="Terisi">Terisi (Sibuk)</option>
                        </select>
                    </div>
                </div>
            </div>

            <button type="button" id="addRowBtn" class="mt-3 text-sm flex items-center gap-1 text-green-600 font-medium hover:text-green-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Sesi Lain
            </button>

            <div class="flex justify-end gap-2 mt-5 pt-3 border-t border-gray-100">
                <button type="button" id="closeModalBtn"
                    class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 transition text-sm">Batal</button>
                <button type="submit"
                    class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition text-sm font-medium">Simpan Bulk</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div id="editModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-white w-[400px] rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-blue-600 text-white px-5 py-3 font-semibold text-lg">
            Edit Jadwal Dosen
        </div>

        <form id="editForm" method="POST" class="p-5 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dosen</label>
                <select name="userId" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
                    @foreach ($dosens as $dosen)
                        <option value="{{ $dosen->userId }}">{{ $dosen->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
                <select name="hari" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
                    <option>Senin</option><option>Selasa</option><option>Rabu</option>
                    <option>Kamis</option><option>Jumat</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jam</label>
                <select name="jam" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
                    <option>08.00 - 08.50</option><option>08.50 - 09.40</option><option>09.40 - 10.30</option><option>10.30 - 11.20</option><option>11.20 - 12.10</option><option>12.10 - 13.00</option><option>13.00 - 14.00</option><option>14.00 - 14.50</option><option>14.50 - 15.30</option><option>15.30 - 16.30</option><option>16.30 - 17.20</option><option>17.20 - 18.10</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
                    <option value="Kosong">Kosong</option>
                    <option value="Terisi">Terisi</option>
                </select>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" id="closeEditModalBtn"
                    class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 transition">Batal</button>

                <button type="submit"
                    class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">Simpan</button>
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

let rowCount = 1;

$('#addRowBtn').on('click', function() {
    let newRow = $('.jadwal-row').first().clone();

    newRow.find('select').each(function() {
        let name = $(this).attr('name');
        if (name) {
            $(this).attr('name', name.replace(/\[0\]/, `[${rowCount}]`));
            
            if($(this).attr('name').includes('status')) $(this).val('Kosong');
            else if($(this).attr('name').includes('hari')) $(this).val('Senin');
            else $(this).val('08.00 - 08.50');
        }
    });

    rowCount++;
    newRow.find('.sesi-label').text(`Sesi ${rowCount}`);
    newRow.find('.remove-row').removeClass('hidden');

    newRow.hide().appendTo('#jadwal-rows').slideDown(200);
    updateRemoveButtons();
});

$(document).on('click', '.remove-row', function() {
    $(this).closest('.jadwal-row').slideUp(200, function() {
        $(this).remove();
        updateRemoveButtons();
    });
});

function updateRemoveButtons() {
    if ($('.jadwal-row').length > 1) {
        $('.jadwal-row .remove-row').removeClass('hidden');
    } else {
        $('.jadwal-row .remove-row').addClass('hidden');
    }
}

$(document).ready(function () {
    const table = $('#jadwalTable').DataTable({
        pageLength: 5,
        lengthChange: true,
        lengthMenu: [5, 10, 25, 50, 100],
        language: {
            search: "",
            searchPlaceholder: "Cari jadwal dosen...",
            lengthMenu: "Tampilkan _MENU_ data",
            paginate: { previous: "←", next: "→" },
            info: "Menampilkan _START_–_END_ dari _TOTAL_ data"
        },
        dom:
            "<'flex justify-between items-center mb-4 flex-wrap gap-3'<'dataTables_length_wrapper'l><'dataTables_filter_wrapper'f>>" +
            "tr" +
            "<'flex justify-between items-center mt-3 flex-wrap gap-3'<'dataTables_info_wrapper'i><'dataTables_pagination_wrapper'p>>"
    });

    $('#filterDosen, #filterHari, #filterJam').on('change', function () {
        table.column(1).search($('#filterDosen').val());
        table.column(2).search($('#filterHari').val());
        table.column(3).search($('#filterJam').val());
        table.draw();
    });
});

const editModal = document.getElementById('editModal');
const closeEditModalBtn = document.getElementById('closeEditModalBtn');
const editForm = document.getElementById('editForm');

function openEditModal(id, userId, hari, jam, status) {
    Swal.fire({
        title: 'Edit Jadwal?',
        text: "Apakah Anda yakin ingin mengubah jadwal ini?",
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

            document.querySelector('#editForm select[name="userId"]').value = userId;
            document.querySelector('#editForm select[name="hari"]').value = hari;
            document.querySelector('#editForm select[name="jam"]').value = jam;
            document.querySelector('#editForm select[name="status"]').value = status;

            editForm.action = `/dashboard/koordinator/jadwal-dosen/${id}`;
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
        text: 'Mohon tunggu, sedang memperbarui jadwal',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading(),
    });

    setTimeout(() => {
        e.target.submit();
    }, 700);
});

function confirmDelete(actionUrl, dosenName) {
    Swal.fire({
        title: `Hapus jadwal ${dosenName}?`,
        text: "Data jadwal ini akan dihapus permanen.",
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
                text: 'Sedang menghapus data jadwal',
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
