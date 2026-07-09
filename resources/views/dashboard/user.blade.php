@extends('layouts.app')

@section('title', 'Dashboard User - E-Presensi')
@section('breadcrumb', 'Dashboard User')
@section('page-title', 'Dashboard Pembuat Event')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="ep-page-hero ep-bg-mesh mb-4">
      <div class="card-body position-relative z-index-2 p-4 p-lg-5">
        <div class="row align-items-center">
          <div class="col-lg-8">
            <span class="badge bg-white text-primary shadow-sm mb-3">Creator Event</span>
            <h2 class="text-white font-weight-bolder mb-2">Dashboard Pembuat Event</h2>
            <p class="text-white opacity-8 mb-0"></p>
          </div>
          <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card bg-white shadow-lg border-0">
              <div class="card-body p-3">
                <div class="d-flex align-items-center">
                  <div class="icon icon-shape bg-gradient-primary shadow text-center rounded-circle me-3">
                    <i class="ni ni-calendar-grid-58 text-lg opacity-10"></i>
                  </div>
                  <div>
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Event Saya</p>
                    <h5 class="font-weight-bolder mb-0">{{ $events->count() }} Agenda</h5>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    @includeWhen(session('success') || session('warning') || session('info') || $errors->any(), 'partials.flash')
  </div>
</div>

<div class="row">
  <div class="col-xl-4 col-md-6 mb-4">
    <div class="card ep-card">
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
  <div class="col-xl-4 col-md-6 mb-4">
    <div class="card ep-card">
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
  <div class="col-xl-4 mb-4">
    <div class="card ep-card">
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
  <div class="col-lg-5 mb-4">
    <div class="card ep-card ep-form-card h-100">
      <div class="card-header pb-0 bg-transparent">
        <div class="d-flex align-items-center">
          <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md me-3">
            <i class="ni ni-fat-add text-white"></i>
          </div>
          <div>
            <h6 class="mb-0">Buat Event Presensi</h6>
            <p class="text-xs text-muted mb-0"></p>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form action="{{ route('event.store') }}" method="POST">
          @csrf
          <span class="ep-section-label">Informasi Event</span>
          <div class="mb-3 mt-2">
            <label class="form-control-label text-xs">Nama kegiatan</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Contoh: Rapat Koordinasi Smart City" required>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-control-label text-xs">Tanggal</label>
              <input type="date" name="date" value="{{ old('date') }}" class="form-control" required>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-control-label text-xs">Mulai</label>
              <input type="time" name="time_start" value="{{ old('time_start') }}" class="form-control" required>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-control-label text-xs">Selesai</label>
              <input type="time" name="time_end" value="{{ old('time_end') }}" class="form-control" required>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-control-label text-xs">Kategori peserta</label>
              <select name="audience_type" class="form-control" required>
                <option value="semua" @selected(old('audience_type') === 'semua')>Semua</option>
                <option value="pegawai" @selected(old('audience_type') === 'pegawai')>Pegawai</option>
                <option value="umum" @selected(old('audience_type') === 'umum')>Umum</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-control-label text-xs">Akses event</label>
              <select name="access_type" id="access_type" class="form-control" required>
                <option value="publik" @selected(old('access_type') === 'publik')>Publik</option>
                <option value="privat" @selected(old('access_type') === 'privat')>Privat</option>
              </select>
            </div>
          </div>

          <div class="mb-3" id="password-wrapper">
            <label class="form-control-label text-xs">Password event privat</label>
            <input type="text" name="password" class="form-control" placeholder="Minimal 4 karakter">
          </div>

          <hr class="horizontal dark my-4">
          <span class="ep-section-label">Field Standar</span>
          <div class="row mt-2 mb-3">
            @foreach([
              'sc-phone' => 'No HP',
              'sc-gender' => 'Jenis kelamin',
              'sc-institution' => 'Instansi',
              'sc-address' => 'Alamat',
              'sc-nip' => 'NIP',
              'sc-photo' => 'Foto wajah',
              'sc-signature' => 'TTD digital',
            ] as $value => $label)
              <div class="col-md-6 mb-1">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" name="{{ $value }}" value="1" id="{{ $value }}" @checked(old($value))>
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

          <button type="submit" class="btn bg-gradient-primary w-100 mb-0 shadow">Simpan Event</button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-7">
    <div class="card ep-card h-100">
      <div class="card-header pb-0 bg-transparent">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <h6 class="mb-0">Event Saya</h6>
            <p class="text-xs text-muted mb-0"></p>
          </div>
          <span class="badge bg-gradient-primary">{{ $events->count() }} total</span>
        </div>
      </div>
      <div class="card-body px-0 pt-0 pb-2 mt-3">
        <div class="table-responsive p-4">
          <table class="table align-items-center mb-0" id="events-table">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder ps-4">Event</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Jadwal</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($events as $event)
                <tr>
                  <td class="ps-4">
                    <div class="d-flex align-items-center">
                      <div class="icon icon-shape icon-sm bg-gradient-light shadow text-center me-3">
                        <i class="ni ni-badge text-primary"></i>
                      </div>
                      <div>
                        <h6 class="mb-0 text-sm">{{ $event->name }}</h6>
                        <span class="badge badge-sm {{ $event->access_type === 'privat' ? 'bg-gradient-warning' : 'bg-gradient-success' }}">{{ ucfirst($event->access_type) }}</span>
                        <span class="badge badge-sm bg-gradient-secondary">{{ ucfirst($event->audience_type) }}</span>
                        <span class="badge badge-sm {{ $event->status_absensi === 'Berlaku' ? 'bg-gradient-success' : 'bg-gradient-danger' }}">{{ $event->status_absensi }}</span>
                      </div>
                    </div>
                  </td>
                  <td class="text-sm">
                    <span class="font-weight-bold">{{ \Carbon\Carbon::parse($event->date)->format('d/m/Y') }}</span><br>
                    <span class="text-xs text-muted">{{ \Carbon\Carbon::parse($event->time_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($event->time_end)->format('H:i') }} WIB</span>
                  </td>
                  <td class="text-center">
                    <a href="{{ route('event.presences', $event->id) }}" class="btn btn-xs bg-gradient-success mb-1">Rekap</a>
                    <a href="{{ route('presence.form', $event->id) }}" class="btn btn-xs bg-gradient-primary mb-1">Buka</a>
                    <button type="button" class="btn btn-xs btn-outline-secondary mb-1" onclick="navigator.clipboard.writeText('{{ route('presence.form', $event->id) }}')">Salin</button>
                    <form action="{{ route('event.destroy', $event) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus event ini?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-xs btn-outline-danger mb-1">Hapus</button>
                    </form>
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

  document.getElementById('add-custom-field').addEventListener('click', () => {
    const row = document.createElement('div');
    row.className = 'row mb-2';
    row.innerHTML = `
      <div class="col-7">
        <input type="text" name="custom_labels[]" class="form-control" placeholder="Nama field">
      </div>
      <div class="col-5">
        <select name="custom_types[]" class="form-control">
          <option value="text">Text</option>
          <option value="number">Number</option>
          <option value="date">Date</option>
          <option value="email">Email</option>
        </select>
      </div>`;
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
  });
</script>
@endsection
