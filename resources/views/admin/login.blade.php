<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - E-Presensi Diskominfo Kota Malang</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body{
            font-family:'Noto Sans',sans-serif;
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-900 via-indigo-900 to-slate-900 flex items-center justify-center p-5">

<div class="w-full max-w-md">

    <!-- Logo -->
    <div class="text-center mb-8">

        <div class="mx-auto h-20 w-20 rounded-full bg-amber-400 flex items-center justify-center shadow-xl">
            <i class="fa-solid fa-building-columns text-4xl text-blue-900"></i>
        </div>

        <h1 class="mt-5 text-3xl font-extrabold text-white tracking-wide">
            E-PRESENSI
        </h1>

        <p class="text-amber-300 font-semibold text-sm tracking-widest">
            DISKOMINFO KOTA MALANG
        </p>

    </div>

    <!-- Card Login -->
    <div class="bg-white rounded-3xl shadow-2xl p-8">

        <h2 class="text-2xl font-extrabold text-slate-800 text-center">
            Login Administrator
        </h2>

        <p class="text-sm text-slate-500 text-center mt-2 mb-6">
            Silakan masuk menggunakan akun admin.
        </p>

        {{-- Error --}}
        @if ($errors->any())
            <div class="mb-5 bg-red-50 border border-red-200 text-red-700 rounded-xl p-3">
                <ul class="text-sm list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">

            @csrf

            <div>

                <label class="block text-sm font-bold text-slate-700 mb-2">
                    Email
                </label>

                <div class="relative">

                    <i class="fa-solid fa-envelope absolute left-4 top-4 text-slate-400"></i>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required

                        class="w-full pl-12 pr-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-900"

                       >

                </div>

            </div>

            <div>

                <label class="block text-sm font-bold text-slate-700 mb-2">
                    Password
                </label>

                <div class="relative">

                    <i class="fa-solid fa-lock absolute left-4 top-4 text-slate-400"></i>

                    <input
                        id="password"
                        type="password"
                        name="password"
                        required

                        class="w-full pl-12 pr-12 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-900"

                        >

                    <button
                        type="button"
                        onclick="togglePassword()"
                        class="absolute right-4 top-3 text-slate-500">

                        <i id="eyeIcon" class="fa-solid fa-eye"></i>

                    </button>

                </div>

            </div>

            <button
                type="submit"

                class="w-full bg-blue-900 hover:bg-blue-950 text-white py-3 rounded-xl font-bold transition duration-300 shadow-lg">

                <i class="fa-solid fa-right-to-bracket mr-2"></i>

                Login

            </button>

        </form>

    </div>

    <p class="text-center text-slate-300 text-xs mt-6">
        © 2026 Dinas Komunikasi dan Informatika Pemerintah Kota Malang
    </p>

</div>

<script>

function togglePassword(){

    let password=document.getElementById("password");

    let eye=document.getElementById("eyeIcon");

    if(password.type==="password"){

        password.type="text";

        eye.classList.remove("fa-eye");

        eye.classList.add("fa-eye-slash");

    }else{

        password.type="password";

        eye.classList.remove("fa-eye-slash");

        eye.classList.add("fa-eye");

    }

}

</script>

</body>
</html>