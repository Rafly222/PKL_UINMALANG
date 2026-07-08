@extends('layouts.app')

@section('title', 'Presensi Acara Resmi - Diskominfo Kota Malang')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="text-center mb-8">
        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold uppercase tracking-wider">
            Target: {{ strtoupper($event->target_audience) }}
        </span>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-blue-950 mt-3">
            Presensi Kehadiran: {{ $event->name }}
        </h2>
        <p class="text-sm text-slate-500 mt-2">Silakan lengkapi data presensi dinas di bawah ini dengan valid dan tertib.</p>
    </div>

    <div class="w-full bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
        <div class="h-2 bg-gradient-to-r from-blue-800 via-indigo-900 to-teal-700"></div>

        <form id="form-presensi" action="{{ route('presensi.submit', $event->id) }}" method="POST" class="p-6 sm:p-10 space-y-8">
            @csrf

            <input type="hidden" name="kategori_peserta" value="{{ $event->target_audience }}">

            <div class="space-y-4">
                <div class="space-y-2">
                    <label for="nama_lengkap" class="block text-sm font-bold text-slate-700">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-800 text-sm font-medium" placeholder="Masukkan nama lengkap beserta gelar Anda">
                </div>

                @if($event->target_audience === 'pegawai' || $event->target_audience === 'semua' || (isset($event->form_fields['semi_custom']['nip']) && $event->form_fields['semi_custom']['nip']))
                <div id="kolom-nip" class="space-y-2">
                    <label for="nip_input" class="block text-sm font-bold text-slate-700">Nomor Induk Pegawai (NIP) <span class="text-red-500">*</span></label>
                    <div class="flex space-x-2">
                        <input type="text" name="nip" id="nip_input" required class="flex-grow px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-800 text-sm font-medium" placeholder="Masukkan 18 digit NIP Dinas">
                        <button type="button" onclick="verifikasiNip()" class="px-5 py-3 bg-blue-900 text-white rounded-xl text-sm font-bold transition-all-300 hover:bg-blue-950 flex items-center shadow-md">
                            <i class="fa-solid fa-magnifying-glass mr-2"></i>Cari API
                        </button>
                    </div>
                    <span id="nip-alert" class="text-xs font-semibold block"></span>
                </div>
                @endif

                @if(isset($event->form_fields['semi_custom']['no_wa']) && $event->form_fields['semi_custom']['no_wa'])
                <div class="space-y-2">
                    <label for="no_wa" class="block text-sm font-bold text-slate-700">Nomor WhatsApp <span class="text-red-500">*</span></label>
                    <input type="text" name="no_wa" id="no_wa" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-800 text-sm font-medium" placeholder="Contoh: 0812345678xx">
                </div>
                @endif

                @if(isset($event->form_fields['semi_custom']['instansi']) && $event->form_fields['semi_custom']['instansi'])
                <div class="space-y-2">
                    <label for="instansi" class="block text-sm font-bold text-slate-700">Instansi / Asal Lembaga <span class="text-red-500">*</span></label>
                    <input type="text" name="instansi" id="instansi" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-800 text-sm font-medium" placeholder="Masukkan nama instansi asal Anda">
                </div>
                @endif

                @if(isset($event->form_fields['full_custom']) && count($event->form_fields['full_custom']) > 0)
                <div class="pt-4 border-t border-dashed border-slate-200">
                    <h4 class="text-xs font-bold text-indigo-950 uppercase tracking-widest mb-4">Informasi Tambahan Keuangan / Dinas Field</h4>
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($event->form_fields['full_custom'] as $cf)
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700">{{ $cf['nama_field'] }} <span class="text-red-500">*</span></label>
                            <input type="{{ $cf['tipe_data'] }}" name="custom_fields[{{ $cf['nama_field'] }}]" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-800 text-sm font-medium">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            @if(isset($event->form_fields['semi_custom']['foto']) && $event->form_fields['semi_custom']['foto'])
            <div class="space-y-3 pt-4 border-t border-slate-100">
                <label class="block text-sm font-bold text-slate-700">
                    <i class="fa-solid fa-camera text-blue-800 mr-2"></i>Capture Foto Wajah <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="relative bg-slate-100 rounded-xl overflow-hidden border border-slate-200 flex flex-col items-center justify-center h-56 shadow-inner">
                        <div id="video-wrapper" class="w-full h-full">
                            <video id="webcam-preview" autoplay playsinline class="w-full h-full object-cover"></video>
                        </div>
                        <div id="camera-fallback-wrapper" class="hidden p-4 text-center">
                            <i class="fa-solid fa-circle-exclamation text-amber-500 text-3xl mb-2"></i>
                            <p class="text-xs text-slate-600 font-medium mb-3">Kamera tidak terdeteksi.</p>
                        </div>
                    </div>
                    <div class="relative bg-slate-50 border-2 border-dashed border-slate-200 rounded-xl flex flex-col items-center justify-center h-56 overflow-hidden">
                        <img id="photo-preview-img" class="hidden absolute inset-0 w-full h-full object-cover z-10">
                        <div class="text-center p-4">
                            <i class="fa-solid fa-image-portrait text-3xl text-slate-300 mb-1"></i>
                            <p class="text-xs text-slate-400 font-semibold">Pratinjau Foto Absensi</p>
                        </div>
                    </div>
                </div>
                <button type="button" id="capture-photo-btn" class="w-full py-2 bg-gradient-to-r from-blue-900 to-indigo-950 text-white rounded-2xl text-xs font-bold flex items-center justify-center shadow-md">
                    <i class="fa-solid fa-camera mr-2"></i>Ambil Foto Presensi (Capture)
                </button>
                <input type="hidden" name="foto_capture" id="foto_capture_input" required>
            </div>
            @endif

            @if(isset($event->form_fields['semi_custom']['ttd']) && $event->form_fields['semi_custom']['ttd'])
            <div class="space-y-2 pt-4 border-t border-slate-100">
                <div class="flex items-center justify-between">
                    <label class="block text-sm font-bold text-slate-700">
                        <i class="fa-solid fa-signature text-blue-800 mr-2"></i>Tanda Tangan Digital <span class="text-red-500">*</span>
                    </label>
                    <button type="button" id="clear-signature" class="text-xs font-bold text-red-600 hover:text-red-800">
                        <i class="fa-solid fa-eraser mr-1"></i>Bersihkan Coretan
                    </button>
                </div>
                <div class="relative border-2 border-slate-200 rounded-xl overflow-hidden bg-slate-50">
                    <canvas id="signature-pad" class="w-full h-44 cursor-crosshair touch-none"></canvas>
                </div>
                <input type="hidden" name="tanda_tangan" id="tanda_tangan_input" required>
            </div>
            @endif

            <div class="pt-4">
                <button type="submit" id="btn-submit" class="w-full py-4 bg-gradient-to-r from-blue-900 to-indigo-950 text-white rounded-2xl text-base font-extrabold transition-all-300 flex items-center justify-center shadow-lg">
                    <i class="fa-solid fa-clipboard-check text-xl mr-3"></i>KIRIM ABSENSI KEHADIRAN RESMI
                </button>
            </div>
        </form>
    </div>
