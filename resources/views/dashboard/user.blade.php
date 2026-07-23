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
                      <button type="button" class="btn btn-xs bg-gradient-secondary text-white mb-0 shadow-xs qr-link-copy" data-url="{{ route('presence.form', $event->uuid) }}">
                        <i class="fas fa-copy me-1"></i> Salin
                      </button>
                      <a href="{{ route('event.presences', $event->uuid) }}" class="btn btn-xs bg-gradient-success mb-0 shadow-xs">Rekap</a>
                      <button type="button" 
                              class="btn btn-xs bg-gradient-info mb-0 shadow-xs" 
                              data-bs-toggle="modal" 
                              data-bs-target="#editEventModal" 
                              data-action="{{ route('event.update', $event->id) }}"
                              data-name="{{ $event->name }}"
                              data-date="{{ $event->date }}"
                              data-date-end="{{ $event->date_end }}"
                              data-time-start="{{ \Carbon\Carbon::parse($event->time_start)->format('H:i') }}"
                              data-time-end="{{ \Carbon\Carbon::parse($event->time_end)->format('H:i') }}"
                              data-audience-type="{{ $event->audience_type }}"
                              data-access-type="{{ $event->access_type }}"
                              data-password="{{ $event->decrypted_password }}"
                              data-fields="{{ json_encode($event->fields ?? []) }}"
                              data-custom-fields="{{ json_encode($event->custom_fields ?? []) }}">
                        Edit
                      </button>
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
                              @if($event->access_type === 'privat' && $event->decrypted_password)
                                <div class="mt-2 text-center qr-password-info">
                                  <span class="badge bg-gradient-warning text-xxs font-weight-bold" style="font-size: 11px; padding: 6px 12px; border-radius: 6px;">
                                    <i class="fas fa-lock me-1"></i> Password: <strong>{{ $event->decrypted_password }}</strong>
                                  </span>
                                </div>
                              @endif
                              
                              <div class="d-flex justify-content-center my-3">
                                <div class="position-relative d-inline-block p-2 bg-white rounded border shadow-xs">
                                  <div id="qrcode-box-{{ $event->id }}"></div>
                                  <div class="position-absolute start-50 top-50 translate-middle bg-white p-1 rounded-3 shadow-xs d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; pointer-events: none;">
                                    <img src="{{ asset('assets/argon-dashboard-pro-html-v2.0.5/assets/img/logos/GKV307_Kota Malang-logobase.net.png') }}" style="width: 32px; height: 32px; object-fit: contain;" alt="Logo Pemkot">
                                  </div>
                                </div>
                              </div>
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

