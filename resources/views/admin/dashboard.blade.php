@extends('layouts.admin')

@section('title', 'Dashboard Rekapitulasi - Diskominfo Kota Malang')

@section('content')
<!-- BARIS UTAMA ATAS: FILTER & TOMBOL BUAT EVENT -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-blue-950">Rekapitulasi Presensi Kehadiran</h2>
        <p class="text-sm text-slate-500 mt-1">Kelola data presensi, cetak rekapan laporan, dan buat event dinas baru.</p>
    </div>
    
    <div class="flex flex-wrap gap-2">
        <!-- Tombol Ekspor ke Excel (Hanya muncul jika event terpilih tersedia) -->
        @if($selectedEvent)
            <a href="{{ route('admin.event.export', $selectedEvent->id) }}" class="px-5 py-3 bg-emerald-600 text-white rounded-xl text-sm font-bold hover:bg-emerald-700 transition-all-300 shadow-md flex items-center">
                <i class="fa-solid fa-file-excel mr-2"></i>Ekspor ke Excel
            </a>
        @endif

        <!-- Tombol Buka Modal Buat Event Baru -->
        <button onclick="toggleModal('modal-event')" class="px-5 py-3 bg-blue-900 text-white rounded-xl text-sm font-bold hover:bg-blue-950 transition-all-300 shadow-md flex items-center">
            <i class="fa-solid fa-circle-plus mr-2"></i>Buat Event Baru
        </button>
    </div>
</div>

<!-- BARIS FILTER UTAMA: DROPDOWN EVENT -->
<div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 mb-8">
    <form action="{{ route('admin.dashboard') }}" method="GET" class="w-full sm:w-1/2 flex items-center space-x-3">
        <label for="event_id" class="text-sm font-bold text-slate-700 whitespace-nowrap">Filter Event:</label>
        <select name="event_id" id="event_id" onchange="this.form.submit()" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm font-semibold text-slate-800">
            @foreach($events as $evt)
                <option value="{{ $evt->id }}" {{ $selectedEventId == $evt->id ? 'selected' : '' }}>
                    {{ $evt->nama_event }} ({{ \Carbon\Carbon::parse($evt->tanggal_event)->format('d/m/Y') }})
                </option>
            @endforeach
        </select>
    </form>
    
    <!-- Informasi Tanggal & Status Event Terpilih -->
    @if($selectedEvent)
        <div class="flex items-center space-x-3 text-sm">
            <span class="px-3 py-1 font-bold rounded-full {{ $selectedEvent->status === 'aktif' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                Status Event: {{ ucfirst($selectedEvent->status) }}
            </span>
            <form action="{{ route('admin.event.toggle', $selectedEvent->id) }}" method="POST">
                @csrf
                <button type="submit" class="text-xs font-bold text-blue-900 hover:underline">Ubah Status</button>
            </form>
        </div>
    @endif
</div>

