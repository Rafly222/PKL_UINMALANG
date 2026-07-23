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
  <script>
    if (localStorage.getItem('dark-mode') === 'true') {
      document.body.classList.add('dark-version');
    }
  </script>
  <div class="min-height-300 bg-primary position-absolute w-100"></div>

  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 fixed-start" id="sidenav-main">
    <div class="sidenav-header">
      <a class="navbar-brand m-0 d-flex align-items-center" href="{{ route('home') }}">
        <img src="{{ asset($argon . '/img/epresensi-logo.png') }}" class="navbar-brand-img shadow-sm" alt="logo">
        <span class="ms-2 font-weight-bold text-dark">E-Presensi</span>
      </a>
      <span id="iconSidenav" class="d-none"></span>

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
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0">
            <li class="breadcrumb-item text-sm"><a class="opacity-7 text-white" href="{{ route('home') }}">E-Presensi</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">@yield('breadcrumb', 'Portal')</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">@yield('page-title', 'Portal Presensi')</h6>
        </nav>
        <div class="sidenav-toggler sidenav-toggler-inner d-xl-block d-none ms-5 me-3">
          <a class="nav-link p-0 cursor-pointer">
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
              <li class="nav-item d-flex align-items-center me-3">
                <a href="javascript:;" class="nav-link text-white p-0 d-flex align-items-center" id="dark-mode-toggle" title="Toggle Dark Mode" style="cursor: pointer;">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-moon-fill text-white" viewBox="0 0 16 16" style="width: 18px; height: 18px; display: inline-block; vertical-align: middle;">
                    <path d="M6 .278a.77.77 0 0 1 .08.858 7.2 7.2 0 0 0-.078 2.18c.088.958.429 1.896 1.055 2.652A5.9 5.9 0 0 0 9.51 7.62c.762.626 1.704.967 2.662 1.055a7.06 7.06 0 0 0 2.18-.078.77.77 0 0 1 .858.08.77.77 0 0 1 .08.858 7.2 7.2 0 0 1-2.18 3.54 7.2 7.2 0 0 1-3.54 2.18 7.2 7.2 0 0 1-3.54-.078A7.2 7.2 0 0 1 .278 10 7.2 7.2 0 0 1 0 6.46 7.2 7.2 0 0 1 2.18 2.92 7.2 7.2 0 0 1 6 .278z"/>
                  </svg>
                </a>
                <input type="checkbox" id="dark-version" style="display: none;">
              </li>
              <li class="nav-item d-flex align-items-center">
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                  @csrf
                  <button type="submit" class="btn btn-sm bg-gradient-danger text-white mb-0 px-3">Keluar</button>
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
        <div class="d-xl-none ps-3 d-flex align-items-center ms-auto me-3">
          <a class="nav-link text-white p-0 cursor-pointer" id="iconNavbarSidenav">
            <div class="sidenav-toggler-inner">
              <i class="sidenav-toggler-line bg-white"></i>
              <i class="sidenav-toggler-line bg-white"></i>
              <i class="sidenav-toggler-line bg-white"></i>
            </div>
          </a>
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
        body.classList.remove('g-sidenav-hidden');
      }

      // Ensure clicking the mobile toggler removes the hidden class (which prevents it from showing)
      const iconNavbarSidenav = document.getElementById('iconNavbarSidenav');
      if (iconNavbarSidenav) {
        iconNavbarSidenav.addEventListener('click', () => {
          body.classList.remove('g-sidenav-hidden');
        });
      }

      // Dark Mode Toggle Logic
      const darkModeToggle = document.getElementById('dark-mode-toggle');
      const darkVersionCheckbox = document.getElementById('dark-version');

      function updateDarkModeIcon() {
        if (body.classList.contains('dark-version')) {
          darkModeToggle.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-sun-fill text-white" viewBox="0 0 16 16" style="width: 18px; height: 18px; display: inline-block; vertical-align: middle;"><path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/></svg>';
          darkModeToggle.setAttribute('title', 'Aktifkan Mode Terang');
        } else {
          darkModeToggle.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-moon-fill text-white" viewBox="0 0 16 16" style="width: 18px; height: 18px; display: inline-block; vertical-align: middle;"><path d="M6 .278a.77.77 0 0 1 .08.858 7.2 7.2 0 0 0-.078 2.18c.088.958.429 1.896 1.055 2.652A5.9 5.9 0 0 0 9.51 7.62c.762.626 1.704.967 2.662 1.055a7.06 7.06 0 0 0 2.18-.078.77.77 0 0 1 .858.08.77.77 0 0 1 .08.858 7.2 7.2 0 0 1-2.18 3.54 7.2 7.2 0 0 1-3.54 2.18 7.2 7.2 0 0 1-3.54-.078A7.2 7.2 0 0 1 .278 10 7.2 7.2 0 0 1 0 6.46 7.2 7.2 0 0 1 2.18 2.92 7.2 7.2 0 0 1 6 .278z"/></svg>';
          darkModeToggle.setAttribute('title', 'Aktifkan Mode Gelap');
        }
      }

      if (darkModeToggle && darkVersionCheckbox) {
        // If dark mode was enabled, trigger Argon's darkMode function to convert sub-elements
        if (localStorage.getItem('dark-mode') === 'true') {
          if (typeof darkMode === 'function') {
            darkMode(darkVersionCheckbox);
          } else {
            body.classList.add('dark-version');
          }
        }
        updateDarkModeIcon();

        darkModeToggle.addEventListener('click', () => {
          if (typeof darkMode === 'function') {
            darkMode(darkVersionCheckbox);
          } else {
            body.classList.toggle('dark-version');
          }
          localStorage.setItem('dark-mode', body.classList.contains('dark-version') ? 'true' : 'false');
          updateDarkModeIcon();
        });
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