</div>

<canvas id="camera-canvas" class="hidden"></canvas>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/signature.js') }}"></script>
<script src="{{ asset('assets/js/camera.js') }}"></script>

<script>
    // Fungsi pencarian data kepegawaian (Autofill API)
    async function verifikasiNip() {
        const nip = document.getElementById('nip_input').value.trim();
        const alertSpan = document.getElementById('nip-alert');
        
        if (!nip) {
            alertSpan.className = 'text-xs text-red-600 font-semibold';
            alertSpan.innerHTML = 'Silakan masukkan NIP terlebih dahulu.';
            return;
        }

        alertSpan.className = 'text-xs text-amber-600 font-semibold';
        alertSpan.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i>Menghubungkan ke API Pegawai...';

        try {
            const response = await fetch(`/api/pegawai/${nip}`);
            const result = await response.json();

            if (result.success) {
                if(document.getElementById('nama_lengkap')) document.getElementById('nama_lengkap').value = result.data.nama;
                if(document.getElementById('instansi')) document.getElementById('instansi').value = result.data.instansi;
                if(document.getElementById('no_wa')) document.getElementById('no_wa').value = result.data.no_wa;

                alertSpan.className = 'text-xs text-emerald-600 font-semibold';
                alertSpan.innerHTML = '<i class="fa-solid fa-circle-check mr-1"></i>Integrasi Berhasil! Data terisi otomatis.';
            }
        } catch (error) {
            alertSpan.className = 'text-xs text-red-600 font-semibold';
            alertSpan.innerHTML = '<i class="fa-solid fa-triangle-exclamation mr-1"></i>Pegawai tidak terdaftar / API tidak merespons.';
        }
    }

    // Mencegah klik ganda saat submit data
    document.getElementById('form-presensi').addEventListener('submit', function() {
        let btn = document.getElementById('btn-submit');
        if(btn) {
            btn.disabled = true;
            btn.classList.add('opacity-70', 'cursor-not-allowed');
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xl mr-3"></i>SEDANG MEMPROSES...';
        }
    });
</script>
@endsection