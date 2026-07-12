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
          <span>Hapus</span>
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
  });
</script>
@endsection
