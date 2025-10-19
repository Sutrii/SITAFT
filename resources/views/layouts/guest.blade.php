<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SITAFT') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased bg-white">

    <div class="min-h-screen flex flex-col md:flex-row overflow-hidden">

        <!-- Left Side (Logo / Illustration) -->
        <div class="md:w-3/5 flex items-center justify-center relative bg-gradient-to-br from-emerald-300 via-green-200 to-lime-50">
            
            <!-- Decorative blurred circle -->
            <div class="absolute -top-24 -left-24 w-[400px] h-[400px] bg-emerald-400 rounded-full blur-[150px] opacity-30"></div>
            <div class="absolute bottom-0 right-0 w-[350px] h-[350px] bg-lime-300 rounded-full blur-[120px] opacity-40"></div>

            <!-- Decorative radial highlight -->
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_rgba(255,255,255,0.35),_transparent_70%)]"></div>

            <!-- Illustration -->
            <img src="{{ asset('assets/images/8347.png') }}" 
                 alt="Logo SITAFT"
                 class="relative z-10 w-[550px] md:w-[700px] max-w-full object-contain drop-shadow-xl animate-fade-in">
        </div>

        <!-- Right Side (Login Form) -->
        <div class="md:w-2/5 flex flex-col justify-center px-10 md:px-16 py-10 bg-white relative z-10">
            <div class="max-w-md mx-auto w-full">
                <h1 class="text-4xl font-extrabold text-green-800 mb-3">Login</h1>
                <p class="text-gray-600 mb-8">Selamat datang! Silakan masuk untuk mengakses sistem tugas akhir Anda.</p>
                {{ $slot }}
            </div>
        </div>
    </div>

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 1s ease-out forwards;
        }
    </style>

</body>
</html>
