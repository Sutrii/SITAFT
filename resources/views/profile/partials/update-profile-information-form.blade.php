<section class="bg-white shadow-md rounded-2xl p-6 border border-[#e8f0e8]">
    <header class="border-b border-[#e8f0e8] pb-3 mb-5">
        <h2 class="text-2xl font-semibold text-[#2d3a32]">
            {{ __('Informasi Profil') }}
        </h2>

        <p class="mt-1 text-sm text-[#6b7d6f]">
            {{ __("Perbarui nama dan alamat email akun Anda di bawah ini.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        {{-- Nama --}}
        <div>
            <label for="name" class="block text-sm font-medium text-[#2d3a32] mb-1">
                {{ __('Nama Lengkap') }}
            </label>
            <input id="name" name="name" type="text"
                class="w-full border border-[#d8e4d8] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#3ea76a] focus:outline-none"
                value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-[#2d3a32] mb-1">
                {{ __('Alamat Email') }}
            </label>
            <input id="email" name="email" type="email"
                class="w-full border border-[#d8e4d8] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#3ea76a] focus:outline-none"
                value="{{ old('email', $user->email) }}" required autocomplete="username">
            @error('email')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-sm text-[#6b7d6f]">
                        {{ __('Email Anda belum diverifikasi.') }}
                        <button form="send-verification"
                            class="underline text-[#3ea76a] font-medium hover:text-[#2d8c5d] focus:outline-none">
                            {{ __('Kirim ulang tautan verifikasi') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm font-medium text-[#3ea76a]">
                            {{ __('Tautan verifikasi baru telah dikirim ke email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Tombol Simpan --}}
        <div class="flex items-center gap-3 pt-2">
            <button type="submit"
                class="bg-[#3ea76a] text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-[#2d8c5d] transition">
                {{ __('Simpan Perubahan') }}
            </button>
        </div>
    </form>
</section>

{{-- SWEET ALERT CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Alert Handling --}}
@if (session('status') === 'profile-updated')
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Perubahan profil berhasil disimpan.',
            showConfirmButton: false,
            timer: 1800,
            background: '#f9fcfa',
            color: '#2d3a32',
            confirmButtonColor: '#3ea76a'
        });
    </script>
@endif

@if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            confirmButtonText: 'Oke',
            confirmButtonColor: '#e3342f',
            background: '#fff',
            color: '#2d3a32'
        });
    </script>
@endif
