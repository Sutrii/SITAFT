<x-app-layout>
    <div class="flex min-h-screen bg-[#fbfdfe]">

        {{-- Sidebar --}}
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

            {{-- Sidebar --}}
            <nav class="mt-4 flex-1 px-4 space-y-1 text-sm">
                <p class="uppercase text-[11px] tracking-wide text-[#6b7d6f] font-semibold mt-2 mb-1">Home</p>

                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-all
                    {{ request()->routeIs('dashboard')
                        ? 'text-[#3ea76a]'
                        : 'hover:text-[#3ea76a] text-[#2d3a32]' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 3.75v16.5h16.5V3.75H3.75zm3 3h10.5v10.5H6.75V6.75z" />
                    </svg>
                    Dashboard
                </a>

                {{-- Jadwal Tugas Akhir --}}
                <a href="{{ route('jadwal') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-all
                    {{ request()->routeIs('jadwal')
                        ? 'text-[#3ea76a]'
                        : 'hover:text-[#3ea76a] text-[#2d3a32]' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.25 3v1.5M15.75 3v1.5M3.75 7.5h16.5M4.5 9.75h15a1.5 1.5 0 011.5 1.5v7.5a1.5 1.5 0 01-1.5 1.5h-15a1.5 1.5 0 01-1.5-1.5v-7.5a1.5 1.5 0 011.5-1.5z" />
                    </svg>
                    Jadwal Tugas Akhir
                </a>

                {{-- Data Dosen --}}
                <a href="{{ route('dosen') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-all
                    {{ request()->routeIs('dosen')
                        ? 'text-[#3ea76a]'
                        : 'hover:text-[#3ea76a] text-[#2d3a32]' }}">
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

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col">

            {{-- Top Navbar --}}
            <header class="bg-white shadow-sm flex items-center justify-between px-8 py-4">
                <h2 class="text-xl font-semibold text-gray-700">Dashboard</h2>

                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" placeholder="Cari jadwal atau dosen..."
                            class="px-4 py-2 pl-10 rounded-full border border-gray-300 text-sm focus:ring-2 focus:ring-green-400 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor"
                            class="w-5 h-5 absolute left-3 top-2.5 text-gray-400">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 18a7.5 7.5 0 006.15-3.35z" />
                        </svg>
                    </div>

                    {{-- User dropdown --}}
                    <div class="relative">
                        <button id="user-menu" type="button"
                            class="flex items-center space-x-3 focus:outline-none group">
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
                            class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100">
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-gray-700 hover:bg-green-50 hover:text-green-800">Profil</a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-700">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Content --}}
            <main class="flex-1 p-8">
                <div class="grid grid-cols-5 gap-6 items-stretch">

                    {{-- 📋 Dosen Kosong (60%) --}}
                    <div class="col-span-3 bg-white rounded-3xl shadow-xl p-6 flex flex-col h-full">
                        <h3 class="text-lg font-semibold text-green-800 mb-4">Dosen Kosong Hari Ini</h3>
                        @php
                            $slots = [
                                "08.00 - 08.50", "08.50 - 09.40", "09.40 - 10.30", "10.30 - 11.20",
                                "11.20 - 12.10", "12.10 - 13.00", "13.00 - 14.00", "14.00 - 14.50",
                                "14.50 - 15.40", "15.40 - 16.30", "16.30 - 17.20", "17.20 - 18.10"
                            ];
                            $dosen = ["Dr. Budi Santoso", "Ir. Rina Wijaya", "Prof. Agus Mahendra", "Dr. Andi Saputra"];
                        @endphp

                        <div class="overflow-y-auto space-y-4 flex-1">
                            @foreach ($dosen as $name)
                                <div class="border border-green-100 rounded-2xl p-4 hover:shadow-md transition">
                                    <h4 class="font-semibold text-gray-800">{{ $name }}</h4>
                                    <p class="text-sm text-gray-500 mt-1">Slot kosong:</p>
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @foreach (collect($slots)->random(3) as $slot)
                                            <span class="px-2.5 py-1 rounded-full text-xs bg-green-100 text-green-800 font-medium">
                                                {{ $slot }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- 📅 Kalender + Jadwal (40%) --}}
                    <div class="col-span-2 flex flex-col gap-6 h-full">
                        
                        {{-- Kalender --}}
                        <div class="bg-white rounded-3xl shadow-xl p-6 flex-1">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-green-800">Oktober 2025</h3>
                                <div class="flex space-x-2">
                                    <button class="px-3 py-1 bg-green-100 hover:bg-green-200 rounded-lg text-green-700 font-medium">‹</button>
                                    <button class="px-3 py-1 bg-green-100 hover:bg-green-200 rounded-lg text-green-700 font-medium">›</button>
                                </div>
                            </div>

                            <div class="grid grid-cols-7 text-center text-sm font-medium text-gray-600">
                                <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
                            </div>

                            <div class="grid grid-cols-7 gap-2 mt-3 text-center">
                                @for ($i = 1; $i <= 31; $i++)
                                    @php
                                        $eventColors = [
                                            3 => 'bg-green-200 text-green-800',
                                            9 => 'bg-blue-200 text-blue-800',
                                            17 => 'bg-red-200 text-red-800',
                                            22 => 'bg-green-200 text-green-800',
                                            26 => 'bg-blue-200 text-blue-800',
                                        ];
                                    @endphp
                                    <div
                                        class="flex items-center justify-center w-9 h-9 mx-auto rounded-full transition cursor-pointer hover:scale-105
                                        {{ $eventColors[$i] ?? 'text-gray-700 hover:bg-green-100' }}">
                                        {{ $i }}
                                    </div>
                                @endfor
                            </div>
                        </div>

                        {{-- Jadwal Seminar --}}
                        <div class="bg-white rounded-3xl shadow-xl p-6 flex-1">
                            <h3 class="text-lg font-semibold text-green-800 mb-4">Jadwal Seminar</h3>
                            <ul class="space-y-4 text-sm">
                                <li class="border-l-4 border-green-400 pl-3">
                                    <p class="text-gray-800 font-medium">3 Okt 2025 — 09.00</p>
                                    <p class="text-green-700">Seminar Proposal</p>
                                </li>
                                <li class="border-l-4 border-blue-400 pl-3">
                                    <p class="text-gray-800 font-medium">9 Okt 2025 — 13.30</p>
                                    <p class="text-blue-700">Seminar Hasil</p>
                                </li>
                                <li class="border-l-4 border-red-400 pl-3">
                                    <p class="text-gray-800 font-medium">17 Okt 2025 — 10.00</p>
                                    <p class="text-red-700">Sidang Akhir</p>
                                </li>
                                <li class="border-l-4 border-green-400 pl-3">
                                    <p class="text-gray-800 font-medium">22 Okt 2025 — 15.00</p>
                                    <p class="text-green-700">Seminar Proposal</p>
                                </li>
                                <li class="border-l-4 border-blue-400 pl-3">
                                    <p class="text-gray-800 font-medium">26 Okt 2025 — 08.30</p>
                                    <p class="text-blue-700">Seminar Hasil</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</x-app-layout>

<script>
document.addEventListener('click', function (e) {
    const button = document.getElementById('user-menu');
    const dropdown = document.getElementById('user-dropdown');
    if (button.contains(e.target)) {
        dropdown.classList.toggle('hidden');
    } else {
        dropdown.classList.add('hidden');
    }
});
</script>
