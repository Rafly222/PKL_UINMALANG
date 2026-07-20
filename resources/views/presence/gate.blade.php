<!DOCTYPE html>
<html lang="id">

<head>
  @php($argon = 'assets/argon-dashboard-pro-html-v2.0.5/assets')
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Gate Event Privat - E-Presensi</title>
  
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset($argon . '/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset($argon . '/img/favicon.png') }}">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="{{ asset($argon . '/css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset($argon . '/css/nucleo-svg.css') }}" rel="stylesheet" />
  <script src="{{ asset($argon . '/js/42d5adcbca.js') }}" crossorigin="anonymous"></script>
  <link id="pagestyle" href="{{ asset($argon . '/css/argon-dashboard.min.css') }}" rel="stylesheet" />

  <style>
    :root {
      --ep-primary: #5e72e4;
      --ep-dark: #172b4d;
      --ep-soft: #f6f9fc;
    }

    body {
      color: #344767;
      background: #f8f9fe;
    }

    .ep-card {
      border: 0;
      border-radius: 1rem;
      box-shadow: 0 14px 35px rgba(50, 50, 93, .08), 0 4px 12px rgba(0, 0, 0, .05);
      background: #fff;
    }

    .ep-card:hover {
      box-shadow: 0 18px 45px rgba(50, 50, 93, .12), 0 7px 18px rgba(0, 0, 0, .07);
    }
    .grecaptcha-badge {
      visibility: hidden;
    }

    @media (min-width: 992px) {
      body {
        height: 100vh;
        overflow: hidden;
      }
      .main-content {
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
      }
      .container {
        max-width: 500px !important;
        width: 90vw !important;
      }
    }
  </style>
</head>

<body class="bg-gray-100">
  <div class="min-height-300 bg-primary position-absolute w-100" style="height: 35vh !important; min-height: unset !important;"></div>

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <div class="container py-4">
      <div class="row justify-content-center">
        <div class="col-12">
          <div class="card ep-card overflow-hidden">
            <div class="card-header bg-gradient-dark text-center p-4">
              <div class="icon icon-shape bg-white shadow text-center border-radius-lg mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                <i class="ni ni-key-25 text-dark text-lg"></i>
              </div>
              <h4 class="text-white font-weight-bolder mb-1">Portal Kegiatan Privat</h4>
              <p class="text-white opacity-8 text-sm mb-0">{{ $event->name }}</p>
            </div>
            <div class="card-body p-4 text-center">
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
            @if(config('services.recaptcha.site_key'))
              <div class="text-center mt-3">
                <small class="text-muted" style="font-size: 11px; line-height: 1.4;">
                  Situs ini dilindungi oleh reCAPTCHA dan berlaku <a href="https://policies.google.com/privacy" target="_blank" class="text-secondary font-weight-bold">Kebijakan Privasi</a> serta <a href="https://policies.google.com/terms" target="_blank" class="text-secondary font-weight-bold">Ketentuan Layanan</a> Google.
                </small>
              </div>
            @endif
          </form>
        @endif
      </div>
    </div>
  </div>
</div>

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
</body>
</html>
