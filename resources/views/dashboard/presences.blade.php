@extends('layouts.app')

@section('title', 'Rekap Presensi - E-Presensi')
@section('breadcrumb', 'Rekap Presensi')
@section('page-title', 'Daftar Kehadiran Event')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card ep-card mb-4">
      <div class="card-header pb-0 bg-transparent">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
          <div>
            <h5 class="mb-0">Presensi Kehadiran: {{ $event->name }}</h5>
            <p class="text-xs text-muted mb-0">
              {{ \Carbon\Carbon::parse($event->date)->translatedFormat('d F Y') }} · 
              {{ \Carbon\Carbon::parse($event->time_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($event->time_end)->format('H:i') }} WIB
            </p>
            <div class="mt-2">
              <span class="badge bg-gradient-info text-white shadow-sm font-weight-bold">
                <i class="ni ni-single-02 me-1"></i> Total Kehadiran: <b>{{ $presences->count() }} Orang</b>
              </span>
            </div>
          </div>
          <div class="d-flex gap-2">
            <a href="{{ route('event.presences.excel', $event->id) }}" class="btn bg-gradient-success mb-0 shadow">
              <i class="fas fa-file-excel me-1"></i> Ekspor ke Excel
            </a>
            <a href="{{ Auth::user()->role === 'admin' ? route('dashboard.admin') : route('dashboard.user') }}" class="btn btn-outline-secondary mb-0">
              Kembali
            </a>
          </div>
        </div>
      </div>
      <div class="card-body px-0 pt-0 pb-2 mt-3">
        <div class="table-responsive p-4">
          <table class="table align-items-center mb-0" id="presences-table">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder ps-3">No</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder">NIK</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder">NIP</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Nama Lengkap</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder">No WhatsApp</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Instansi</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Kategori</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center">Foto</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center">TTD</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Waktu Hadir</th>
              </tr>
            </thead>
            <tbody>
              @foreach($presences as $index => $presence)
                <tr>
                  <td class="text-sm font-weight-bold ps-3">{{ $index + 1 }}</td>
                  <td class="text-sm">{{ $presence->nik }}</td>
                  <td class="text-sm">{{ $presence->nip ?? '-' }}</td>
                  <td class="text-sm font-weight-bold">{{ $presence->name }}</td>
                  <td class="text-sm">{{ $presence->phone ?? '-' }}</td>
                  <td class="text-sm">{{ $presence->institution }}</td>
                  <td>
                    <span class="badge badge-sm {{ $presence->nip ? 'bg-gradient-info' : 'bg-gradient-secondary' }}">
                      {{ $presence->nip ? 'Pegawai' : 'Warga Umum' }}
                    </span>
                  </td>
                  <td class="text-center">
                    @if($presence->photo)
                      <button type="button" class="btn btn-link p-0 mb-0" data-bs-toggle="modal" data-bs-target="#photoModal-{{ $presence->id }}">
                        <img src="{{ $presence->photo }}" class="avatar avatar-sm rounded-circle shadow-sm border border-2 border-success" alt="foto">
                      </button>
                    @else
                      <span class="text-xs text-muted">-</span>
                    @endif
                  </td>
                  <td class="text-center">
                    @if($presence->signature)
                      <button type="button" class="btn btn-xs btn-outline-info mb-0 py-1" data-bs-toggle="modal" data-bs-target="#sigModal-{{ $presence->id }}">
                        Lihat TTD
                      </button>
                    @else
                      <span class="text-xs text-muted">-</span>
                    @endif
                  </td>
                  <td class="text-sm">{{ $presence->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</td>
                </tr>

                <!-- Modal Foto -->
                @if($presence->photo)
                  <div class="modal fade" id="photoModal-{{ $presence->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content border-radius-xl">
                        <div class="modal-header bg-gradient-dark">
                          <h6 class="modal-title text-white">Foto Bukti Hadir: {{ $presence->name }}</h6>
                          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0 text-center bg-dark">
                          <img src="{{ $presence->photo }}" class="w-100" style="max-height: 480px; object-fit: contain;" alt="Foto Wajah">
                        </div>
                      </div>
                    </div>
                  </div>
                @endif

                <!-- Modal TTD -->
                @if($presence->signature)
                  <div class="modal fade" id="sigModal-{{ $presence->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content border-radius-xl">
                        <div class="modal-header bg-gradient-dark">
                          <h6 class="modal-title text-white">Tanda Tangan Digital: {{ $presence->name }}</h6>
                          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4 text-center">
                          <div class="border border-radius-lg p-3 bg-white shadow-sm d-inline-block">
                            <img src="{{ $presence->signature }}" style="max-height: 160px; max-width: 100%; object-fit: contain;" alt="Tanda Tangan">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                @endif
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/argon-dashboard-pro-html-v2.0.5/assets/js/plugins/datatables.js') }}"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const table = document.getElementById('presences-table');
    if (table) {
      const dataTable = new simpleDatatables.DataTable("#presences-table", {
        searchable: true,
        fixedHeight: false,
        perPage: 10,
        perPageSelect: false, // Hapus/sembunyikan dropdown entri per halaman
        labels: {
          placeholder: "Cari...",
          noRows: "Tidak ada data ditemukan",
          info: "Menampilkan {start} sampai {end} dari {rows} entri",
        }
      });
    }
  });
</script>
@endsection
