@extends('layouts.admin')

@section('title', 'Dashboard Pegawai - E-Presensi')

@section('content')
<div class="space-y-6">
    <!-- Card Selamat Datang -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Selamat Datang, {{ Auth::user()->name }}!</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola dan terbitkan lembar presensi kegiatan Diskominfo Anda di sini.</p>
        </div>
        <button onclick="openModal()" class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-sm shadow-blue-500/20 transition cursor-pointer">
            <i class="fa-solid fa-plus mr-2"></i> Buat Event Baru
        </button>
    </div>

    <!-- Tabel Lembar Kegiatan -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-900">Daftar Acara / Event Buatan Anda</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-100">
                        <th class="px-6 py-3.5">Nama Acara</th>
                        <th class="px-6 py-3.5">Tanggal & Waktu</th>
                        <th class="px-6 py-3.5 text-center">Sasaran</th>
                        <th class="px-6 py-3.5 text-center">Sifat Akses</th>
                        <th class="px-6 py-3.5 text-center">Link Absen Tamu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($events as $event)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4 font-bold text-slate-900">{{ $event->name }}</td>
                        <td class="px-6 py-4 text-slate-600">
                            <span class="block font-medium">{{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}</span>
                            <span class="text-xs text-slate-400 mt-0.5 block">{{ $event->time_start }} s.d {{ $event->time_end }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-blue-50 text-blue-700">
                                {{ strtoupper($event->target_audience) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold {{ $event->access_type === 'privat' ? 'bg-rose-50 text-rose-700' : 'bg-emerald-50 text-emerald-700' }}">
                                {{ strtoupper($event->access_type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('presensi.access', $event->id) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-lg transition">
                                <i class="fa-solid fa-up-right-from-square mr-1.5"></i> Buka Link
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-400 font-medium text-xs">
                            <i class="fa-regular fa-folder-open text-3xl block mb-2 text-slate-300"></i>
                            Belum ada event yang dibuat. Silakan klik tombol "Buat Event Baru" di atas!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Dialog Tailwind CSS (Struktur Murni Tanpa Tabrakan Overflow) -->
<div id="eventModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-xl border border-slate-100 transform transition-all overflow-hidden animate-fade-in my-auto">
        
        <!-- Header Modal -->
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="text-lg font-bold text-slate-900">Form Tambah Event</h3>
            <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-slate-600 text-lg font-bold cursor-pointer">✕</button>
        </div>
        
        <!-- Form Utama -->
        <form action="{{ route('event.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Kegiatan</label>
                    <input name="name" type="text" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition" placeholder="Contoh: Rapat Koordinasi">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tanggal</label>
                        <input name="date" type="date" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Sasaran</label>
                        <select name="target_audience" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                            <option value="umum">Umum (Tamu Eksternal)</option>
                            <option value="pegawai">Internal Pegawai (ASN)</option>
                            <option value="semua">Semua Peserta</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Jam Mulai</label>
                        <input name="time_start" type="time" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Jam Selesai</label>
                        <input name="time_end" type="time" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Jenis Akses</label>
                        <select name="access_type" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition" id="accessTypeSelect" onchange="togglePasswordInput()">
                            <option value="publik">Publik (Buka Bebas)</option>
                            <option value="privat">Privat (Pakai Sandi)</option>
                        </select>
                    </div>
                    <div id="passwordInputGroup" class="hidden">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Sandi Pengaman</label>
                        <input name="password" id="eventPassword" type="text" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition" placeholder="Min. 4 Karakter">
                    </div>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Opsi Formulir Tambahan</label>
                    <div class="space-y-3 bg-slate-50 p-3.5 rounded-xl border border-slate-100 text-xs">
                        <label class="flex items-center text-sm font-medium text-slate-700 cursor-pointer">
                            <input name="fields[0][name]" type="checkbox" value="foto" class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500 mr-2.5"> 
                            <input name="fields[0][type]" type="hidden" value="system_foto">
                            Foto Wajah Presensi
                        </label>
                        <label class="flex items-center text-sm font-medium text-slate-700 cursor-pointer">
                            <input name="fields[1][name]" type="checkbox" value="ttd" class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500 mr-2.5"> 
                            <input name="fields[1][type]" type="hidden" value="system_ttd">
                            Tanda Tangan Digital
                        </label>
                        
                        <div class="pt-3 border-t border-slate-200 mt-2 space-y-2">
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Pertanyaan Kustom Tambahan (Dengan Tipe Data)</label>
                            <div id="customFieldsWrapper" class="space-y-2">
                                <!-- Wadah suntikan input baru via Javascript -->
                            </div>
                            <button type="button" onclick="addCustomFieldInput()" class="inline-flex items-center text-xs font-bold text-blue-600 hover:text-blue-700 cursor-pointer transition mt-1">
                                <i class="fa-solid fa-circle-plus mr-1"></i> Tambah Kolom Isian Baru
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer Modal Tetap Menyatu Alami -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-semibold text-slate-500 hover:text-slate-700 transition cursor-pointer">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm transition cursor-pointer">Simpan Event</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('eventModal').classList.remove('hidden');
    }
    
    function closeModal() {
        document.getElementById('eventModal').classList.add('hidden');
    }
    
    function togglePasswordInput() {
        const type = document.getElementById('accessTypeSelect').value;
        const group = document.getElementById('passwordInputGroup');
        const passInput = document.getElementById('eventPassword');
        
        if (type === 'privat') {
            group.classList.remove('hidden');
            passInput.setAttribute('required', 'required');
        } else {
            group.classList.add('hidden');
            passInput.removeAttribute('required');
        }
    }

    let fieldIndex = 2; 

    function addCustomFieldInput() {
        const wrapper = document.getElementById('customFieldsWrapper');
        
        const div = document.createElement('div');
        div.className = 'grid grid-cols-12 gap-2 items-center animate-fade-in bg-white p-2 rounded-lg border border-slate-200/60 shadow-sm';
        div.innerHTML = `
            <div class="col-span-6">
                <input name="fields[${fieldIndex}][name]" type="text" required class="w-full px-2.5 py-1.5 border border-slate-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition" placeholder="Nama Kolom (Misal: Alamat Rumah)">
            </div>
            <div class="col-span-5">
                <select name="fields[${fieldIndex}][type]" class="w-full px-2 py-1.5 border border-slate-200 rounded-lg text-xs bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                    <option value="text">Teks Biasa</option>
                    <option value="number">Angka / WA</option>
                    <option value="date">Tanggal Kustom</option>
                </select>
            </div>
            <div class="col-span-1 text-center">
                <button type="button" onclick="removeCustomFieldInput(this)" class="text-rose-500 hover:text-rose-600 text-sm font-bold cursor-pointer transition">✕</button>
            </div>
        `;
        
        wrapper.appendChild(div);
        fieldIndex++; 
    }

    function removeCustomFieldInput(button) {
        button.closest('.grid').remove();
    }
</script>
@endsection