@extends('layouts.app')

@section('title', 'Akses Terbatas - E-Presensi')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-slate-100 py-12 px-4">
    <div class="max-w-md w-full space-y-6 bg-white p-8 rounded-2xl shadow-lg border border-red-100">
        <div class="text-center">
            <i class="fa-solid fa-lock text-red-500 text-5xl"></i>
            <h2 class="mt-4 text-2xl font-extrabold text-slate-950">Akses Event Terbatas</h2>
            <p class="text-sm text-slate-600 mt-2">
                Presensi ini memerlukan kata sandi khusus. Silakan tanyakan kepada panitia penyelenggara.
            </p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-3 rounded text-sm text-red-700 font-semibold">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('presensi.gate.verify', $id) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700">Masukkan Kata Sandi Acara</label>
                <input name="password" type="password" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-center font-mono text-lg tracking-widest mt-1" placeholder="••••••">
            </div>

            <button type="submit" class="w-full py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-lg transition">
                Buka Formulir Presensi
            </button>
        </form>
    </div>
</div>
@endsection