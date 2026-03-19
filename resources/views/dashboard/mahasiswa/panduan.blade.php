<x-app-layout>
    <div class="flex min-h-screen bg-[#fbfdfe]" id="main-container">


        <div class="flex-1 flex flex-col h-screen overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm flex items-center justify-between px-8 py-4 shrink-0 z-10">
                <div class="flex items-center gap-4">

                    <div>
                        <h2 class="text-xl font-semibold text-gray-700">Panduan Tugas Akhir</h2>
                        <p class="text-sm text-gray-500 mt-1">Lihat dan pelajari panduan</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard.mahasiswa') }}" class="text-gray-500 hover:text-gray-700 font-medium text-sm flex items-center gap-2 mr-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
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
            <main class="flex-1 p-6 overflow-hidden flex flex-col">
                <div class="bg-white rounded-lg shadow-md flex-1 overflow-hidden">
                    <iframe src="{{ asset('storage/panduan/Panduan-Tugas-Akhir-v-2.0.pdf') }}" class="w-full h-full border-none" title="Panduan Tugas Akhir"></iframe>
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
