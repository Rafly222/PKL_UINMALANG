<!DOCTYPE html>
<html lang="id">

<head>
  @php($argon = 'assets/argon-dashboard-pro-html-v2.0.5/assets')
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>@yield('title', 'E-Presensi Diskominfo Kota Malang')</title>

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

    .ep-bg-mesh {
      background:
        linear-gradient(135deg, rgba(94, 114, 228, .96), rgba(17, 205, 239, .88)),
        url('{{ asset($argon . '/img/meeting.jpg') }}') center / cover;
    }

    .ep-page-hero {
      position: relative;
      overflow: hidden;
      min-height: 245px;
      border-radius: 1rem;
      box-shadow: 0 20px 45px rgba(94, 114, 228, .22);
    }

    .ep-page-hero:after {
      content: "";
      position: absolute;
      right: -90px;
      bottom: -120px;
      width: 280px;
      height: 280px;
      border-radius: 50%;
      background: rgba(255, 255, 255, .12);
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

    .form-control,
    .form-select {
      border: 1px solid #dee2e6;
      box-shadow: 0 3px 8px rgba(50, 50, 93, .04);
    }

    .form-control:focus,
    .form-select:focus {
      border-color: #5e72e4;
      box-shadow: 0 0 0 .2rem rgba(94, 114, 228, .15);
    }

    .table thead th {
      color: #8392ab;
      letter-spacing: .04em;
    }

    .ep-media-frame {
      border: 1px solid rgba(203, 213, 225, .8);
      border-radius: 1rem;
      background: #111827;
      box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .06), 0 16px 30px rgba(15, 23, 42, .14);
      overflow: hidden;
    }

    .ep-signature-frame {
      border: 2px dashed #cbd5e1;
      border-radius: 1rem;
      background: #fff;
      box-shadow: inset 0 1px 14px rgba(15, 23, 42, .05);
    }

    .sidenav .navbar-brand-img {
      max-height: 34px;
    }

    @media (max-width: 1199.98px) {
      .g-sidenav-show .main-content {
        margin-left: 0 !important;
      }
    }
  </style>

  @stack('styles')
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-primary position-absolute w-100"></div>

  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 shadow-lg" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0 d-flex align-items-center" href="{{ route('home') }}">
        <img src="{{ asset($argon . '/img/logo-ct-dark.png') }}" class="navbar-brand-img" alt="logo">
        <span class="ms-2 font-weight-bold text-dark">E-Presensi</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto h-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
            <div class="icon icon-shape icon-sm text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-shop text-primary text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Landing Presensi</span>
          </a>
        </li>
        @auth
          @if(Auth::user()->role === 'admin')
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('dashboard.admin') ? 'active' : '' }}" href="{{ route('dashboard.admin') }}">
                <div class="icon icon-shape icon-sm text-center me-2 d-flex align-items-center justify-content-center">
                  <i class="ni ni-settings-gear-65 text-danger text-sm opacity-10"></i>
                </div>
                <span class="nav-link-text ms-1">Dashboard Admin</span>
              </a>
            </li>
          @else
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('dashboard.user') ? 'active' : '' }}" href="{{ route('dashboard.user') }}">
                <div class="icon icon-shape icon-sm text-center me-2 d-flex align-items-center justify-content-center">
                  <i class="ni ni-calendar-grid-58 text-info text-sm opacity-10"></i>
                </div>
                <span class="nav-link-text ms-1">Dashboard User</span>
              </a>
            </li>
          @endif
        @endauth
      </ul>
    </div>
    <div class="sidenav-footer mx-3 mt-4">
      <div class="card card-plain shadow-none bg-gray-100 border-radius-lg">
        <div class="card-body p-3">
          <div class="d-flex">
            <div class="icon icon-shape icon-sm bg-gradient-primary shadow text-center me-2">
              <i class="ni ni-badge text-white"></i>
            </div>
            <div>
              <h6 class="mb-0 text-xs">Diskominfo Malang</h6>
              <p class="text-xxs text-secondary mb-0">Presensi digital event</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </aside>

  <main class="main-content position-relative border-radius-lg">
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0">
            <li class="breadcrumb-item text-sm"><a class="opacity-7 text-white" href="{{ route('home') }}">E-Presensi</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">@yield('breadcrumb', 'Portal')</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">@yield('page-title', 'Portal Presensi')</h6>
        </nav>
        <div class="sidenav-toggler sidenav-toggler-inner d-xl-none d-block ms-auto">
          <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
            <div class="sidenav-toggler-inner">
              <i class="sidenav-toggler-line bg-white"></i>
              <i class="sidenav-toggler-line bg-white"></i>
              <i class="sidenav-toggler-line bg-white"></i>
            </div>
          </a>
        </div>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center"></div>
          <ul class="navbar-nav justify-content-end">
            @auth
              <li class="nav-item d-flex align-items-center me-3 text-white">
                <span class="text-sm d-sm-inline d-none">Hai, <b>{{ Auth::user()->name }}</b></span>
              </li>
              <li class="nav-item d-flex align-items-center">
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                  @csrf
                  <button type="submit" class="btn btn-sm bg-gradient-light text-dark mb-0 px-3">Keluar</button>
                </form>
              </li>
            @else
              <li class="nav-item d-flex align-items-center">
                <a href="{{ route('login') }}" class="nav-link text-white font-weight-bold px-0">
                  <i class="fa fa-user me-sm-1"></i>
                  <span class="d-sm-inline d-none">Login Portal</span>
                </a>
              </li>
            @endauth
          </ul>
        </div>
      </div>
    </nav>

    <div class="container-fluid py-4">
      @yield('content')
      <footer class="footer pt-4">
        <div class="row align-items-center justify-content-lg-between">
          <div class="col-lg-6 mb-lg-0 mb-3">
            <div class="copyright text-center text-sm text-muted text-lg-start">
              Copyright © {{ date('Y') }} Diskominfo Kota Malang.
            </div>
          </div>
          <div class="col-lg-6">
            <div class="text-sm text-muted text-center text-lg-end">Laravel 11 · Argon Dashboard 2</div>
          </div>
        </div>
      </footer>
    </div>
  </main>

  <script src="{{ asset($argon . '/js/core/popper.min.js') }}"></script>
  <script src="{{ asset($argon . '/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset($argon . '/js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset($argon . '/js/plugins/smooth-scrollbar.min.js') }}"></script>
  <script src="{{ asset($argon . '/js/argon-dashboard.min.js') }}"></script>

  @yield('scripts')
</body>

</html>
