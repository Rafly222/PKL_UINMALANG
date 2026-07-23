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

  <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
  @stack('styles')
</head>

<body class="g-sidenav-show g-sidenav-pinned bg-gray-100">
  <div class="min-height-300 bg-primary position-absolute w-100"></div>

  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 fixed-start" id="sidenav-main">
    <div class="sidenav-header">
      <a class="navbar-brand m-0 d-flex align-items-center" href="{{ route('home') }}">
        <img src="{{ asset($argon . '/img/epresensi-logo.png') }}" class="navbar-brand-img shadow-sm" alt="logo">
        <span class="ms-2 font-weight-bold text-dark">E-Presensi</span>
      </a>
      <div class="sidenav-toggler sidenav-toggler-inner cursor-pointer" id="iconSidenav" style="cursor: pointer;">
        <div class="sidenav-toggler-inner">
          <i class="sidenav-toggler-line bg-dark"></i>
          <i class="sidenav-toggler-line bg-dark"></i>
          <i class="sidenav-toggler-line bg-dark"></i>
        </div>
      </div>
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
                  <i class="ni ni-calendar-grid-58 text-primary text-sm opacity-10"></i>
                </div>
                <span class="nav-link-text ms-1">Pembuatan Event</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}" href="{{ route('admin.users') }}">
                <div class="icon icon-shape icon-sm text-center me-2 d-flex align-items-center justify-content-center">
                  <i class="ni ni-single-02 text-info text-sm opacity-10"></i>
                </div>
                <span class="nav-link-text ms-1">Manajemen Akun</span>
                @if(!empty($sidebarPendingCount) && $sidebarPendingCount > 0)
                  <span class="badge bg-gradient-warning text-white ms-auto font-weight-bold" style="font-size: 9px; padding: 2px 6px;">
                    {{ $sidebarPendingCount }}
                  </span>
                @endif
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('admin.logs') ? 'active' : '' }}" href="{{ route('admin.logs') }}">
                <div class="icon icon-shape icon-sm text-center me-2 d-flex align-items-center justify-content-center">
                  <i class="ni ni-bullet-list-67 text-dark text-sm opacity-10"></i>
                </div>
                <span class="nav-link-text ms-1">Manajemen Log Aktivitas</span>
              </a>
            </li>
          @else
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('dashboard.user') ? 'active' : '' }}" href="{{ route('dashboard.user') }}">
                <div class="icon icon-shape icon-sm text-center me-2 d-flex align-items-center justify-content-center">
                  <i class="ni ni-calendar-grid-58 text-primary text-sm opacity-10"></i>
                </div>
                <span class="nav-link-text ms-1">Pembuatan Event</span>
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
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-0 mb-3 shadow-none border-radius-xl sticky-top" id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <div class="d-flex align-items-center me-3 d-xl-none">
          <div class="sidenav-toggler sidenav-toggler-inner cursor-pointer" id="iconNavbarSidenav" style="cursor: pointer;">
            <div class="sidenav-toggler-inner">
              <i class="sidenav-toggler-line bg-white"></i>
              <i class="sidenav-toggler-line bg-white"></i>
              <i class="sidenav-toggler-line bg-white"></i>
            </div>
          </div>
        </div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0">
            <li class="breadcrumb-item text-sm"><a class="opacity-7 text-white" href="{{ route('home') }}">E-Presensi</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">@yield('breadcrumb', 'Portal')</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">@yield('page-title', 'Portal Presensi')</h6>
        </nav>
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

    <div class="container-fluid px-0 py-3">
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
  <script src="{{ asset($argon . '/js/plugins/qrcode.min.js') }}"></script>
  <script src="{{ asset($argon . '/js/argon-dashboard.min.js') }}"></script>
  <script src="{{ asset('js/custom-events.js') }}"></script>

  @yield('scripts')

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const body = document.body;

      // Auto close sidebar on mobile/tablet screens
      if (window.innerWidth < 1200) {
        body.classList.remove('g-sidenav-pinned');
        body.classList.add('g-sidenav-hidden');
      }

      // Navbar scroll listener for sticky card styling
      const navbarBlur = document.getElementById('navbarBlur');
      if (navbarBlur) {
        const handleScroll = () => {
          if (window.scrollY > 10) {
            navbarBlur.classList.add('navbar-scrolled');
          } else {
            navbarBlur.classList.remove('navbar-scrolled');
          }
        };
        window.addEventListener('scroll', handleScroll);
        handleScroll();
      }
    });
  </script>
</body>

</html>
