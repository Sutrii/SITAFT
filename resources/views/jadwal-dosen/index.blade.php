<x-app-layout>
    <div class="flex min-h-screen bg-[#fbfdfe]">
        
        {{-- Sidebar --}}
        @include('layouts.sidebar')

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col">

            {{-- Topbar --}}
            <header class="bg-white shadow-sm flex items-center justify-between px-8 py-4">
                <h2 class="text-xl font-semibold text-gray-700">Data Dosen & Jadwal Kosong</h2>

                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" placeholder="Cari dosen..."
                            class="w-80 px-4 py-2 pl-10 rounded-full border border-gray-300 text-sm focus:ring-2 focus:ring-green-400 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor"
                            class="w-5 h-5 absolute left-3 top-2.5 text-gray-400">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 18a7.5 7.5 0 006.15-3.35z" />
                        </svg>
                    </div>

                    {{-- User Menu --}}
                    @include('layouts.userdropdown')
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 p-8">
                @include('jadwal-dosen.partials.table')
            </main>
        </div>
    </div>
</x-app-layout>
