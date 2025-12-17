{{-- Header & Tombol Tambah --}}
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold text-[#2d3a32]">Jadwal Tugas Akhir</h2>

    @if(!(Auth::user()->roleId == 1 && Auth::user()->positionId == 3))
        <div class="flex items-center gap-3">
            <button id="openImportModalBtn"
                class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-600 transition">
                📥 Import Excel
            </button>

            <button id="openModalBtn"
                class="bg-[#3ea76a] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-[#2d8c5d] transition">
                + Tambah Jadwal
            </button>
        </div>
    @endif
</div>

{{-- Filter Bar --}}
<div class="bg-white rounded-2xl shadow-sm p-5 mb-6">
    <div class="grid grid-cols-3 gap-4 items-end w-full">
        <div class="w-full">
            <label class="block text-sm font-medium text-[#2d3a32] mb-1">Nama Mahasiswa</label>
            <input type="text" id="filterNama" placeholder="Cari nama mahasiswa..."
                class="w-full border border-[#d8e4d8] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#3ea76a] focus:outline-none" />
        </div>

        <div>
            <label class="block text-sm font-medium text-[#2d3a32] mb-1">Nama Dosen</label>
            <input type="text" id="filterDosen" placeholder="Cari nama dosen..."
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

            {{-- Tombol Share disembunyikan jika Mahasiswa Viewer --}}
            @if(!(Auth::user()->roleId == 1 && Auth::user()->positionId == 3))
                <button id="shareLinkBtn"
                    class="h-[38px] px-4 ml-2.5 flex items-center gap-1 bg-[#3ea76a] text-white rounded-lg text-sm font-medium hover:bg-[#2d8c5d] transition">
                    🔗 Share Link
                </button>
            @endif
        </div>
    </div>
</div>

{{-- DataTable --}}
<div class="bg-white rounded-2xl shadow-md p-6 overflow-x-auto">
    <table id="jadwalTable" class="display text-sm min-w-[950px] w-full border-collapse">
        <thead>
            <tr class="text-[#2d3a32] border-b border-[#e8f0e8]">
                <th>No</th>
                <th>Mahasiswa</th>
                <th>Judul Skripsi</th>
                <th>Dosen Pembimbing 1</th>
                <th>Dosen Pembimbing 2</th>
                <th>Dosen Penguji 1</th>
                <th>Dosen Penguji 2</th>
                <th>Tanggal</th>
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
                <th>Status</th>
                <th class="text-center aksi-col {{ Auth::user()->roleId == 1 && Auth::user()->positionId == 3 ? 'hidden' : '' }}">
                    Aksi
                </th>
            </tr>
        </thead>

        <tbody>
        @forelse ($jadwals as $index => $jadwal)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $jadwal->mahasiswa->name ?? '-' }}</td>
                <td>{{ $jadwal->skripsi->judul_skripsi ?? '-' }}</td>
                <td>{{ $jadwal->skripsi->dosen1->name ?? '-' }}</td>
                <td>{{ $jadwal->skripsi->dosen2->name ?? '-' }}</td>
                <td>{{ $jadwal->dosen1->name ?? '-' }}</td>
                <td>{{ $jadwal->dosen2->name ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($jadwal->jadwal_seminar)->format('Y-m-d') }}</td>
                <td>{{ \Carbon\Carbon::parse($jadwal->jadwal_seminar)->format('H:i') }}</td>
                <td>{{ \Carbon\Carbon::parse($jadwal->jadwal_seminar_selesai)->format('H:i') }}</td>
                @php
                    $status = $jadwal->status;
                    $badgeColor = match($status) {
                        'Seminar Proposal' => 'background-color:#fde8e8; color:#b91c1c;', // merah lembut
                        'Seminar Hasil' => 'background-color:#e0e7ff; color:#3730a3;',   // biru lembut
                        'Sidang Akhir' => 'background-color:#dcfce7; color:#166534;',   // hijau lembut
                        default => 'background-color:#f3f4f6; color:#374151;'           // abu default
                    };
                @endphp

                <td class="text-left" data-status="{{ $jadwal->status }}">
                    <span style="
                        display:inline-block;
                        min-width:90px;
                        text-align:left;
                        padding:2px 8px;
                        border-radius:12px;
                        font-size:0.78rem;
                        font-weight:600;
                        line-height:1.2;
                        white-space:nowrap;
                        {{ $badgeColor }};
                    ">
                        {{ $jadwal->status }}
                    </span>
                </td>

                <td class="text-center aksi-col {{ Auth::user()->roleId == 1 && Auth::user()->positionId == 3 ? 'hidden' : '' }}">
                    @if(!(Auth::user()->roleId == 1 && Auth::user()->positionId == 3))
                        <div class="flex justify-center gap-3">
                            <button type="button" class="text-blue-500 hover:text-blue-700 transition"
                            onclick="openEditModal('{{ $jadwal->id }}','{{ $jadwal->mahasiswaId }}','{{ $jadwal->skripsiId }}','{{ $jadwal->dosenId1 }}','{{ $jadwal->dosenId2 }}','{{ $jadwal->jadwal_seminar }}','{{ $jadwal->jadwal_seminar_selesai }}','{{ $jadwal->status }}','{{ $jadwal->ruang }}')">
                                ✏️
                            </button>
                            <button type="button" class="text-red-500 hover:text-red-700 transition"
                                onclick="confirmDelete('{{ route('jadwal.destroy', $jadwal->id) }}', '{{ $jadwal->mahasiswa->name ?? '-' }}')">
                                🗑️
                            </button>
                        </div>
                    @endif
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dosen Pembimbing 1</label>
                    <input type="text" id="pembimbing1" readonly
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dosen Pembimbing 2</label>
                    <input type="text" id="pembimbing2" readonly
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100 cursor-not-allowed">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal & Jam Seminar Mulai</label>
                <input type="datetime-local" name="jadwal_seminar" id="jadwalMulai" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal & Jam Seminar Selesai</label>
                <input type="datetime-local" name="jadwal_seminar_selesai" id="jadwalSelesai" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2">
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
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="Seminar Proposal">Seminar Proposal</option>
                    <option value="Seminar Hasil">Seminar Hasil</option>
                    <option value="Sidang Akhir">Sidang Akhir</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ruangan</label>
                <select name="ruang" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">-- Pilih Ruangan --</option>
                    <option value="Seminar PSTI 1">Seminar PSTI 1</option>
                    <option value="Seminar PSTI 2">Seminar PSTI 2</option>
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dosen Pembimbing 1</label>
                    <input type="text" id="editPembimbing1" readonly
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dosen Pembimbing 2</label>
                    <input type="text" id="editPembimbing2" readonly
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100 cursor-not-allowed">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal & Jam Seminar Mulai</label>
                <input type="datetime-local" name="jadwal_seminar" id="editTanggal" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal & Jam Seminar Selesai</label>
                <input type="datetime-local" name="jadwal_seminar_selesai" id="editTanggalSelesai" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2">
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
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="editStatus" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option>Seminar Proposal</option>
                    <option>Seminar Hasil</option>
                    <option>Sidang Akhir</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ruangan</label>
                <select name="ruang" id="editRuang" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">-- Pilih Ruangan --</option>
                    <option value="Seminar PSTI 1">Seminar PSTI 1</option>
                    <option value="Seminar PSTI 2">Seminar PSTI 2</option>
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

