<x-app-layout>
    <div class="flex min-h-screen bg-[#fbfdfe]">
        @include('layouts.sidebar')

        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow-sm flex items-center justify-between px-8 py-4">
                <h2 class="text-xl font-semibold text-gray-700">Kelola Data Pengguna</h2>

                <div class="flex items-center space-x-4">
                    @include('layouts.userdropdown')
                </div>
            </header>

            <main class="flex-1 p-8">
                @include('dashboard.koordinator.users.partials.table')
            </main>
        </div>
    </div>
</x-app-layout>
