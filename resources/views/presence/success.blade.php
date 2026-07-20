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
      background: #f8f9fe;
    }

    .ep-card {
      border: 0;
      border-radius: 1rem;
      box-shadow: 0 14px 35px rgba(50, 50, 93, .08), 0 4px 12px rgba(0, 0, 0, .05);
    }

    .ep-card:hover {
      box-shadow: 0 18px 45px rgba(50, 50, 93, .12), 0 7px 18px rgba(0, 0, 0, .07);
    }

    .ep-form-card {
      border: 1px solid rgba(226, 232, 240, .95);
      background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
    }

    .ep-section-label {
      color: #8392ab;
      font-size: .68rem;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: .04rem;
    }

    .ep-media-frame {
      border: 1px solid rgba(203, 213, 225, .8);
      border-radius: 1rem;
      background: #111827;
      box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .06), 0 16px 30px rgba(15, 23, 42, .14);
      overflow: hidden;
    }

    .ep-signature-frame {
      border: 1px solid rgba(203, 213, 225, .8);
      border-radius: 1rem;
      background: #fff;
      box-shadow: inset 0 1px 14px rgba(15, 23, 42, .05);
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

<body class="bg-gray-100">
  <div class="min-height-300 bg-primary position-absolute w-100" style="height: 35vh !important; min-height: unset !important;"></div>
  
  <main class="main-content position-relative border-radius-lg">
    <div class="container py-3">
      <div class="row justify-content-center">
        <div class="col-12">
          
          <!-- Success Top Alert Card -->
          <div class="card ep-card mb-3 overflow-hidden">
            <div class="card-header bg-gradient-success text-center p-4">
              <div class="icon icon-shape bg-white shadow text-center border-radius-lg mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                <i class="ni ni-check-bold text-success text-lg"></i>
              </div>
              <h3 class="text-white font-weight-bolder mb-1">Presensi Berhasil Dicatat</h3>
              <p class="text-white opacity-8 text-sm mb-0">Data kehadiran Anda sudah tersimpan di sistem E-Presensi.</p>
            </div>
          </div>

          <!-- Attendance Info Card -->
          <div class="card ep-card ep-form-card">
            <div class="card-header pb-0 bg-transparent pt-3">
              <div class="d-flex align-items-center justify-content-between">
                <div>
                  <h6 class="mb-0 font-weight-bolder">Kartu Kehadiran Resmi</h6>
                  <p class="text-xs text-muted mb-0">{{ $presence->event->name }}</p>
                </div>
                <span class="badge bg-gradient-success">Hadir</span>
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
                      <h5 class="mb-0 font-weight-bolder text-md">{{ $presence->name }}</h5>
                    </div>
                    <div class="col-sm-6 mb-3">
                      <p class="ep-section-label mb-1">Waktu hadir</p>
                      <p class="text-sm font-weight-bold mb-0 text-dark">{{ $presence->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</p>
                    </div>
                    <div class="col-12 mb-3">
                      <p class="ep-section-label mb-1">Instansi</p>
                      <p class="text-sm text-dark mb-0 font-weight-bold">{{ $presence->institution ?? '-' }}</p>
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
              <a href="{{ route('presence.form', $presence->event->uuid) }}" class="btn bg-gradient-success mb-0 me-2">
                <i class="fas fa-plus-circle me-1"></i> Presensi Berikutnya
              </a>
              <a href="{{ route('home') }}" class="btn btn-outline-secondary mb-0">
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
