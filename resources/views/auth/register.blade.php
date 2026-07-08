<!DOCTYPE html>
<html lang="id">

<head>
  @php($argon = 'assets/argon-dashboard-pro-html-v2.0.5/assets')
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Registrasi Staff - E-Presensi Diskominfo</title>
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset($argon . '/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset($argon . '/img/favicon.png') }}">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="{{ asset($argon . '/css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset($argon . '/css/nucleo-svg.css') }}" rel="stylesheet" />
  <script src="{{ asset($argon . '/js/42d5adcbca.js') }}" crossorigin="anonymous"></script>
  <link id="pagestyle" href="{{ asset($argon . '/css/argon-dashboard.min.css') }}" rel="stylesheet" />
</head>

<body class="bg-gray-100">
  <nav class="navbar navbar-expand-lg position-absolute top-0 z-index-3 w-100 shadow-none my-3 navbar-transparent">
    <div class="container">
      <a class="navbar-brand font-weight-bolder text-white" href="{{ route('home') }}">E-Presensi Diskominfo</a>
    </div>
  </nav>
  <main class="main-content mt-0">
    <div class="page-header align-items-start min-vh-50 pt-7 pb-9 m-3 border-radius-lg" style="background-image: linear-gradient(120deg, rgba(23, 43, 77, .88), rgba(17, 205, 239, .68)), url('{{ asset($argon . '/img/crm.jpg') }}'); background-size: cover; background-position: center;">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-7 text-center mx-auto mt-5">
            <h1 class="text-white mb-2 font-weight-bolder">Registrasi Staff Baru</h1>
            <p class="text-lead text-white opacity-8">Buat akun creator untuk menerbitkan dan mengelola event presensi.</p>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
        <div class="col-xl-5 col-lg-6 col-md-8 mx-auto">
          <div class="card shadow-lg border-0">
            <div class="card-header pb-0 text-start bg-transparent">
              <h3 class="font-weight-bolder">Buat Akun Creator</h3>
              <p class="mb-0">Gunakan identitas resmi pegawai atau staff.</p>
            </div>
            <div class="card-body">
              @includeWhen(session('success') || session('warning') || session('info') || $errors->any(), 'partials.flash')
              <form action="{{ route('register') }}" method="POST" role="form">
                @csrf
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label>NIP</label>
                    <input type="text" name="nip" required class="form-control" placeholder="18 digit NIP">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label>NIK</label>
                    <input type="text" name="nik" required class="form-control" placeholder="16 digit NIK">
                  </div>
                </div>
                <label>Nama Lengkap</label>
                <div class="mb-3">
                  <input type="text" name="name" required class="form-control" placeholder="Nama lengkap dan gelar">
                </div>
                <label>Email</label>
                <div class="mb-3">
                  <input type="email" name="email" required class="form-control" placeholder="email@malangkota.go.id">
                </div>
                <label>Kata Sandi</label>
                <div class="mb-3">
                  <input type="password" name="password" required class="form-control" placeholder="Minimal 6 karakter">
                </div>
                <button type="submit" class="btn bg-gradient-primary w-100 mt-3 mb-0 shadow">Daftar Akun Staff</button>
              </form>
            </div>
            <div class="card-footer text-center pt-0">
              <p class="mb-0 text-sm">Sudah memiliki akun? <a href="{{ route('login') }}" class="text-primary font-weight-bold">Login</a></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <script src="{{ asset($argon . '/js/core/popper.min.js') }}"></script>
  <script src="{{ asset($argon . '/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset($argon . '/js/argon-dashboard.min.js') }}"></script>
</body>

</html>
