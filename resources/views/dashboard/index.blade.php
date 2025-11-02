<x-app-layout>
    <div class="flex min-h-screen bg-[#fbfdfe]">
        @include('layouts.sidebar')

        <div class="flex-1 flex flex-col">
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

            <main class="flex-1 p-8">
                <div class="grid grid-cols-5 gap-6 items-stretch">

                    <div class="col-span-3 bg-white rounded-3xl shadow-xl p-6 flex flex-col h-full">
                        <h3 id="dosenTitle" class="text-lg font-semibold text-green-800 mb-4">Dosen Kosong Hari Ini</h3>
                        <div id="dosenKosongContainer" class="overflow-y-auto space-y-4 flex-1">
                            @forelse ($dosenKosong as $group)
                                @php
                                    $d = $group->first()->dosen;
                                    $slots = $group->pluck('jam')->toArray();
                                @endphp
                                <div class="border border-green-100 rounded-2xl p-4 hover:shadow-md transition">
                                    <h4 class="font-semibold text-gray-800">{{ $d->name }}</h4>
                                    <p class="text-sm text-gray-500 mt-1">Slot kosong:</p>
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @foreach ($slots as $slot)
                                            <span
                                                class="px-2.5 py-1 rounded-full text-xs bg-green-100 text-green-800 font-medium">
                                                {{ $slot }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">Tidak ada dosen kosong hari ini.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="col-span-2 flex flex-col gap-6 h-full">
                        <div class="bg-white rounded-3xl shadow-xl p-6 flex-1">
                            <div class="flex justify-between items-center mb-4">
                                <h3 id="monthTitle" class="text-lg font-semibold text-green-800">{{ $currentMonth }}</h3>
                                <div class="flex space-x-2">
                                    <button id="prevMonthBtn"
                                        class="px-3 py-1 bg-green-100 hover:bg-green-200 rounded-lg text-green-700 font-medium">‹</button>
                                    <button id="nextMonthBtn"
                                        class="px-3 py-1 bg-green-100 hover:bg-green-200 rounded-lg text-green-700 font-medium">›</button>
                                </div>
                            </div>

                            <div class="grid grid-cols-7 text-center text-sm font-medium text-gray-600">
                                <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
                            </div>

                            <div id="calendarGrid" class="grid grid-cols-7 gap-2 mt-3 text-center"></div>
                        </div>

                        <div class="bg-white rounded-3xl shadow-xl p-6 flex-1">
                            <h3 class="text-lg font-semibold text-green-800 mb-4">Jadwal Seminar</h3>
                            <ul id="seminarList" class="space-y-4 text-sm"></ul>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</x-app-layout>

<script>
document.addEventListener("DOMContentLoaded", () => {
    let currentMonth = new Date().getMonth() + 1;
    const bulan = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];

    const monthTitle = document.getElementById("monthTitle");
    const calendarGrid = document.getElementById("calendarGrid");
    const seminarList = document.getElementById("seminarList");
    const dosenKosongContainer = document.getElementById("dosenKosongContainer");

    const renderDashboard = async (month) => {
        const today = new Date();
        const year = today.getFullYear();
        const firstDay = new Date(year, month - 1, 1).getDay();
        const daysInMonth = new Date(year, month, 0).getDate();

        const res = await fetch(`/dashboard/month/${month}`);
        const data = await res.json();

        monthTitle.textContent = `${bulan[month - 1]} ${year}`;
        calendarGrid.innerHTML = "";

        for (let i = 0; i < firstDay; i++) {
            const emptyDiv = document.createElement("div");
            calendarGrid.appendChild(emptyDiv);
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const seminar = data.find(j => new Date(j.jadwal_seminar).getDate() === day);
            const isToday = day === today.getDate() && month === (today.getMonth() + 1);

            let colorClass = "text-gray-700 hover:bg-green-100";
            if (seminar) {
                colorClass = {
                    "Seminar Proposal": "bg-green-200 text-green-800",
                    "Seminar Hasil": "bg-blue-200 text-blue-800",
                    "Sidang Akhir": "bg-red-200 text-red-800"
                }[seminar.status] || colorClass;
            }

            const div = document.createElement("div");
            div.textContent = day;
            div.className = `
                flex items-center justify-center w-9 h-9 mx-auto rounded-full cursor-pointer transition
                hover:scale-105 ${colorClass} ${isToday ? "ring-2 ring-green-500 font-bold" : ""}
            `;

            div.addEventListener("click", () => {
                document.querySelectorAll("#calendarGrid div").forEach(d => {
                    d.classList.remove("ring-2", "ring-green-500", "font-bold");
                });
                div.classList.add("ring-2", "ring-green-500", "font-bold");
                loadDayData(month, day);
            });

            calendarGrid.appendChild(div);
        }

        seminarList.innerHTML = "";
        if (data.length) {
            data.forEach(j => {
                const tanggal = new Date(j.jadwal_seminar).toLocaleDateString("id-ID", {
                    day: "numeric", month: "short", year: "numeric",
                    hour: "2-digit", minute: "2-digit"
                });
                const colorMap = {
                    "Seminar Proposal": "border-green-400 text-green-700",
                    "Seminar Hasil": "border-blue-400 text-blue-700",
                    "Sidang Akhir": "border-red-400 text-red-700"
                };
                seminarList.innerHTML += `
                    <li class="border-l-4 ${colorMap[j.status] ?? "border-gray-300 text-gray-700"} pl-3">
                        <p class="text-gray-800 font-medium">${tanggal}</p>
                        <p>${j.status} — ${j.mahasiswa?.name ?? "-"}</p>
                    </li>`;
            });
        } else {
            seminarList.innerHTML = `<p class="text-gray-500 text-sm">Belum ada jadwal seminar bulan ini.</p>`;
        }
    };

    async function loadDayData(month, day) {
        const res = await fetch(`/dashboard/data/${month}/${day}`);
        const { hari, tanggal, dosenKosong, jadwalSeminar } = await res.json();

        const title = document.getElementById("dosenTitle");
        title.textContent = `Dosen Kosong ${hari}, ${tanggal}`;
  
        seminarList.innerHTML = "";
        if (jadwalSeminar.length) {
            jadwalSeminar.forEach(j => {
                const tanggal = new Date(j.jadwal_seminar).toLocaleDateString("id-ID", {
                    day: "numeric", month: "short", year: "numeric",
                    hour: "2-digit", minute: "2-digit"
                });
                const colorMap = {
                    "Seminar Proposal": "border-green-400 text-green-700",
                    "Seminar Hasil": "border-blue-400 text-blue-700",
                    "Sidang Akhir": "border-red-400 text-red-700"
                };
                seminarList.innerHTML += `
                    <li class="border-l-4 ${colorMap[j.status] ?? "border-gray-300 text-gray-700"} pl-3">
                        <p class="text-gray-800 font-medium">${tanggal}</p>
                        <p>${j.status} — ${j.mahasiswa?.name ?? "-"}</p>
                    </li>`;
            });
        } else {
            seminarList.innerHTML = `<p class="text-gray-500 text-sm">Tidak ada jadwal seminar tanggal ini.</p>`;
        }

        dosenKosongContainer.innerHTML = "";
        const groups = Object.values(dosenKosong ?? {});
        if (groups.length) {
            groups.forEach(group => {
                const d = group[0].dosen;
                dosenKosongContainer.innerHTML += `
                    <div class="border border-green-100 rounded-2xl p-4 hover:shadow-md transition">
                        <h4 class="font-semibold text-gray-800">${d.name}</h4>
                        <p class="text-sm text-gray-500 mt-1">Slot kosong:</p>
                        <div class="mt-2 flex flex-wrap gap-2">
                            ${group.map(g => `<span class='px-2.5 py-1 rounded-full text-xs bg-green-100 text-green-800 font-medium'>${g.jam}</span>`).join("")}
                        </div>
                    </div>`;
            });
        } else {
            dosenKosongContainer.innerHTML = `<p class="text-gray-500 text-sm">Tidak ada dosen kosong tanggal ini.</p>`;
        }
    }


    document.getElementById("prevMonthBtn").addEventListener("click", () => {
        currentMonth = currentMonth <= 1 ? 12 : currentMonth - 1;
        renderDashboard(currentMonth);
    });
    document.getElementById("nextMonthBtn").addEventListener("click", () => {
        currentMonth = currentMonth >= 12 ? 1 : currentMonth + 1;
        renderDashboard(currentMonth);
    });

    renderDashboard(currentMonth);
});
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const userMenu = document.getElementById('user-menu');
  const dropdown = document.getElementById('user-dropdown');

  if (userMenu && dropdown) {
    userMenu.addEventListener('click', (e) => {
      e.stopPropagation();
      dropdown.classList.toggle('hidden');
    });

    document.addEventListener('click', (e) => {
      if (!userMenu.contains(e.target)) dropdown.classList.add('hidden');
    });
  }
});
</script>

<style>
[x-cloak],
[x-data],
[x-init],
[x-show],
[x-transition],
[x-bind] {
  z-index: auto !important;
}

header {
  position: relative !important;
  z-index: 100 !important;
}

#user-dropdown {
  z-index: 99999 !important;
  pointer-events: auto;
}

.flex-1,
main {
  overflow: visible !important;
}
</style>

