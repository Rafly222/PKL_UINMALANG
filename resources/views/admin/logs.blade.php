@extends('layouts.app')

@section('title', 'Log Aktivitas - E-Presensi Diskominfo')

@section('content')
<div class="row">
  <div class="col-12 mb-4">
    <div class="card ep-card ep-bg-mesh">
      <div class="card-body ep-page-hero d-flex flex-column justify-content-end p-4">
        <h3 class="text-white font-weight-bolder mb-1">Manajemen Log Aktivitas</h3>
        <p class="text-white opacity-8 mb-0">Audit trail aktivitas dan keamanan sistem E-Presensi.</p>
      </div>
    </div>
  </div>
</div>

<div class="row mb-4">
  <div class="col-xl-2 col-md-4 col-sm-6 mb-3 mb-xl-0">
    <div class="card ep-card">
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

  <div class="col-xl-2 col-md-4 col-sm-6 mb-3 mb-xl-0">
    <a href="{{ route('admin.logs', array_merge(request()->except('page'), ['activity_filter' => 'login'])) }}" class="text-decoration-none">
      <div class="card ep-card {{ ($activityFilter ?? '') === 'login' ? 'border border-2 border-success' : '' }}">
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
    </a>
  </div>

  <div class="col-xl-2 col-md-4 col-sm-6 mb-3 mb-xl-0">
    <a href="{{ route('admin.logs', array_merge(request()->except('page'), ['activity_filter' => 'login_failed'])) }}" class="text-decoration-none">
      <div class="card ep-card {{ ($activityFilter ?? '') === 'login_failed' ? 'border border-2 border-warning' : '' }}">
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
    </a>
  </div>

  <div class="col-xl-2 col-md-4 col-sm-6 mb-3 mb-xl-0">
    <a href="{{ route('admin.logs', array_merge(request()->except('page'), ['activity_filter' => 'blocked'])) }}" class="text-decoration-none">
      <div class="card ep-card {{ ($activityFilter ?? '') === 'blocked' ? 'border border-2 border-danger' : '' }}">
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
    </a>
  </div>

  <div class="col-xl-2 col-md-4 col-sm-6 mb-3 mb-xl-0">
    <a href="{{ route('admin.logs', array_merge(request()->except('page'), ['activity_filter' => 'logout'])) }}" class="text-decoration-none">
      <div class="card ep-card {{ ($activityFilter ?? '') === 'logout' ? 'border border-2 border-info' : '' }}">
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
    </a>
  </div>

  <div class="col-xl-2 col-md-4 col-sm-6">
    <div class="card ep-card">
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
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
          <div>
            <h6 class="mb-0">Log Aktivitas Sistem</h6>
            <p class="text-xs text-muted mb-0">Audit trail aktivitas pengguna dan keamanan sistem E-Presensi.</p>
          </div>
          <div>
            <form action="{{ route('admin.logs') }}" method="GET" class="d-flex flex-wrap align-items-center gap-2 mb-0">
              <div class="d-flex align-items-center gap-1">
                <label class="form-control-label text-xs mb-0 text-nowrap font-weight-bold">Jenis Log:</label>
                <select name="activity_filter" class="form-select form-select-sm" style="min-width: 155px;" onchange="this.form.submit()">
                  <option value="all" {{ ($activityFilter ?? 'all') === 'all' ? 'selected' : '' }}>Semua Aktivitas</option>
                  <option value="login" {{ ($activityFilter ?? '') === 'login' ? 'selected' : '' }}>Berhasil Login</option>
                  <option value="login_failed" {{ ($activityFilter ?? '') === 'login_failed' ? 'selected' : '' }}>Gagal Login</option>
                  <option value="blocked" {{ ($activityFilter ?? '') === 'blocked' ? 'selected' : '' }}>Blokir</option>
                  <option value="logout" {{ ($activityFilter ?? '') === 'logout' ? 'selected' : '' }}>Logout</option>
                </select>
              </div>
              <div class="d-flex align-items-center gap-1">
                <label class="form-control-label text-xs mb-0 text-nowrap font-weight-bold">Dari:</label>
                <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}" style="max-width: 135px;">
              </div>
              <div class="d-flex align-items-center gap-1">
                <label class="form-control-label text-xs mb-0 text-nowrap font-weight-bold">Sampai:</label>
                <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}" style="max-width: 135px;">
              </div>
              <button type="submit" class="btn bg-gradient-primary btn-sm mb-0 shadow-xs px-3">
                <i class="fas fa-search me-1"></i> Filter
              </button>
              @if(request('start_date') || request('end_date') || (request('activity_filter') && request('activity_filter') !== 'all'))
                <a href="{{ route('admin.logs') }}" class="btn btn-outline-secondary btn-sm mb-0 shadow-xs px-3">
                  <i class="fas fa-undo me-1"></i> Reset
                </a>
              @endif
            </form>
          </div>
        </div>
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
                      elseif ($log->activity === 'login_failed') $color = 'bg-gradient-warning';
                      elseif (in_array($log->activity, ['login_blocked', 'blacklist_add'])) $color = 'bg-gradient-danger';
                      elseif ($log->activity === 'logout') $color = 'bg-gradient-dark';
                      elseif ($log->activity === 'create_event') $color = 'bg-gradient-primary';
                      elseif ($log->activity === 'update_event') $color = 'bg-gradient-info';
                      elseif ($log->activity === 'delete_event') $color = 'bg-gradient-danger';
                      elseif ($log->activity === 'submit_presence') $color = 'bg-gradient-info';
                      elseif ($log->activity === 'delete_user') $color = 'bg-gradient-warning';
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
  document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('activity-logs-table')) {
      new simpleDatatables.DataTable("#activity-logs-table", {
        searchable: true,
        fixedHeight: false,
        perPage: 10,
        labels: {
          placeholder: "Cari log aktivitas...",
          perPage: "",
          noRows: "Tidak ada log ditemukan",
          info: "Menampilkan {start} sampai {end} dari {rows} entri",
        }
      });
    }
  });
</script>
@endsection
