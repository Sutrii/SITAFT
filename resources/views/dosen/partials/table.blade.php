{{-- Filter Bar --}}
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold text-[#2d3a32]">Jadwal Kosong Dosen</h2>
</div>

<div class="bg-white rounded-2xl shadow-sm p-5 mb-6">
    <div class="grid grid-cols-3 gap-4 items-end w-full">

        {{-- Pilih Dosen --}}
        <div>
            <label class="block text-sm font-medium text-[#2d3a32] mb-1">Pilih Dosen</label>
            <select id="filterDosen"
                class="w-full border border-[#d8e4d8] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#3ea76a] focus:outline-none">
                <option value="">Semua Dosen</option>
                <option>Dr. Ir. Hasan Yudie Sastra, DEA</option>
                <option>Ir. Ilyas, MT</option>
                <option>Ir. Awal Aflizal Zubir, S.T., M.Sc</option>
                <option>Ir. Riski Arifin, S.T., M.T.</option>
            </select>
        </div>

        {{-- Pilih Hari --}}
        <div>
            <label class="block text-sm font-medium text-[#2d3a32] mb-1">Hari</label>
            <select id="filterHari"
                class="w-full border border-[#d8e4d8] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#3ea76a] focus:outline-none">
                <option value="">Semua Hari</option>
                <option>Senin</option>
                <option>Selasa</option>
                <option>Rabu</option>
                <option>Kamis</option>
                <option>Jumat</option>
            </select>
        </div>

        {{-- Pilih Jam --}}
        <div>
            <label class="block text-sm font-medium text-[#2d3a32] mb-1">Jam</label>
            <select id="filterJam"
                class="w-full border border-[#d8e4d8] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#3ea76a] focus:outline-none">
                <option value="">Semua Jam</option>
                <option>08.00 - 08.50</option>
                <option>08.50 - 09.40</option>
                <option>09.40 - 10.30</option>
                <option>10.30 - 11.20</option>
                <option>11.20 - 12.10</option>
                <option>12.10 - 13.00</option>
                <option>13.00 - 14.00</option>
                <option>14.00 - 14.50</option>
                <option>14.50 - 15.40</option>
                <option>15.40 - 16.30</option>
                <option>16.30 - 17.20</option>
                <option>17.20 - 18.10</option>
            </select>
        </div>
    </div>
</div>

{{-- DataTable --}}
<div class="bg-white rounded-2xl shadow-md p-6 overflow-x-auto">
    <table id="dosenTable" class="display text-sm min-w-[1000px]">
        <thead>
            <tr class="text-[#2d3a32] border-b border-[#e8f0e8]">
                <th class="py-3 px-2 text-left">No</th>
                <th class="py-3 px-2 text-left">Nama Dosen</th>
                <th class="py-3 px-2 text-left">Hari</th>
                <th class="py-3 px-2 text-left">Jam</th>
                <th class="py-3 px-2 text-left">Status</th>
            </tr>
        </thead>
        <tbody>
            {{-- Contoh data dummy hasil filter --}}
            <tr>
                <td>1</td>
                <td>Ir. Ilyas, MT</td>
                <td>Selasa</td>
                <td>10.30 - 11.20</td>
                <td><span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">Kosong</span></td>
            </tr>
            <tr>
                <td>2</td>
                <td>Ir. Ilyas, MT</td>
                <td>Selasa</td>
                <td>13.00 - 14.00</td>
                <td><span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">Kosong</span></td>
            </tr>
            <tr>
                <td>3</td>
                <td>Dr. Ir. Hasan Yudie Sastra, DEA</td>
                <td>Senin</td>
                <td>09.40 - 10.30</td>
                <td><span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">Kosong</span></td>
            </tr>
        </tbody>
    </table>
</div>

{{-- DataTables Script --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function () {
        const table = $('#dosenTable').DataTable({
            scrollX: true,
            pageLength: 5,
            lengthChange: false,
            language: {
                search: "",
                searchPlaceholder: "Cari dosen atau jam...",
                paginate: { previous: "←", next: "→" },
                info: "Menampilkan _START_–_END_ dari _TOTAL_ data"
            }
        });

        // Filter manual (dummy logic)
        $('#filterDosen, #filterHari, #filterJam').on('change', function () {
            let dosen = $('#filterDosen').val().toLowerCase();
            let hari = $('#filterHari').val().toLowerCase();
            let jam = $('#filterJam').val().toLowerCase();

            table.rows().every(function () {
                const data = this.data();
                const matchDosen = !dosen || data[1].toLowerCase().includes(dosen);
                const matchHari = !hari || data[2].toLowerCase().includes(hari);
                const matchJam = !jam || data[3].toLowerCase().includes(jam);
                this.visible(matchDosen && matchHari && matchJam);
            });
        });
    });
</script>

<style>
/* 🌿 Gaya Search DataTables */
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
}

.dataTables_filter label {
    color: #2d3a32;
    font-weight: 500;
    font-size: 0.875rem;
}

.dataTables_filter input::placeholder {
    color: #6b7d6f;
    opacity: 0.7;
}

.dataTables_filter {
    margin-bottom: 1rem;
    margin-top: -0.5rem;
}
</style>
