{{-- resources/views/layouts/sidebar.blade.php --}}
<aside class="w-64 bg-[#f4f8f4] text-[#2d3a32] flex flex-col border-r border-[#e6eee7]">
    <div class="p-6 flex items-center gap-3">
        <div class="w-10 h-10 flex items-center justify-center rounded-full overflow-hidden">
            <img src="{{ asset('assets/images/unsyiah.png') }}" alt="Logo Unsyiah" class="w-full h-full object-cover">
        </div>
        <div>
            <h1 class="font-semibold text-[#2d3a32]">SITAFT</h1>
            <p class="text-sm text-[#6b7d6f]">Sistem Tugas Akhir</p>
            <p class="text-sm text-[#6b7d6f]">Fakultas Teknik</p>
        </div>
    </div>

    {{-- Sidebar Menu --}}
    <nav class="mt-4 flex-1 px-4 space-y-1 text-sm">
        <p class="uppercase text-[11px] tracking-wide text-[#6b7d6f] font-semibold mt-2 mb-1">Menu</p>

        @php
            $isMahasiswaViewer = Auth::user()->roleId == 1 && Auth::user()->positionId == 3;
        @endphp

        @if($isMahasiswaViewer)
            {{-- ==== Hanya tampilkan untuk Mahasiswa Viewer ==== --}}
            <a href="{{ route('borang') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-all
                {{ request()->routeIs('borang') ? 'text-[#3ea76a]' : 'hover:text-[#3ea76a] text-[#2d3a32]' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                Borang Penilaian
            </a>

            <a href="{{ route('jadwal') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-all
                {{ request()->routeIs('jadwal') ? 'text-[#3ea76a]' : 'hover:text-[#3ea76a] text-[#2d3a32]' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8.25 3v1.5M15.75 3v1.5M3.75 7.5h16.5M4.5 9.75h15a1.5 1.5 0 011.5 1.5v7.5a1.5 1.5 0 01-1.5 1.5h-15a1.5 1.5 0 01-1.5-1.5v-7.5a1.5 1.5 0 011.5-1.5z" />
                </svg>
                Jadwal Tugas Akhir
            </a>
        @else
            {{-- ==== Menu default untuk Admin, Koordinator, Dosen, dll ==== --}}
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-all
                {{ request()->routeIs('dashboard') ? 'text-[#3ea76a]' : 'hover:text-[#3ea76a] text-[#2d3a32]' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 3.75v16.5h16.5V3.75H3.75zm3 3h10.5v10.5H6.75V6.75z" />
                </svg>
                Dashboard
            </a>

            <a href="{{ route('jadwal') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-all
                {{ request()->routeIs('jadwal') ? 'text-[#3ea76a]' : 'hover:text-[#3ea76a] text-[#2d3a32]' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8.25 3v1.5M15.75 3v1.5M3.75 7.5h16.5M4.5 9.75h15a1.5 1.5 0 011.5 1.5v7.5a1.5 1.5 0 01-1.5 1.5h-15a1.5 1.5 0 01-1.5-1.5v-7.5a1.5 1.5 0 011.5-1.5z" />
                </svg>
                Jadwal Tugas Akhir
            </a>

            <a href="{{ route('skripsi') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-all
                {{ request()->routeIs('skripsi') ? 'text-[#3ea76a]' : 'hover:text-[#3ea76a] text-[#2d3a32]' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4.5 6.75h15m-15 3.75h15m-15 3.75h9.75M4.5 3.75h15a.75.75 0 01.75.75v15a.75.75 0 01-.75.75h-15a.75.75 0 01-.75-.75v-15a.75.75 0 01.75-.75z" />
                </svg>
                Data Skripsi Mahasiswa
            </a>

            <a href="{{ route('jadwal-dosen') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-all
                {{ request()->routeIs('jadwal-dosen') ? 'text-[#3ea76a]' : 'hover:text-[#3ea76a] text-[#2d3a32]' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 14.25c3.728 0 6.75 3.022 6.75 6.75H5.25c0-3.728 3.022-6.75 6.75-6.75zm0-9a3 3 0 100 6 3 3 0 000-6z" />
                </svg>
                Jadwal Dosen
            </a>

            <a href="{{ route('data-dosen') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-all
                {{ request()->routeIs('data-dosen') ? 'text-[#3ea76a]' : 'hover:text-[#3ea76a] text-[#2d3a32]' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 14.25c3.728 0 6.75 3.022 6.75 6.75H5.25c0-3.728 3.022-6.75 6.75-6.75zm0-9a3 3 0 100 6 3 3 0 000-6z" />
                </svg>
                Data Dosen
            </a>

            <a href="{{ route('mahasiswa') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-all
                {{ request()->routeIs('mahasiswa') ? 'text-[#3ea76a]' : 'hover:text-[#3ea76a] text-[#2d3a32]' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 14.25c3.728 0 6.75 3.022 6.75 6.75H5.25c0-3.728 3.022-6.75 6.75-6.75zm0-9a3 3 0 100 6 3 3 0 000-6z" />
                </svg>
                Data Mahasiswa
            </a>

            <a href="{{ route('users') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-all
                {{ request()->routeIs('users') ? 'text-[#3ea76a]' : 'hover:text-[#3ea76a] text-[#2d3a32]' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 9A3.75 3.75 0 1112 5.25 3.75 3.75 0 0115.75 9zM6 20.25a6 6 0 0112 0H6z" />
                </svg>
                Kelola Data Pengguna
            </a>
        @endif
    </nav>

    <div class="p-6 text-xs text-[#6b7d6f] border-t border-[#e6eee7]">
        © 2025 SITAFT
    </div>
</aside>
