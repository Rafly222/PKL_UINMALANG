@extends('layouts.app')

@section('title', 'Manajemen Akun - E-Presensi Diskominfo')

@section('content')
<div class="row">
  <div class="col-12 mb-3">
    <div class="card ep-card ep-bg-mesh">
      <div class="card-body ep-page-hero p-3 p-lg-4">
        <h4 class="text-white font-weight-bolder mb-1">Manajemen Akun</h4>
        <p class="text-white opacity-9 text-sm mb-0">Kelola akun creator (staff/user) dan admin aplikasi.</p>
      </div>
    </div>
  </div>
<div class="row mb-4">
  <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
    <div class="card ep-card">
      <div class="card-body p-3">
        <div class="d-flex align-items-center">
          <div>
            <p class="text-xs mb-0 text-uppercase font-weight-bold text-muted">User Aktif</p>
            <h4 class="font-weight-bolder mb-0 text-dark">{{ $countActive ?? 0 }}</h4>
          </div>
          <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle ms-auto d-flex align-items-center justify-content-center">
            <i class="ni ni-badge text-lg opacity-10 text-white" style="top: 0 !important;"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
    <div class="card ep-card">
      <div class="card-body p-3">
        <div class="d-flex align-items-center">
          <div>
            <p class="text-xs mb-0 text-uppercase font-weight-bold text-muted">User Pending</p>
            <h4 class="font-weight-bolder mb-0 text-dark">{{ $countPending ?? 0 }}</h4>
          </div>
          <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle ms-auto d-flex align-items-center justify-content-center">
            <i class="ni ni-time-alarm text-lg opacity-10 text-white" style="top: 0 !important;"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
    <div class="card ep-card">
      <div class="card-body p-3">
        <div class="d-flex align-items-center">
          <div>
            <p class="text-xs mb-0 text-uppercase font-weight-bold text-muted">User Terblokir</p>
            <h4 class="font-weight-bolder mb-0 text-dark">{{ $countBlacklisted ?? 0 }}</h4>
          </div>
          <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle ms-auto d-flex align-items-center justify-content-center">
            <i class="ni ni-lock-circle-open text-lg opacity-10 text-white" style="top: 0 !important;"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6">
    <div class="card ep-card">
      <div class="card-body p-3">
        <div class="d-flex align-items-center">
          <div>
            <p class="text-xs mb-0 text-uppercase font-weight-bold text-muted">User Terhapus</p>
            <h4 class="font-weight-bolder mb-0 text-dark">{{ $countTrashed ?? 0 }}</h4>
          </div>
          <div class="icon icon-shape bg-gradient-secondary shadow-secondary text-center rounded-circle ms-auto d-flex align-items-center justify-content-center">
            <i class="ni ni-basket text-lg opacity-10 text-white" style="top: 0 !important;"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    @includeWhen(session('success') || session('warning') || session('info') || $errors->any(), 'partials.flash')
  </div>

  <div class="col-12 mb-4">
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
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder ps-3">Nama</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder ps-2">Email</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder ps-2">NIP</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-end pe-3">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($pendingUsers as $pUser)
                  <tr>
                    <td class="ps-3">
                      <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm bg-gradient-warning text-white rounded-circle me-3 d-flex align-items-center justify-content-center">
                          {{ strtoupper(substr($pUser->name, 0, 1)) }}
                        </div>
                        <h6 class="text-sm mb-0 font-weight-bold">{{ $pUser->name }}</h6>
                      </div>
                    </td>
                    <td>
                      <span class="text-sm text-secondary font-weight-bold">{{ $pUser->email }}</span>
                    </td>
                    <td>
                      <span class="text-sm text-secondary font-weight-bold">{{ $pUser->nip ?? '-' }}</span>
                    </td>
                    <td class="text-end pe-3">
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
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-3">
          <div>
            <h6 class="mb-0 font-weight-bolder">Daftar Pengguna</h6>
            <p class="text-xs text-muted mb-0">List seluruh administrator dan staff terdaftar dalam sistem.</p>
          </div>
          <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-sm bg-gradient-primary mb-0 shadow-sm" data-bs-toggle="modal" data-bs-target="#createAccountModal">
              <i class="fas fa-user-plus me-1"></i> Tambah Akun
            </button>
          </div>
        </div>

        <!-- Category Pill Buttons Bar for User Filters (Like Logs Page) -->
        <div class="d-flex flex-wrap gap-2 mb-3 p-1 bg-gray-100 border-radius-lg">
          <a href="{{ route('admin.users', ['status_filter' => 'all']) }}" 
             class="btn btn-sm mb-0 flex-fill text-center font-weight-bolder py-2 {{ ($filter ?? 'all') === 'all' ? 'bg-gradient-dark text-white shadow-xs' : 'bg-transparent border-0 text-dark opacity-7 shadow-none' }}">
            <i class="ni ni-bullet-list-67 me-1 text-xs"></i> Semua Akun ({{ ($countActive ?? 0) + ($countPending ?? 0) + ($countBlacklisted ?? 0) + ($countTrashed ?? 0) }})
          </a>
          <a href="{{ route('admin.users', ['status_filter' => 'approved']) }}" 
             class="btn btn-sm mb-0 flex-fill text-center font-weight-bolder py-2 {{ ($filter ?? '') === 'approved' ? 'bg-gradient-success text-white shadow-xs' : 'bg-transparent border-0 text-dark opacity-7 shadow-none' }}">
            <i class="ni ni-badge me-1 text-xs {{ ($filter ?? '') === 'approved' ? 'text-white' : 'text-success' }}"></i> Akun Aktif ({{ $countActive ?? 0 }})
          </a>
          <a href="{{ route('admin.users', ['status_filter' => 'pending']) }}" 
             class="btn btn-sm mb-0 flex-fill text-center font-weight-bolder py-2 {{ ($filter ?? '') === 'pending' ? 'bg-gradient-warning text-white shadow-xs' : 'bg-transparent border-0 text-dark opacity-7 shadow-none' }}">
            <i class="ni ni-time-alarm me-1 text-xs {{ ($filter ?? '') === 'pending' ? 'text-white' : 'text-warning' }}"></i> Menunggu Persetujuan ({{ $countPending ?? 0 }})
          </a>
          <a href="{{ route('admin.users', ['status_filter' => 'blacklisted']) }}" 
             class="btn btn-sm mb-0 flex-fill text-center font-weight-bolder py-2 {{ ($filter ?? '') === 'blacklisted' ? 'bg-gradient-danger text-white shadow-xs' : 'bg-transparent border-0 text-dark opacity-7 shadow-none' }}">
            <i class="ni ni-lock-circle-open me-1 text-xs {{ ($filter ?? '') === 'blacklisted' ? 'text-white' : 'text-danger' }}"></i> Akun Terblokir ({{ $countBlacklisted ?? 0 }})
          </a>
          <a href="{{ route('admin.users', ['status_filter' => 'trashed']) }}" 
             class="btn btn-sm mb-0 flex-fill text-center font-weight-bolder py-2 {{ ($filter ?? '') === 'trashed' ? 'bg-gradient-secondary text-white shadow-xs' : 'bg-transparent border-0 text-dark opacity-7 shadow-none' }}">
            <i class="ni ni-basket me-1 text-xs {{ ($filter ?? '') === 'trashed' ? 'text-white' : 'text-secondary' }}"></i> Akun Terhapus ({{ $countTrashed ?? 0 }})
          </a>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive p-3">
          <table class="table mb-0 align-items-center" id="users-table-admin">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder ps-3">Nama</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder ps-2">Email</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder ps-2">NIP</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder ps-2">Hak Akses</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder ps-2">Status</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-end pe-3">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($users as $user)
                <?php 
                  $isTrashed = method_exists($user, 'trashed') && $user->trashed();
                  $isBlacklisted = !$isTrashed && !empty($user->nip) && in_array($user->nip, $blacklistedNips);
                  $isPending = !$isTrashed && $user->status === 'pending';
                ?>
                <tr>
                  <td class="ps-3">
                    <div class="d-flex align-items-center">
                      <div class="avatar avatar-sm {{ $isTrashed ? 'bg-gradient-secondary' : ($isBlacklisted ? 'bg-gradient-danger' : ($isPending ? 'bg-gradient-warning' : 'bg-gradient-info')) }} text-white rounded-circle me-3 d-flex align-items-center justify-content-center">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                      </div>
                      <h6 class="text-sm mb-0 font-weight-bold {{ $isTrashed ? 'text-decoration-line-through text-muted' : '' }}">{{ $user->name }}</h6>
                    </div>
                  </td>
                  <td>
                    <span class="text-sm text-secondary font-weight-bold">{{ $user->email }}</span>
                  </td>
                  <td>
                    <span class="text-sm text-secondary font-weight-bold">{{ $user->nip ?? '-' }}</span>
                  </td>
                  <td>
                    <span class="badge badge-xs bg-gradient-secondary">{{ $user->role }}</span>
                  </td>
                  <td>
                    @if($isTrashed)
                      <span class="badge badge-xs bg-gradient-secondary">Terhapus</span>
                    @elseif($isBlacklisted)
                      <span class="badge badge-xs bg-gradient-danger">Terblokir</span>
                    @elseif($isPending)
                      <span class="badge badge-xs bg-gradient-warning">Pending</span>
                    @else
                      <span class="badge badge-xs bg-gradient-success">Aktif</span>
                    @endif
                  </td>
                  <td class="text-end pe-3">
                    <div class="d-flex justify-content-end gap-1">
                      @if($isTrashed)
                        <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Pulihkan kembali akun terhapus ini?')">
                          @csrf
                          <button type="submit" class="btn btn-xs bg-gradient-success text-white mb-0 shadow-sm">Pulihkan Akun</button>
                        </form>
                      @else
                        <button class="btn btn-xs btn-outline-info mb-0 shadow-sm" data-bs-toggle="modal" data-bs-target="#previewUserModal-{{ $user->id }}">Preview</button>
                        <button class="btn btn-xs btn-outline-warning mb-0 shadow-sm" data-bs-toggle="modal" data-bs-target="#editUserModal-{{ $user->id }}">Edit</button>
                        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus akun user ini?')">
                          @csrf
                          @method('DELETE')
                          <button class="btn btn-xs btn-outline-danger mb-0 shadow-sm">Hapus</button>
                        </form>
                        @if($isBlacklisted)
                          <form action="{{ route('admin.users.unblock', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Pulihkan akses akun pengguna ini?')">
                            @csrf
                            <button type="submit" class="btn btn-xs bg-gradient-success text-white mb-0 shadow-sm">Pulihkan</button>
                          </form>
                        @else
                          <form action="{{ route('admin.users.block', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Blokir akses identitas NIP akun pengguna ini?')">
                            @csrf
                            <button type="submit" class="btn btn-xs bg-gradient-danger text-white mb-0 shadow-sm" {{ empty($user->nip) ? 'disabled title="NIP tidak tersedia"' : '' }}>Blokir</button>
                          </form>
                        @endif
                      @endif
                    </div>

                    <!-- Modal Preview User -->
                    <div class="modal fade" id="previewUserModal-{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="previewUserModalLabel-{{ $user->id }}" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content border-0 ep-card">
                          <div class="modal-header bg-gradient-info text-white">
                            <h5 class="modal-title font-weight-bolder text-white" id="previewUserModalLabel-{{ $user->id }}">Detail Profil Pengguna</h5>
                            <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body text-start p-4">
                            <div class="text-center mb-4">
                              <div class="avatar avatar-xl {{ $isBlacklisted ? 'bg-gradient-danger' : 'bg-gradient-info' }} text-white rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center fs-3 font-weight-bold" style="width: 64px; height: 64px;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                              </div>
                              <h5 class="font-weight-bolder mb-0">{{ $user->name }}</h5>
                              <p class="text-xs text-muted mb-0">{{ $user->email }}</p>
                            </div>
                            <ul class="list-group list-group-flush border-radius-lg">
                              <li class="list-group-item d-flex justify-content-between align-items-center px-0 text-sm">
                                <span class="text-muted font-weight-bold">Nomor Induk Pegawai (NIP):</span>
                                <span class="font-weight-bolder text-dark">{{ $user->nip ?? '-' }}</span>
                              </li>
                              <li class="list-group-item d-flex justify-content-between align-items-center px-0 text-sm">
                                <span class="text-muted font-weight-bold">Hak Akses (Role):</span>
                                <span class="badge bg-gradient-secondary text-uppercase">{{ $user->role }}</span>
                              </li>
                              <li class="list-group-item d-flex justify-content-between align-items-center px-0 text-sm">
                                <span class="text-muted font-weight-bold">Status Keaktifan:</span>
                                @if($isBlacklisted)
                                  <span class="badge bg-gradient-danger">Terblokir (Blacklist)</span>
                                @else
                                  <span class="badge bg-gradient-success">Aktif (Approved)</span>
                                @endif
                              </li>
                              <li class="list-group-item d-flex justify-content-between align-items-center px-0 text-sm">
                                <span class="text-muted font-weight-bold">Tanggal Pendaftaran:</span>
                                <span class="font-weight-bold text-dark">{{ $user->created_at ? $user->created_at->format('d M Y H:i') : '-' }}</span>
                              </li>
                            </ul>
                            <div class="text-end mt-4">
                              <button type="button" class="btn btn-outline-secondary mb-0 shadow-sm" data-bs-dismiss="modal">Tutup</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Modal Edit User -->
                    <div class="modal fade" id="editUserModal-{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel-{{ $user->id }}" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content border-0 ep-card">
                          <div class="modal-header">
                            <h5 class="modal-title font-weight-bolder text-start" id="editUserModalLabel-{{ $user->id }}">Edit Akun Pengguna</h5>
                            <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body text-start">
                            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                              @csrf
                              @method('PUT')
                              <div class="mb-3">
                                <label class="form-control-label text-xs">Nama Lengkap</label>
                                <input name="name" class="form-control" value="{{ $user->name }}" required>
                              </div>
                              <div class="mb-3">
                                <label class="form-control-label text-xs">Email Resmi</label>
                                <input name="email" type="email" class="form-control" value="{{ $user->email }}" required>
                              </div>
                              <div class="mb-3">
                                <label class="form-control-label text-xs">NIP (Nomor Induk Pegawai)</label>
                                <input name="nip" class="form-control" value="{{ $user->nip }}" placeholder="NIP (opsional)">
                              </div>
                              <div class="mb-3">
                                <label class="form-control-label text-xs">Password Baru (Kosongkan jika tidak ingin diubah)</label>
                                <input name="password" type="password" class="form-control" placeholder="Minimal 6 karakter (opsional)">
                              </div>
                              <div class="mb-3">
                                <label class="form-control-label text-xs">Hak Akses (Role)</label>
                                <select name="role" class="form-control">
                                  <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User / Staff Creator</option>
                                  <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Super Admin</option>
                                </select>
                              </div>
                              <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="button" class="btn btn-outline-secondary mb-0 shadow-sm" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn bg-gradient-primary mb-0 shadow-sm">Simpan Perubahan</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
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

