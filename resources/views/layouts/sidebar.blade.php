{{-- resources/views/layouts/sidebar.blade.php --}}
<aside class="w-64 bg-[#f4f8f4] text-[#2d3a32] flex flex-col border-r border-[#e6eee7]">
    <div class="p-6 flex items-center gap-3">
        <div class="w-10 h-10 bg-[#dff3e5] flex items-center justify-center rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-[#3ea76a]">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 8.25v3.75l2.25 1.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div>
            <h1 class="font-semibold text-[#2d3a32]">SITAFT</h1>
            <p class="text-sm text-[#6b7d6f]">Tugas Akhir FT</p>
        </div>
    </div>

    {{-- Sidebar Menu --}}
    <nav class="mt-4 flex-1 px-4 space-y-1 text-sm">
        <p class="uppercase text-[11px] tracking-wide text-[#6b7d6f] font-semibold mt-2 mb-1">Menu</p>

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

        <a href="{{ route('dosen') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-all
            {{ request()->routeIs('dosen') ? 'text-[#3ea76a]' : 'hover:text-[#3ea76a] text-[#2d3a32]' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 14.25c3.728 0 6.75 3.022 6.75 6.75H5.25c0-3.728 3.022-6.75 6.75-6.75zm0-9a3 3 0 100 6 3 3 0 000-6z" />
            </svg>
            Data Dosen
        </a>
    </nav>

    <div class="p-6 text-xs text-[#6b7d6f] border-t border-[#e6eee7]">
        © 2025 SITAFT
    </div>
</aside>
