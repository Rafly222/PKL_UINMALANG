@extends('layouts.app')

@section('title', 'Daftar Akun Pegawai - E-Presensi')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-slate-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-6 bg-white p-8 rounded-2xl shadow-lg">
        <div>
            <h2 class="text-center text-3xl font-extrabold text-slate-950">
                Pendaftaran Pegawai
            </h2>
            <p class="mt-2 text-center text-sm text-slate-600">
                Khusus Staff dan Pegawai Diskominfo Kota Malang
            </p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
                <p class="text-sm text-red-700 font-bold">Terjadi Kesalahan:</p>
                <ul class="list-disc pl-5 text-xs text-red-600 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="space-y-4" action="{{ route('register') }}" method="POST">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700">Nama Lengkap</label>
                <input name="name" type="text" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" placeholder="Nama lengkap beserta gelar" value="{{ old('name') }}">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700">Alamat Email</label>
                <input name="email" type="email" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" placeholder="email@malangkota.go.id" value="{{ old('email') }}">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700">NIK (Sesuai KTP)</label>
                    <input name="nik" type="text" required maxlength="16" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" placeholder="16 Digit NIK" value="{{ old('nik') }}">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700">NIP (Kosongkan jika non-ASN)</label>
                    <input name="nip" type="text" maxlength="18" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" placeholder="18 Digit NIP" value="{{ old('nip') }}">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700">Kata Sandi</label>
                    <input name="password" type="password" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" placeholder="Minimal 6 karakter">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700">Konfirmasi Sandi</label>
                    <input name="password_confirmation" type="password" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" placeholder="Ulangi sandi">
                </div>
            </div>

            <button type="submit" class="w-full py-2 px-4 text-sm font-bold rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition">
                Daftar Akun Baru
            </button>
        </form>

        <div class="text-center">
            <p class="text-sm text-slate-600">Sudah punya akun? <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-blue-700">Masuk di sini</a></p>
        </div>
    </div>
</div>
@endsection