{{-- Modal Import --}}
<div id="importModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-white w-[400px] rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-blue-700 text-white px-5 py-3 font-semibold text-lg">
            Import Jadwal
        </div>

        <form action="{{ route('jadwal.import') }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status Seminar</label>
                <select name="status" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">-- Pilih Status --</option>
                    <option value="Seminar Proposal">Seminar Proposal</option>
                    <option value="Seminar Hasil">Seminar Hasil</option>
                    <option value="Sidang Akhir">Sidang Akhir</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Upload File Excel</label>
                <input type="file" name="file" required
                    accept=".xlsx,.xls,.csv"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" id="closeImportModalBtn"
                    class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 transition">Batal</button>
                <button type="submit"
                    class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">Import</button>
            </div>
        </form>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).on('change', 'select[name="skripsiId"]', function() {
    const skripsiId = $(this).val();
    if (!skripsiId) return;

    fetch(`/skripsi/${skripsiId}/detail`)
        .then(res => res.json())
        .then(data => {
            if (data.error) return;

            $('#pembimbing1').val(data.dosen1.name || '-');
            $('#pembimbing2').val(data.dosen2.name || '-');

            $('select[name="dosenId1"]').val('');
            $('select[name="dosenId2"]').val('');

            ['#pembimbing1', '#pembimbing2'].forEach(sel => {
                $(sel).css('box-shadow', '0 0 0 3px #bbf7d0');
                setTimeout(() => $(sel).css('box-shadow', 'none'), 1200);
            });
        })
        .catch(err => console.error(err));
});

