@extends('layouts.app')

@section('title', 'Manajemen Blacklist - E-Presensi Diskominfo')

@section('content')
<div class="row">
  <div class="col-12 mb-4">
    <div class="card ep-card ep-bg-mesh">
      <div class="card-body ep-page-hero d-flex flex-column justify-content-end p-4">
        <h3 class="text-white font-weight-bolder mb-1">Manajemen Blacklist</h3>
        <p class="text-white opacity-8 mb-0">Kelola identitas NIP/NIK yang diblokir dari sistem.</p>
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
        <h6 class="mb-0">Blokir Identitas Manual</h6>
        <p class="text-xs text-muted mb-0">Blokir identitas baru agar tidak bisa mendaftar atau absen.</p>
      </div>
      <div class="card-body">
        <form action="{{ route('admin.blacklist.store') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label class="form-control-label text-xs">NIP (Nomor Induk Pegawai) <span class="text-danger">*</span></label>
            <input name="nip" class="form-control" placeholder="Masukkan 18 digit NIP" required>
          </div>
          <button class="btn bg-gradient-dark w-100 mb-0 shadow">Blokir Identitas</button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-8 mb-4">
    <div class="card ep-card h-100">
      <div class="card-header pb-0 bg-transparent">
        <h6 class="mb-0">Daftar Identitas Terblokir</h6>
        <p class="text-xs text-muted mb-0">List NIP yang saat ini diblokir dari sistem E-Presensi.</p>
      </div>
      <div class="card-body">
        <div class="table-responsive p-3">
          <table class="table mb-0 align-items-center" id="blacklists-table-admin">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder ps-3">Identitas terblokir</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-end">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($blacklists as $blacklist)
                <tr>
                  <td class="text-sm ps-3">
                    <span class="badge bg-gradient-warning me-2">Terblokir</span>
                    <strong>NIP:</strong> {{ $blacklist->nip ?? '-' }}
                  </td>
                  <td class="text-end">
                    <form action="{{ route('admin.blacklist.delete', $blacklist->id) }}" method="POST" onsubmit="return confirm('Pulihkan identitas ini?')">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-xs btn-outline-secondary mb-0 shadow-sm">Pulihkan</button>
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
    if (document.getElementById('blacklists-table-admin')) {
      new simpleDatatables.DataTable("#blacklists-table-admin", {
        searchable: true,
        fixedHeight: false,
        perPage: 5,
        labels: {
          placeholder: "Cari blacklist...",
          perPage: "",
          noRows: "Tidak ada data blacklist ditemukan",
          info: "Menampilkan {start} sampai {end} dari {rows} entri",
        }
      });
    }
  });
</script>
@endsection