<!-- BARIS 2: KARTU METRIK STATISTIK & GRAFIK TREN KEHADIRAN -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Kartu Metrik Samping Kiri -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-4">
        <!-- Metrik 1: Total Peserta Terdaftar -->
        <div class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Total Kehadiran</p>
                <h3 class="text-3xl font-extrabold text-blue-950 mt-1">{{ $totalHadir }} <span class="text-sm font-normal text-slate-400">peserta</span></h3>
            </div>
            <div class="h-12 w-12 rounded-xl bg-blue-100 flex items-center justify-center text-blue-900 text-xl font-bold">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <!-- Metrik 2: Kategori Pegawai (Fitur Custom) -->
        <div class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Kategori Pegawai (Custom)</p>
                <h3 class="text-3xl font-extrabold text-teal-700 mt-1">{{ $totalPegawai }} <span class="text-sm font-normal text-slate-400">orang</span></h3>
            </div>
            <div class="h-12 w-12 rounded-xl bg-teal-100 flex items-center justify-center text-teal-800 text-xl font-bold">
                <i class="fa-solid fa-user-tie"></i>
            </div>
        </div>

        <!-- Metrik 3: Kategori Tamu Umum (Normal) -->
        <div class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Kategori Tamu (Normal)</p>
                <h3 class="text-3xl font-extrabold text-amber-600 mt-1">{{ $totalTamu }} <span class="text-sm font-normal text-slate-400">orang</span></h3>
            </div>
            <div class="h-12 w-12 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 text-xl font-bold">
                <i class="fa-solid fa-user"></i>
            </div>
        </div>

        <!-- Metrik 4: Rasio Target Kehadiran -->
        <div class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Rasio Target Kehadiran</p>
                    <h3 class="text-2xl font-extrabold text-slate-800 mt-1">{{ $rasioKehadiran }}%</h3>
                </div>
                <div class="text-xs font-bold text-slate-400 bg-slate-100 px-2 py-1 rounded">Target: {{ $selectedEvent->target_peserta ?? 100 }}</div>
            </div>
            <!-- Progress Bar -->
            <div class="w-full bg-slate-100 h-2.5 rounded-full mt-3 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-900 to-indigo-700 h-full rounded-full" style="width: {{ min($rasioKehadiran, 100) }}%"></div>
            </div>
        </div>
    </div>

    <!-- Grafik Tren Kedatangan Per Jam -->
    <div class="lg:col-span-2 bg-white p-6 rounded-xl border border-slate-100 shadow-sm">
        <h4 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4">Grafik Analitik Tren Kehadiran Jam Sibuk</h4>
        <div class="h-64">
            <canvas id="hourlyChart"></canvas>
        </div>
    </div>
</div>

<!-- BARIS 3: FITUR PENCARIAN & TABEL REKAPAN ABSENSI -->
<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
    <!-- Header Tabel + Filter Pencarian -->
    <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h3 class="text-base font-extrabold text-slate-800 uppercase tracking-wider">Daftar Rekapan Hadir</h3>
        
        <!-- Kolom Pencarian Dinamis -->
        <form action="{{ route('admin.dashboard') }}" method="GET" class="relative w-full sm:w-64">
            <input type="hidden" name="event_id" value="{{ $selectedEventId }}">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama/NIP/Instansi..." class="w-full pl-9 pr-4 py-2 border border-slate-200 rounded-lg text-xs font-medium focus:outline-none focus:ring-1 focus:ring-blue-800">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
            @if(request('search'))
                <a href="{{ route('admin.dashboard', ['event_id' => $selectedEventId]) }}" class="absolute right-3 top-2 text-slate-400 hover:text-slate-600 text-xs font-bold">✕</a>
            @endif
        </form>
    </div>

    <!-- Container Tabel -->
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs font-medium text-slate-600 border-collapse">
            <thead class="bg-slate-50 text-slate-400 uppercase tracking-wider font-bold border-b border-slate-100">
                <tr>
                    <th class="p-4 w-12">No</th>
                    <th class="p-4">Waktu</th>
                    <th class="p-4">Nama Peserta</th>
                    <th class="p-4">NIP (Custom)</th>
                    <th class="p-4">Instansi</th>
                    <th class="p-4">No WA</th>
                    <th class="p-4 text-center">Tanda Tangan</th>
                    <th class="p-4 text-center">Foto Capture</th>
                    <th class="p-4 text-center">Aksi (CRUD)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($presences as $idx => $pres)
                    <tr class="hover:bg-slate-50 transition-all-300">
                        <td class="p-4 text-slate-800 font-bold">{{ $presences->firstItem() + $idx }}</td>
                        <td class="p-4 text-slate-400">
                            {{ \Carbon\Carbon::parse($pres->waktu_absensi)->format('H:i') }}
                            <span class="block text-[10px]">{{ \Carbon\Carbon::parse($pres->waktu_absensi)->format('d/m/Y') }}</span>
                        </td>
                        <td class="p-4">
                            <span class="text-slate-800 font-bold block">{{ $pres->nama_lengkap }}</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $pres->kategori_peserta === 'pegawai' ? 'bg-teal-50 text-teal-700' : 'bg-amber-50 text-amber-700' }}">
                                {{ ucfirst($pres->kategori_peserta) }}
                            </span>
                        </td>
                        <td class="p-4 font-mono text-slate-500">{{ $pres->nip ?? '-' }}</td>
                        <td class="p-4 text-slate-700 max-w-xs truncate">{{ $pres->instansi }}</td>
                        <td class="p-4">{{ $pres->no_wa }}</td>
                        <!-- Coretan TTD Thumbnail -->
                        <td class="p-4 text-center">
                            <div class="inline-block border border-slate-200 bg-white p-1 rounded overflow-hidden shadow-sm hover:scale-105 transition-all-300">
                                <img src="{{ $pres->tanda_tangan }}" class="h-8 w-16 object-contain">
                            </div>
                        </td>
                        <!-- Capture Foto Thumbnail -->
                        <td class="p-4 text-center">
                            <div class="inline-block border border-slate-200 rounded-lg overflow-hidden shadow-sm hover:scale-105 transition-all-300">
                                <img src="{{ $pres->foto_capture }}" class="h-10 w-10 object-cover">
                            </div>
                        </td>
                        <!-- Aksi Eye & Delete -->
                        <td class="p-4 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center space-x-1.5">
                                <!-- Tombol Eye Detail (Fitur Ikon Mata Detail Form) -->
                                <button onclick="bukaModalDetail({{ json_encode($pres) }})" class="p-1.5 bg-blue-50 text-blue-900 rounded hover:bg-blue-100 transition-all-300" title="Buka Detail">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </button>
                                
                                <!-- Tombol Hapus CRUD -->
                                <form action="{{ route('admin.presensi.destroy', $pres->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data presensi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 bg-rose-50 text-rose-600 rounded hover:bg-rose-100 transition-all-300" title="Hapus Data">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="p-8 text-center text-slate-400">
                            <i class="fa-solid fa-folder-open text-3xl mb-2 block"></i>
                            Belum ada kehadiran peserta pada event ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Sederhana -->
    <div class="p-4 bg-slate-50 border-t border-slate-100">
        {{ $presences->links() }}
    </div>