$(document).on('change', 'select[name="mahasiswaId"]', function() {
    const mhsId = $(this).val();
    if (!mhsId) return;

    fetch(`/mahasiswa/${mhsId}/skripsi`)
        .then(res => res.json())
        .then(data => {
            if (data.error) return;

            $('select[name="skripsiId"]').val(data.id).trigger("change");
            $('#pembimbing1').val(data.dosen1);
            $('#pembimbing2').val(data.dosen2);
        })
        .catch(err => console.error(err));

    fetch(`/jadwal/by-mahasiswa/${mhsId}`)
        .then(res => res.json())
        .then(data => {
            if (!data.exists) return;

            $('#jadwalMulai').val(
                data.jadwal_seminar.replace(' ', 'T')
            );

            $('#jadwalSelesai').val(
                data.jadwal_seminar_selesai.replace(' ', 'T')
            );

            ['#jadwalMulai', '#jadwalSelesai'].forEach(sel => {
                $(sel).css('box-shadow', '0 0 0 3px #bbf7d0');
                setTimeout(() => $(sel).css('box-shadow', 'none'), 1000);
            });
        })
        .catch(err => console.error(err));
});

let allDosens = [];

fetch(`/dosen/all`)
    .then(res => res.json())
    .then(data => allDosens = data)
    .catch(err => console.error(err));

    $(document).on('change', 'select[name="dosenId1"]', function() {
        const val = $(this).val();
        const select2 = $('select[name="dosenId2"]');
        const currentVal2 = select2.val();

        if (val && !currentVal2) {
            select2.empty().append('<option value="">-- Pilih Dosen Penguji 2 --</option>');
            allDosens.forEach(d => select2.append(`<option value="${d.id}">${d.name}</option>`));
        }
    });

    $(document).on('change', 'select[name="dosenId2"]', function() {
        const val = $(this).val();
        const select1 = $('select[name="dosenId1"]');
        const currentVal1 = select1.val();

        if (val && !currentVal1) {
            select1.empty().append('<option value="">-- Pilih Dosen Penguji 1 --</option>');
            allDosens.forEach(d => select1.append(`<option value="${d.id}">${d.name}</option>`));
        }
    });

    $(document).on(
        'change',
        'input[name="jadwal_seminar"], input[name="jadwal_seminar_selesai"]',
        function () {
            const tanggal = $('input[name="jadwal_seminar"]').val();
            const tanggalSelesai = $('input[name="jadwal_seminar_selesai"]').val();
            const skripsiId = $('select[name="skripsiId"]').val();

            if (!tanggal || !tanggalSelesai || !skripsiId) return;

            const url = `/skripsi/${skripsiId}/auto-penguji-by-tanggal?mulai=${encodeURIComponent(tanggal)}&selesai=${encodeURIComponent(tanggalSelesai)}`;

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    if (data.error) return;

                    const selectPenguji1 = $('select[name="dosenId1"]');
                    const selectPenguji2 = $('select[name="dosenId2"]');

                    if (!selectPenguji1.find(`option[value="${data.penguji1.id}"]`).length) {
                        selectPenguji1.append(`<option value="${data.penguji1.id}">${data.penguji1.name}</option>`);
                    }
                    if (!selectPenguji2.find(`option[value="${data.penguji2.id}"]`).length) {
                        selectPenguji2.append(`<option value="${data.penguji2.id}">${data.penguji2.name}</option>`);
                    }

                    selectPenguji1.val(data.penguji1.id);
                    selectPenguji2.val(data.penguji2.id);
                })
                .catch(err => console.error(err));
        }
    );

    $(document).on('change', 'select[name="skripsiId"], select[name="status"]', function () {
        const skripsiId = $('select[name="skripsiId"]').val();
        const status = $('select[name="status"]').val();

        if (!skripsiId) return;

        if (status === 'Seminar Proposal') return;

        fetch(`/skripsi/${skripsiId}/penguji-proposal`)
            .then(res => res.json())
            .then(data => {
                if (!data.exists) return;

                const penguji1 = $('select[name="dosenId1"]');
                const penguji2 = $('select[name="dosenId2"]');

                if (!penguji1.find(`option[value="${data.penguji1.id}"]`).length) {
                    penguji1.append(`<option value="${data.penguji1.id}">${data.penguji1.name}</option>`);
                }
                if (!penguji2.find(`option[value="${data.penguji2.id}"]`).length) {
                    penguji2.append(`<option value="${data.penguji2.id}">${data.penguji2.name}</option>`);
                }

                penguji1.val(data.penguji1.id);
                penguji2.val(data.penguji2.id);

                penguji1.css("box-shadow", "0 0 0 3px #bbf7d0");
                penguji2.css("box-shadow", "0 0 0 3px #bbf7d0");
                setTimeout(() => {
                    penguji1.css("box-shadow", "none");
                    penguji2.css("box-shadow", "none");
                }, 1000);
            });
    });

