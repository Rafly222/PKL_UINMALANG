@extends('layouts.app')

@section('title', 'Sistem Presensi Resmi - Diskominfo Kota Malang')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Judul & Deskripsi Header Form -->
    <div class="text-center mb-8">
        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold uppercase tracking-wider">Aplikasi Publik</span>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-blue-950 mt-3">
            @if($selectedEvent)
                Absensi Kehadiran {{ $selectedEvent->nama_event }}
            @else
                Sistem Presensi Tamu & Pegawai
            @endif
        </h2>
        <p class="text-sm text-slate-500 mt-2">Silakan lengkapi formulir di bawah ini dengan tertib. Pastikan kamera dan layar tanda tangan berfungsi penuh.</p>
    </div>

    <!-- Kotak Utama Formulir -->
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
        <!-- Banner Hiasan Header Card -->
        <div class="h-2 bg-gradient-to-r from-blue-800 via-indigo-900 to-teal-700"></div>

        <!-- <form action="{{ route('presensi.store') }}" method="POST" class="p-6 sm:p-10 space-y-8"> -->
        <form id="form-presensi" action="{{ route('presensi.store') }}" method="POST" class="p-6 sm:p-10 space-y-8">
            @csrf

            <!-- 1. OPSI PILIHAN EVENT -->
            <div class="space-y-2">
                <label for="event_id" class="block text-sm font-bold text-slate-700">
                    <i class="fa-solid fa-calendar-day text-blue-800 mr-2"></i>Pilih Event Kehadiran <span class="text-red-500">*</span>
                </label>
                <select name="event_id" id="event_id" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-800 transition-all-300 text-sm sm:text-base font-medium" onchange="window.location.href = '/?event_id=' + this.value">
                    @forelse($events as $evt)
                        <option value="{{ $evt->id }}" {{ $selectedEvent && $selectedEvent->id == $evt->id ? 'selected' : '' }}>
                            {{ $evt->nama_event }} ({{ \Carbon\Carbon::parse($evt->tanggal_event)->translatedFormat('d F Y') }})
                        </option>
                    @empty
                        <option value="">Tidak ada event aktif hari ini</option>
                    @endforelse
                </select>
                <p class="text-xs text-slate-400">Judul judul form akan berubah secara otomatis menyesuaikan nama event profesional dinas.</p>
            </div>

            <!-- 2. PILIHAN JENIS FORMULIR (CUSTOM PEG/TAMU) -->
            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-700">
                    <i class="fa-solid fa-users-gear text-blue-800 mr-2"></i>Kategori Peserta Presensi <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 gap-4">
                    <!-- Opsi Pegawai (Fitur Custom Autofill) -->
                    <label for="kat_pegawai" class="relative flex flex-col items-center justify-center p-4 rounded-xl border-2 cursor-pointer transition-all-300 hover:bg-slate-50 border-slate-200" id="label-kat-peg">
                        <input type="radio" name="kategori_peserta" id="kat_pegawai" value="pegawai" class="absolute top-3 right-3 h-4 w-4 text-blue-800 focus:ring-blue-800" onclick="switchKategori('pegawai')">
                        <i class="fa-solid fa-user-tie text-2xl text-slate-400 mb-2 mt-2" id="icon-kat-peg"></i>
                        <span class="text-sm font-bold text-slate-700">Pegawai (Custom)</span>
                        <span class="text-xs text-slate-400 mt-1 text-center">Autofill otomatis via NIP</span>
                    </label>

                    <!-- Opsi Tamu (Fitur Normal Presensi) -->
                    <label for="kat_tamu" class="relative flex flex-col items-center justify-center p-4 rounded-xl border-2 cursor-pointer transition-all-300 hover:bg-slate-50 border-slate-200" id="label-kat-tamu">
                        <input type="radio" name="kategori_peserta" id="kat_tamu" value="tamu" class="absolute top-3 right-3 h-4 w-4 text-blue-800 focus:ring-blue-800" onclick="switchKategori('tamu')" checked>
                        <i class="fa-solid fa-id-card text-2xl text-slate-400 mb-2 mt-2" id="icon-kat-tamu"></i>
                        <span class="text-sm font-bold text-slate-700">Tamu Umum (Normal)</span>
                        <span class="text-xs text-slate-400 mt-1 text-center font-medium">Pengisian data manual</span>
                    </label>
                </div>
            </div>

            <!-- 3. ISIAN FORMULIR DINAMIS -->
            <div class="space-y-4">
                <!-- Kolom NIP (Khusus Pegawai - Muncul jika klik Pegawai) -->
                <div id="kolom-nip" class="hidden space-y-2 transition-all-300">
                    <label for="nip_input" class="block text-sm font-bold text-slate-700">Nomor Induk Pegawai (NIP) <span class="text-red-500">*</span></label>
                    <div class="flex space-x-2">
                        <input type="text" name="nip" id="nip_input" class="flex-grow px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-800 text-sm font-medium" placeholder="Masukkan 18 digit NIP Anda (Contoh: 19850315...)">
                        <button type="button" onclick="verifikasiNip()" class="px-5 py-3 bg-blue-900 text-white rounded-xl text-sm font-bold transition-all-300 hover:bg-blue-950 flex items-center shadow-md">
                            <i class="fa-solid fa-magnifying-glass mr-2"></i>Cari API
                        </button>
                    </div>
                    <span id="nip-alert" class="text-xs font-semibold block"></span>
                </div>

                <!-- Kolom Nama Lengkap -->
                <div class="space-y-2">
                    <label for="nama_lengkap" class="block text-sm font-bold text-slate-700">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-800 text-sm font-medium" placeholder="Masukkan nama lengkap beserta gelar Anda">
                </div>

                <!-- Grid Instansi & No WA -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label for="instansi" class="block text-sm font-bold text-slate-700">Instansi / Unit Kerja <span class="text-red-500">*</span></label>
                        <input type="text" name="instansi" id="instansi" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-800 text-sm font-medium" placeholder="Contoh: Universitas Islam Negeri Malang">
                    </div>

                    <div class="space-y-2">
                        <label for="no_wa" class="block text-sm font-bold text-slate-700">Nomor WhatsApp <span class="text-red-500">*</span></label>
                        <input type="text" name="no_wa" id="no_wa" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-800 text-sm font-medium" placeholder="Contoh: 0812345678xx">
                    </div>
                </div>
            </div>

            <!-- 4. MODUL LIVE WEBCAM CAPTURE -->
            <div class="space-y-3">
                <label class="block text-sm font-bold text-slate-700">
                    <i class="fa-solid fa-camera text-blue-800 mr-2"></i>Capture Foto Wajah <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Penampang Kamera Live -->
                    <div class="relative bg-slate-100 rounded-xl overflow-hidden border border-slate-200 flex flex-col items-center justify-center h-56 shadow-inner">
                        <div id="video-wrapper" class="w-full h-full">
                            <video id="webcam-preview" autoplay playsinline class="w-full h-full object-cover"></video>
                        </div>
                        
                        <!-- Fallback jika webcam mati -->
                        <div id="camera-fallback-wrapper" class="hidden p-4 text-center">
                            <i class="fa-solid fa-circle-exclamation text-amber-500 text-3xl mb-2"></i>
                            <p class="text-xs text-slate-600 font-medium mb-3">Kamera tidak terdeteksi di perangkat Anda.</p>
                            <input type="file" id="photo-upload-fallback" accept="image/*" class="hidden">
                            <label for="photo-upload-fallback" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 rounded-lg text-xs font-bold text-slate-700 cursor-pointer block">
                                <i class="fa-solid fa-upload mr-1"></i>Unggah Foto Manual
                            </label>
                        </div>
                    </div>

                    <!-- Hasil Pratinjau Capture -->
                    <!-- <div class="relative bg-slate-50 border-2 border-dashed border-slate-200 rounded-xl flex flex-col items-center justify-center h-56 overflow-hidden">
                        <img id="photo-preview-img" class="hidden w-full h-full object-cover">
                        <div class="text-center p-4"> -->
                    <div class="relative bg-slate-50 border-2 border-dashed border-slate-200 rounded-xl flex flex-col items-center justify-center h-56 overflow-hidden">
                        <img id="photo-preview-img" class="hidden absolute inset-0 w-full h-full object-cover z-10">
                        <div class="text-center p-4">
                            <i class="fa-solid fa-image-portrait text-3xl text-slate-300 mb-1"></i>
                            <p class="text-xs text-slate-400 font-semibold">Pratinjau Foto Absensi</p>
                        </div>
                    </div>
                </div>

                <!-- Tombol Ambil Foto -->
                <button type="button" id="capture-photo-btn" class="w-full py-2 bg-teal-700 text-white rounded-xl text-xs font-bold transition-all-300 hover:bg-teal-800 shadow shadow-teal-700/30">
                    <i class="fa-solid fa-shutter-button mr-2"></i>Ambil Foto Presensi (Capture)
                </button>

                <!-- Input Hidden untuk menampung data capture base64 -->
                <input type="hidden" name="foto_capture" id="foto_capture_input" required>
            </div>

            <!-- 5. MODUL TANDA TANGAN (SOLUSI TOUCHSCREEN) -->
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <label class="block text-sm font-bold text-slate-700">
                        <i class="fa-solid fa-signature text-blue-800 mr-2"></i>Goresan Tanda Tangan <span class="text-red-500">*</span>
                    </label>
                    <button type="button" id="clear-signature" class="text-xs font-bold text-red-600 hover:text-red-800 transition-all-300">
                        <i class="fa-solid fa-eraser mr-1"></i>Bersihkan Coreta
                    </button>
                </div>
                
                <!-- Pembungkus Canvas Touchscreen -->
                <div class="relative border-2 border-slate-200 rounded-xl overflow-hidden bg-slate-50">
                    <canvas id="signature-pad" class="w-full h-44 cursor-crosshair touch-action-none"></canvas>
                </div>
                <p class="text-xs text-slate-400">Gunakan layar sentuh touchscreen (tablet/HP/PC Kerja) atau mouse untuk membuat tanda tangan formal.</p>
                
                <!-- Input Hidden untuk menampung tanda tangan base64 -->
                <input type="hidden" name="tanda_tangan" id="tanda_tangan_input" required>
            </div>

            <!-- TOMBOL SUBMIT PRESENSI -->
            <div class="pt-4">
                <!-- <button type="submit" class="w-full py-4 bg-gradient-to-r from-blue-900 to-indigo-950 text-white rounded-2xl text-base font-extrabold transition-all-300 btn-premium flex items-center justify-center shadow-lg"> -->
                <button type="submit" id="btn-submit" class="w-full py-4 bg-gradient-to-r from-blue-900 to-indigo-950 text-white rounded-2xl text-base font-extrabold transition-all-300 btn-premium flex items-center justify-center shadow-lg">
                    <i class="fa-solid fa-clipboard-check text-xl mr-3"></i>CATAT KEHADIRAN RESMI
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Canvas Rendering Tersembunyi untuk Camera capture -->
<canvas id="camera-canvas" class="hidden"></canvas>
@endsection

