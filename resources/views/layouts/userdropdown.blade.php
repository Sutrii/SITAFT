{{-- resources/views/layouts/userdropdown.blade.php --}}
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
        class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 z-50">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full text-left px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-700">
                Logout
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('click', function (e) {
    const button = document.getElementById('user-menu');
    const dropdown = document.getElementById('user-dropdown');
    if (button && dropdown) {
        if (button.contains(e.target)) dropdown.classList.toggle('hidden');
        else dropdown.classList.add('hidden');
    }
});
</script>