</div>

<!-- ==================== MODAL: BUAT EVENT BARU (CREATE EVENT) ==================== -->
<div id="modal-event" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden transform transition-all">
        <div class="p-6 bg-blue-900 text-white flex items-center justify-between">
            <h3 class="font-extrabold text-base"><i class="fa-solid fa-calendar-plus mr-2"></i>Buat Event Kehadiran Baru</h3>
            <button onclick="toggleModal('modal-event')" class="text-white hover:text-slate-200">✕</button>
        </div>
        <form action="{{ route('admin.event.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div class="space-y-1">
                <label for="nama_event" class="text-xs font-bold text-slate-700">Nama Event Dinas <span class="text-red-500">*</span></label>
                <input type="text" name="nama_event" id="nama_event" required class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-blue-950" placeholder="Contoh: PKL UIN Malang">
            </div>
            
            <div class="space-y-1">
                <label for="tanggal_event" class="text-xs font-bold text-slate-700">Tanggal Pelaksanaan <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_event" id="tanggal_event" required class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-blue-950">
            </div>

            <div class="space-y-1">
                <label for="target_peserta" class="text-xs font-bold text-slate-700">Target Jumlah Peserta <span class="text-red-500">*</span></label>
                <input type="number" name="target_peserta" id="target_peserta" required min="1" value="100" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-blue-950">
            </div>

            <div class="pt-2 flex justify-end space-x-2">
                <button type="button" onclick="toggleModal('modal-event')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-xs font-bold transition-all-300">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-900 hover:bg-blue-950 text-white rounded-lg text-xs font-bold transition-all-300 shadow">Simpan Event</button>
            </div>
        </form>
    </div>
</div>

