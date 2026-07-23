@extends('layouts.app')

@section('title', 'Dashboard User - E-Presensi')
@section('breadcrumb', 'Dashboard User')
@section('page-title', 'Dashboard Pembuat Event')

@push('styles')
<style>
  @media (max-width: 575.98px) {
    .ep-dashboard-stat-card .card-body {
      padding: 0.75rem !important;
    }
    .ep-dashboard-stat-card .icon-shape {
      width: 32px !important;
      height: 32px !important;
      min-width: 32px !important;
    }
    .ep-dashboard-stat-card .icon-shape i {
      font-size: 0.85rem !important;
      top: 0 !important;
    }
    .ep-dashboard-stat-card p {
      font-size: 0.65rem !important;
    }
    .ep-dashboard-stat-card h5 {
      font-size: 0.95rem !important;
    }
  }
</style>
@endpush

@section('content')
<div class="row">
  <div class="col-12 mb-3">
    <div class="card ep-card ep-bg-mesh">
      <div class="card-body ep-page-hero p-3 p-lg-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
          <span class="badge bg-white text-primary shadow-sm mb-1">Creator Event</span>
          <h4 class="text-white font-weight-bolder mb-0">Dashboard Pembuat Event</h4>
        </div>
        <div class="badge bg-white text-dark shadow-sm px-3 py-2 font-weight-bold d-flex align-items-center gap-2">
          <i class="ni ni-calendar-grid-58 text-primary"></i>
          <span>{{ $events->count() }} Agenda Event</span>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-6 col-sm-4 col-md-4 col-xl-4 mb-4">
    <div class="card ep-card ep-dashboard-stat-card">
      <div class="card-body p-3">
        <div class="d-flex">
          <div>
            <p class="text-sm mb-0 text-uppercase font-weight-bold">Link Event</p>
            <h5 class="font-weight-bolder mb-0">Direct URL</h5>
          </div>
          <div class="icon icon-shape bg-gradient-info shadow-info text-center rounded-circle ms-auto">
            <i class="ni ni-world text-lg opacity-10"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-6 col-sm-4 col-md-4 col-xl-4 mb-4">
    <div class="card ep-card ep-dashboard-stat-card">
      <div class="card-body p-3">
        <div class="d-flex">
          <div>
            <p class="text-sm mb-0 text-uppercase font-weight-bold">Field Dinamis</p>
            <h5 class="font-weight-bolder mb-0">Pengaturan Form</h5>
          </div>
          <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle ms-auto">
            <i class="ni ni-settings text-lg opacity-10"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-4 col-md-4 col-xl-4 mb-4">
    <div class="card ep-card ep-dashboard-stat-card">
      <div class="card-body p-3">
        <div class="d-flex">
          <div>
            <p class="text-sm mb-0 text-uppercase font-weight-bold">Bukti Presensi</p>
            <h5 class="font-weight-bolder mb-0">Foto & TTD</h5>
          </div>
          <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle ms-auto">
            <i class="ni ni-camera-compact text-lg opacity-10"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card ep-card">
      <div class="card-header pb-0 bg-transparent">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <h6 class="mb-0">Event Saya</h6>
            <p class="text-xs text-muted mb-0">Kelola agenda kegiatan yang Anda daftarkan.</p>
          </div>
          <div class="d-flex align-items-center gap-2">
            <span class="badge bg-gradient-primary me-2">{{ $events->count() }} total</span>
            <button type="button" class="btn btn-sm bg-gradient-primary mb-0 shadow-sm" data-bs-toggle="modal" data-bs-target="#createEventModal">
              <i class="fas fa-plus me-1"></i> Buat Event
            </button>
          </div>
        </div>
      </div>
      <div class="card-body px-0 pt-0 pb-2 mt-3">
        <div class="table-responsive p-3">
          <table class="table align-items-center mb-0" id="events-table" style="width: 100%;">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder ps-3">Event</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Jadwal</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($events as $event)
                <tr>
                  <td class="ps-3" style="white-space: normal;">
                    <div class="d-flex align-items-center">
                      <div class="icon icon-shape icon-sm bg-gradient-light shadow text-center me-3 flex-shrink-0">
                        <i class="ni ni-badge text-primary"></i>
                      </div>
                      <div>
                        <h6 class="mb-0 text-sm font-weight-bold" style="white-space: normal; word-break: break-word; max-width: 380px;">{{ $event->name }}</h6>
                        <span class="badge badge-sm {{ $event->access_type === 'privat' ? 'bg-gradient-warning' : 'bg-gradient-success' }}">{{ ucfirst($event->access_type) }}</span>
                        <span class="badge badge-sm bg-gradient-secondary">{{ ucfirst($event->audience_type) }}</span>
                        <span class="badge badge-sm {{ $event->status_absensi === 'Berlaku' ? 'bg-gradient-success' : 'bg-gradient-danger' }}">{{ $event->status_absensi }}</span>
                      </div>
                    </div>
                  </td>
                  <td class="text-sm" style="white-space: nowrap;">
                    <span class="font-weight-bold text-dark">{{ $event->formatted_date_range }}</span><br>
                    <span class="text-xs text-muted">{{ \Carbon\Carbon::parse($event->time_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($event->time_end)->format('H:i') }} WIB</span>
                  </td>
                  <td class="text-center" style="white-space: normal; min-width: 220px;">
                    <div class="d-flex flex-wrap justify-content-center gap-1">
                      <button type="button" class="btn btn-xs bg-gradient-dark mb-0 shadow-xs" data-bs-toggle="modal" data-bs-target="#qrModal-{{ $event->id }}">
                        <i class="fas fa-qrcode me-1"></i> QR
                      </button>
                      <a href="{{ route('event.presences', $event->uuid) }}" class="btn btn-xs bg-gradient-success mb-0 shadow-xs">Rekap</a>
                      <button type="button" class="btn btn-xs bg-gradient-info mb-0 shadow-xs" data-bs-toggle="modal" data-bs-target="#editEventModal-{{ $event->id }}">Edit</button>
                      <a href="{{ route('presence.form', $event->uuid) }}" class="btn btn-xs bg-gradient-primary mb-0 shadow-xs">Buka</a>
                      <form action="{{ route('event.destroy', $event) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus event ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-xs btn-outline-danger mb-0 shadow-xs">Hapus</button>
                      </form>
                    </div>

                    <!-- Modal Preview QR Code -->
                    <div class="modal fade" id="qrModal-{{ $event->id }}" tabindex="-1" role="dialog" aria-labelledby="qrModalLabel-{{ $event->id }}" aria-hidden="true" data-url="{{ route('presence.form', $event->uuid) }}">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content border-0 ep-card">
                          <div class="modal-header bg-gradient-primary text-start">
                            <h6 class="modal-title text-white font-weight-bolder mb-0" id="qrModalLabel-{{ $event->id }}"><i class="fas fa-qrcode me-2"></i> QR Code Event Presensi</h6>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body text-center p-4">
                            <div class="p-3 bg-white rounded-3 border shadow-sm d-inline-block w-100" id="qrCardArea-{{ $event->id }}">
                              <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                                <img src="{{ asset('assets/argon-dashboard-pro-html-v2.0.5/assets/img/epresensi-logo.png') }}" style="height: 32px;" alt="Logo">
                                <h6 class="font-weight-bolder mb-0 text-dark">E-Presensi</h6>
                              </div>
                              <h5 class="font-weight-bolder text-primary mb-1">{{ $event->name }}</h5>
                              <p class="text-xs text-muted mb-3 font-weight-bold">
                                <i class="ni ni-calendar-grid-58 text-info me-1"></i> {{ $event->formatted_date_range }} ({{ \Carbon\Carbon::parse($event->time_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($event->time_end)->format('H:i') }} WIB)
                              </p>
                              
                              <div class="d-flex justify-content-center my-3">
                                <div class="position-relative d-inline-block p-2 bg-white rounded border shadow-xs">
                                  <div id="qrcode-box-{{ $event->id }}"></div>
                                  <div class="position-absolute start-50 top-50 translate-middle bg-white p-1 rounded-3 shadow-xs d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; pointer-events: none;">
                                    <img src="{{ asset('assets/argon-dashboard-pro-html-v2.0.5/assets/img/logos/GKV307_Kota Malang-logobase.net.png') }}" style="width: 32px; height: 32px; object-fit: contain;" alt="Logo Pemkot">
                                  </div>
                                </div>
                              </div>
                              
                              <p class="text-xs text-muted mb-0 text-break qr-link-copy" style="font-size: 11px; cursor: pointer;" title="Klik untuk menyalin" data-url="{{ route('presence.form', $event->uuid) }}">
                                <i class="fas fa-copy me-1"></i> {{ route('presence.form', $event->uuid) }}
                              </p>
                            </div>
                          </div>
                          <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-outline-secondary mb-0 shadow-sm" data-bs-dismiss="modal">Tutup</button>
                            <button type="button" class="btn bg-gradient-primary mb-0 shadow-sm download-qr-btn" data-event-id="{{ $event->id }}" data-event-name="{{ Str::slug($event->name) }}">
                              <i class="fas fa-download me-1"></i> Unduh QR Code
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
                @include('partials.edit_event_modal', ['event' => $event])
              @empty
                <tr>
                  <td colspan="3" class="text-center py-5">
                    <div class="icon icon-shape bg-gradient-light shadow text-center mx-auto mb-3">
                      <i class="ni ni-calendar-grid-58 text-primary"></i>
                    </div>
                    <p class="text-sm text-muted mb-0">Belum ada event.</p>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Buat Event Baru -->
<div class="modal fade" id="createEventModal" tabindex="-1" role="dialog" aria-labelledby="createEventModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content border-0 ep-card">
      <div class="modal-header bg-gradient-primary text-white">
        <h5 class="modal-title font-weight-bolder text-white" id="createEventModalLabel">
          <i class="ni ni-fat-add me-1"></i> Buat Event Presensi Baru
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-start p-4">
        <form action="{{ route('event.store') }}" method="POST">
          @csrf
          <span class="ep-section-label">Informasi Event</span>
          <div class="mb-3 mt-2">
            <label class="form-control-label text-xs font-weight-bold">Nama kegiatan</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Contoh: Rapat Koordinasi Smart City" required>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-control-label text-xs font-weight-bold">Mulai Tanggal</label>
              <input type="date" name="date" value="{{ old('date') }}" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-control-label text-xs font-weight-bold">Selesai Tanggal (Opsional)</label>
              <input type="date" name="date_end" value="{{ old('date_end') }}" class="form-control" placeholder="Sama dengan tanggal mulai">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-control-label text-xs font-weight-bold">Jam Mulai</label>
              <input type="time" name="time_start" value="{{ old('time_start') }}" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-control-label text-xs font-weight-bold">Jam Selesai</label>
              <input type="time" name="time_end" value="{{ old('time_end') }}" class="form-control" required>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-control-label text-xs font-weight-bold">Kategori peserta</label>
              <select name="audience_type" class="form-control" required>
                <option value="semua" @selected(old('audience_type') === 'semua')>Semua</option>
                <option value="pegawai" @selected(old('audience_type') === 'pegawai')>Pegawai</option>
                <option value="umum" @selected(old('audience_type') === 'umum')>Umum</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-control-label text-xs font-weight-bold">Akses event</label>
              <select name="access_type" id="access_type" class="form-control" required>
                <option value="publik" @selected(old('access_type') === 'publik')>Publik</option>
                <option value="privat" @selected(old('access_type') === 'privat')>Privat</option>
              </select>
            </div>
          </div>

          <div class="mb-3" id="password-wrapper">
            <label class="form-control-label text-xs font-weight-bold">Password event privat</label>
            <div class="input-group">
              <input type="password" name="password" id="user-event-password" class="form-control" placeholder="Minimal 4 karakter" style="border-right: 0;">
              <span class="input-group-text bg-white cursor-pointer" id="toggle-user-event-password" style="cursor: pointer; border-left: 0;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16" style="width: 16px; height: 16px;"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 4 8 4c2.12 0 3.879.668 5.168 1.957A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12 8 12c-2.12 0-3.879-.668-5.168-1.957A13.133 13.133 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/></svg>
              </span>
            </div>
          </div>

          <hr class="horizontal dark my-4">
          <span class="ep-section-label">Field Standar</span>
          <div class="row mt-2 mb-3">
            @foreach([
              'sc-phone' => 'No HP',
              'sc-gender' => 'Jenis kelamin',
              'sc-institution' => 'Instansi',
              'sc-email' => 'Email',
              'sc-nip' => 'NIP',
              'sc-photo' => 'Foto wajah',
              'sc-signature' => 'TTD digital',
            ] as $value => $label)
              <div class="col-md-4 col-6 mb-1">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" name="{{ $value }}" value="1" id="{{ $value }}" @checked(old() ? old($value) : true)>
                  <label class="form-check-label text-sm" for="{{ $value }}">{{ $label }}</label>
                </div>
              </div>
            @endforeach
          </div>

          <span class="ep-section-label">Custom Field</span>
          <div id="custom-fields" class="mt-2 mb-3"></div>
          <button type="button" class="btn btn-sm btn-outline-primary mb-3" id="add-custom-field">
            <i class="ni ni-fat-add me-1"></i> Tambah Field
          </button>

          <div class="d-flex justify-content-end gap-2 mt-3">
            <button type="button" class="btn btn-outline-secondary mb-0 shadow-sm" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn bg-gradient-primary mb-0 shadow">Simpan Event</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/argon-dashboard-pro-html-v2.0.5/assets/js/plugins/datatables.js') }}"></script>
<script>
  const accessType = document.getElementById('access_type');
  const passwordWrapper = document.getElementById('password-wrapper');

  function togglePassword() {
    passwordWrapper.style.display = accessType.value === 'privat' ? 'block' : 'none';
  }

  accessType.addEventListener('change', togglePassword);
  togglePassword();

  const toggleUserBtn = document.getElementById('toggle-user-event-password');
  const userEventPasswordInput = document.getElementById('user-event-password');

  const eyeSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16" style="width: 16px; height: 16px;"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 4 8 4c2.12 0 3.879.668 5.168 1.957A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12 8 12c-2.12 0-3.879-.668-5.168-1.957A13.133 13.133 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/></svg>`;
  const eyeSlashSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16" style="width: 16px; height: 16px;"><path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a8.09 8.09 0 0 0-2.312.333L9.73 6.868c.403-.345.925-.535 1.47-.535 1.38 0 2.5 1.12 2.5 2.5 0 .545-.19 1.067-.535 1.47l2.194 2.193zm-5.09-5.09L10 8.357A2.49 2.49 0 0 0 8.005 6.5a2.49 2.49 0 0 0-2.464 1.857l-1.815-1.815C4.857 6.136 6.326 5.5 8 5.5z"/><path d="M8 12c-2.12 0-3.879-.668-5.168-1.957a13.133 13.133 0 0 1-1.66-2.043C1.22 7.712 1.9 6.837 2.73 6.012L1.082 4.364A8.907 8.907 0 0 0 0 8s3 5.5 8 5.5a8.09 8.09 0 0 0 2.312-.333L8.641 11.51c-.403.345-.925.535-1.47.535z"/><path d="M12.42 13.482a8.238 8.238 0 0 1-2.585.836l1.246 1.246a.5.5 0 0 0 .707-.707l-1.368-1.375z"/><path d="M5.433 11.104A3.5 3.5 0 0 1 4.5 8a3.5 3.5 0 0 1 1.037-2.433l-1.037-1.037a5 5 0 0 0-.25 5.576l1.183 1.183-.003-.186zm6.825 2.193a5 5 0 0 0 .25-5.576l-1.183-1.183.003.186a3.5 3.5 0 0 1 1.037 3.03l1.183 1.183-.29-.64z"/></svg>`;

  toggleUserBtn.addEventListener('click', function() {
    if (userEventPasswordInput.type === 'password') {
      userEventPasswordInput.type = 'text';
      toggleUserBtn.innerHTML = eyeSlashSvg;
    } else {
      userEventPasswordInput.type = 'password';
      toggleUserBtn.innerHTML = eyeSvg;
    }
  });

  document.getElementById('add-custom-field').addEventListener('click', () => {
    const row = document.createElement('div');
    row.className = 'row mb-2 align-items-center';
    row.innerHTML = `
      <div class="col-6">
        <input type="text" name="custom_labels[]" class="form-control" placeholder="Nama field" required>
      </div>
      <div class="col-3">
        <select name="custom_types[]" class="form-control">
          <option value="text">Text</option>
          <option value="number">Number</option>
          <option value="date">Date</option>
          <option value="email">Email</option>
        </select>
      </div>
      <div class="col-3 text-end">
        <button type="button" class="btn btn-outline-danger btn-xs mb-0 remove-custom-field d-flex align-items-center justify-content-center gap-1 w-100">
          <i class="ni ni-fat-remove me-1"></i>
          <span class="d-none d-sm-inline">Hapus</span>
        </button>
      </div>`;
    
    row.querySelector('.remove-custom-field').addEventListener('click', () => {
      row.remove();
    });
    
    document.getElementById('custom-fields').appendChild(row);
  });

  document.addEventListener('DOMContentLoaded', () => {
    const table = document.getElementById('events-table');
    if (table) {
      new simpleDatatables.DataTable("#events-table", {
        searchable: true,
        fixedHeight: false,
        perPage: 5,
        labels: {
          placeholder: "Cari...",
          perPage: "",
          noRows: "Tidak ada data ditemukan",
          info: "Menampilkan {start} sampai {end} dari {rows} entri",
        }
      });
    }

    const qrModals = document.querySelectorAll('.modal[id^="qrModal-"]');
    qrModals.forEach(modal => {
      modal.addEventListener('shown.bs.modal', function () {
        const eventId = this.id.replace('qrModal-', '');
        const qrBox = document.getElementById('qrcode-box-' + eventId);
        const url = this.getAttribute('data-url');
        if (qrBox && qrBox.children.length === 0) {
          try {
            if (typeof QRCode !== 'undefined') {
              new QRCode(qrBox, {
                text: url,
                width: 180,
                height: 180,
                colorDark : "#0f172a",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
              });

              const canvas = qrBox.querySelector('canvas');
              if (canvas) {
                const ctx = canvas.getContext('2d');
                const logo = new Image();
                logo.onload = function () {
                  const logoSize = 36;
                  const x = (canvas.width - logoSize) / 2;
                  const y = (canvas.height - logoSize) / 2;

                  ctx.fillStyle = '#ffffff';
                  ctx.fillRect(x - 3, y - 3, logoSize + 6, logoSize + 6);

                  ctx.drawImage(logo, x, y, logoSize, logoSize);

                  const img = qrBox.querySelector('img');
                  if (img) {
                    img.src = canvas.toDataURL('image/png');
                  }
                };
                const logoSrc = '{{ asset("assets/argon-dashboard-pro-html-v2.0.5/assets/img/logos/GKV307_Kota Malang-logobase.net.png") }}';
                try {
                  logo.src = new URL(logoSrc).pathname;
                } catch (e) {
                  logo.src = logoSrc;
                }
              }
            } else {
              qrBox.innerHTML = `<img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=${encodeURIComponent(url)}" alt="QR Code" class="img-fluid rounded" />`;
            }
          } catch (err) {
            qrBox.innerHTML = `<img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=${encodeURIComponent(url)}" alt="QR Code" class="img-fluid rounded" />`;
          }
        }
      });
    });

    document.querySelectorAll('.download-qr-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const eventId = this.getAttribute('data-event-id');
        const eventName = this.getAttribute('data-event-name');
        const qrModal = document.getElementById('qrModal-' + eventId);
        const url = qrModal ? qrModal.getAttribute('data-url') : '';
        
        if (!url) return;

        if (typeof QRCode !== 'undefined') {
          // Create a temporary div to generate a high-res QR code
          const tempDiv = document.createElement('div');
          new QRCode(tempDiv, {
            text: url,
            width: 600,
            height: 600,
            colorDark : "#0f172a",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
          });

          const canvas = tempDiv.querySelector('canvas');
          if (canvas) {
            const ctx = canvas.getContext('2d');
            const logo = new Image();
            const logoSrc = '{{ asset("assets/argon-dashboard-pro-html-v2.0.5/assets/img/logos/GKV307_Kota Malang-logobase.net.png") }}';
            try {
              logo.src = new URL(logoSrc).pathname;
            } catch (e) {
              logo.src = logoSrc;
            }

            logo.onload = function() {
              const logoSize = 110;
              const x = (600 - logoSize) / 2;
              const y = (600 - logoSize) / 2;

              // Draw a clean white circle background card for the logo
              ctx.fillStyle = '#ffffff';
              ctx.beginPath();
              ctx.arc(300, 300, (logoSize + 22) / 2, 0, 2 * Math.PI);
              ctx.fill();

              // Draw logo inside the circle
              ctx.drawImage(logo, x, y, logoSize, logoSize);

              // Trigger download
              const a = document.createElement('a');
              a.href = canvas.toDataURL('image/png');
              a.download = 'QR_Code_' + eventName + '.png';
              document.body.appendChild(a);
              a.click();
              document.body.removeChild(a);
            };

            logo.onerror = function() {
              const a = document.createElement('a');
              a.href = canvas.toDataURL('image/png');
              a.download = 'QR_Code_' + eventName + '.png';
              document.body.appendChild(a);
              a.click();
              document.body.removeChild(a);
            };
          }
        } else {
          // Fallback if QRCode is not defined: Use API
          const qrImgUrl = `https://api.qrserver.com/v1/create-qr-code/?size=600x600&data=${encodeURIComponent(url)}`;
          const qrImg = new Image();
          qrImg.crossOrigin = 'anonymous';
          qrImg.src = qrImgUrl;
          
          qrImg.onload = function() {
            const canvas = document.createElement('canvas');
            canvas.width = 600;
            canvas.height = 600;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(qrImg, 0, 0, 600, 600);
            
            const logo = new Image();
            const logoSrc = '{{ asset("assets/argon-dashboard-pro-html-v2.0.5/assets/img/logos/GKV307_Kota Malang-logobase.net.png") }}';
            try {
              logo.src = new URL(logoSrc).pathname;
            } catch (e) {
              logo.src = logoSrc;
            }
            
            logo.onload = function() {
              const logoSize = 110;
              const x = (600 - logoSize) / 2;
              const y = (600 - logoSize) / 2;

              ctx.fillStyle = '#ffffff';
              ctx.beginPath();
              ctx.arc(300, 300, (logoSize + 22) / 2, 0, 2 * Math.PI);
              ctx.fill();

              ctx.drawImage(logo, x, y, logoSize, logoSize);

              const a = document.createElement('a');
              a.href = canvas.toDataURL('image/png');
              a.download = 'QR_Code_' + eventName + '.png';
              document.body.appendChild(a);
              a.click();
              document.body.removeChild(a);
            };
            
            logo.onerror = function() {
              const a = document.createElement('a');
              a.href = canvas.toDataURL('image/png');
              a.download = 'QR_Code_' + eventName + '.png';
              document.body.appendChild(a);
              a.click();
              document.body.removeChild(a);
            };
          };
          
          qrImg.onerror = function() {
            window.open(qrImgUrl, '_blank');
          };
        }
      });
    });

    // Copy link on click
    document.querySelectorAll('.qr-link-copy').forEach(el => {
      el.addEventListener('click', function() {
        const url = this.getAttribute('data-url');
        navigator.clipboard.writeText(url).then(() => {
          const originalHTML = this.innerHTML;
          this.innerHTML = '<span class="text-success font-weight-bold"><i class="fas fa-check me-1"></i> Berhasil disalin!</span>';
          setTimeout(() => {
            this.innerHTML = originalHTML;
          }, 2000);
        }).catch(err => {
          const tempInput = document.createElement('input');
          tempInput.value = url;
          document.body.appendChild(tempInput);
          tempInput.select();
          document.execCommand('copy');
          document.body.removeChild(tempInput);

          const originalHTML = this.innerHTML;
          this.innerHTML = '<span class="text-success font-weight-bold"><i class="fas fa-check me-1"></i> Berhasil disalin!</span>';
          setTimeout(() => {
            this.innerHTML = originalHTML;
          }, 2000);
        });
      });
    });

    @if($errors->any())
      const createEventModalEl = document.getElementById('createEventModal');
      if (createEventModalEl) {
        const modal = new bootstrap.Modal(createEventModalEl);
        modal.show();
      }
    @endif
  });
</script>
@endsection
