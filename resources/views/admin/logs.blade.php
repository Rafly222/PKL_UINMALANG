@extends('layouts.app')

@section('title', 'Log Aktivitas - E-Presensi Diskominfo')

@push('styles')
<style>
  @media (max-width: 575.98px) {
    .ep-log-stat-card .card-body {
      padding: 0.75rem !important;
    }
    .ep-log-stat-card .icon-shape {
      width: 32px !important;
      height: 32px !important;
      min-width: 32px !important;
    }
    .ep-log-stat-card .icon-shape i {
      font-size: 0.85rem !important;
      top: 0 !important;
    }
    .ep-log-stat-card p {
      font-size: 0.65rem !important;
    }
    .ep-log-stat-card h5 {
      font-size: 0.95rem !important;
    }
  }
</style>
@endpush

@section('content')
<div class="row">
  <div class="col-12 mb-3">
    <div class="card ep-card ep-bg-mesh">
      <div class="card-body ep-page-hero p-3 p-lg-4">
        <h4 class="text-white font-weight-bolder mb-1">Manajemen Log Aktivitas</h4>
        <p class="text-white opacity-9 text-sm mb-0">Audit trail aktivitas dan keamanan sistem E-Presensi.</p>
      </div>
    </div>
  </div>
</div>

<div class="row mb-4">
  <div class="col-6 col-sm-6 col-md-4 col-xl-2 mb-3 mb-xl-0">
    <div class="card ep-card ep-log-stat-card">
      <div class="card-body p-3">
        <div class="d-flex align-items-center">
          <div>
            <p class="text-xs mb-0 text-uppercase font-weight-bold text-muted">Total Event</p>
            <h5 class="font-weight-bolder mb-0 text-dark">{{ $countTotalEvents ?? 0 }}</h5>
          </div>
          <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle ms-auto d-flex align-items-center justify-content-center">
            <i class="ni ni-calendar-grid-58 text-lg opacity-10 text-white" style="top: 0 !important;"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-6 col-sm-6 col-md-4 col-xl-2 mb-3 mb-xl-0">
    <div class="card ep-card ep-log-stat-card">
      <div class="card-body p-3">
        <div class="d-flex align-items-center">
          <div>
            <p class="text-xs mb-0 text-uppercase font-weight-bold text-muted">Berhasil Login</p>
            <h5 class="font-weight-bolder mb-0 text-dark">{{ $countLoginSuccess ?? 0 }}</h5>
          </div>
          <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle ms-auto d-flex align-items-center justify-content-center">
            <i class="ni ni-check-bold text-lg opacity-10 text-white" style="top: 0 !important;"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-6 col-sm-6 col-md-4 col-xl-2 mb-3 mb-xl-0">
    <div class="card ep-card ep-log-stat-card">
      <div class="card-body p-3">
        <div class="d-flex align-items-center">
          <div>
            <p class="text-xs mb-0 text-uppercase font-weight-bold text-muted">Gagal Login</p>
            <h5 class="font-weight-bolder mb-0 text-dark">{{ $countLoginFailed ?? 0 }}</h5>
          </div>
          <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle ms-auto d-flex align-items-center justify-content-center">
            <i class="ni ni-fat-remove text-lg opacity-10 text-white" style="top: 0 !important;"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-6 col-sm-6 col-md-4 col-xl-2 mb-3 mb-xl-0">
    <div class="card ep-card ep-log-stat-card">
      <div class="card-body p-3">
        <div class="d-flex align-items-center">
          <div>
            <p class="text-xs mb-0 text-uppercase font-weight-bold text-muted">Blokir</p>
            <h5 class="font-weight-bolder mb-0 text-dark">{{ $countBlocked ?? 0 }}</h5>
          </div>
          <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle ms-auto d-flex align-items-center justify-content-center">
            <i class="ni ni-lock-circle-open text-lg opacity-10 text-white" style="top: 0 !important;"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-6 col-sm-6 col-md-4 col-xl-2 mb-3 mb-xl-0">
    <div class="card ep-card ep-log-stat-card">
      <div class="card-body p-3">
        <div class="d-flex align-items-center">
          <div>
            <p class="text-xs mb-0 text-uppercase font-weight-bold text-muted">Logout</p>
            <h5 class="font-weight-bolder mb-0 text-dark">{{ $countLogout ?? 0 }}</h5>
          </div>
          <div class="icon icon-shape bg-gradient-info shadow-info text-center rounded-circle ms-auto d-flex align-items-center justify-content-center">
            <i class="ni ni-button-power text-lg opacity-10 text-white" style="top: 0 !important;"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-6 col-sm-6 col-md-4 col-xl-2 mb-3">
    <div class="card ep-card ep-log-stat-card">
      <div class="card-body p-3">
        <div class="d-flex align-items-center">
          <div>
            <p class="text-xs mb-0 text-uppercase font-weight-bold text-muted">IP Unik</p>
            <h5 class="font-weight-bolder mb-0 text-dark">{{ $countUniqueIps ?? 0 }}</h5>
          </div>
          <div class="icon icon-shape bg-gradient-dark shadow-dark text-center rounded-circle ms-auto d-flex align-items-center justify-content-center">
            <i class="ni ni-world-2 text-lg opacity-10 text-white" style="top: 0 !important;"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card ep-card mb-4">
      <div class="card-header pb-0 bg-transparent">
        <form action="{{ route('admin.logs') }}" method="GET" id="logs-filter-form">
          <input type="hidden" name="activity_filter" id="active-activity-filter-input" value="{{ $activityFilter ?? 'all' }}">

          <!-- Header Title & Date Filters -->
          <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-3">
            <div>
              <h6 class="mb-0 font-weight-bolder">Log Aktivitas Sistem</h6>
              <p class="text-xs text-muted mb-0">Audit trail aktivitas pengguna dan keamanan sistem E-Presensi.</p>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
              <div class="d-flex align-items-center gap-1">
                <label class="form-control-label text-xs mb-0 text-nowrap font-weight-bold">Dari:</label>
                <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}" style="max-width: 135px;" onchange="this.form.submit()">
              </div>
              <div class="d-flex align-items-center gap-1">
                <label class="form-control-label text-xs mb-0 text-nowrap font-weight-bold">Sampai:</label>
                <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}" style="max-width: 135px;" onchange="this.form.submit()">
              </div>
              @if(request('start_date') || request('end_date') || (request('activity_filter') && request('activity_filter') !== 'all'))
                <a href="{{ route('admin.logs') }}" class="btn btn-outline-secondary btn-sm mb-0 shadow-xs px-3">
                  <i class="fas fa-undo me-1"></i> Reset Filter
                </a>
              @endif
            </div>
          </div>

          <!-- 4 Category Pill Tabs -->
          @php
            $activeCategory = 'all';
            if (in_array($activityFilter ?? '', ['auth', 'login', 'login_failed', 'login_blocked', 'register', 'logout'])) $activeCategory = 'auth';
            elseif (in_array($activityFilter ?? '', ['user', 'create_user', 'update_user', 'delete_user', 'restore_user', 'approve_user', 'reject_user'])) $activeCategory = 'user';
            elseif (in_array($activityFilter ?? '', ['security', 'blacklist_add', 'blacklist_remove', 'blocked'])) $activeCategory = 'security';
            elseif (in_array($activityFilter ?? '', ['event', 'create_event', 'update_event', 'delete_event', 'submit_presence'])) $activeCategory = 'event';
          @endphp

          <!-- 4 Category Pill Buttons Bar -->
          <div class="d-flex flex-wrap gap-2 mb-3 p-1 bg-gray-100 border-radius-lg">
            <a href="{{ route('admin.logs', array_merge(request()->query(), ['activity_filter' => 'all'])) }}" 
               class="btn btn-sm mb-0 flex-fill text-center font-weight-bolder py-2 {{ $activeCategory === 'all' ? 'bg-gradient-dark text-white shadow-xs' : 'bg-transparent border-0 text-dark opacity-7 shadow-none' }}">
              <i class="ni ni-bullet-list-67 me-1 text-xs"></i> Semua Log
            </a>
            <a href="{{ route('admin.logs', array_merge(request()->query(), ['activity_filter' => 'auth'])) }}" 
               class="btn btn-sm mb-0 flex-fill text-center font-weight-bolder py-2 {{ $activeCategory === 'auth' ? 'bg-gradient-primary text-white shadow-xs' : 'bg-transparent border-0 text-dark opacity-7 shadow-none' }}">
              <i class="ni ni-key-25 me-1 text-xs {{ $activeCategory === 'auth' ? 'text-white' : 'text-primary' }}"></i> Autentikasi 
            </a>
            <a href="{{ route('admin.logs', array_merge(request()->query(), ['activity_filter' => 'user'])) }}" 
               class="btn btn-sm mb-0 flex-fill text-center font-weight-bolder py-2 {{ $activeCategory === 'user' ? 'bg-gradient-info text-white shadow-xs' : 'bg-transparent border-0 text-dark opacity-7 shadow-none' }}">
              <i class="ni ni-badge me-1 text-xs {{ $activeCategory === 'user' ? 'text-white' : 'text-info' }}"></i> Staff/Admin
            </a>
            <a href="{{ route('admin.logs', array_merge(request()->query(), ['activity_filter' => 'security'])) }}" 
               class="btn btn-sm mb-0 flex-fill text-center font-weight-bolder py-2 {{ $activeCategory === 'security' ? 'bg-gradient-danger text-white shadow-xs' : 'bg-transparent border-0 text-dark opacity-7 shadow-none' }}">
              <i class="ni ni-lock-circle-open me-1 text-xs {{ $activeCategory === 'security' ? 'text-white' : 'text-danger' }}"></i> Blacklist
            </a>
            <a href="{{ route('admin.logs', array_merge(request()->query(), ['activity_filter' => 'event'])) }}" 
               class="btn btn-sm mb-0 flex-fill text-center font-weight-bolder py-2 {{ $activeCategory === 'event' ? 'bg-gradient-success text-white shadow-xs' : 'bg-transparent border-0 text-dark opacity-7 shadow-none' }}">
              <i class="ni ni-calendar-grid-58 me-1 text-xs {{ $activeCategory === 'event' ? 'text-white' : 'text-success' }}"></i> Event Presensi
            </a>
          </div>

          <!-- Interactive Sub-Chips Bar -->
          <div class="p-2 border-radius-lg bg-white border d-flex flex-wrap align-items-center gap-1">
            <span class="text-xxs font-weight-bolder text-uppercase text-muted me-2 ms-1">Sub Filter:</span>

            <!-- Tab All Chips -->
            <div class="sub-chips-group d-flex flex-wrap gap-1 {{ $activeCategory === 'all' ? '' : 'd-none' }}" id="chips-all">
              <a href="{{ route('admin.logs', array_merge(request()->query(), ['activity_filter' => 'all'])) }}" class="btn btn-xs {{ ($activityFilter ?? 'all') === 'all' ? 'bg-gradient-dark text-white' : 'btn-outline-dark' }} mb-0">Semua Aktivitas</a>
            </div>

            <!-- Tab Auth Chips -->
            <div class="sub-chips-group d-flex flex-wrap gap-1 {{ $activeCategory === 'auth' ? '' : 'd-none' }}" id="chips-auth">
              <a href="{{ route('admin.logs', array_merge(request()->query(), ['activity_filter' => 'auth'])) }}" class="btn btn-xs {{ ($activityFilter ?? '') === 'auth' ? 'bg-gradient-primary text-white' : 'btn-outline-primary' }} mb-0">Semua Autentikasi</a>
              <a href="{{ route('admin.logs', array_merge(request()->query(), ['activity_filter' => 'login'])) }}" class="btn btn-xs {{ ($activityFilter ?? '') === 'login' ? 'bg-gradient-success text-white' : 'btn-outline-success' }} mb-0">Berhasil Login</a>
              <a href="{{ route('admin.logs', array_merge(request()->query(), ['activity_filter' => 'login_failed'])) }}" class="btn btn-xs {{ ($activityFilter ?? '') === 'login_failed' ? 'bg-gradient-warning text-white' : 'btn-outline-warning' }} mb-0">Gagal Login</a>
              <a href="{{ route('admin.logs', array_merge(request()->query(), ['activity_filter' => 'login_blocked'])) }}" class="btn btn-xs {{ ($activityFilter ?? '') === 'login_blocked' ? 'bg-gradient-danger text-white' : 'btn-outline-danger' }} mb-0">Login Terblokir</a>
              <a href="{{ route('admin.logs', array_merge(request()->query(), ['activity_filter' => 'register'])) }}" class="btn btn-xs {{ ($activityFilter ?? '') === 'register' ? 'bg-gradient-info text-white' : 'btn-outline-info' }} mb-0">Register</a>
              <a href="{{ route('admin.logs', array_merge(request()->query(), ['activity_filter' => 'logout'])) }}" class="btn btn-xs {{ ($activityFilter ?? '') === 'logout' ? 'bg-gradient-dark text-white' : 'btn-outline-dark' }} mb-0">Logout</a>
            </div>

            <!-- Tab User Chips -->
            <div class="sub-chips-group d-flex flex-wrap gap-1 {{ $activeCategory === 'user' ? '' : 'd-none' }}" id="chips-user">
              <a href="{{ route('admin.logs', array_merge(request()->query(), ['activity_filter' => 'user'])) }}" class="btn btn-xs {{ ($activityFilter ?? '') === 'user' ? 'bg-gradient-primary text-white' : 'btn-outline-primary' }} mb-0">Semua User Admin</a>
              <a href="{{ route('admin.logs', array_merge(request()->query(), ['activity_filter' => 'create_user'])) }}" class="btn btn-xs {{ ($activityFilter ?? '') === 'create_user' ? 'bg-gradient-primary text-white' : 'btn-outline-primary' }} mb-0">Tambah Akun</a>
              <a href="{{ route('admin.logs', array_merge(request()->query(), ['activity_filter' => 'update_user'])) }}" class="btn btn-xs {{ ($activityFilter ?? '') === 'update_user' ? 'bg-gradient-info text-white' : 'btn-outline-info' }} mb-0">Edit Akun</a>
              <a href="{{ route('admin.logs', array_merge(request()->query(), ['activity_filter' => 'delete_user'])) }}" class="btn btn-xs {{ ($activityFilter ?? '') === 'delete_user' ? 'bg-gradient-danger text-white' : 'btn-outline-danger' }} mb-0">Hapus Akun</a>
              <a href="{{ route('admin.logs', array_merge(request()->query(), ['activity_filter' => 'restore_user'])) }}" class="btn btn-xs {{ ($activityFilter ?? '') === 'restore_user' ? 'bg-gradient-success text-white' : 'btn-outline-success' }} mb-0">Pulihkan Akun</a>
              <a href="{{ route('admin.logs', array_merge(request()->query(), ['activity_filter' => 'approve_user'])) }}" class="btn btn-xs {{ ($activityFilter ?? '') === 'approve_user' ? 'bg-gradient-success text-white' : 'btn-outline-success' }} mb-0">Setujui Akun</a>
              <a href="{{ route('admin.logs', array_merge(request()->query(), ['activity_filter' => 'reject_user'])) }}" class="btn btn-xs {{ ($activityFilter ?? '') === 'reject_user' ? 'bg-gradient-danger text-white' : 'btn-outline-danger' }} mb-0">Tolak Akun</a>
            </div>

            <!-- Tab Security Chips -->
            <div class="sub-chips-group d-flex flex-wrap gap-1 {{ $activeCategory === 'security' ? '' : 'd-none' }}" id="chips-security">
              <a href="{{ route('admin.logs', array_merge(request()->query(), ['activity_filter' => 'security'])) }}" class="btn btn-xs {{ ($activityFilter ?? '') === 'security' ? 'bg-gradient-primary text-white' : 'btn-outline-primary' }} mb-0">Semua Keamanan</a>
              <a href="{{ route('admin.logs', array_merge(request()->query(), ['activity_filter' => 'blacklist_add'])) }}" class="btn btn-xs {{ ($activityFilter ?? '') === 'blacklist_add' ? 'bg-gradient-danger text-white' : 'btn-outline-danger' }} mb-0">Tambah Blacklist</a>
              <a href="{{ route('admin.logs', array_merge(request()->query(), ['activity_filter' => 'blacklist_remove'])) }}" class="btn btn-xs {{ ($activityFilter ?? '') === 'blacklist_remove' ? 'bg-gradient-success text-white' : 'btn-outline-success' }} mb-0">Hapus Blacklist</a>
              <a href="{{ route('admin.logs', array_merge(request()->query(), ['activity_filter' => 'blocked'])) }}" class="btn btn-xs {{ ($activityFilter ?? '') === 'blocked' ? 'bg-gradient-dark text-white' : 'btn-outline-dark' }} mb-0">Semua Pemblokiran</a>
            </div>

            <!-- Tab Event Chips -->
            <div class="sub-chips-group d-flex flex-wrap gap-1 {{ $activeCategory === 'event' ? '' : 'd-none' }}" id="chips-event">
              <a href="{{ route('admin.logs', array_merge(request()->query(), ['activity_filter' => 'event'])) }}" class="btn btn-xs {{ ($activityFilter ?? '') === 'event' ? 'bg-gradient-primary text-white' : 'btn-outline-primary' }} mb-0">Semua Event</a>
              <a href="{{ route('admin.logs', array_merge(request()->query(), ['activity_filter' => 'create_event'])) }}" class="btn btn-xs {{ ($activityFilter ?? '') === 'create_event' ? 'bg-gradient-primary text-white' : 'btn-outline-primary' }} mb-0">Buat Event</a>
              <a href="{{ route('admin.logs', array_merge(request()->query(), ['activity_filter' => 'update_event'])) }}" class="btn btn-xs {{ ($activityFilter ?? '') === 'update_event' ? 'bg-gradient-info text-white' : 'btn-outline-info' }} mb-0">Edit Event</a>
              <a href="{{ route('admin.logs', array_merge(request()->query(), ['activity_filter' => 'delete_event'])) }}" class="btn btn-xs {{ ($activityFilter ?? '') === 'delete_event' ? 'bg-gradient-danger text-white' : 'btn-outline-danger' }} mb-0">Hapus Event</a>
              <a href="{{ route('admin.logs', array_merge(request()->query(), ['activity_filter' => 'submit_presence'])) }}" class="btn btn-xs {{ ($activityFilter ?? '') === 'submit_presence' ? 'bg-gradient-success text-white' : 'btn-outline-success' }} mb-0">Presensi Tamu / Peserta</a>
            </div>
          </div>
        </form>
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
                      $badgeConfig = [
                        'login' => ['color' => 'bg-gradient-success', 'label' => 'Berhasil Login'],
                        'login_failed' => ['color' => 'bg-gradient-warning', 'label' => 'Gagal Login'],
                        'login_blocked' => ['color' => 'bg-gradient-danger', 'label' => 'Login Terblokir'],
                        'register' => ['color' => 'bg-gradient-info', 'label' => 'Register'],
                        'logout' => ['color' => 'bg-gradient-dark', 'label' => 'Logout'],

                        'create_user' => ['color' => 'bg-gradient-primary', 'label' => 'Tambah Akun'],
                        'update_user' => ['color' => 'bg-gradient-info', 'label' => 'Edit Akun'],
                        'delete_user' => ['color' => 'bg-gradient-danger', 'label' => 'Hapus Akun'],
                        'restore_user' => ['color' => 'bg-gradient-success', 'label' => 'Pulihkan Akun'],
                        'approve_user' => ['color' => 'bg-gradient-success', 'label' => 'Setujui Pendaftaran'],
                        'reject_user' => ['color' => 'bg-gradient-danger', 'label' => 'Tolak Pendaftaran'],

                        'blacklist_add' => ['color' => 'bg-gradient-danger', 'label' => 'Tambah Blacklist'],
                        'blacklist_remove' => ['color' => 'bg-gradient-success', 'label' => 'Hapus Blacklist'],

                        'create_event' => ['color' => 'bg-gradient-primary', 'label' => 'Buat Event'],
                        'update_event' => ['color' => 'bg-gradient-info', 'label' => 'Edit Event'],
                        'delete_event' => ['color' => 'bg-gradient-danger', 'label' => 'Hapus Event'],
                      ];

                      $cfg = $badgeConfig[$log->activity] ?? ['color' => 'bg-gradient-secondary', 'label' => ucfirst(str_replace('_', ' ', $log->activity))];
                    @endphp
                    <span class="badge badge-sm {{ $cfg['color'] }}">{{ $cfg['label'] }}</span>
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
  function switchCategoryTab(cat) {
    document.querySelectorAll('.nav-category-btn').forEach(btn => {
      btn.classList.remove('active', 'bg-white', 'text-dark', 'shadow-xs');
      btn.classList.add('text-muted');
      if (btn.getAttribute('data-cat') === cat) {
        btn.classList.add('active', 'bg-white', 'text-dark', 'shadow-xs');
        btn.classList.remove('text-muted');
      }
    });

    applySubChipFilter(cat);
  }

  function applySubChipFilter(val) {
    document.getElementById('active-activity-filter-input').value = val || 'all';
    document.getElementById('logs-filter-form').submit();
  }

  document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('activity-logs-table')) {
      new simpleDatatables.DataTable("#activity-logs-table", {
        searchable: true,
        fixedHeight: false,
        perPage: 10,
        perPageSelect: [5, 10, 15, 20, 25],
        labels: {
          placeholder: "Cari log aktivitas...",
          perPage: "{select} data per halaman",
          noRows: "Tidak ada log ditemukan",
          info: "Menampilkan {start} sampai {end} dari {rows} entri",
        }
      });
    }
  });
</script>
@endsection
