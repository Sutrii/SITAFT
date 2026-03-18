<x-app-layout>
    <div class="flex min-h-screen bg-[#fbfdfe]" id="main-container">
        <div id="sidebar-wrapper" class="transition-all duration-300 ease-in-out">
            @include('layouts.sidebar')
        </div>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm flex items-center justify-between px-8 py-4">
                <div class="flex items-center gap-4">
                    <button id="sidebar-toggle" type="button" class="p-2 hover:bg-gray-100 rounded-lg transition" title="Toggle Sidebar">
                        <svg id="hamburger-icon" class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-700">Jadwal Seminar</h2>
                        <p class="text-sm text-gray-500 mt-1">Daftar jadwal seminar proposal, hasil, dan sidang</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button id="user-menu" type="button" class="flex items-center space-x-3 focus:outline-none group">
                            <span class="text-gray-600 font-medium group-hover:text-green-700 transition">
                                {{ Auth::user()->name ?? 'User' }}
                            </span>
                            <img class="w-9 h-9 rounded-full border border-green-200"
                                src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=16a34a&color=fff"
                                alt="avatar">
                            <svg class="w-4 h-4 text-gray-500 group-hover:text-green-700 transition" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div id="user-dropdown"
                            class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 z-50">
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-gray-700 hover:bg-green-50 hover:text-green-800 rounded-t-lg">Profil</a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-700 rounded-b-lg">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto overflow-x-hidden p-8 relative min-w-0 w-full">
                
                <!-- Back Button -->
                <div class="mb-6">
                    <a href="{{ route('dashboard.mahasiswa') }}" class="inline-flex items-center text-green-600 hover:text-green-700 font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali ke Dashboard
                    </a>
                </div>

                <!-- Jadwal Table -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Daftar Jadwal Seminar</h3>
                    </div>

                    <!-- Search & Filter Controls -->
                    @if($jadwals->count() > 0)
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Search Input -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Mahasiswa/Dosen</label>
                                    <input 
                                        type="text" 
                                        id="search-input" 
                                        placeholder="Ketik nama mahasiswa atau dosen..."
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                    >
                                </div>

                                <!-- Seminar Type Filter -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter Jenis Seminar</label>
                                    <select 
                                        id="seminar-filter" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                    >
                                        <option value="">Semua Jenis Seminar</option>
                                        <option value="Seminar Proposal">Seminar Proposal</option>
                                        <option value="Seminar Hasil">Seminar Hasil</option>
                                        <option value="Sidang Akhir">Sidang Akhir</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Reset Button -->
                            <div class="mt-4">
                                <button 
                                    id="reset-filters" 
                                    class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-lg transition text-sm font-medium"
                                >
                                    Reset Filter
                                </button>
                            </div>
                        </div>
                    @endif

                    @if($jadwals->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Mahasiswa</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Judul Skripsi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Dosen Pembimbing 1</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Dosen Pembimbing 2</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Dosen Penguji 1</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Dosen Penguji 2</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Jam Mulai</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Jam Selesai</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200" id="jadwal-table-body">
                                    @foreach($jadwals as $index => $jadwal)
                                        <tr class="hover:bg-gray-50 transition jadwal-row" 
                                            data-mahasiswa="{{ strtolower($jadwal->mahasiswa->name ?? '') }}"
                                            data-dosen1="{{ strtolower($jadwal->skripsi->dosen1->name ?? '') }}"
                                            data-dosen2="{{ strtolower($jadwal->skripsi->dosen2->name ?? '') }}"
                                            data-dosen-penguji1="{{ strtolower($jadwal->dosen1->name ?? '') }}"
                                            data-dosen-penguji2="{{ strtolower($jadwal->dosen2->name ?? '') }}"
                                            data-status="{{ $jadwal->status }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $jadwal->mahasiswa->name ?? '-' }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">{{ $jadwal->skripsi->judul_skripsi ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $jadwal->skripsi->dosen1->name ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $jadwal->skripsi->dosen2->name ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $jadwal->dosen1->name ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $jadwal->dosen2->name ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($jadwal->jadwal_seminar)->format('Y-m-d') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($jadwal->jadwal_seminar)->format('H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($jadwal->jadwal_seminar_selesai)->format('H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                @php
                                                    $status = $jadwal->status;
                                                    $badgeColor = match($status) {
                                                        'Seminar Proposal' => 'bg-red-100 text-red-800',
                                                        'Seminar Hasil' => 'bg-blue-100 text-blue-800',
                                                        'Sidang Akhir' => 'bg-green-100 text-green-800',
                                                        default => 'bg-gray-100 text-gray-800'
                                                    };
                                                @endphp
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap {{ $badgeColor }}">
                                                    {{ $status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="px-6 py-4 border-t border-gray-200 bg-white">
                            {{ $jadwals->withQueryString()->links() }}
                        </div>
                    @else
                        <div class="px-6 py-12 text-center">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Jadwal</h3>
                            <p class="text-gray-500 mb-6">Anda belum terdaftar pada jadwal seminar apapun.</p>
                            <a href="{{ route('dashboard.mahasiswa') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-lg transition">
                                Daftar Seminar
                            </a>
                        </div>
                    @endif
                </div>

            </main>
        </div>
    </div>

    <!-- User Menu, Sidebar Toggle & Filter Script -->
    <script>
        // User Menu
        const userMenu = document.getElementById('user-menu');
        const userDropdown = document.getElementById('user-dropdown');

        userMenu?.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown?.classList.toggle('hidden');
        });

        document.addEventListener('click', () => {
            userDropdown?.classList.add('hidden');
        });

        userDropdown?.addEventListener('click', (e) => {
            e.stopPropagation();
        });

        // Sidebar Toggle
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebarWrapper = document.getElementById('sidebar-wrapper');
        let sidebarVisible = true;

        sidebarToggle?.addEventListener('click', () => {
            sidebarVisible = !sidebarVisible;
            
            if (sidebarVisible) {
                sidebarWrapper.classList.remove('hidden');
                sidebarWrapper.classList.add('w-64');
                localStorage.setItem('sidebarVisible', 'true');
            } else {
                sidebarWrapper.classList.add('hidden');
                sidebarWrapper.classList.remove('w-64');
                localStorage.setItem('sidebarVisible', 'false');
            }
        });

        // Restore sidebar state
        window.addEventListener('load', () => {
            const savedState = localStorage.getItem('sidebarVisible') !== 'false';
            if (!savedState) {
                sidebarWrapper.classList.add('hidden');
                sidebarWrapper.classList.remove('w-64');
                sidebarVisible = false;
            }
        });

        // Jadwal Search & Filter
        const searchInput = document.getElementById('search-input');
        const seminarFilter = document.getElementById('seminar-filter');
        const resetButton = document.getElementById('reset-filters');
        const jadwalRows = document.querySelectorAll('.jadwal-row');

        function filterTable() {
            const searchTerm = searchInput?.value.toLowerCase() || '';
            const filterStatus = seminarFilter?.value || '';

            jadwalRows.forEach(row => {
                const mahasiswa = row.dataset.mahasiswa || '';
                const dosen1 = row.dataset.dosen1 || '';
                const dosen2 = row.dataset.dosen2 || '';
                const dosenPenguji1 = row.dataset.dosenPenguji1 || '';
                const dosenPenguji2 = row.dataset.dosenPenguji2 || '';
                const status = row.dataset.status || '';

                // Check search term (search in all names)
                const searchMatch = searchTerm === '' || 
                    mahasiswa.includes(searchTerm) || 
                    dosen1.includes(searchTerm) || 
                    dosen2.includes(searchTerm) || 
                    dosenPenguji1.includes(searchTerm) || 
                    dosenPenguji2.includes(searchTerm);

                // Check status filter
                const statusMatch = filterStatus === '' || status === filterStatus;

                // Show or hide row
                if (searchMatch && statusMatch) {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });

            // Check if any rows are visible
            updateNoDataMessage();
        }

        function updateNoDataMessage() {
            const visibleRows = document.querySelectorAll('.jadwal-row:not(.hidden)');
            const tableContainer = document.querySelector('.overflow-x-auto');
            let noDataMsg = document.getElementById('no-data-message');

            if (visibleRows.length === 0 && tableContainer) {
                if (!noDataMsg) {
                    noDataMsg = document.createElement('div');
                    noDataMsg.id = 'no-data-message';
                    noDataMsg.className = 'px-6 py-12 text-center';
                    noDataMsg.innerHTML = `
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak Ada Data</h3>
                        <p class="text-gray-500">Tidak ada jadwal yang sesuai dengan pencarian Anda.</p>
                    `;
                    tableContainer.parentNode.appendChild(noDataMsg);
                }
                tableContainer.classList.add('hidden');
            } else if (visibleRows.length > 0) {
                if (noDataMsg) {
                    noDataMsg.remove();
                }
                tableContainer?.classList.remove('hidden');
            }
        }

        function resetFilters() {
            searchInput.value = '';
            seminarFilter.value = '';
            jadwalRows.forEach(row => row.classList.remove('hidden'));
            updateNoDataMessage();
        }

        searchInput?.addEventListener('input', filterTable);
        seminarFilter?.addEventListener('change', filterTable);
        resetButton?.addEventListener('click', resetFilters);
    </script>
</x-app-layout>
