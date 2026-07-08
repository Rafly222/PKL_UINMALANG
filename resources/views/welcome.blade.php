<!DOCTYPE html>
<html lang="id">

<head>
  @php($argon = 'assets/argon-dashboard-pro-html-v2.0.5/assets')
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Portal E-Presensi Diskominfo Kota Malang</title>
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset($argon . '/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset($argon . '/img/favicon.png') }}">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="{{ asset($argon . '/css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset($argon . '/css/nucleo-svg.css') }}" rel="stylesheet" />
  <script src="{{ asset($argon . '/js/42d5adcbca.js') }}" crossorigin="anonymous"></script>
  <link id="pagestyle" href="{{ asset($argon . '/css/argon-dashboard.min.css') }}" rel="stylesheet" />
  <style>
    body { background: #f8f9fe; color: #344767; }
    .landing-hero {
      min-height: 540px;
      background-image: linear-gradient(120deg, rgba(23, 43, 77, .9), rgba(94, 114, 228, .72)), url('{{ asset($argon . '/img/meeting.jpg') }}');
      background-size: cover;
      background-position: center;
    }
    .event-card { transition: transform .18s ease, box-shadow .18s ease; }
    .event-card:hover { transform: translateY(-4px); box-shadow: 0 18px 45px rgba(50, 50, 93, .14); }
    .glass-panel { background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.18); backdrop-filter: blur(6px); }
    
    /* Responsivitas Hero & Stat Card */
    @media (min-width: 992px) {
      .stat-card { margin-top: -72px; position: relative; z-index: 2; }
    }
    @media (max-width: 991.98px) {
      .landing-hero {
        padding-top: 120px;
        padding-bottom: 60px;
        min-height: auto;
      }
      .stat-card { margin-top: 24px; position: relative; z-index: 2; }
    }
  </style>
</head>

