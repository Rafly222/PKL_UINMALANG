@extends('layouts.app')

@section('title', 'Log Aktivitas - E-Presensi Diskominfo')

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

<div class="row">
  <div class="col-12">
    <div class="card ep-card mb-4">
      <div class="card-header pb-0 bg-transparent">
        <h6 class="mb-0">Log Aktivitas Sistem</h6>
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
                      elseif ($log->activity === 'update_event') $color = 'bg-gradient-info';
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
