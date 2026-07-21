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
      background: #0f172a;
      overflow-x: hidden;
      min-height: 100vh;
      position: relative;
    }

    /* Aurora Waves Background Canvas */
    .ep-aurora-bg {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      z-index: 0;
      background: radial-gradient(circle at 50% 30%, #1a2b56 0%, #0b1329 70%, #050a17 100%);
      overflow: hidden;
    }

    .ep-wave-container {
      position: absolute;
      width: 200%;
      height: 100%;
      bottom: 0;
      left: -50%;
      pointer-events: none;
      opacity: 0.85;
    }

    .ep-wave {
      position: absolute;
      bottom: 0;
      width: 100%;
      height: 65vh;
      will-change: transform;
    }

    .ep-wave-1 {
      fill: rgba(94, 114, 228, 0.35);
      animation: epWaveMove1 14s ease-in-out infinite alternate;
    }

    .ep-wave-2 {
      fill: rgba(17, 205, 239, 0.28);
      animation: epWaveMove2 18s ease-in-out infinite alternate;
    }

    .ep-wave-3 {
      fill: rgba(130, 94, 228, 0.22);
      animation: epWaveMove3 22s ease-in-out infinite alternate;
    }

    .ep-wave-4 {
      fill: rgba(33, 212, 253, 0.18);
      animation: epWaveMove4 12s ease-in-out infinite alternate;
    }

    @keyframes epWaveMove1 {
      0% { transform: translateX(0) scaleY(1); }
      50% { transform: translateX(8%) scaleY(1.15) rotate(1deg); }
      100% { transform: translateX(-5%) scaleY(0.95); }
    }

    @keyframes epWaveMove2 {
      0% { transform: translateX(0) scaleY(1.05); }
      50% { transform: translateX(-10%) scaleY(0.9) rotate(-1.5deg); }
      100% { transform: translateX(6%) scaleY(1.1); }
    }

    @keyframes epWaveMove3 {
      0% { transform: translateX(0) scaleY(0.95); }
      50% { transform: translateX(7%) scaleY(1.2) rotate(1deg); }
      100% { transform: translateX(-8%) scaleY(1); }
    }

    @keyframes epWaveMove4 {
      0% { transform: translateX(0) scaleY(1.1); }
      50% { transform: translateX(-6%) scaleY(0.85) rotate(-1deg); }
      100% { transform: translateX(9%) scaleY(1.15); }
    }

    /* Glassmorphism Card Style */
    .ep-card {
      border: 1px solid rgba(255, 255, 255, 0.22) !important;
      border-radius: 1.25rem;
      background: rgba(255, 255, 255, 0.93) !important;
      backdrop-filter: blur(24px);
      -webkit-backdrop-filter: blur(24px);
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.6);
    }

    .ep-card:hover {
      box-shadow: 0 25px 60px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.8);
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

<body>
  <!-- Aurora Wave Background Layer -->
  <div class="ep-aurora-bg">
    <div class="ep-wave-container">
      <svg class="ep-wave ep-wave-1" viewBox="0 0 1440 320" preserveAspectRatio="none">
        <path d="M0,192L48,176C96,160,192,128,288,138.7C384,149,480,203,576,213.3C672,224,768,192,864,165.3C960,139,1056,117,1152,128C1248,139,1344,181,1392,202.7L1440,224L1440,320L0,320Z"></path>
      </svg>
      <svg class="ep-wave ep-wave-2" viewBox="0 0 1440 320" preserveAspectRatio="none">
        <path d="M0,128L60,149.3C120,171,240,213,360,208C480,203,600,149,720,138.7C840,128,960,160,1080,170.7C1200,181,1320,171,1380,165.3L1440,160L1440,320L0,320Z"></path>
      </svg>
      <svg class="ep-wave ep-wave-3" viewBox="0 0 1440 320" preserveAspectRatio="none">
        <path d="M0,224L48,213.3C96,203,192,181,288,154.7C384,128,480,96,576,106.7C672,117,768,171,864,186.7C960,203,1056,181,1152,154.7C1248,128,1344,96,1392,80L1440,64L1440,320L0,320Z"></path>
      </svg>
      <svg class="ep-wave ep-wave-4" viewBox="0 0 1440 320" preserveAspectRatio="none">
        <path d="M0,96L48,112C96,128,192,160,288,186.7C384,213,480,235,576,213.3C672,192,768,128,864,117.3C960,107,1056,149,1152,160C1248,171,1344,149,1392,138.7L1440,128L1440,320L0,320Z"></path>
      </svg>
    </div>
  </div>

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

              @if(session('lockout_seconds'))
                <div class="mt-2 mb-3 py-2 px-3 border-radius-lg bg-gray-100 text-dark text-center border d-flex align-items-center justify-content-center gap-2" style="max-width: 280px; margin: 0 auto;">
                  <i class="fas fa-clock text-warning text-sm"></i>
                  <span class="text-xs text-muted font-weight-bold">Coba lagi dalam:</span>
                  <span class="font-weight-bolder text-dark text-sm mb-0" id="lockout-timer" style="font-family: monospace; font-size: 14px;">--:--</span>
                </div>
                <script>
                  document.addEventListener('DOMContentLoaded', function() {
                    let secondsLeft = parseInt("{{ session('lockout_seconds') }}") || 180;
                    const timerElement = document.getElementById('lockout-timer');
                    const submitBtn = document.querySelector('#gate-form button[type="submit"]');
                    const passwordInput = document.getElementById('gate-password');

                    if (submitBtn) submitBtn.disabled = true;
                    if (passwordInput) passwordInput.disabled = true;

                    function updateTimer() {
                      if (secondsLeft <= 0) {
                        timerElement.innerText = "00:00";
                        if (submitBtn) submitBtn.disabled = false;
                        if (passwordInput) passwordInput.disabled = false;
                        window.location.reload();
                        return;
                      }

                      const mins = Math.floor(secondsLeft / 60);
                      const secs = secondsLeft % 60;
                      timerElement.innerText = 
                        (mins < 10 ? '0' : '') + mins + ':' + (secs < 10 ? '0' : '') + secs;

                      secondsLeft--;
                    }

                    updateTimer();
                    const interval = setInterval(function() {
                      updateTimer();
                      if (secondsLeft < 0) {
                        clearInterval(interval);
                      }
                    }, 1000);
                  });
                </script>
              @endif

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