<body class="bg-gray-100">
  <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute top-0 z-index-3 w-100 shadow-none my-3">
    <div class="container">
      <a class="navbar-brand text-white font-weight-bolder" href="{{ route('home') }}">E-Presensi Diskominfo</a>
      <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#landingNav">
        <span class="navbar-toggler-icon mt-2">
          <span class="navbar-toggler-bar bar1 bg-white"></span>
          <span class="navbar-toggler-bar bar2 bg-white"></span>
          <span class="navbar-toggler-bar bar3 bg-white"></span>
        </span>
      </button>
      <div class="collapse navbar-collapse" id="landingNav">
        <ul class="navbar-nav ms-auto">
          @auth
            <li class="nav-item">
              <a class="nav-link text-white" href="{{ Auth::user()->role === 'admin' ? route('dashboard.admin') : route('dashboard.user') }}">
                <i class="ni ni-tv-2 me-1"></i> Dashboard
              </a>
            </li>
          @else
            <li class="nav-item">
              <a class="nav-link text-white" href="{{ route('login') }}">
                <i class="fa fa-user me-1"></i> Login Staff
              </a>
            </li>
          @endauth
        </ul>
      </div>
    </div>
  </nav>

  <header class="page-header landing-hero align-items-center position-relative">
    <span class="mask bg-gradient-dark opacity-3"></span>
    <div class="container position-relative z-index-2">
      <div class="row align-items-center">
        <div class="col-lg-7">
          <span class="badge bg-white text-primary shadow-sm mb-3">Sistem Presensi Digital Pemerintah Kota Malang</span>
          <h1 class="text-white font-weight-bolder mb-3" style="font-size: clamp(2.25rem, 5vw, 4rem); letter-spacing: 0;">
            E-Presensi Mandiri Diskominfo
          </h1>
          <p class="text-white text-lg opacity-9 mb-4">
            Pilih agenda aktif, isi identitas, ambil foto wajah, dan bubuhkan tanda tangan digital melalui perangkat apa pun.
          </p>
          <div class="d-flex flex-wrap gap-2">
            <a href="#agenda" class="btn bg-gradient-info shadow-lg mb-0">Lihat Agenda</a>
            <a href="{{ route('login') }}" class="btn btn-outline-white mb-0">Portal Staff</a>
          </div>
        </div>
        <div class="col-lg-4 ms-auto mt-5 mt-lg-0">
          <div class="card glass-panel shadow-lg border-radius-xl">
            <div class="card-body p-4">
              <div class="d-flex align-items-center mb-3">
                <div class="icon icon-shape bg-white shadow text-center border-radius-md me-3">
                  <i class="ni ni-check-bold text-success"></i>
                </div>
                <div>
                  <h6 class="text-white mb-0">Validasi realtime</h6>
                  <p class="text-white opacity-8 text-xs mb-0">Waktu, akses privat, foto, dan TTD digital.</p>
                </div>
              </div>
              <hr class="horizontal light my-3">
              <div class="d-flex justify-content-between">
                <span class="text-white opacity-8 text-sm">Event tersedia</span>
                <span class="text-white font-weight-bolder">{{ $events->count() }}</span>
              </div>
              <div class="d-flex justify-content-between mt-2">
                <span class="text-white opacity-8 text-sm">Mode akses</span>
                <span class="text-white font-weight-bolder">Publik/Privat</span>
              </div>
              <div class="d-flex justify-content-between mt-2">
                <span class="text-white opacity-8 text-sm">Perangkat</span>
                <span class="text-white font-weight-bolder">Mobile/Desktop</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>

  <main class="container py-5">
    <div class="row stat-card">
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="card shadow-lg border-0">
          <div class="card-body p-3">
            <div class="d-flex">
              <div>
                <p class="text-sm mb-0 text-uppercase font-weight-bold">Agenda</p>
                <h5 class="font-weight-bolder mb-0">{{ $events->count() }} Event</h5>
              </div>
              <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle ms-auto">
                <i class="ni ni-calendar-grid-58 text-lg opacity-10"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="card shadow-lg border-0">
          <div class="card-body p-3">
            <div class="d-flex">
              <div>
                <p class="text-sm mb-0 text-uppercase font-weight-bold">Keamanan</p>
                <h5 class="font-weight-bolder mb-0">Gate Privat</h5>
              </div>
              <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle ms-auto">
                <i class="ni ni-lock-circle-open text-lg opacity-10"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 mb-4">
        <div class="card shadow-lg border-0">
          <div class="card-body p-3">
            <div class="d-flex">
              <div>
                <p class="text-sm mb-0 text-uppercase font-weight-bold">Bukti Hadir</p>
                <h5 class="font-weight-bolder mb-0">Foto & TTD</h5>
              </div>
              <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle ms-auto">
                <i class="ni ni-camera-compact text-lg opacity-10"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <section id="agenda" class="pt-2">
      <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between mb-4">
        <div>
          <span class="badge bg-gradient-primary mb-2">Direktori Event</span>
          <h3 class="font-weight-bolder mb-1">Agenda Presensi Aktif</h3>
          <p class="text-muted mb-0">Buka formulir melalui link event yang sesuai dengan agenda Anda.</p>
        </div>
      </div>

      <div class="row">
        @forelse($events as $ev)
          <div class="col-xl-4 col-md-6 mb-4">
            <div class="card event-card h-100 border-0 shadow">
              <div class="card-header p-3 pb-0 bg-transparent">
                <div class="d-flex align-items-center">
                  <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md me-3">
                    <i class="ni ni-badge text-white"></i>
                  </div>
                  <div>
                    <span class="badge badge-sm {{ $ev->access_type === 'privat' ? 'bg-gradient-warning' : 'bg-gradient-success' }}">{{ ucfirst($ev->access_type) }}</span>
                    <span class="badge badge-sm bg-gradient-secondary">{{ ucfirst($ev->audience_type) }}</span>
                  </div>
                </div>
              </div>
              <div class="card-body p-3">
                <h5 class="font-weight-bolder mb-3">{{ $ev->name }}</h5>
                <div class="list-group">
                  <div class="list-group-item border-0 d-flex align-items-center px-0 py-2">
                    <div class="icon icon-shape icon-xs bg-gradient-light shadow text-center me-3">
                      <i class="ni ni-calendar-grid-58 text-primary"></i>
                    </div>
                    <span class="text-sm font-weight-bold">{{ \Carbon\Carbon::parse($ev->date)->translatedFormat('d F Y') }}</span>
                  </div>
                  <div class="list-group-item border-0 d-flex align-items-center px-0 py-2">
                    <div class="icon icon-shape icon-xs bg-gradient-light shadow text-center me-3">
                      <i class="ni ni-watch-time text-info"></i>
                    </div>
                    <span class="text-sm font-weight-bold">{{ \Carbon\Carbon::parse($ev->time_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($ev->time_end)->format('H:i') }} WIB</span>
                  </div>
                </div>
              </div>
              <div class="card-footer bg-transparent border-0 pt-0 p-3">
                <a href="{{ route('presence.form', $ev->id) }}" class="btn bg-gradient-dark w-100 mb-0">
                  <i class="fas fa-file-signature me-1"></i> Masuk Presensi
                </a>
              </div>
            </div>
          </div>
        @empty
          <div class="col-12">
            <div class="card shadow border-0">
              <div class="card-body text-center p-5">
                <div class="icon icon-shape bg-gradient-light shadow text-center border-radius-md mx-auto mb-3">
                  <i class="ni ni-calendar-grid-58 text-primary"></i>
                </div>
                <h5 class="font-weight-bolder">Belum Ada Agenda Aktif</h5>
                <p class="text-sm text-muted mb-0">Silakan hubungi admin bidang terkait untuk mendaftarkan agenda baru.</p>
              </div>
            </div>
          </div>
        @endforelse
      </div>
    </section>

    <section class="row mt-4">
      <div class="col-lg-7 mb-4">
        <div class="card shadow border-0 h-100">
          <div class="card-header pb-0">
            <h6 class="mb-0">Alur Presensi Mandiri</h6>
          </div>
          <div class="card-body">
            <div class="timeline timeline-one-side">
              <div class="timeline-block mb-3">
                <span class="timeline-step bg-primary"><i class="ni ni-button-play text-white text-xs"></i></span>
                <div class="timeline-content">
                  <h6 class="text-dark text-sm font-weight-bold mb-0">Pilih Event</h6>
                  <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">Gunakan link event yang dibagikan penyelenggara atau kartu agenda aktif.</p>
                </div>
              </div>
              <div class="timeline-block mb-3">
                <span class="timeline-step bg-info"><i class="ni ni-single-02 text-white text-xs"></i></span>
                <div class="timeline-content">
                  <h6 class="text-dark text-sm font-weight-bold mb-0">Isi Identitas</h6>
                  <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">Peserta pegawai mengisi NIP, peserta umum mengisi data sesuai field event.</p>
                </div>
              </div>
              <div class="timeline-block">
                <span class="timeline-step bg-success"><i class="ni ni-check-bold text-white text-xs"></i></span>
                <div class="timeline-content">
                  <h6 class="text-dark text-sm font-weight-bold mb-0">Kirim Bukti Hadir</h6>
                  <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">Capture wajah dan tanda tangan digital untuk bukti presensi.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-5 mb-4">
        <div class="card shadow border-0 h-100 bg-gradient-dark">
          <div class="card-body p-4">
            <div class="icon icon-shape bg-white shadow text-center border-radius-md mb-3">
              <i class="ni ni-notification-70 text-warning"></i>
            </div>
            <h5 class="text-white">Maklumat Integritas</h5>
            <p class="text-white opacity-8 text-sm mb-0">
              Data yang dikirimkan menjadi bukti kehadiran resmi pada agenda Diskominfo Kota Malang. Pastikan identitas, foto, dan tanda tangan sesuai dengan peserta yang hadir.
            </p>
          </div>
        </div>
      </div>
    </section>
  </main>

  <footer class="footer py-4">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 text-center text-lg-start text-sm text-muted">Copyright © {{ date('Y') }} Diskominfo Kota Malang.</div>
        <div class="col-lg-6 text-center text-lg-end text-sm text-muted">Smart City · Laravel 11 · Argon Dashboard 2</div>
      </div>
    </div>
  </footer>

  <script src="{{ asset($argon . '/js/core/popper.min.js') }}"></script>
  <script src="{{ asset($argon . '/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset($argon . '/js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset($argon . '/js/plugins/smooth-scrollbar.min.js') }}"></script>
  <script src="{{ asset($argon . '/js/argon-dashboard.min.js') }}"></script>
</body>

</html>
