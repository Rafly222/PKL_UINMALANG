@extends('layouts.app')

@section('title', 'Manajemen Akun - E-Presensi Diskominfo')

@section('content')
<div class="row">
  <div class="col-12 mb-4">
    <div class="card ep-card ep-bg-mesh">
      <div class="card-body ep-page-hero d-flex flex-column justify-content-end p-4">
        <h3 class="text-white font-weight-bolder mb-1">Manajemen Akun</h3>
        <p class="text-white opacity-8 mb-0">Kelola akun creator (staff/user) dan admin aplikasi.</p>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    @includeWhen(session('success') || session('warning') || session('info') || $errors->any(), 'partials.flash')
  </div>

  <div class="col-lg-4 mb-4">
    <div class="card ep-card ep-form-card">
      <div class="card-header pb-0 bg-transparent">
        <h6 class="mb-0">Tambah Akun Pengguna</h6>
        <p class="text-xs text-muted mb-0">Daftarkan akun admin atau staff baru secara langsung.</p>
      </div>
      <div class="card-body">
        <form action="{{ route('admin.users.store') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label class="form-control-label text-xs">Nama Lengkap</label>
            <input name="name" class="form-control" placeholder="Nama lengkap & gelar" required>
          </div>
          <div class="mb-3">
            <label class="form-control-label text-xs">Email Resmi</label>
            <input name="email" type="email" class="form-control" placeholder="email@malangkota.go.id" required>
          </div>
          <div class="mb-3">
            <label class="form-control-label text-xs">NIP (Nomor Induk Pegawai)</label>
            <input name="nip" class="form-control" placeholder="NIP 18 digit (opsional)">
          </div>
          <div class="mb-3">
            <label class="form-control-label text-xs">Password</label>
            <input name="password" type="password" class="form-control" placeholder="Minimal 6 karakter" required>
          </div>
          <div class="mb-3">
            <label class="form-control-label text-xs">Hak Akses (Role)</label>
            <select name="role" class="form-control">
              <option value="user">User / Staff Creator</option>
              <option value="admin">Super Admin</option>
            </select>
          </div>
          <button class="btn bg-gradient-primary w-100 mb-0 shadow">Daftarkan Pengguna</button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-8 mb-4">
    @if($pendingUsers->count() > 0)
      <div class="card ep-card mb-4 border-warning border-1 shadow-warning">
        <div class="card-header pb-0 bg-transparent">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <h6 class="text-warning mb-0 font-weight-bolder"><i class="fas fa-exclamation-triangle me-1"></i> Menunggu Persetujuan Akun</h6>
              <p class="text-xs text-muted mb-0">Terdapat pendaftaran mandiri yang membutuhkan persetujuan Anda.</p>
            </div>
            <span class="badge bg-gradient-warning">{{ $pendingUsers->count() }} Pendaftaran</span>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive p-3">
            <table class="table mb-0 align-items-center">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder ps-3">Informasi Pendaftar</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-end">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($pendingUsers as $pUser)
                  <tr>
                    <td>
                      <div class="d-flex align-items-center ps-2">
                        <div class="avatar avatar-sm bg-gradient-warning text-white rounded-circle me-3 d-flex align-items-center justify-content-center">
                          {{ strtoupper(substr($pUser->name, 0, 1)) }}
                        </div>
                        <div>
                          <h6 class="text-sm mb-0 font-weight-bold">{{ $pUser->name }}</h6>
                          <span class="text-xs text-muted">{{ $pUser->email }} · NIP: {{ $pUser->nip ?? '-' }}</span>
                        </div>
                      </div>
                    </td>
                    <td class="text-end">
                      <div class="d-flex justify-content-end gap-1">
                        <form action="{{ route('admin.users.approve', $pUser->id) }}" method="POST" class="d-inline">
                          @csrf
                          <button type="submit" class="btn btn-xs bg-gradient-success mb-0 shadow-sm">Setujui</button>
                        </form>
                        <form action="{{ route('admin.users.reject', $pUser->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tolak pendaftaran ini?')">
                          @csrf
                          <button type="submit" class="btn btn-xs btn-outline-danger mb-0 shadow-sm">Tolak</button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    @endif

    <div class="card ep-card">
      <div class="card-header pb-0 bg-transparent">
        <h6 class="mb-0">Daftar Pengguna Aktif</h6>
        <p class="text-xs text-muted mb-0">List seluruh administrator dan staff terdaftar dalam sistem.</p>
      </div>
      <div class="card-body">
        <div class="table-responsive p-3">
          <table class="table mb-0 align-items-center" id="users-table-admin">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder ps-3">Informasi user</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-end">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($users as $user)
                <tr>
                  <td>
                    <div class="d-flex align-items-center ps-2">
                      <div class="avatar avatar-sm bg-gradient-info text-white rounded-circle me-3 d-flex align-items-center justify-content-center">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                      </div>
                      <div>
                        <h6 class="text-sm mb-0">{{ $user->name }}</h6>
                        <span class="text-xs text-muted">{{ $user->email }} · <span class="badge badge-xs bg-gradient-secondary">{{ $user->role }}</span></span>
                      </div>
                    </div>
                  </td>
                  <td class="text-end">
                    <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Hapus dan blacklist user ini?')">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-xs btn-outline-danger mb-0 shadow-sm">Hapus + Blacklist</button>
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
@endsection

@section('scripts')
<script src="{{ asset('assets/argon-dashboard-pro-html-v2.0.5/assets/js/plugins/datatables.js') }}"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('users-table-admin')) {
      new simpleDatatables.DataTable("#users-table-admin", {
        searchable: true,
        fixedHeight: false,
        perPage: 5,
        labels: {
          placeholder: "Cari user...",
          perPage: "",
          noRows: "Tidak ada user ditemukan",
          info: "Menampilkan {start} sampai {end} dari {rows} entri",
        }
      });
    }
  });
</script>
@endsection
