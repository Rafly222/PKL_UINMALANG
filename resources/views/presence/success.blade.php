@extends('layouts.app')

@section('title', 'Presensi Berhasil - E-Presensi')
@section('breadcrumb', 'Presensi Berhasil')
@section('page-title', 'Kartu Kehadiran')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="card ep-card mb-4 overflow-hidden">
      <div class="card-header bg-gradient-success text-center p-4">
        <div class="icon icon-shape bg-white shadow text-center border-radius-lg mx-auto mb-3">
          <i class="ni ni-check-bold text-success"></i>
        </div>
        <h3 class="text-white font-weight-bolder mb-1">Presensi Berhasil Dicatat</h3>
        <p class="text-white opacity-8 text-sm mb-0">Data kehadiran Anda sudah tersimpan di sistem E-Presensi Diskominfo Kota Malang.</p>
      </div>
    </div>

    <div class="card ep-card">
      <div class="card-header pb-0 bg-transparent">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <h6 class="mb-0">Kartu Kehadiran Resmi</h6>
            <p class="text-xs text-muted mb-0">{{ $presence->event->name }}</p>
          </div>
          <span class="badge bg-gradient-success">Hadir</span>
        </div>
      </div>
      <div class="card-body p-4">
        <div class="row align-items-center">
          <div class="col-md-4 mb-4 mb-md-0">
            <div class="ep-media-frame bg-gray-100" style="aspect-ratio: 3 / 4;">
              @if($presence->photo)
                <img src="{{ $presence->photo }}" alt="Foto wajah" class="w-100 h-100" style="object-fit: cover;">
              @else
                <div class="h-100 d-flex align-items-center justify-content-center text-muted text-sm bg-gray-100">Tanpa foto</div>
              @endif
            </div>
          </div>
          <div class="col-md-8">
            <div class="row">
              <div class="col-sm-6 mb-3">
                <p class="ep-section-label mb-1">Nama</p>
                <h5 class="mb-0">{{ $presence->name }}</h5>
              </div>
              <div class="col-sm-6 mb-3">
                <p class="ep-section-label mb-1">Waktu hadir</p>
                <p class="text-sm font-weight-bold mb-0">{{ $presence->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</p>
              </div>
              <div class="col-12 mb-3">
                <p class="ep-section-label mb-1">Instansi</p>
                <p class="text-sm text-dark mb-0">{{ $presence->institution ?? '-' }}</p>
              </div>
              @if($presence->signature)
                <div class="col-12">
                  <p class="ep-section-label mb-1">Tanda tangan</p>
                  <div class="ep-signature-frame p-2 d-inline-block">
                    <img src="{{ $presence->signature }}" alt="Tanda tangan" style="max-height: 90px; max-width: 260px;">
                  </div>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
      <div class="card-footer text-center bg-transparent">
        <a href="{{ route('home') }}" class="btn bg-gradient-primary mb-0">Kembali ke Beranda</a>
      </div>
    </div>
  </div>
</div>
@endsection
