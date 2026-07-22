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
      background: linear-gradient(135deg, #5e72e4 0%, #11cdef 100%);
    }

    .ep-page-hero {
      position: relative;
      overflow: hidden;
      min-height: auto !important;
      padding: 1.25rem 1.5rem !important;
      border-radius: 1rem;
      box-shadow: 0 10px 25px rgba(94, 114, 228, .18);
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

    .input-group:focus-within {
      box-shadow: 0 0 0 .2rem rgba(94, 114, 228, .15) !important;
      border-radius: 0.5rem !important;
    }

    .input-group:focus-within .form-control,
    .input-group:focus-within .input-group-text {
      border-color: #5e72e4 !important;
      box-shadow: none !important;
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

    .sidenav {
      z-index: 1030 !important;
      background-color: #ffffff !important;
      transition: all 0.3s ease !important;
    }
    .main-content {
      transition: all 0.3s ease !important;
    }

    .sidenav .navbar-brand-img {
      max-height: 48px !important;
      width: auto;
      border-radius: 0.5rem;
    }

    .sidenav .navbar-brand span {
      font-size: 1.25rem !important;
      font-weight: 800 !important;
      letter-spacing: -0.02em;
    }

    .sidenav .nav-link-text {
      font-size: 0.95rem !important;
      font-weight: 700 !important;
      color: #334155;
      white-space: nowrap;
      transition: color 0.25s ease !important;
    }

    .sidenav .nav-link {
      padding: 0.75rem 1rem !important;
      margin: 0.25rem 0.75rem !important;
      border-radius: 0.75rem !important;
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
      position: relative;
    }

    /* Hover State on Inactive Sidebar Links */
    .sidenav .nav-link:hover:not(.active) {
      background-color: rgba(94, 114, 228, 0.08) !important;
      transform: translateX(6px) scale(1.01);
    }

    .sidenav .nav-link:hover:not(.active) .nav-link-text {
      color: #5e72e4 !important;
    }

    .sidenav .nav-link:hover:not(.active) .icon i {
      transform: scale(1.2) rotate(6deg);
      transition: transform 0.25s ease !important;
      color: #5e72e4 !important;
    }

    /* Active State on Sidebar Links */
    .sidenav .nav-link.active {
      background: linear-gradient(135deg, #5e72e4 0%, #825ee4 100%) !important;
      box-shadow: 0 6px 20px rgba(94, 114, 228, 0.35) !important;
      transform: translateX(4px);
    }

    .sidenav .nav-link.active .nav-link-text {
      color: #ffffff !important;
      font-weight: 800 !important;
    }

    .sidenav .nav-link.active .icon i {
      color: #ffffff !important;
      opacity: 1 !important;
    }

    .sidenav .nav-link:active {
      transform: scale(0.97) translateX(3px) !important;
    }

    .sidenav .icon-shape {
      width: 38px !important;
      height: 38px !important;
      border-radius: 0.75rem !important;
      transition: all 0.25s ease !important;
    }

    .sidenav .nav-link i {
      font-size: 1.15rem !important;
      transition: all 0.25s ease !important;
    }

    /* Universal Button & Chip Interactive Effects */
    .btn, .nav-category-btn, .sub-chips-group .btn {
      transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    .btn:hover, .sub-chips-group .btn:hover {
      transform: translateY(-2px) scale(1.02);
      box-shadow: 0 6px 16px rgba(50, 50, 93, 0.12), 0 3px 6px rgba(0, 0, 0, 0.08);
    }

    .btn:active, .sub-chips-group .btn:active {
      transform: translateY(0) scale(0.97) !important;
    }

    /* Desktop Expanded & Compact Sidebar Mode */
    @media (min-width: 1200px) {
      .g-sidenav-show:not(.g-sidenav-hidden) .sidenav {
        max-width: 17.5rem !important;
        width: 17.5rem !important;
      }
      .g-sidenav-show:not(.g-sidenav-hidden) .main-content {
        margin-left: 20.25rem !important;
        padding-left: 1.5rem !important;
        padding-right: 1.5rem !important;
      }

      body.g-sidenav-hidden .sidenav {
        max-width: 5.5rem !important;
        width: 5.5rem !important;
        overflow: hidden;
      }
      body.g-sidenav-hidden .sidenav .nav-link-text,
      body.g-sidenav-hidden .sidenav .navbar-brand span,
      body.g-sidenav-hidden .sidenav .sidenav-footer .card-body div > div:last-child,
      body.g-sidenav-hidden .sidenav .badge:not(.avatar) {
        display: none !important;
      }
      body.g-sidenav-hidden .sidenav .nav-link {
        justify-content: center !important;
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
      }
      body.g-sidenav-hidden .sidenav .nav-link .icon {
        margin-right: 0 !important;
      }
      body.g-sidenav-hidden .sidenav .navbar-brand {
        justify-content: center !important;
        margin-right: 0 !important;
        padding: 0.5rem 0 !important;
      }
      body.g-sidenav-hidden .sidenav .sidenav-footer {
        margin-left: 0.5rem !important;
        margin-right: 0.5rem !important;
      }
      body.g-sidenav-hidden .sidenav .sidenav-footer .card-body {
        padding: 0.5rem !important;
      }
      body.g-sidenav-hidden .sidenav .sidenav-footer .card-body .d-flex {
        justify-content: center !important;
      }
      body.g-sidenav-hidden .sidenav .sidenav-footer .card-body .icon {
        margin-right: 0 !important;
      }
      body.g-sidenav-hidden .main-content {
        margin-left: 7.75rem !important;
        padding-left: 1.5rem !important;
        padding-right: 1.5rem !important;
      }
    }

    /* Sidebar Header Layout for Toggle Button */
    .sidenav-header {
      display: flex !important;
      align-items: center !important;
      justify-content: space-between !important;
      padding: 0 1.25rem !important;
    }
    .sidenav-header .navbar-brand {
      padding: 1.5rem 0 !important;
      margin: 0 !important;
    }
    #iconNavbarSidenav {
      z-index: 1000;
      padding: 0.5rem;
      border-radius: 0.375rem;
      transition: all 0.2s ease;
      margin-top: 0.25rem;
      position: relative;
    }
    #iconNavbarSidenav:hover {
      background-color: rgba(0, 0, 0, 0.05);
    }
    
    /* Style when sidebar is minimized (hidden) */
    @media (min-width: 1200px) {
      body.g-sidenav-hidden .sidenav-header {
        flex-direction: column !important;
        justify-content: center !important;
        align-items: center !important;
        height: auto !important;
        padding: 1.5rem 0 !important;
        gap: 0.75rem;
      }
      body.g-sidenav-hidden .sidenav-header .navbar-brand {
        padding: 0 !important;
      }
      body.g-sidenav-hidden #iconNavbarSidenav {
        margin: 0 !important;
      }
    }

    /* Mobile Sidebar Toggle Off-Canvas */
    @media (max-width: 1199.98px) {
      body.g-sidenav-hidden .sidenav {
        transform: translateX(-21rem) !important;
      }
      body.g-sidenav-hidden .main-content {
        margin-left: 0 !important;
      }
    }
  </style>

  @stack('styles')
</head>

<body class="g-sidenav-show g-sidenav-pinned bg-gray-100">
  <div class="min-height-300 bg-primary position-absolute w-100"></div>

  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 shadow-lg" id="sidenav-main">
    <div class="sidenav-header">
      <a class="navbar-brand m-0 d-flex align-items-center" href="{{ route('home') }}">
        <img src="{{ asset($argon . '/img/epresensi-logo.png') }}" class="navbar-brand-img shadow-sm" alt="logo">
        <span class="ms-2 font-weight-bold text-dark">E-Presensi</span>
      </a>
      <div class="sidenav-toggler sidenav-toggler-inner cursor-pointer" id="iconNavbarSidenav" style="cursor: pointer;">
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
                <?php
                  $sidebarPendingCount = \App\Models\User::where('status', 'pending')->count();
                ?>
                @if($sidebarPendingCount > 0)
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
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-0 mb-3 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <div class="d-flex align-items-center me-3 d-xl-none">
          <div class="sidenav-toggler sidenav-toggler-inner cursor-pointer" id="iconNavbarSidenavMobile" style="cursor: pointer;">
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

  @yield('scripts')

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const iconNavbarSidenav = document.getElementById('iconNavbarSidenav');
      const iconNavbarSidenavMobile = document.getElementById('iconNavbarSidenavMobile');
      const iconSidenav = document.getElementById('iconSidenav');
      const body = document.body;

      function toggleSidebar(e) {
        if (e) e.preventDefault();
        if (body.classList.contains('g-sidenav-pinned')) {
          body.classList.remove('g-sidenav-pinned');
          body.classList.add('g-sidenav-hidden');
        } else {
          body.classList.remove('g-sidenav-hidden');
          body.classList.add('g-sidenav-pinned');
        }
      }

      if (iconNavbarSidenav) {
        iconNavbarSidenav.addEventListener('click', toggleSidebar);
      }
      if (iconNavbarSidenavMobile) {
        iconNavbarSidenavMobile.addEventListener('click', toggleSidebar);
      }
      if (iconSidenav) {
        iconSidenav.addEventListener('click', toggleSidebar);
      }
    });
  </script>
</body>

</html>