</script>

<script>
const addModal = document.getElementById('addModal');
const openModalBtn = document.getElementById('openModalBtn');
const closeModalBtn = document.getElementById('closeModalBtn');

openModalBtn?.addEventListener('click', () => {
    addModal.classList.remove('hidden', 'opacity-0');
    addModal.classList.add('flex');
});

closeModalBtn?.addEventListener('click', () => {
    addModal.classList.add('hidden', 'opacity-0');
    addModal.classList.remove('flex');
});

addModal?.addEventListener('click', (e) => {
    if (e.target === addModal) {
        addModal.classList.add('hidden', 'opacity-0');
        addModal.classList.remove('flex');
    }
});

$(document).ready(() => {
    const isMahasiswa = {{ Auth::user()->roleId == 1 && Auth::user()->positionId == 3 ? 'true' : 'false' }};
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
            "<'flex justify-between items-center mt-3 flex-wrap gap-3'<'dataTables_info_wrapper'i><'dataTables_pagination_wrapper'p>>",
            columnDefs: [
                {
                    targets: 10,    
                    render: function (data, type, row) {
                        if (type === 'display') return data;
                        return $(data).text();
                    }
                },
                { targets: isMahasiswa ? [] : [11], orderable: false, searchable: false },
                { targets: isMahasiswa ? [11] : [], visible: !isMahasiswa }
            ]
        });

    $('#filterNama').on('keyup', function () {
        table.column(1).search(this.value).draw();
    });
    $('#filterDosen').on('keyup', function () {
        const val = this.value.trim().toLowerCase();

        $.fn.dataTable.ext.search = [];

        if (val) {
            $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                const pemb1 = data[3]?.toLowerCase() || '';
                const pemb2 = data[4]?.toLowerCase() || '';
                const peng1 = data[5]?.toLowerCase() || '';
                const peng2 = data[6]?.toLowerCase() || '';
                return (
                    pemb1.includes(val) ||
                    pemb2.includes(val) ||
                    peng1.includes(val) ||
                    peng2.includes(val)
                );
            });
        }

        table.draw();
    });

    $('#filterStatus').on('change', function () {
        const val = this.value.trim();

        $.fn.dataTable.ext.search = [];

        if (val) {
            $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                const cell = $(table.row(dataIndex).node()).find('td[data-status]');
                const status = cell.data('status');
                return status === val;
            });
        }

        table.draw();
    });

    $('#shareLinkBtn').on('click', function () {
        const visibleData = table.rows({ filter: 'applied' }).data().toArray();
        if (visibleData.length === 0) {
            Swal.fire('Oops!', 'Tidak ada data yang ditampilkan di tabel.', 'info');
            return;
        }

        const tanggalList = visibleData.map(row => row[7]);
        const sortedTanggal = tanggalList.sort((a, b) => new Date(a) - new Date(b));
        const from = sortedTanggal[0];
        const to = sortedTanggal[sortedTanggal.length - 1];
        const selectedStatus = $('#filterStatus').val() || 'all';
        const title = 'Jadwal-Seminar-Tugas-Akhir-Mahasiswa';

        const url = `${window.location.origin}/jadwal/share/${title}/${from}/${to}?status=${selectedStatus}`;

        navigator.clipboard.writeText(url).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Link Dibagikan!',
                html: `URL disalin ke clipboard:<br><a href="${url}" target="_blank" class="text-green-600 underline">${url}</a>`,
                showConfirmButton: false,
                timer: 3000
            });
        }).catch(() => Swal.fire('Error', 'Gagal menyalin link!', 'error'));
    });
});