<!-- ==================== MODAL: DETAIL PRESENSI ==================== -->
<div id="modal-detail" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden transform transition-all">
        <div class="p-6 bg-slate-900 text-white flex items-center justify-between">
            <h3 class="font-extrabold text-base"><i class="fa-solid fa-address-card mr-2"></i>Kartu Detail Presensi</h3>
            <button onclick="toggleModal('modal-detail')" class="text-white hover:text-slate-200">✕</button>
        </div>
        <div class="p-6 space-y-6">
            <div class="flex items-center space-x-4">
                <img id="detail-foto" class="h-20 w-20 rounded-xl object-cover border border-slate-200 shadow shadow-inner">
                <div>
                    <h4 id="detail-nama" class="font-extrabold text-base text-slate-800"></h4>
                    <span id="detail-kategori" class="inline-block px-2 py-0.5 rounded text-[10px] font-bold mt-1"></span>
                    <p id="detail-waktu" class="text-xs text-slate-400 mt-1"></p>
                </div>
            </div>

            <hr class="border-slate-100">

            <div class="grid grid-cols-2 gap-4 text-xs">
                <div>
                    <p class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">NIP (Pegawai)</p>
                    <p id="detail-nip" class="font-bold text-slate-700 mt-0.5"></p>
                </div>
                <div>
                    <p class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">No WhatsApp</p>
                    <p id="detail-wa" class="font-bold text-slate-700 mt-0.5"></p>
                </div>
                <div class="col-span-2">
                    <p class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Instansi / Unit Kerja</p>
                    <p id="detail-instansi" class="font-bold text-slate-700 mt-0.5"></p>
                </div>
            </div>

            <hr class="border-slate-100">

            <div>
                <p class="text-slate-400 font-bold uppercase tracking-wider text-[10px] mb-2">Tanda Tangan Terdaftar</p>
                <div class="border border-slate-200 p-2 bg-slate-50 rounded-lg flex items-center justify-center">
                    <img id="detail-ttd" class="h-24 object-contain">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Pustaka Chart.js untuk render grafik -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Fungsi buka/tutup modal
    function toggleModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
        } else {
            modal.classList.add('hidden');
        }
    }

    // Fungsi detail mata
    function bukaModalDetail(data) {
        document.getElementById('detail-nama').innerText = data.nama_lengkap;
        document.getElementById('detail-nip').innerText = data.nip ? data.nip : '-';
        document.getElementById('detail-instansi').innerText = data.instansi;
        document.getElementById('detail-wa').innerText = data.no_wa;
        document.getElementById('detail-foto').src = data.foto_capture;
        document.getElementById('detail-ttd').src = data.tanda_tangan;
        document.getElementById('detail-waktu').innerText = 'Hadir pada: ' + data.waktu_absensi;

        const katSpan = document.getElementById('detail-kategori');
        katSpan.innerText = data.kategori_peserta.toUpperCase();
        if (data.kategori_peserta === 'pegawai') {
            katSpan.className = 'inline-block px-2 py-0.5 rounded text-[10px] font-bold mt-1 bg-teal-100 text-teal-800';
        } else {
            katSpan.className = 'inline-block px-2 py-0.5 rounded text-[10px] font-bold mt-1 bg-amber-100 text-amber-800';
        }

        toggleModal('modal-detail');
    }

    // Inisialisasi Chart.js untuk diagram jam sibuk
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('hourlyChart').getContext('2d');
        const trendData = @json($hourlyTrend);
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: Object.keys(trendData),
                datasets: [{
                    label: 'Jumlah Peserta Absen',
                    data: Object.values(trendData),
                    backgroundColor: 'rgba(30, 58, 138, 0.85)',
                    borderColor: '#1e3a8a',
                    borderWidth: 1.5,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: '#94a3b8' },
                        grid: { color: 'rgba(148, 163, 184, 0.1)' }
                    },
                    x: {
                        ticks: { color: '#94a3b8' },
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    });
</script>
@endsection