@section('scripts')
<!-- Panggil Modul Engine Kamera & Tanda Tangan Digital -->
<script src="{{ asset('assets/js/signature.js') }}"></script>
<script src="{{ asset('assets/js/camera.js') }}"></script>

<script>
    // Fungsi untuk mengubah jenis kategori peserta
    function switchKategori(kat) {
        const kolomNip = document.getElementById('kolom-nip');
        const nipInput = document.getElementById('nip_input');
        
        const labelPeg = document.getElementById('label-kat-peg');
        const labelTamu = document.getElementById('label-kat-tamu');
        const iconPeg = document.getElementById('icon-kat-peg');
        const iconTamu = document.getElementById('icon-kat-tamu');

        if (kat === 'pegawai') {
            kolomNip.classList.remove('hidden');
            nipInput.setAttribute('required', 'required');
            
            // Perbarui warna style opsi aktif
            labelPeg.classList.add('border-blue-900', 'bg-blue-50/50');
            iconPeg.classList.add('text-blue-900');
            labelTamu.classList.remove('border-blue-900', 'bg-blue-50/50');
            iconTamu.classList.remove('text-blue-900');
        } else {
            kolomNip.classList.add('hidden');
            nipInput.removeAttribute('required');
            nipInput.value = '';
            
            // Perbarui warna style opsi aktif
            labelTamu.classList.add('border-blue-900', 'bg-blue-50/50');
            iconTamu.classList.add('text-blue-900');
            labelPeg.classList.remove('border-blue-900', 'bg-blue-50/50');
            iconPeg.classList.remove('text-blue-900');
            
            // Reset isian yang mungkin sudah diautofill sebelumnya
            document.getElementById('nama_lengkap').value = '';
            document.getElementById('instansi').value = '';
            document.getElementById('no_wa').value = '';
            document.getElementById('nip-alert').className = 'text-xs';
            document.getElementById('nip-alert').innerHTML = '';
        }
    }

    // Fungsi pencarian API data kepegawaian (Autofill)
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
                // Berhasil mendapatkan data, autofill kolom input
                document.getElementById('nama_lengkap').value = result.data.nama;
                document.getElementById('instansi').value = result.data.instansi;
                document.getElementById('no_wa').value = result.data.no_wa;

                alertSpan.className = 'text-xs text-emerald-600 font-semibold';
                alertSpan.innerHTML = '<i class="fa-solid fa-circle-check mr-1"></i>Integrasi Berhasil! Data terisi otomatis.';
            }
        } catch (error) {
            alertSpan.className = 'text-xs text-red-600 font-semibold';
            alertSpan.innerHTML = '<i class="fa-solid fa-triangle-exclamation mr-1"></i>Pegawai tidak terdaftar / API tidak merespons.';
            
            // Reset form jika pencarian gagal
            document.getElementById('nama_lengkap').value = '';
            document.getElementById('instansi').value = '';
            document.getElementById('no_wa').value = '';
        }
    }

    // Set setelan awal halaman
    document.addEventListener("DOMContentLoaded", () => {
        switchKategori('tamu'); // Default dibuka dengan tamu umum
    });

    // menghindari data duplikat karena klik-klik
    document.getElementById('form-presensi').addEventListener('submit', function() {
    let btn = document.getElementById('btn-submit');
    btn.disabled = true; // Matikan tombol
    btn.classList.add('opacity-70', 'cursor-not-allowed'); // Ubah tampilan
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xl mr-3"></i>SEDANG MEMPROSES...';
});
</script>
@endsection