@extends('layouts.app')

@section('title', 'Login - E-Presensi')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-slate-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-lg">
        <div>
            <div class="flex justify-center">
                <i class="fa-solid fa-building-shield text-5xl text-blue-600"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-slate-950">
                E-Presensi Digital
            </h2>
            <p class="mt-2 text-center text-sm text-slate-600">
                Silakan login untuk mengakses dashboard Anda
            </p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-circle-xmark text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700 font-semibold">
                            {{ $errors->first() }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="rounded-md shadow-sm space-y-4">
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700">Alamat Email</label>
                    <input id="email" name="email" type="email" required class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-slate-300 placeholder-slate-400 text-slate-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" placeholder="nama@mail.com" value="{{ old('email') }}">
                </div>
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700">Kata Sandi</label>
                    <input id="password" name="password" type="password" required class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-slate-300 placeholder-slate-400 text-slate-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" placeholder="••••••••">
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-bold rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                    Masuk ke Sistem
                </button>
            </div>
        </form>

        <div class="text-center mt-4">
            <p class="text-sm text-slate-600">Belum punya akun pegawai? <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:text-blue-700">Daftar Sekarang</a></p>
        </div>
    </div>
</div>
@endsection