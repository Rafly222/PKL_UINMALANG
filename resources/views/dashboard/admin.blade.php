@extends('layouts.app')

@section('title', 'Dashboard Admin - E-Presensi')
@section('breadcrumb', 'Dashboard Admin')
@section('page-title', 'Portal Super Admin')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="ep-page-hero ep-bg-mesh mb-4">
      <div class="card-body position-relative z-index-2 p-4 p-lg-5">
        <div class="row align-items-center">
          <div class="col-lg-8">
            <span class="badge bg-white text-danger shadow-sm mb-3">Super Admin</span>
            <h2 class="text-white font-weight-bolder mb-2">Portal Kendali E-Presensi</h2>
            <!-- <p class="text-white opacity-8 mb-0">Kelola event global, akun user, blacklist NIK/NIP, dan log sistem dari satu dashboard.</p> -->
          </div>
          <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card bg-white shadow-lg border-0">
              <div class="card-body p-3">
                <div class="d-flex align-items-center">
                  <div class="icon icon-shape bg-gradient-danger shadow text-center rounded-circle me-3">
                    <i class="ni ni-settings-gear-65 text-lg opacity-10"></i>
                  </div>
                  <div>
                    <p class="text-sm mb-0 text-uppercase font-weight-bold"></p>
                    <h5 class="font-weight-bolder mb-0">ADMIN</h5>
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
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card ep-card">
      <div class="card-body p-3">
        <div class="d-flex">
          <div>
            <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Event</p>
            <h5 class="font-weight-bolder mb-0">{{ $events->count() }}</h5>
          </div>
          <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle ms-auto">
            <i class="ni ni-calendar-grid-58 text-lg opacity-10"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card ep-card">
      <div class="card-body p-3">
        <div class="d-flex">
          <div>
            <p class="text-sm mb-0 text-uppercase font-weight-bold">User</p>
            <h5 class="font-weight-bolder mb-0">{{ $users->count() }}</h5>
          </div>
          <div class="icon icon-shape bg-gradient-info shadow-info text-center rounded-circle ms-auto">
            <i class="ni ni-single-02 text-lg opacity-10"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card ep-card">
      <div class="card-body p-3">
        <div class="d-flex">
          <div>
            <p class="text-sm mb-0 text-uppercase font-weight-bold">Blacklist</p>
            <h5 class="font-weight-bolder mb-0">{{ $blacklists->count() }}</h5>
          </div>
          <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle ms-auto">
            <i class="ni ni-lock-circle-open text-lg opacity-10"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card ep-card">
      <div class="card-body p-3">
        <div class="d-flex">
          <div>
            <p class="text-sm mb-0 text-uppercase font-weight-bold">Log</p>
            <h5 class="font-weight-bolder mb-0">{{ $systemLogs->count() }}</h5>
          </div>
          <div class="icon icon-shape bg-gradient-dark shadow-dark text-center rounded-circle ms-auto">
            <i class="ni ni-bullet-list-67 text-lg opacity-10"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-4 mb-4">
    <div class="card ep-card ep-form-card h-100">
      <div class="card-header pb-0 bg-transparent">
        <div class="d-flex align-items-center">
          <div class="icon icon-shape bg-gradient-danger shadow text-center border-radius-md me-3">
            <i class="ni ni-fat-add text-white"></i>
          </div>
          <div>
            <h6 class="mb-0">Buat Event Global</h6>
            <p class="text-xs text-muted mb-0">Event admin bisa diakses dan dikelola secara global.</p>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form action="{{ route('admin.event.store') }}" method="POST">
          @csrf
          <input type="text" name="name" class="form-control mb-3" placeholder="Nama event" required>
          <div class="row">
            <div class="col-6"><input type="date" name="date" class="form-control mb-3" required></div>
            <div class="col-3"><input type="time" name="time_start" class="form-control mb-3" required></div>
            <div class="col-3"><input type="time" name="time_end" class="form-control mb-3" required></div>
          </div>
          <div class="row">
            <div class="col-6">
              <select name="audience_type" class="form-control mb-3">
                <option value="semua">Semua</option>
                <option value="pegawai">Pegawai</option>
                <option value="umum">Umum</option>
              </select>
            </div>
            <div class="col-6">
              <select name="access_type" class="form-control mb-3" id="admin-access-type">
                <option value="publik">Publik</option>
                <option value="privat">Privat</option>
              </select>
            </div>
          </div>
          <div class="input-group mb-3" id="admin-event-password-group">
            <input type="password" name="password" id="admin-event-password" class="form-control" placeholder="Password event privat" style="border-right: 0;">
            <span class="input-group-text bg-white cursor-pointer" id="toggle-admin-event-password" style="cursor: pointer; border-left: 0;">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16" style="width: 16px; height: 16px;"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 4 8 4c2.12 0 3.879.668 5.168 1.957A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12 8 12c-2.12 0-3.879-.668-5.168-1.957A13.133 13.133 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/></svg>
            </span>
          </div>

          <span class="ep-section-label">Field Presensi</span>
          <div class="row mt-2 mb-3">
            @foreach([
              'sc-phone' => 'No HP',
              'sc-gender' => 'Gender',
              'sc-institution' => 'Instansi',
              'sc-address' => 'Alamat',
              'sc-nip' => 'NIP',
              'sc-photo' => 'Foto',
              'sc-signature' => 'TTD',
            ] as $value => $label)
              <div class="col-6">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" name="{{ $value }}" value="1" id="admin-{{ $value }}">
                  <label class="form-check-label text-sm" for="admin-{{ $value }}">{{ $label }}</label>
                </div>
              </div>
            @endforeach
          </div>

          <span class="ep-section-label">Custom Field</span>
          <div id="admin-custom-fields" class="mt-2 mb-3"></div>
          <button type="button" class="btn btn-sm btn-outline-danger mb-3" id="admin-add-custom-field">
            <i class="ni ni-fat-add me-1"></i> Tambah Field
          </button>
          <button class="btn bg-gradient-danger w-100 mb-0 shadow">Simpan Event</button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-8 mb-4">
    <div class="card ep-card h-100">
      <div class="card-header pb-0 bg-transparent">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <h6 class="mb-0">Semua Event</h6>
            <p class="text-xs text-muted mb-0">Pantau dan kelola seluruh agenda presensi.</p>
          </div>
          <span class="badge bg-gradient-primary">{{ $events->count() }} event</span>
        </div>
      </div>
      <div class="card-body px-0 pt-0 pb-2 mt-3">
        <div class="table-responsive p-4">
          <table class="table align-items-center mb-0" id="events-table-admin">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder ps-4">Event</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Pembuat</th>
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
                        <i class="ni ni-badge text-danger"></i>
                      </div>
                      <div>
                        <h6 class="text-sm mb-0">{{ $event->name }}</h6>
                        <span class="badge badge-sm {{ $event->access_type === 'privat' ? 'bg-gradient-warning' : 'bg-gradient-success' }}">{{ ucfirst($event->access_type) }}</span>
                        <span class="badge badge-sm {{ $event->status_absensi === 'Berlaku' ? 'bg-gradient-success' : 'bg-gradient-danger' }}">{{ $event->status_absensi }}</span>
                      </div>
                    </div>
                  </td>
                  <td class="text-sm">{{ $event->creator->name ?? 'Admin' }}</td>
                  <td class="text-sm">
                    <span class="font-weight-bold">{{ \Carbon\Carbon::parse($event->date)->format('d/m/Y') }}</span><br>
                    <span class="text-xs text-muted">{{ \Carbon\Carbon::parse($event->time_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($event->time_end)->format('H:i') }}</span>
                  </td>
                  <td class="text-center">
                    <a href="{{ route('event.presences', $event->id) }}" class="btn btn-xs bg-gradient-success mb-1">Rekap</a>
                    <a href="{{ route('presence.form', $event->id) }}" class="btn btn-xs bg-gradient-primary mb-1">Buka</a>
                    <button type="button" class="btn btn-xs btn-outline-secondary mb-1" onclick="navigator.clipboard.writeText('{{ route('presence.form', $event->id) }}')">Salin</button>
                    <form action="{{ route('event.destroy', $event) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus event ini?')">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-xs btn-outline-danger mb-1">Hapus</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="4" class="text-center text-sm text-muted py-5">Belum ada event.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-6 mb-4">
    <div class="card ep-card ep-form-card h-100">
      <div class="card-header pb-0 bg-transparent">
        <h6 class="mb-0">Manajemen User</h6>
        <p class="text-xs text-muted mb-0">Tambah akun user atau admin, serta hapus dan blacklist identitas.</p>
      </div>
      <div class="card-body">
        <form action="{{ route('admin.users.store') }}" method="POST" class="mb-4">
          @csrf
          <div class="row">
            <div class="col-md-6"><input name="name" class="form-control mb-2" placeholder="Nama" required></div>
            <div class="col-md-6"><input name="email" type="email" class="form-control mb-2" placeholder="Email" required></div>
            <div class="col-md-6"><input name="nik" class="form-control mb-2" placeholder="NIK 16 digit" required></div>
            <div class="col-md-6"><input name="nip" class="form-control mb-2" placeholder="NIP 18 digit"></div>
            <div class="col-md-6"><input name="password" type="password" class="form-control mb-2" placeholder="Password" required></div>
            <div class="col-md-6">
              <select name="role" class="form-control mb-2">
                <option value="user">User</option>
                <option value="admin">Admin</option>
              </select>
            </div>
          </div>
          <button class="btn bg-gradient-primary mb-0 shadow">Tambah User</button>
        </form>

        <div class="table-responsive p-3">
          <table class="table mb-0" id="users-table-admin">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder ps-3">User</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-end">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($users as $user)
                <tr>
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="avatar avatar-sm bg-gradient-info text-white rounded-circle me-3 d-flex align-items-center justify-content-center">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                      </div>
                      <div>
                        <h6 class="text-sm mb-0">{{ $user->name }}</h6>
                        <span class="text-xs text-muted">{{ $user->email }} · {{ $user->role }}</span>
                      </div>
                    </div>
                  </td>
                  <td class="text-end">
                    <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Hapus dan blacklist user ini?')">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-xs btn-outline-danger mb-0">Hapus + Blacklist</button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-6 mb-4">
    <div class="card ep-card ep-form-card h-100">
      <div class="card-header pb-0 bg-transparent">
        <h6 class="mb-0">Blacklist NIK/NIP</h6>
        <p class="text-xs text-muted mb-0">Blokir identitas yang tidak boleh melakukan registrasi kembali.</p>
      </div>
      <div class="card-body">
        <form action="{{ route('admin.blacklist.store') }}" method="POST" class="row mb-4">
          @csrf
          <div class="col-md-5"><input name="nik" class="form-control mb-2" placeholder="NIK"></div>
          <div class="col-md-5"><input name="nip" class="form-control mb-2" placeholder="NIP"></div>
          <div class="col-md-2"><button class="btn bg-gradient-dark w-100 mb-0">Blokir</button></div>
        </form>

        <div class="table-responsive p-3">
          <table class="table mb-0" id="blacklists-table-admin">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder ps-3">Identitas</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-end">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($blacklists as $blacklist)
                <tr>
                  <td class="text-sm">
                    <span class="badge bg-gradient-warning me-1">Blacklist</span>
                    NIK: {{ $blacklist->nik ?? '-' }}<br>
                    <span class="ms-6 text-muted">NIP: {{ $blacklist->nip ?? '-' }}</span>
                  </td>
                  <td class="text-end">
                    <form action="{{ route('admin.blacklist.delete', $blacklist->id) }}" method="POST">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-xs btn-outline-secondary mb-0">Pulihkan</button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-12">
    <div class="card ep-card mb-4">
      <div class="card-header pb-0 bg-transparent">
        <h6 class="mb-0">Log Aktivitas Sistem</h6>
        <!-- <p class="text-xs text-muted mb-0">Audit trail aktivitas penting dalam aplikasi E-Presensi.</p> -->
      </div>
      <div class="card-body px-0 pt-0 pb-2 mt-3">
        <div class="table-responsive p-4">
          <table class="table align-items-center mb-0" id="activity-logs-table">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder ps-3">Waktu</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Pengguna</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Aktivitas</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Keterangan</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder">IP Address</th>
              </tr>
            </thead>
            <tbody>
              @foreach($systemLogs as $log)
                <tr>
                  <td class="text-sm ps-3">{{ $log->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i:s') }} WIB</td>
                  <td class="text-sm font-weight-bold">{{ $log->user_name }}</td>
                  <td>
                    @php
                      $color = 'bg-gradient-secondary';
                      if ($log->activity === 'login') $color = 'bg-gradient-success';
                      elseif ($log->activity === 'logout') $color = 'bg-gradient-dark';
                      elseif ($log->activity === 'create_event') $color = 'bg-gradient-primary';
                      elseif ($log->activity === 'delete_event') $color = 'bg-gradient-danger';
                      elseif ($log->activity === 'submit_presence') $color = 'bg-gradient-info';
                      elseif (in_array($log->activity, ['blacklist_add', 'delete_user'])) $color = 'bg-gradient-warning';
                    @endphp
                    <span class="badge badge-sm {{ $color }}">{{ ucfirst(str_replace('_', ' ', $log->activity)) }}</span>
                  </td>
                  <td class="text-sm text-wrap" style="max-width: 350px;">{{ $log->description }}</td>
                  <td class="text-sm">{{ $log->ip_address }}</td>
                </tr>
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
  const adminAccessType = document.getElementById('admin-access-type');
  const adminEventPasswordGroup = document.getElementById('admin-event-password-group');

  function toggleAdminPassword() {
    adminEventPasswordGroup.style.display = adminAccessType.value === 'privat' ? 'flex' : 'none';
  }

  adminAccessType.addEventListener('change', toggleAdminPassword);
  toggleAdminPassword();

  const toggleAdminBtn = document.getElementById('toggle-admin-event-password');
  const adminEventPasswordInput = document.getElementById('admin-event-password');

  const eyeSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16" style="width: 16px; height: 16px;"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 4 8 4c2.12 0 3.879.668 5.168 1.957A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12 8 12c-2.12 0-3.879-.668-5.168-1.957A13.133 13.133 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/></svg>`;
  const eyeSlashSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16" style="width: 16px; height: 16px;"><path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a8.09 8.09 0 0 0-2.312.333L9.73 6.868c.403-.345.925-.535 1.47-.535 1.38 0 2.5 1.12 2.5 2.5 0 .545-.19 1.067-.535 1.47l2.194 2.193zm-5.09-5.09L10 8.357A2.49 2.49 0 0 0 8.005 6.5a2.49 2.49 0 0 0-2.464 1.857l-1.815-1.815C4.857 6.136 6.326 5.5 8 5.5z"/><path d="M8 12c-2.12 0-3.879-.668-5.168-1.957a13.133 13.133 0 0 1-1.66-2.043C1.22 7.712 1.9 6.837 2.73 6.012L1.082 4.364A8.907 8.907 0 0 0 0 8s3 5.5 8 5.5a8.09 8.09 0 0 0 2.312-.333L8.641 11.51c-.403.345-.925.535-1.47.535z"/><path d="M12.42 13.482a8.238 8.238 0 0 1-2.585.836l1.246 1.246a.5.5 0 0 0 .707-.707l-1.368-1.375z"/><path d="M5.433 11.104A3.5 3.5 0 0 1 4.5 8a3.5 3.5 0 0 1 1.037-2.433l-1.037-1.037a5 5 0 0 0-.25 5.576l1.183 1.183-.003-.186zm6.825 2.193a5 5 0 0 0 .25-5.576l-1.183-1.183.003.186a3.5 3.5 0 0 1 1.037 3.03l1.183 1.183-.29-.64z"/></svg>`;

  toggleAdminBtn.addEventListener('click', function() {
    if (adminEventPasswordInput.type === 'password') {
      adminEventPasswordInput.type = 'text';
      toggleAdminBtn.innerHTML = eyeSlashSvg;
    } else {
      adminEventPasswordInput.type = 'password';
      toggleAdminBtn.innerHTML = eyeSvg;
    }
  });

  document.getElementById('admin-add-custom-field').addEventListener('click', () => {
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
    
    document.getElementById('admin-custom-fields').appendChild(row);
  });

  document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('events-table-admin')) {
      new simpleDatatables.DataTable("#events-table-admin", {
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

    if (document.getElementById('users-table-admin')) {
      new simpleDatatables.DataTable("#users-table-admin", {
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

    if (document.getElementById('blacklists-table-admin')) {
      new simpleDatatables.DataTable("#blacklists-table-admin", {
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

    if (document.getElementById('activity-logs-table')) {
      new simpleDatatables.DataTable("#activity-logs-table", {
        searchable: true,
        fixedHeight: false,
        perPage: 10,
        labels: {
          placeholder: "Cari log...",
          perPage: "",
          noRows: "Tidak ada log aktivitas ditemukan",
          info: "Menampilkan {start} sampai {end} dari {rows} entri",
        }
      });
    }
  });
</script>
@endsection
