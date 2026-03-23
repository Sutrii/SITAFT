<x-app-layout>
    <div class="flex min-h-screen bg-[#fbfdfe]" id="main-container">
        <!-- Sidebar -->
        <div id="sidebar-wrapper" class="transition-all duration-300 ease-in-out w-64">
            @include('layouts.sidebar')
        </div>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm flex items-center justify-between px-8 py-4">
                <div class="flex items-center gap-4">
                    <button id="sidebar-toggle" type="button" class="p-2 hover:bg-gray-100 rounded-lg transition" title="Toggle Sidebar">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-700">Permintaan Seminar</h2>
                        <p class="text-sm text-gray-500 mt-1">Kelola dan tinjau permintaan pendaftaran seminar mahasiswa</p>
                    </div>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button id="user-menu" type="button" class="flex items-center space-x-3 focus:outline-none group">
                            <span class="text-gray-600 font-medium group-hover:text-green-700 transition">
                                {{ Auth::user()->name ?? 'Koordinator' }}
                            </span>
                            <img class="w-9 h-9 rounded-full border border-green-200"
                                src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Koordinator') }}&background=16a34a&color=fff"
                                alt="avatar">
                            <svg class="w-4 h-4 text-gray-500 group-hover:text-green-700 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-8">
                
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <p class="text-sm text-green-800 font-medium">{{ session('success') }}</p>
                    </div>
                @endif

                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800">Daftar Pengajuan</h3>
                        <div class="flex space-x-2">
                            <select id="statusFilter" onchange="filterTable()" class="border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 text-gray-700 bg-white">
                                <option value="all">Semua Status</option>
                                <option value="pending">Pending</option>
                                <option value="disetujui">Disetujui</option>
                                <option value="revisi">Revisi</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Mahasiswa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Jenis Seminar</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Judul Tugas Akhir</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider w-48">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="requestTableBody" class="divide-y divide-gray-200">
                                @forelse($pengajuans ?? [] as $index => $pengajuan)
                                    <tr class="hover:bg-gray-50 transition request-row" data-status="{{ $pengajuan->status == 'acc' ? 'disetujui' : $pengajuan->status }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-medium text-gray-900">{{ $pengajuan->mahasiswa->name ?? 'Anonim' }}</div>
                                            <div class="text-gray-500 text-xs mt-1">{{ $pengajuan->mahasiswa->nim ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @php
                                                $jenis = str_replace('_', ' ', $pengajuan->jenis_seminar);
                                                $jenis = ucwords($jenis);
                                            @endphp
                                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">{{ $jenis }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">
                                            {{ $pengajuan->skripsi->judul_skripsi ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($pengajuan->created_at)->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($pengajuan->status == 'pending')
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Pending</span>
                                            @elseif($pengajuan->status == 'acc')
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Disetujui</span>
                                            @elseif($pengajuan->status == 'revisi')
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">Revisi</span>
                                            @else
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Ditolak</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <div class="flex items-center justify-center space-x-2">
                                                <!-- Detail Button -->
                                                <button onclick="openModal('modal-detail-{{ $pengajuan->id }}')" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-1.5 rounded transition" title="Lihat Detail">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                </button>
                                                
                                                <!-- ACC Button -->
                                                <form action="{{ route('koordinator.request-seminar.update-status', $pengajuan->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin akan meng-ACC pengajuan ini?')">
                                                    @csrf
                                                    <input type="hidden" name="status" value="acc">
                                                    <button type="submit" class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 p-1.5 rounded transition" title="ACC">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    </button>
                                                </form>

                                                <!-- Revisi Button -->
                                                <button onclick="openModal('modal-revisi-{{ $pengajuan->id }}')" type="button" class="text-orange-600 hover:text-orange-900 bg-orange-50 hover:bg-orange-100 p-1.5 rounded transition" title="Revisi">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </button>

                                                <!-- Tolak Button -->
                                                <form action="{{ route('koordinator.request-seminar.update-status', $pengajuan->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin akan menolak pengajuan ini?')">
                                                    @csrf
                                                    <input type="hidden" name="status" value="ditolak">
                                                    <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-1.5 rounded transition" title="Tolak">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Detail Modal Setup -->
                                    <div id="modal-detail-{{ $pengajuan->id }}" class="hidden fixed inset-0 z-50 overflow-y-auto">
                                        <div class="fixed inset-0 bg-gray-900 bg-opacity-60 transition-opacity" onclick="closeModal('modal-detail-{{ $pengajuan->id }}')"></div>
                                        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                                            <div class="relative bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-4xl w-full flex flex-col max-h-[90vh]">
                                                
                                                <!-- Modal Header -->
                                                <div class="bg-blue-600 px-6 py-4 flex justify-between items-center shrink-0">
                                                    <div>
                                                        <h3 class="text-xl font-bold text-white">Detail Pengajuan: {{ $jenis }}</h3>
                                                        <p class="text-blue-100 text-sm mt-1">NIM: {{ $pengajuan->mahasiswa->nim ?? '-' }}</p>
                                                    </div>
                                                    <button onclick="closeModal('modal-detail-{{ $pengajuan->id }}')" class="text-white hover:text-blue-200 transition bg-blue-700 p-2 rounded-lg">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </button>
                                                </div>

                                                <!-- Modal Body -->
                                                <div class="px-8 py-6 overflow-y-auto flex-1 bg-[#fdf5e6]">
                                                    <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100 mb-6">
                                                        <h4 class="text-md font-bold text-gray-800 mb-4 border-b pb-2">Identitas Mahasiswa</h4>
                                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
                                                            <div>
                                                                <p class="text-sm font-medium text-gray-500">NIM</p>
                                                                <p class="text-base font-semibold text-gray-900">{{ $pengajuan->mahasiswa->nim ?? '-' }}</p>
                                                            </div>
                                                            <div>
                                                                <p class="text-sm font-medium text-gray-500">Nama Lengkap</p>
                                                                <p class="text-base font-semibold text-gray-900">{{ $pengajuan->mahasiswa->name ?? '-' }}</p>
                                                            </div>
                                                            <div>
                                                                <p class="text-sm font-medium text-gray-500">No. HP/WA</p>
                                                                <p class="text-base font-semibold text-gray-900">{{ $pengajuan->no_hp ?? '-' }}</p>
                                                            </div>
                                                            <div>
                                                                <p class="text-sm font-medium text-gray-500">Nomor Registrasi Seminar</p>
                                                                <p class="text-base font-semibold text-gray-900">{{ $pengajuan->nomor_registrasi ?? '-' }}</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100 mb-6">
                                                        <h4 class="text-md font-bold text-gray-800 mb-4 border-b pb-2">Informasi Skripsi</h4>
                                                        <div class="space-y-4">
                                                            <div>
                                                                <p class="text-sm font-medium text-gray-500 mb-1">Judul Tugas Akhir</p>
                                                                <p class="text-base font-semibold text-gray-900">{{ $pengajuan->skripsi->judul_skripsi ?? '-' }}</p>
                                                            </div>
                                                            <div>
                                                                <p class="text-sm font-medium text-gray-500 mb-1">Bidang Tugas Akhir</p>
                                                                <p class="text-base font-semibold text-gray-900">{{ $pengajuan->skripsi->bidang ?? '-' }}</p>
                                                            </div>
                                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                <div>
                                                                    <p class="text-sm font-medium text-gray-500 mb-1">Dosen Pembimbing 1</p>
                                                                    <p class="text-base font-semibold text-blue-700">{{ $pengajuan->skripsi->dosen1->name ?? '-' }}</p>
                                                                </div>
                                                                <div>
                                                                    <p class="text-sm font-medium text-gray-500 mb-1">Dosen Pembimbing 2</p>
                                                                    <p class="text-base font-semibold text-blue-700">{{ $pengajuan->skripsi->dosen2->name ?? '-' }}</p>
                                                                </div>
                                                            </div>
                                                            
                                                            @if($pengajuan->jenis_seminar == 'seminar_hasil')
                                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 border-t pt-4">
                                                                <div>
                                                                    <p class="text-sm font-medium text-gray-500 mb-1">Dosen Penguji 1</p>
                                                                    <p class="text-base font-semibold text-yellow-700">{{ $pengajuan->skripsi->penguji1->name ?? '-' }}</p>
                                                                </div>
                                                                <div>
                                                                    <p class="text-sm font-medium text-gray-500 mb-1">Dosen Penguji 2</p>
                                                                    <p class="text-base font-semibold text-yellow-700">{{ $pengajuan->skripsi->penguji2->name ?? '-' }}</p>
                                                                </div>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Section Berkas -->
                                                    <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100">
                                                        <h4 class="text-md font-bold text-gray-800 mb-4 border-b pb-2">Berkas Persyaratan</h4>
                                                        
                                                        @php
                                                            $files = json_decode($pengajuan->file_persyaratan ?? '[]', true);
                                                        @endphp
                                                        
                                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                                            @forelse($files as $label => $path)
                                                            <div class="border border-gray-200 rounded-lg p-4 flex flex-col items-center justify-center text-center hover:shadow-md transition bg-gray-50 hover:bg-white">
                                                                <div class="bg-blue-100 p-3 rounded-full text-blue-600 mb-3">
                                                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>
                                                                </div>
                                                                <p class="text-sm font-bold text-gray-800 mb-1">{{ Str::title($label) }}</p>
                                                                <a href="{{ asset('storage/' . $path) }}" target="_blank" class="text-xs text-blue-600 hover:underline flex items-center mt-2 font-medium">
                                                                    Buka Dokumen <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                                </a>
                                                            </div>
                                                            @empty
                                                            <p class="text-xs text-red-500 mt-2">Belum ada berkas tersimpan.</p>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Modal Footer Actions -->
                                                <div class="bg-gray-50 px-6 py-4 flex justify-between items-center border-t border-gray-200 shrink-0">
                                                    <button onclick="closeModal('modal-detail-{{ $pengajuan->id }}')" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-800 font-medium py-2 px-6 rounded-lg transition">
                                                        Tutup
                                                    </button>
                                                    <div class="flex space-x-3">
                                                        <button onclick="closeModal('modal-detail-{{ $pengajuan->id }}'); openModal('modal-revisi-{{ $pengajuan->id }}')" class="bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 px-6 rounded-lg transition">
                                                            Minta Revisi
                                                        </button>
                                                        <form action="{{ route('koordinator.request-seminar.update-status', $pengajuan->id) }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="status" value="acc">
                                                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-8 rounded-lg transition shadow-md">
                                                                Disetujui
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Revisi -->
                                    <div id="modal-revisi-{{ $pengajuan->id }}" class="hidden fixed inset-0 z-[60] overflow-y-auto">
                                        <div class="fixed inset-0 bg-gray-900 bg-opacity-60 transition-opacity" onclick="closeModal('modal-revisi-{{ $pengajuan->id }}')"></div>
                                        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
                                            <div class="relative bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-lg w-full">
                                                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-orange-500">
                                                    <h3 class="text-lg font-bold text-white">📝 Catatan Revisi Permintaan</h3>
                                                    <button onclick="closeModal('modal-revisi-{{ $pengajuan->id }}')" class="text-white hover:text-orange-200">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </button>
                                                </div>
                                                <form action="{{ route('koordinator.request-seminar.update-status', $pengajuan->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="revisi">
                                                    <div class="px-6 py-6">
                                                        <p class="text-sm text-gray-600 mb-4">Berikan pesan revisi untuk mahasiswa <span class="font-bold">{{ $pengajuan->mahasiswa->name ?? '-' }}</span> agar mereka dapat memperbaiki pengajuannya.</p>
                                                        <label for="keterangan" class="block mb-2 font-medium text-gray-800">Catatan Revisi <span class="text-red-500">*</span></label>
                                                        <textarea name="keterangan" rows="4" class="w-full border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500 p-3" placeholder="Contoh: Dokumen KRS tidak terbaca, silakan upload ulang." required></textarea>
                                                    </div>
                                                    <div class="bg-gray-50 px-6 py-4 flex justify-end items-center gap-3">
                                                        <button type="button" onclick="closeModal('modal-revisi-{{ $pengajuan->id }}')" class="text-gray-600 font-medium px-4 hover:text-gray-800">Batal</button>
                                                        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 px-6 rounded-lg transition">Kirim Revisi</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center text-gray-500 bg-white">
                                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            Belum ada permintaan seminar.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Modal Handlers
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // Sidebar Toggle
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebarWrapper = document.getElementById('sidebar-wrapper');
        let sidebarVisible = true;

        sidebarToggle?.addEventListener('click', () => {
            sidebarVisible = !sidebarVisible;
            if (sidebarVisible) {
                sidebarWrapper.classList.remove('hidden', 'w-0');
                sidebarWrapper.classList.add('w-64');
                localStorage.setItem('sidebarKoordinatorVisible', 'true');
            } else {
                sidebarWrapper.classList.add('hidden', 'w-0');
                sidebarWrapper.classList.remove('w-64');
                localStorage.setItem('sidebarKoordinatorVisible', 'false');
            }
        });

        // Restore sidebar state
        window.addEventListener('load', () => {
            const savedState = localStorage.getItem('sidebarKoordinatorVisible') !== 'false';
            if (!savedState) {
                sidebarWrapper.classList.add('hidden', 'w-0');
                sidebarWrapper.classList.remove('w-64');
                sidebarVisible = false;
            }
        });

        // Table Filter
        function filterTable() {
            const status = document.getElementById('statusFilter').value;
            const rows = document.querySelectorAll('.request-row');
            
            rows.forEach(row => {
                if (status === 'all') {
                    row.style.display = '';
                } else {
                    if (row.getAttribute('data-status') === status) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        }
    </script>
</x-app-layout>