@include('partials.edit_event_modal')

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

          <div class="mb-3" id="password-wrapper" style="display: none;">
            <label class="form-control-label text-xs font-weight-bold">Password Event Privat</label>
            <div class="input-group">
              <input type="text" name="password" id="user-event-password" class="form-control" placeholder="Password event privat" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
              <button type="button" class="btn bg-gradient-dark mb-0 px-3" id="btn-generate-create-user-password" style="border-top-left-radius: 0; border-bottom-left-radius: 0; font-size: 11px;">
                <i class="fas fa-random me-1"></i> Generate Acak
              </button>
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
  const userEventPasswordInput = document.getElementById('user-event-password');
  const btnGenerateCreateUserPassword = document.getElementById('btn-generate-create-user-password');

  function generateRandomPassword() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let randomPass = '';
    for (let i = 0; i < 6; i++) {
      randomPass += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return randomPass;
  }

  function togglePassword() {
    if (accessType.value === 'privat') {
      passwordWrapper.style.display = 'block';
      if (!userEventPasswordInput.value) {
        userEventPasswordInput.value = generateRandomPassword();
      }
    } else {
      passwordWrapper.style.display = 'none';
    }
  }

  accessType.addEventListener('change', togglePassword);
  togglePassword();

  if (btnGenerateCreateUserPassword && userEventPasswordInput) {
    btnGenerateCreateUserPassword.addEventListener('click', function() {
      userEventPasswordInput.value = generateRandomPassword();
    });
  }



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
        perPageSelect: [5, 10, 15, 20, 25],
        labels: {
          placeholder: "Cari...",
          perPage: "{select} data per halaman",
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

        // Helper to draw rounded rectangles
        function drawRoundedRect(ctx, x, y, width, height, radius) {
          ctx.beginPath();
          ctx.moveTo(x + radius, y);
          ctx.lineTo(x + width - radius, y);
          ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
          ctx.lineTo(x + width, y + height - radius);
          ctx.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
          ctx.lineTo(x + radius, y + height);
          ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
          ctx.lineTo(x, y + radius);
          ctx.quadraticCurveTo(x, y, x + radius, y);
          ctx.closePath();
        }

        // Helper to wrap text
        function wrapText(ctx, text, x, y, maxWidth, lineHeight) {
          const words = text.split(' ');
          let line = '';
          let currentY = y;
          
          for (let n = 0; n < words.length; n++) {
            let testLine = line + words[n] + ' ';
            let metrics = ctx.measureText(testLine);
            let testWidth = metrics.width;
            if (testWidth > maxWidth && n > 0) {
              ctx.fillText(line, x, currentY);
              line = words[n] + ' ';
              currentY += lineHeight;
            } else {
              line = testLine;
            }
          }
          ctx.fillText(line, x, currentY);
          return currentY;
        }

        // Load images using Promise
        const loadImage = (src) => {
          return new Promise((resolve) => {
            const img = new Image();
            img.crossOrigin = 'anonymous';
            img.onload = () => resolve(img);
            img.onerror = () => resolve(null);
            try {
              img.src = new URL(src, window.location.origin).pathname;
            } catch (e) {
              img.src = src;
            }
          });
        };

        const logoMalangSrc = '{{ asset("assets/argon-dashboard-pro-html-v2.0.5/assets/img/logos/GKV307_Kota Malang-logobase.net.png") }}';
        const logoEPresensiSrc = '{{ asset("assets/argon-dashboard-pro-html-v2.0.5/assets/img/epresensi-logo.png") }}';

        Promise.all([
          loadImage(logoMalangSrc),
          loadImage(logoEPresensiSrc)
        ]).then(([logoMalang, logoEPresensi]) => {
          // Get dynamic texts from the modal card
          const cardArea = document.getElementById('qrCardArea-' + eventId);
          const eventNameText = cardArea.querySelector('h5').innerText;
          const eventDateTimeText = cardArea.querySelector('p').innerText;

          // Create canvas
          const canvas = document.createElement('canvas');
          canvas.width = 800;
          
          // Temporary context to measure event name lines and calculate height dynamically
          const ctx = canvas.getContext('2d');
          ctx.font = 'bold 38px sans-serif';
          
          // Wrap text dry run to get height of Title
          const words = eventNameText.split(' ');
          let line = '';
          let tempY = 150; // Starting Y of Title
          for (let n = 0; n < words.length; n++) {
            let testLine = line + words[n] + ' ';
            let metrics = ctx.measureText(testLine);
            if (metrics.width > 680 && n > 0) {
              line = words[n] + ' ';
              tempY += 50; // Line height for title
            } else {
              line = testLine;
            }
          }
          const titleEndY = tempY + 15; // Y position at the bottom of the title text
          const dateY = titleEndY + 45; // Y position for Event Date & Time
          
          // Check if password exists
          const passwordInfo = cardArea.querySelector('.qr-password-info');
          let passwordY = 0;
          let qrBoxY = dateY + 45;
          if (passwordInfo) {
            passwordY = dateY + 40;
            qrBoxY = passwordY + 45;
          }
          
          // Set dynamic canvas height (balanced top/bottom 40px gaps)
          canvas.height = qrBoxY + 528;

          // Draw outer rounded card with compact shadow on transparent canvas
          ctx.shadowColor = 'rgba(0, 0, 0, 0.12)';
          ctx.shadowBlur = 8;
          ctx.shadowOffsetX = 0;
          ctx.shadowOffsetY = 3;
          
          ctx.fillStyle = '#ffffff';
          drawRoundedRect(ctx, 8, 8, 784, canvas.height - 16, 24);
          ctx.fill();

          // Reset shadow
          ctx.shadowColor = 'transparent';
          ctx.shadowBlur = 0;
          ctx.shadowOffsetX = 0;
          ctx.shadowOffsetY = 0;

          // Draw outer rounded border
          ctx.strokeStyle = '#e2e8f0';
          ctx.lineWidth = 2;
          drawRoundedRect(ctx, 8, 8, 784, canvas.height - 16, 24);
          ctx.stroke();

          // Draw E-Presensi logo and brand (perfectly centered)
          if (logoEPresensi) {
            ctx.font = 'bold 34px sans-serif';
            ctx.fillStyle = '#344767';
            const brandText = 'E-Presensi';
            const brandTextWidth = ctx.measureText(brandText).width;
            const logoWidth = 44;
            const headerGap = 14;
            const totalHeaderWidth = logoWidth + headerGap + brandTextWidth;
            const startHeaderX = (800 - totalHeaderWidth) / 2;
            
            ctx.drawImage(logoEPresensi, startHeaderX, 48, logoWidth, logoWidth);
            
            ctx.textAlign = 'left';
            ctx.textBaseline = 'middle';
            ctx.fillText(brandText, startHeaderX + logoWidth + headerGap, 48 + logoWidth / 2);
          } else {
            ctx.font = 'bold 34px sans-serif';
            ctx.fillStyle = '#344767';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText('E-Presensi', 400, 70);
          }

          // Draw Event Name
          ctx.font = 'bold 38px sans-serif';
          ctx.fillStyle = '#5e72e4';
          ctx.textAlign = 'center';
          ctx.textBaseline = 'top';
          wrapText(ctx, eventNameText, 400, 150, 680, 50);

           // Draw Event Date & Time
          ctx.font = 'bold 22px sans-serif';
          ctx.fillStyle = '#8898aa';
          ctx.textAlign = 'center';
          ctx.textBaseline = 'top';
          ctx.fillText(eventDateTimeText.trim(), 400, dateY);

          // Draw Password if present
          if (passwordInfo) {
            ctx.font = 'bold 20px sans-serif';
            ctx.fillStyle = '#fb6340'; // Warning color matching the badge
            ctx.textAlign = 'center';
            ctx.textBaseline = 'top';
            ctx.fillText(passwordInfo.innerText.trim(), 400, passwordY);
          }

          // Draw QR Code Border Box with shadow (perfectly centered)
          ctx.shadowColor = 'rgba(0, 0, 0, 0.04)';
          ctx.shadowBlur = 12;
          ctx.shadowOffsetX = 0;
          ctx.shadowOffsetY = 4;

          ctx.fillStyle = '#ffffff';
          drawRoundedRect(ctx, 160, qrBoxY, 480, 480, 24);
          ctx.fill();

          // Reset shadow
          ctx.shadowColor = 'transparent';
          ctx.shadowBlur = 0;
          ctx.shadowOffsetX = 0;
          ctx.shadowOffsetY = 0;

          ctx.strokeStyle = '#e2e8f0';
          ctx.lineWidth = 2;
          drawRoundedRect(ctx, 160, qrBoxY, 480, 480, 24);
          ctx.stroke();

          // Draw QR Code directly from the already-rendered modal canvas/img (Guarantees pixels are ready)
          const qrBox = document.getElementById('qrcode-box-' + eventId);
          if (qrBox) {
            const modalCanvas = qrBox.querySelector('canvas');
            const modalImg = qrBox.querySelector('img');
            
            ctx.imageSmoothingEnabled = false;
            ctx.mozImageSmoothingEnabled = false;
            ctx.webkitImageSmoothingEnabled = false;
            ctx.msImageSmoothingEnabled = false;
            
            if (modalCanvas) {
              ctx.drawImage(modalCanvas, 180, qrBoxY + 20, 440, 440);
              ctx.imageSmoothingEnabled = true;
              finishDrawing();
            } else if (modalImg && modalImg.src) {
              const tempImg = new Image();
              tempImg.crossOrigin = 'anonymous';
              tempImg.onload = function() {
                ctx.drawImage(tempImg, 180, qrBoxY + 20, 440, 440);
                ctx.imageSmoothingEnabled = true;
                finishDrawing();
              };
              tempImg.onerror = function() {
                ctx.imageSmoothingEnabled = true;
                finishDrawing();
              };
              tempImg.src = modalImg.src;
            } else {
              ctx.imageSmoothingEnabled = true;
              finishDrawing();
            }
          } else {
            finishDrawing();
          }

          function finishDrawing() {
            // Draw Malang Kota logo in center of QR
            const logoSize = 72;
            const centerX = 400;
            const centerY = qrBoxY + 240;
            
            ctx.fillStyle = '#ffffff';
            ctx.beginPath();
            ctx.arc(centerX, centerY, 48, 0, 2 * Math.PI);
            ctx.fill();

            if (logoMalang) {
              ctx.drawImage(logoMalang, centerX - (logoSize / 2), centerY - (logoSize / 2), logoSize, logoSize);
            }

            // Trigger download
            const a = document.createElement('a');
            a.href = canvas.toDataURL('image/png');
            a.download = 'QR_Card_' + eventName + '.png';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
          }
        }).catch(err => {
          console.error('Failed to generate QR card: ', err);
          alert('Gagal mengunduh kartu QR: ' + err.message);
          window.open(`https://api.qrserver.com/v1/create-qr-code/?size=600x600&data=${encodeURIComponent(url)}`, '_blank');
        });
      });
    });

    function showCopyNotification() {
      let existing = document.getElementById('copy-toast-notification');
      if (existing) {
        existing.remove();
      }

      const toast = document.createElement('div');
      toast.id = 'copy-toast-notification';
      toast.style.position = 'fixed';
      toast.style.bottom = '20px';
      toast.style.left = '20px';
      toast.style.zIndex = '9999';
      toast.style.backgroundColor = '#2dce89';
      toast.style.color = '#ffffff';
      toast.style.padding = '12px 24px';
      toast.style.borderRadius = '8px';
      toast.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
      toast.style.display = 'flex';
      toast.style.alignItems = 'center';
      toast.style.gap = '10px';
      toast.style.fontSize = '13px';
      toast.style.fontWeight = 'bold';
      toast.style.transition = 'all 0.3s ease';
      toast.style.opacity = '0';
      toast.style.transform = 'translateY(20px)';
      toast.innerHTML = '<i class="fas fa-check-circle" style="font-size: 16px;"></i> Link presensi berhasil disalin!';

      document.body.appendChild(toast);
      toast.offsetHeight;
      toast.style.opacity = '1';
      toast.style.transform = 'translateY(0)';

      setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(20px)';
        setTimeout(() => {
          toast.remove();
        }, 300);
      }, 3000);
    }

    // Copy link on click
    document.querySelectorAll('.qr-link-copy').forEach(el => {
      el.addEventListener('click', function() {
        const url = this.getAttribute('data-url');
        navigator.clipboard.writeText(url).then(() => {
          showCopyNotification();
        }).catch(err => {
          const tempInput = document.createElement('input');
          tempInput.value = url;
          document.body.appendChild(tempInput);
          tempInput.select();
          document.execCommand('copy');
          document.body.removeChild(tempInput);
          showCopyNotification();
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
