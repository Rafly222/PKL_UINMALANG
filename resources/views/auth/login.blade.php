<!DOCTYPE html>
<html lang="id">

<head>
  @php($argon = 'assets/argon-dashboard-pro-html-v2.0.5/assets')
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Login Staff - E-Presensi Diskominfo</title>
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset($argon . '/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset($argon . '/img/favicon.png') }}">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="{{ asset($argon . '/css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset($argon . '/css/nucleo-svg.css') }}" rel="stylesheet" />
  <script src="{{ asset($argon . '/js/42d5adcbca.js') }}" crossorigin="anonymous"></script>
  <link id="pagestyle" href="{{ asset($argon . '/css/argon-dashboard.min.css') }}" rel="stylesheet" />
  <style>
    .input-group:focus-within {
      box-shadow: 0 0 0 .2rem rgba(94, 114, 228, .15) !important;
      border-radius: 0.5rem !important;
    }
    .input-group:focus-within .form-control,
    .input-group:focus-within .input-group-text {
      border-color: #5e72e4 !important;
      box-shadow: none !important;
    }
  </style>
</head>

<body class="bg-gray-100">
  <nav class="navbar navbar-expand-lg position-absolute top-0 z-index-3 w-100 shadow-none my-3 navbar-transparent">
    <div class="container">
      <a class="navbar-brand font-weight-bolder text-white" href="{{ route('home') }}">E-Presensi Diskominfo</a>
    </div>
  </nav>
  <main class="main-content mt-0">
    <div class="page-header align-items-start min-vh-50 pt-7 pb-9 m-3 border-radius-lg" style="background-image: linear-gradient(120deg, rgba(23, 43, 77, .88), rgba(94, 114, 228, .74)), url('{{ asset($argon . '/img/office-dark.jpg') }}'); background-size: cover; background-position: center;">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-6 text-center mx-auto mt-5">
            <h1 class="text-white mb-2 font-weight-bolder">Portal Masuk Staff</h1>
            <p class="text-lead text-white opacity-8">Masuk untuk mengelola event presensi Diskominfo Kota Malang.</p>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
        <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
          <div class="card shadow-lg border-0">
            <div class="card-header pb-0 text-start bg-transparent">
              <h3 class="font-weight-bolder">Selamat Datang</h3>
              <p class="mb-0">Gunakan email dan kata sandi akun staff.</p>
            </div>
            <div class="card-body">
              @includeWhen(session('success') || session('warning') || session('info') || $errors->any(), 'partials.flash')
              <form action="{{ route('login') }}" method="POST" role="form" class="text-start">
                @csrf
                <label>Email</label>
                <div class="mb-3">
                  <input type="email" name="email" required class="form-control" placeholder="email@malangkota.go.id">
                </div>
                <label>Kata Sandi</label>
                <div class="input-group mb-3">
                  <input type="password" name="password" id="password" required class="form-control" placeholder="Masukkan kata sandi" style="border-right: 0;">
                  <span class="input-group-text bg-white cursor-pointer" id="toggle-password" style="cursor: pointer; border-left: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16" style="width: 16px; height: 16px;"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 4 8 4c2.12 0 3.879.668 5.168 1.957A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12 8 12c-2.12 0-3.879-.668-5.168-1.957A13.133 13.133 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/></svg>
                  </span>
                </div>
                <button type="submit" class="btn bg-gradient-dark w-100 mt-3 mb-0 shadow">Masuk ke Dashboard</button>
              </form>
            </div>
            <div class="card-footer text-center pt-0">
              <p class="mb-0 text-sm">Belum terdaftar? <a href="{{ route('register') }}" class="text-primary font-weight-bold">Buat akun</a></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <script src="{{ asset($argon . '/js/core/popper.min.js') }}"></script>
  <script src="{{ asset($argon . '/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset($argon . '/js/argon-dashboard.min.js') }}"></script>
  <script>
    const toggleBtn = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('password');

    const eyeSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16" style="width: 16px; height: 16px;"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 4 8 4c2.12 0 3.879.668 5.168 1.957A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12 8 12c-2.12 0-3.879-.668-5.168-1.957A13.133 13.133 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/></svg>`;
    const eyeSlashSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16" style="width: 16px; height: 16px;"><path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a8.09 8.09 0 0 0-2.312.333L9.73 6.868c.403-.345.925-.535 1.47-.535 1.38 0 2.5 1.12 2.5 2.5 0 .545-.19 1.067-.535 1.47l2.194 2.193zm-5.09-5.09L10 8.357A2.49 2.49 0 0 0 8.005 6.5a2.49 2.49 0 0 0-2.464 1.857l-1.815-1.815C4.857 6.136 6.326 5.5 8 5.5z"/><path d="M8 12c-2.12 0-3.879-.668-5.168-1.957a13.133 13.133 0 0 1-1.66-2.043C1.22 7.712 1.9 6.837 2.73 6.012L1.082 4.364A8.907 8.907 0 0 0 0 8s3 5.5 8 5.5a8.09 8.09 0 0 0 2.312-.333L8.641 11.51c-.403.345-.925.535-1.47.535z"/><path d="M12.42 13.482a8.238 8.238 0 0 1-2.585.836l1.246 1.246a.5.5 0 0 0 .707-.707l-1.368-1.375z"/><path d="M5.433 11.104A3.5 3.5 0 0 1 4.5 8a3.5 3.5 0 0 1 1.037-2.433l-1.037-1.037a5 5 0 0 0-.25 5.576l1.183 1.183-.003-.186zm6.825 2.193a5 5 0 0 0 .25-5.576l-1.183-1.183.003.186a3.5 3.5 0 0 1 1.037 3.03l1.183 1.183-.29-.64z"/></svg>`;

    toggleBtn.addEventListener('click', function() {
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleBtn.innerHTML = eyeSlashSvg;
      } else {
        passwordInput.type = 'password';
        toggleBtn.innerHTML = eyeSvg;
      }
    });
  </script>
</body>

</html>
