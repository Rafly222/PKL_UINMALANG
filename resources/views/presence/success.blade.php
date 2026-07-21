<!DOCTYPE html>
<html lang="id">

<head>
  @php($argon = 'assets/argon-dashboard-pro-html-v2.0.5/assets')
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Presensi Berhasil - E-Presensi</title>
  
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
      background: radial-gradient(circle at 50% 30%, #064e3b 0%, #0b1329 70%, #042f2e 100%);
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
      fill: rgba(45, 206, 137, 0.35);
      animation: epWaveMove1 14s ease-in-out infinite alternate;
    }

    .ep-wave-2 {
      fill: rgba(17, 205, 239, 0.28);
      animation: epWaveMove2 18s ease-in-out infinite alternate;
    }

    .ep-wave-3 {
      fill: rgba(16, 185, 129, 0.22);
      animation: epWaveMove3 22s ease-in-out infinite alternate;
    }

    .ep-wave-4 {
      fill: rgba(94, 114, 228, 0.18);
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

    /* High-Contrast Card Style */
    .ep-card {
      border: 1px solid rgba(255, 255, 255, 0.4) !important;
      border-radius: 1.25rem;
      background: #ffffff !important;
      box-shadow: 0 25px 60px rgba(0, 0, 0, 0.45);
    }

    .ep-form-card {
      border: 1px solid #e2e8f0 !important;
      background: #ffffff !important;
      box-shadow: 0 20px 45px rgba(0, 0, 0, 0.35);
    }

    .ep-section-label {
      color: #475569 !important;
      font-size: .72rem;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: .06rem;
    }

    .ep-text-title {
      color: #0f172a !important;
      font-weight: 800 !important;
    }

    .ep-text-subtitle {
      color: #334155 !important;
      font-weight: 600 !important;
    }

    .ep-text-value {
      color: #0f172a !important;
      font-weight: 700 !important;
    }

    .ep-media-frame {
      border: 2px solid #e2e8f0;
      border-radius: 1rem;
      background: #0f172a;
      box-shadow: 0 10px 25px rgba(15, 23, 42, .2);
      overflow: hidden;
    }

    .ep-signature-frame {
      border: 1.5px solid #cbd5e1;
      border-radius: 1rem;
      background: #fff;
      box-shadow: inset 0 1px 10px rgba(15, 23, 42, .06);
    }

    .main-content {
      z-index: 1;
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
        max-height: 98vh;
        padding-top: 5px !important;
        padding-bottom: 5px !important;
        max-width: 900px !important;
        width: 90vw !important;
      }
      .ep-form-card {
        max-height: 96vh;
        display: flex;
        flex-direction: column;
        margin-bottom: 0 !important;
      }
      .ep-form-card .card-body {
        overflow-y: auto;
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
  
  <main class="main-content position-relative border-radius-lg">
    <div class="container py-3">
      <div class="row justify-content-center">
        <div class="col-12">
          
          <!-- Success Top Alert Card -->
          <div class="card ep-card mb-3 overflow-hidden">
            <div class="card-header bg-gradient-success text-center p-4">
              <div class="icon icon-shape bg-white shadow-md text-center border-radius-lg mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                <i class="ni ni-check-bold text-success text-xl font-weight-bold"></i>
              </div>
              <h3 class="text-white font-weight-bolder mb-1">Presensi Berhasil Dicatat</h3>
              <p class="text-white opacity-9 text-sm mb-0 font-weight-semibold">Data kehadiran Anda sudah tersimpan di sistem E-Presensi.</p>
            </div>
          </div>

          <!-- Attendance Info Card -->
          <div class="card ep-card ep-form-card">
            <div class="card-header pb-0 bg-transparent pt-3">
              <div class="d-flex align-items-center justify-content-between">
                <div>
                  <h6 class="mb-0 ep-text-title text-md">Kartu Kehadiran Resmi</h6>
                  <p class="text-xs ep-text-subtitle mb-0">{{ $presence->event->name }}</p>
                </div>
                <span class="badge bg-gradient-success font-weight-bold px-3 py-2">Hadir</span>
              </div>
            </div>
            
            <div class="card-body p-3">
              <div class="row align-items-center">
                <div class="col-md-4 mb-3 mb-md-0">
                  <div class="ep-media-frame bg-gray-100" style="aspect-ratio: 3 / 4; max-height: 200px; margin: 0 auto;">
                    @if($presence->photo)
                      <img src="{{ route('presence.photo', $presence->id) }}" alt="Foto wajah" class="w-100 h-100" style="object-fit: cover;">
                    @else
                      <div class="h-100 d-flex align-items-center justify-content-center text-muted text-sm bg-gray-100">
                        <i class="fas fa-user-circle fa-3x opacity-4"></i>
                      </div>
                    @endif
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="row">
                    <div class="col-sm-6 mb-3">
                      <p class="ep-section-label mb-1">Nama</p>
                      <h5 class="mb-0 ep-text-title text-md">{{ $presence->name }}</h5>
                    </div>
                    <div class="col-sm-6 mb-3">
                      <p class="ep-section-label mb-1">Waktu hadir</p>
                      <p class="text-sm ep-text-value mb-0">{{ $presence->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</p>
                    </div>
                    <div class="col-12 mb-3">
                      <p class="ep-section-label mb-1">Instansi</p>
                      <p class="text-sm ep-text-value mb-0">{{ $presence->institution ?? '-' }}</p>
                    </div>
                    @if($presence->signature)
                      <div class="col-12">
                        <p class="ep-section-label mb-1">Tanda tangan</p>
                        <div class="ep-signature-frame p-2 d-inline-block bg-white">
                          <img src="{{ route('presence.signature', $presence->id) }}" alt="Tanda tangan" style="max-height: 70px; max-width: 220px; object-fit: contain;">
                        </div>
                      </div>
                    @endif
                  </div>
                </div>
              </div>
            </div>
            
            <div class="card-footer text-center bg-transparent border-top py-3">
              <a href="{{ route('presence.form', $presence->event->uuid) }}" class="btn bg-gradient-success font-weight-bold mb-0 me-2 shadow-sm">
                <i class="fas fa-plus-circle me-1"></i> Presensi Berikutnya
              </a>
              <a href="{{ route('home') }}" class="btn btn-outline-dark font-weight-bold mb-0" style="border-color: #cbd5e1; color: #1e293b;">
                <i class="fas fa-home me-1"></i> Beranda
              </a>
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
