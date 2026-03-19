<x-app-layout>
    <div class="flex min-h-screen bg-[#fbfdfe]" id="main-container">


        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow-sm flex items-center justify-between px-8 py-4">
                <div class="flex items-center gap-4">

                    <div>
                        <h2 class="text-xl font-semibold text-gray-700">Dashboard Mahasiswa</h2>
                        <p class="text-sm text-gray-500 mt-1">Selamat datang, {{ Auth::user()->name }}</p>
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
            <main class="flex-1 overflow-auto p-8">
                <!-- Quick Stats - Coming Soon -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Seminar Proposal Status -->
                    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Seminar Proposal</p>
                                @if($registrationStatus['seminar_proposal'])
                                    @php
                                        $statusClass = 'text-green-500';
                                        if ($registrationStatus['seminar_proposal']->status == 'pending') $statusClass = 'text-yellow-500';
                                        elseif ($registrationStatus['seminar_proposal']->status == 'ditolak' || $registrationStatus['seminar_proposal']->status == 'revisi') $statusClass = 'text-red-500';
                                    @endphp
                                    <p class="text-3xl font-bold text-gray-800 mt-2 uppercase">{{ $registrationStatus['seminar_proposal']->status }}</p>
                                    <p class="{{ $statusClass }} text-xs mt-2 font-medium">Status: Sudah Terdaftar</p>
                                @else
                                    <p class="text-3xl font-bold text-gray-800 mt-2">-</p>
                                    <p class="text-gray-400 text-xs mt-2">Status: Belum Terdaftar</p>
                                @endif
                            </div>
                            <div class="text-blue-500">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Seminar Hasil Status -->
                    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Seminar Hasil</p>
                                <p class="text-3xl font-bold text-gray-800 mt-2">-</p>
                                <p class="text-gray-400 text-xs mt-2">Status: Belum Terdaftar</p>
                            </div>
                            <div class="text-yellow-500">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Sidang Status -->
                    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Sidang</p>
                                <p class="text-3xl font-bold text-gray-800 mt-2">-</p>
                                <p class="text-gray-400 text-xs mt-2">Status: Belum Terdaftar</p>
                            </div>
                            <div class="text-red-500">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Actions -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Daftar Seminar -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Pendaftaran Seminar</h3>
                        <p class="text-gray-600 text-sm mb-6">Daftarkan diri Anda untuk mengikuti seminar proposal atau seminar hasil</p>
                        <a href="{{ route('mahasiswa.daftar-seminar') }}" class="w-full block text-center bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition">
                            Daftar Seminar
                        </a>
                    </div>

                    <!-- Lihat Jadwal -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Jadwal Seminar</h3>
                        <p class="text-gray-600 text-sm mb-6">Lihat jadwal seminar proposal, hasil, dan sidang yang telah dijadwalkan</p>
                        <a href="{{ route('mahasiswa.jadwal') }}" class="w-full block text-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition">
                            Lihat Jadwal
                        </a>
                    </div>

                    <!-- Profil Tugas Akhir -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Download Berita Acara</h3>
                        <p class="text-gray-600 text-sm mb-6">Download berita acara seminar proposal, seminar hasil, dan sidang akhir</p>
                        <a href="{{ route('mahasiswa.download-berita-acara') }}" class="w-full inline-block text-center bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg transition">
                            Download Berita Acara
                        </a>
                    </div>

                    <!-- Panduan -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Panduan</h3>
                        <p class="text-gray-600 text-sm mb-6">Pelajari langkah-langkah dan persyaratan mendaftar seminar</p>
                        <a href="{{ route('mahasiswa.panduan') }}" class="w-full block text-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition">
                            Baca Panduan
                        </a>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <div class="flex">
                        <svg class="w-6 h-6 text-blue-600 mr-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd" />
                        </svg>
                        <div>
                            <h4 class="text-sm font-semibold text-blue-900">Informasi Penting</h4>
                            <p class="text-sm text-blue-700 mt-1">Pastikan Anda telah menyelesaikan semua persyaratan sebelum mendaftar seminar. Hubungi pembimbing untuk konsultasi lebih lanjut.</p>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <!-- User Menu & Sidebar Toggle Script -->
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


    </script>
</x-app-layout>
