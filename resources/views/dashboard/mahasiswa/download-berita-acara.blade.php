<x-app-layout>
    <div class="flex min-h-screen bg-[#fbfdfe]" id="main-container">


        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow-sm flex items-center justify-between px-8 py-4">
                <div class="flex items-center gap-4">

                    <div>
                        <h2 class="text-xl font-semibold text-gray-700">Download Berita Acara</h2>
                        <p class="text-sm text-gray-500 mt-1">Unduh template dokumen berita acara yang diperlukan</p>
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
                <!-- Back Button -->
                <div class="mb-6">
                    <a href="{{ route('dashboard.mahasiswa') }}" class="inline-flex items-center text-green-600 hover:text-green-700 font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali ke Dashboard
                    </a>
                </div>

                <div class="space-y-6 max-w-4xl mx-auto">
                    <!-- Berita Acara Seminar Proposal -->
                    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500 flex flex-col md:flex-row items-center justify-between gap-6 hover:shadow-lg transition">
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center shrink-0">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800 mb-1">Berita Acara Seminar Proposal</h3>
                                <p class="text-sm text-gray-500">Unduh template word untuk dokumen berita acara seminar proposal.</p>
                            </div>
                        </div>
                        
                        <a href="{{ asset('storage/template/proposal/Template_Berita_Acara_Seminar_Proposal.docx') }}" download="Template_Berita_Acara_Seminar_Proposal.docx" class="shrink-0 py-2.5 px-6 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Download
                        </a>
                    </div>

                    <!-- Berita Acara Seminar Hasil -->
                    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500 flex flex-col md:flex-row items-center justify-between gap-6 hover:shadow-lg transition">
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-16 bg-yellow-50 text-yellow-500 rounded-full flex items-center justify-center shrink-0">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800 mb-1">Berita Acara Seminar Hasil</h3>
                                <p class="text-sm text-gray-500">Unduh template word untuk dokumen berita acara seminar hasil penelitian.</p>
                            </div>
                        </div>
                        
                        <a href="{{ asset('storage/template/hasil/Template_Berita_Acara_Seminar_Hasil.docx') }}" download="Template_Berita_Acara_Seminar_Hasil.docx" class="shrink-0 py-2.5 px-6 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg font-medium transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Download
                        </a>
                    </div>

                    <!-- Berita Acara Sidang Akhir -->
                    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500 flex flex-col md:flex-row items-center justify-between gap-6 hover:shadow-lg transition">
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center shrink-0">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800 mb-1">Berita Acara Sidang Akhir</h3>
                                <p class="text-sm text-gray-500">Unduh template word untuk dokumen berita acara kelulusan sidang akhir skripsi.</p>
                            </div>
                        </div>
                        
                        <a href="{{ asset('storage/template/sidang/Template_Berita_Acara_Sidang_Akhir.docx') }}" download="Template_Berita_Acara_Sidang_Akhir.docx" class="shrink-0 py-2.5 px-6 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Download
                        </a>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // User Menu Interaction
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
