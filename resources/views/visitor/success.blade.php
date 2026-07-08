@extends('layouts.app')

@section('title', 'Presensi Berhasil - E-Presensi')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-slate-50 py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-100">
        <div class="bg-gradient-to-r from-emerald-500 to-teal-600 p-8 text-center text-white">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-full mb-4">
                <i class="fa-solid fa-circle-check text-4xl"></i>
            </div>
            <h2 class="text-2xl font-black">Presensi Berhasil!</h2>
            <p class="text-sm text-emerald-100 mt-1">Terima kasih atas partisipasi kehadiran Anda.</p>
        </div>

        <div class="p-8 space-y-6">
            <div class="border-b border-dashed border-slate-200 pb-4 space-y-2">
                <div class="flex justify-between text-xs text-slate-500">
                    <span>Nama Lengkap:</span>
                    <span class="font-bold text-slate-900">{{ $presence->nama_lengkap }}</span>
                </div>
                <div class="flex justify-between text-xs text-slate-500">
                    <span>Kategori Peserta:</span>
                    <span class="font-bold text-slate-900">{{ strtoupper($presence->kategori_peserta) }}</span>
                </div>
                @if($presence->nip)
                <div class="flex justify-between text-xs text-slate-500">
                    <span>NIP Pegawai:</span>
                    <span class="font-bold text-slate-900">{{ $presence->nip }}</span>
                </div>
                @endif
                <div class="flex justify-between text-xs text-slate-500">
                    <span>Instansi:</span>
                    <span class="font-bold text-slate-900">{{ $presence->instansi }}</span>
                </div>
            </div>

            <!-- Simulasi QR Code Ramah Performa -->
            <div class="flex flex-col items-center justify-center bg-slate-50 p-4 rounded-2xl border border-slate-200">
                <div class="bg-white p-2 rounded-lg shadow-sm">
                    <svg class="w-32 h-32 text-slate-900" viewBox="0 0 100 100" fill="currentColor">
                        <!-- Pola SVG Grid Kotak mensimulasikan QR Code -->
                        <path d="M5 5h30v30H5zm5 5v20h20V10zM65 5h30v30H65zm5 5v20h20V10zM5 65h30v30H5zm5 5v20h20V70z" />
                        <path d="M15 15h10v10H15zM75 15h10v10H75zM15 75h10v10H15zM45 10h10v10H45zM45 30h10v10H45zM55 45h10v10H55zM35 45h10v10H35zM75 45h10v10H75z" />
                        <path d="M45 65h10v10H45zM65 65h10v10H65zm10 10h10v10H75zm-15 10h10v10H60z" />
                    </svg>
                </div>
                <span class="text-[10px] text-slate-500 font-mono tracking-widest mt-3">PRESENSI_ID-{{ $presence->id }}</span>
            </div>

            <div class="text-center">
                <p class="text-xs text-slate-400">Silakan tangkap layar (screenshot) halaman ini sebagai bukti kehadiran fisik kepada panitia di lokasi.</p>
            </div>
        </div>
    </div>
</div>
@endsection
