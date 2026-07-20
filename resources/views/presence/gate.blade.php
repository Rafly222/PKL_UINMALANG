@extends('layouts.app')

@section('title', 'Gate Event Privat - E-Presensi')
@section('breadcrumb', 'Gate Privat')
@section('page-title', 'Akses Event')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-5 col-md-8">
    <div class="card ep-card overflow-hidden">
      <div class="card-header bg-gradient-dark text-center p-4">
        <div class="icon icon-shape bg-white shadow text-center border-radius-lg mx-auto mb-3">
          <i class="ni ni-key-25 text-dark"></i>
        </div>
        <h4 class="text-white font-weight-bolder mb-1">Portal Kegiatan Privat</h4>
        <p class="text-white opacity-8 text-sm mb-0">{{ $event->name }}</p>
      </div>
      <div class="card-body p-4">
        @includeWhen(session('warning') || $errors->any(), 'partials.flash')

        @if(isset($error))
          <div class="alert alert-danger text-white text-center shadow">{{ $error }}</div>
          <div class="text-center">
            <a href="{{ route('home') }}" class="btn btn-outline-secondary mb-0">Kembali ke Beranda</a>
          </div>
        @else
          <p class="text-sm text-muted text-center">Masukkan password event dari penyelenggara untuk membuka formulir presensi.</p>
          <form action="{{ route('presence.gate', $event->uuid) }}" method="POST" id="gate-form">
            @csrf
            <div class="input-group mb-3">
              <input type="password" name="password" id="gate-password" class="form-control text-center" placeholder="Password event" required style="border-right: 0;">
              <span class="input-group-text bg-white cursor-pointer" id="toggle-gate-password" style="cursor: pointer; border-left: 0;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16" style="width: 16px; height: 16px;"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 4 8 4c2.12 0 3.879.668 5.168 1.957A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12 8 12c-2.12 0-3.879-.668-5.168-1.957A13.133 13.133 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/></svg>
              </span>
            </div>
            <button type="submit" class="btn bg-gradient-primary w-100 mb-0 shadow">Buka Formulir</button>
          </form>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  const toggleGateBtn = document.getElementById('toggle-gate-password');
  const gatePasswordInput = document.getElementById('gate-password');

  const eyeSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16" style="width: 16px; height: 16px;"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 4 8 4c2.12 0 3.879.668 5.168 1.957A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12 8 12c-2.12 0-3.879-.668-5.168-1.957A13.133 13.133 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/></svg>`;
  const eyeSlashSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16" style="width: 16px; height: 16px;"><path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a8.09 8.09 0 0 0-2.312.333L9.73 6.868c.403-.345.925-.535 1.47-.535 1.38 0 2.5 1.12 2.5 2.5 0 .545-.19 1.067-.535 1.47l2.194 2.193zm-5.09-5.09L10 8.357A2.49 2.49 0 0 0 8.005 6.5a2.49 2.49 0 0 0-2.464 1.857l-1.815-1.815C4.857 6.136 6.326 5.5 8 5.5z"/><path d="M8 12c-2.12 0-3.879-.668-5.168-1.957a13.133 13.133 0 0 1-1.66-2.043C1.22 7.712 1.9 6.837 2.73 6.012L1.082 4.364A8.907 8.907 0 0 0 0 8s3 5.5 8 5.5a8.09 8.09 0 0 0 2.312-.333L8.641 11.51c-.403.345-.925.535-1.47.535z"/><path d="M12.42 13.482a8.238 8.238 0 0 1-2.585.836l1.246 1.246a.5.5 0 0 0 .707-.707l-1.368-1.375z"/><path d="M5.433 11.104A3.5 3.5 0 0 1 4.5 8a3.5 3.5 0 0 1 1.037-2.433l-1.037-1.037a5 5 0 0 0-.25 5.576l1.183 1.183-.003-.186zm6.825 2.193a5 5 0 0 0 .25-5.576l-1.183-1.183.003.186a3.5 3.5 0 0 1 1.037 3.03l1.183 1.183-.29-.64z"/></svg>`;

  toggleGateBtn.addEventListener('click', function() {
    if (gatePasswordInput.type === 'password') {
      gatePasswordInput.type = 'text';
      toggleGateBtn.innerHTML = eyeSlashSvg;
    } else {
      gatePasswordInput.type = 'password';
      toggleGateBtn.innerHTML = eyeSvg;
    }
  });
</script>

@if(config('services.recaptcha.site_key'))
  <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
  <script>
    document.getElementById('gate-form').addEventListener('submit', function(e) {
      e.preventDefault();
      const form = this;
      if (form.checkValidity() === false) {
        form.reportValidity();
        return;
      }
      grecaptcha.ready(function() {
        grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: 'gate'}).then(function(token) {
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