const editModal = document.getElementById('editModal');
const closeEditModalBtn = document.getElementById('closeEditModalBtn');
const editForm = document.getElementById('editForm');

function openEditModal(id, mahasiswaId, skripsiId, dosen1, dosen2, jadwalMulai, jadwalSelesai, status, ruang) {
    Swal.fire({
        title: 'Edit Jadwal?',
        text: 'Apakah Anda yakin ingin mengubah jadwal ini?',
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

            document.getElementById('editMahasiswa').value = mahasiswaId;
            document.getElementById('editSkripsi').value = skripsiId;
            document.getElementById('editDosen1').value = dosen1;
            document.getElementById('editDosen2').value = dosen2;
            document.getElementById('editTanggal').value = jadwalMulai;
            document.getElementById('editTanggalSelesai').value = jadwalSelesai;
            document.getElementById('editStatus').value = status;
            document.getElementById('editRuang').value = ruang || "";

            editForm.action = `/jadwal/${id}`;

            fetch(`/skripsi/${skripsiId}/detail`)
                .then(res => res.json())
                .then(data => {
                    if (data.error) return;
                    $('#editPembimbing1').val(data.dosen1.name || '-');
                    $('#editPembimbing2').val(data.dosen2.name || '-');
                })
                .catch(err => console.error(err));
        }
    });
}

$(document).on('change', '#editSkripsi', function() {
    const skripsiId = $(this).val();
    if (!skripsiId) return;

    fetch(`/skripsi/${skripsiId}/detail`)
        .then(res => res.json())
        .then(data => {
            if (data.error) return;

            $('#editPembimbing1').val(data.dosen1.name || '-');
            $('#editPembimbing2').val(data.dosen2.name || '-');

            ['#editPembimbing1', '#editPembimbing2'].forEach(sel => {
                $(sel).css('box-shadow', '0 0 0 3px #bbf7d0');
                setTimeout(() => $(sel).css('box-shadow', 'none'), 1200);
            });
        })
        .catch(err => console.error(err));
});

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
        text: 'Sedang memperbarui jadwal...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
    setTimeout(() => e.target.submit(), 700);
});

document.querySelector('#importModal form')?.addEventListener('submit', function (e) {
    e.preventDefault();

    Swal.fire({
        title: 'Mengimpor Data...',
        text: 'Sedang memproses file Excel. Mohon tunggu sebentar...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    setTimeout(() => {
        e.target.submit();
    }, 500);
});

const importModal = document.getElementById('importModal');
const openImportModalBtn = document.getElementById('openImportModalBtn');
const closeImportModalBtn = document.getElementById('closeImportModalBtn');

openImportModalBtn?.addEventListener('click', () => {
    importModal.classList.remove('hidden', 'opacity-0');
    importModal.classList.add('flex');
});

closeImportModalBtn?.addEventListener('click', () => {
    importModal.classList.add('hidden', 'opacity-0');
    importModal.classList.remove('flex');
});

importModal?.addEventListener('click', (e) => {
    if (e.target === importModal) {
        importModal.classList.add('hidden', 'opacity-0');
        importModal.classList.remove('flex');
    }
});

function confirmDelete(actionUrl, name) {
    Swal.fire({
        title: `Hapus jadwal ${name}?`,
        text: 'Data ini tidak bisa dikembalikan!',
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
                didOpen: () => Swal.showLoading()
            });

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = actionUrl;
            form.innerHTML = `@csrf @method('DELETE')`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

@if (session('success'))
Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', showConfirmButton: false, timer: 1800 });
@endif

@if (session('error'))
Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session('error') }}' });
@endif

@if ($errors->any())
Swal.fire({ icon: 'warning', title: 'Validasi Gagal!', html: `{!! implode('<br>', $errors->all()) !!}`, confirmButtonText: 'Oke' });
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
.aksi-col.hidden {
    display: none !important;
    visibility: collapse !important;
}
</style>
