@extends('layouts.guest')

@section('title', 'Login Staff - E-Presensi')

@section('content')
  @php($argon = 'assets/argon-dashboard-pro-html-v2.0.5/assets')
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
            <form action="{{ route('login') }}" method="POST" role="form" class="text-start" id="login-form">
              @csrf
              <label>Email</label>
              <div class="mb-3">
                <input type="email" name="email" value="{{ old('email') }}" required class="form-control" placeholder="email@malangkota.go.id" autocomplete="email">
              </div>
              <label>Kata Sandi</label>
              <div class="input-group mb-3">
                <input type="password" name="password" id="password" required class="form-control" placeholder="Masukkan kata sandi" style="border-right: 0;">
                <span class="input-group-text bg-white cursor-pointer" id="toggle-password" style="cursor: pointer; border-left: 0;">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16" style="width: 16px; height: 16px;"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 4 8 4c2.12 0 3.879.668 5.168 1.957A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12 8 12c-2.12 0-3.879-.668-5.168-1.957A13.133 13.133 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/></svg>
                </span>
              </div>
              <button type="submit" class="btn bg-gradient-dark w-100 mt-3 mb-0 shadow">Masuk ke Dashboard</button>
              @if(config('services.recaptcha.site_key'))
                <div class="text-center mt-3">
                  <small class="text-muted" style="font-size: 11px; line-height: 1.4;">
                    Situs ini dilindungi oleh reCAPTCHA dan berlaku <a href="https://policies.google.com/privacy" target="_blank" class="text-secondary font-weight-bold">Kebijakan Privasi</a> serta <a href="https://policies.google.com/terms" target="_blank" class="text-secondary font-weight-bold">Ketentuan Layanan</a> Google.
                  </small>
                </div>
              @endif
            </form>
          </div>
          <div class="card-footer text-center pt-0">
            <p class="mb-0 text-sm">Belum terdaftar? <a href="{{ route('register') }}" class="text-primary font-weight-bold">Buat akun</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  @if(config('services.recaptcha.site_key'))
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
    <script>
      document.getElementById('login-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        if (form.checkValidity() === false) {
          form.reportValidity();
          return;
        }
        grecaptcha.ready(function() {
          grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: 'login'}).then(function(token) {
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'g-recaptcha-response';
            input.value = token;
            form.appendChild(input);
            form.submit();
          });
        });
      });
    </script>
  @endif
@endsection