<!-- Modal Tambah Akun -->
<div class="modal fade" id="createAccountModal" tabindex="-1" role="dialog" aria-labelledby="createAccountModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 ep-card">
      <div class="modal-header">
        <h5 class="modal-title font-weight-bolder" id="createAccountModalLabel">Tambah Akun Pengguna</h5>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-start">
        <form action="{{ route('admin.users.store') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label class="form-control-label text-xs">Nama Lengkap</label>
            <input name="name" class="form-control" placeholder="Nama lengkap & gelar" required value="{{ old('name') }}">
          </div>
          <div class="mb-3">
            <label class="form-control-label text-xs">Email Resmi</label>
            <input name="email" type="email" class="form-control" placeholder="email@malangkota.go.id" required value="{{ old('email') }}">
          </div>
          <div class="mb-3">
            <label class="form-control-label text-xs">NIP (Nomor Induk Pegawai)</label>
            <input name="nip" class="form-control" placeholder="NIP (opsional)" value="{{ old('nip') }}">
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
          <div class="d-flex justify-content-end gap-2 mt-4">
            <button type="button" class="btn btn-outline-secondary mb-0 shadow-sm" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn bg-gradient-primary mb-0 shadow-sm">Daftarkan Pengguna</button>
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
  document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('users-table-admin')) {
      const dataTable = new simpleDatatables.DataTable("#users-table-admin", {
        searchable: true,
        fixedHeight: false,
        perPage: 5,
        perPageSelect: [5, 10, 15, 20, 25],
        labels: {
          placeholder: "Cari user...",
          perPage: "{select} data per halaman",
          noRows: "Tidak ada user ditemukan",
          info: "Menampilkan {start} sampai {end} dari {rows} entri",
        }
      });

      // Pindahkan input "Cari user..." ke samping Filter Status pada header kartu
      setTimeout(() => {
        const dtSearch = document.querySelector('.datatable-search');
        const filterContainer = document.getElementById('header-filter-container');
        if (dtSearch && filterContainer) {
          filterContainer.appendChild(dtSearch);
          
          // Bersihkan sisa margin datatable-top jika kosong
          const dtTop = document.querySelector('.datatable-top');
          if (dtTop && dtTop.children.length === 0) {
            dtTop.style.display = 'none';
          }
        }
      }, 50);
    }

    @if($errors->any())
      const createModalElement = document.getElementById('createAccountModal');
      if (createModalElement) {
        const createModal = new bootstrap.Modal(createModalElement);
        createModal.show();
      }
    @endif
  });
</script>
@endsection
