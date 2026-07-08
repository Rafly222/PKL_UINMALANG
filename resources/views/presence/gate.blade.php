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
          <form action="{{ route('presence.gate', $event->id) }}" method="POST">
            @csrf
            <input type="password" name="password" class="form-control text-center mb-3" placeholder="Password event" required>
            <button type="submit" class="btn bg-gradient-primary w-100 mb-0 shadow">Buka Formulir</button>
          </form>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
