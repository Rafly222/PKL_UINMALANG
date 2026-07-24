<!DOCTYPE html>
<html lang="id">

<head>
  @php
    $argon = 'assets/argon-dashboard-pro-html-v2.0.5/assets';
    $hasPhoto = in_array('sc-photo', $event->fields ?? []);
    $hasSignature = in_array('sc-signature', $event->fields ?? []);
    $hasMedia = $hasPhoto || $hasSignature;
  @endphp
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Form Presensi - E-Presensi</title>
  
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset($argon . '/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset($argon . '/img/favicon.png') }}">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="{{ asset($argon . '/css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset($argon . '/css/nucleo-svg.css') }}" rel="stylesheet" />
  <script src="{{ asset($argon . '/js/42d5adcbca.js') }}" crossorigin="anonymous"></script>
  <link id="pagestyle" href="{{ asset($argon . '/css/argon-dashboard.min.css') }}" rel="stylesheet" />

  <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>

<body class="bg-gray-100 ep-form-page">
  <div class="min-height-300 bg-primary position-absolute w-100" style="height: 35vh !important; min-height: unset !important;"></div>
  
  <main class="main-content position-relative border-radius-lg">
    <div class="container py-3">

      <!-- Form Wrapper -->
      <div class="row justify-content-center">
        <div class="col-12">
          @if($isBypassed)
            <div class="alert alert-success text-white shadow-lg border-0 d-flex align-items-center p-2 mb-2" role="alert" style="font-size: 13px;">
              <div class="icon icon-shape bg-white shadow text-center border-radius-md me-2 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; min-width: 28px;">
                <i class="ni ni-badge text-success text-xs"></i>
              </div>
              <div>
                <span class="font-weight-bolder">Mode Uji Coba Aktif</span> ·
                <span class="opacity-9">Validasi waktu dan password dilewati.</span>
              </div>
            </div>
          @endif

          @includeWhen(session('success') || session('warning') || session('info') || $errors->any(), 'partials.flash')

          <div class="card ep-card ep-form-card">
            <div class="card-header bg-transparent pb-0 pt-3">
              <div class="d-flex align-items-center">
                <div class="me-3">
                  <img src="{{ asset('assets/argon-dashboard-pro-html-v2.0.5/assets/img/icons/flags/logo.png') }}" alt="Logo" style="width: 38px; height: 38px; object-fit: contain;">
                </div>
                <div>
                  <h5 class="mb-0 text-md font-weight-bolder">{{ $event->name }}</h5>
                  <p class="text-xs text-muted mb-0">
                    {{ \Carbon\Carbon::parse($event->date)->translatedFormat('d F Y') }} ·
                    {{ \Carbon\Carbon::parse($event->time_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($event->time_end)->format('H:i') }} WIB
                  </p>
                </div>
              </div>
            </div>
            
            <div class="card-body p-3">
              <form action="{{ route('presence.form', $event->uuid) }}" method="POST" id="presence-main-form">
                @csrf
                <input type="hidden" name="photo" id="photo-base64">
                <input type="hidden" name="signature" id="signature-base64">


                @if($hasMedia)
                  <div class="row">
                    <!-- Kolom Kiri: Identitas Peserta -->
                    <div class="col-lg-6 pb-2 kolom-kiri" style="border-right: 1px solid #e9ecef;">
                      <!-- Kategori Kehadiran -->
                      @if($event->audience_type === 'semua')
                        <div class="mb-2">
                          <label class="form-control-label text-xs">Kategori Kehadiran <span class="text-danger">*</span></label>
                          <div class="d-flex gap-3 mt-1">
                            <div class="form-check">
                              <input class="form-check-input" type="radio" name="tipe_peserta" id="tipe_peserta_umum" value="umum" @checked(old('tipe_peserta', 'umum') === 'umum') onchange="toggleParticipantType()">
                              <label class="form-check-label text-xs font-weight-bold" for="tipe_peserta_umum" style="cursor: pointer;">
                                Masyarakat Umum (Warga Biasa)
                              </label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" name="tipe_peserta" id="tipe_peserta_pegawai" value="pegawai" @checked(old('tipe_peserta') === 'pegawai') onchange="toggleParticipantType()">
                              <label class="form-check-label text-xs font-weight-bold" for="tipe_peserta_pegawai" style="cursor: pointer;">
                                Pegawai Pemerintah (ASN/Non-ASN)
                              </label>
                            </div>
                          </div>
                        </div>
                      @elseif($event->audience_type === 'pegawai')
                        <input type="hidden" name="tipe_peserta" id="tipe_peserta" value="pegawai">
                      @else
                        <input type="hidden" name="tipe_peserta" id="tipe_peserta" value="umum">
                      @endif

                      <!-- Integrasi Data Pegawai -->
                      <div id="nip-card-wrapper" style="display: {{ $event->audience_type === 'pegawai' ? 'block' : 'none' }};">
                        <div class="card ep-card bg-gray-100 border-0 mb-2">
                          <div class="card-body p-2">
                            <div class="d-flex align-items-center mb-2">
                              <div class="icon icon-shape bg-gradient-info shadow text-center rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; min-width: 24px;">
                                <i class="fas fa-id-card text-white" style="font-size: 10px;"></i>
                              </div>
                              <div>
                                <h6 class="text-xs mb-0 font-weight-bold">Integrasi Data Pegawai</h6>
                              </div>
                            </div>
                            <div class="d-flex align-items-end gap-2">
                              <div class="flex-grow-1">
                                <label class="form-control-label text-xxs">NIP Pegawai <span class="text-danger">*</span></label>
                                <input type="text" name="nip" id="form-nip" value="{{ old('nip') }}" placeholder="18 digit NIP" class="form-control form-control-sm" maxlength="18" autocomplete="on">
                              </div>
                              <div>
                                <button type="button" onclick="fetchEmployeeApi(this)" class="btn btn-xs bg-gradient-info mb-0 shadow" style="min-width: 85px;">
                                  Cari NIP
                                </button>
                              </div>
                            </div>
                            <!-- Indikator Loading NIP -->
                            <div class="nip-loading-indicator mt-2 text-start" style="display: none;">
                              <div class="d-flex align-items-center gap-2">
                                <div class="spinner-border text-info" role="status" style="width: 12px; height: 12px; border-width: 1.5px; margin: 0;"></div>
                                <span class="text-xxs text-info font-weight-bold">Sedang memverifikasi data NIP, mohon tunggu sebentar...</span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <span class="ep-section-label">Identitas Peserta</span>
                      <div class="row mt-1">
                        <div class="col-12 mb-2">
                          <label class="form-control-label text-xs">Nama Lengkap <span class="text-danger">*</span></label>
                          <input type="text" name="name" id="form-name" required placeholder="Nama lengkap beserta gelar" class="form-control form-control-sm" value="{{ old('name') }}" autocomplete="name">
                        </div>

                        @if(in_array('sc-phone', $event->fields ?? []))
                          <div class="col-md-6 mb-2">
                            <label class="form-control-label text-xs">Nomor WhatsApp <span class="text-danger">*</span></label>
                            <input type="tel" name="phone" id="form-phone" required placeholder="Contoh: 081234567890" class="form-control form-control-sm" value="{{ old('phone') }}" autocomplete="tel">
                          </div>
                        @endif

                        @if(in_array('sc-gender', $event->fields ?? []))
                          <div class="col-md-6 mb-2">
                            <label class="form-control-label text-xs d-block">Jenis Kelamin</label>
                            <div class="d-flex gap-3 mt-1 align-items-center" style="min-height: 40px;">
                              <div class="form-check mb-0">
                                <input class="form-check-input" type="radio" name="gender" id="gender-peg-l" value="Laki-Laki" @checked(old('gender') === 'Laki-Laki' || !old('gender')) required>
                                <label class="form-check-label text-xs font-weight-bold cursor-pointer" for="gender-peg-l">Laki-Laki</label>
                              </div>
                              <div class="form-check mb-0">
                                <input class="form-check-input" type="radio" name="gender" id="gender-peg-p" value="Perempuan" @checked(old('gender') === 'Perempuan') required>
                                <label class="form-check-label text-xs font-weight-bold cursor-pointer" for="gender-peg-p">Perempuan</label>
                              </div>
                            </div>
                          </div>
                        @endif

                        @if(in_array('sc-institution', $event->fields ?? []))
                          <div class="col-md-6 mb-2">
                            <label class="form-control-label text-xs">Instansi / Lembaga <span class="text-danger">*</span></label>
                            <input type="text" name="institution" id="form-institution" required placeholder="Nama instansi/lembaga" class="form-control form-control-sm" value="{{ old('institution') }}" autocomplete="organization">
                          </div>
                        @endif

                        @if(in_array('sc-email', $event->fields ?? []))
                          <div class="col-md-6 mb-2">
                            <label class="form-control-label text-xs">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="form-email" required placeholder="Contoh: nama@domain.com" class="form-control form-control-sm" value="{{ old('email') }}" autocomplete="email">
                          </div>
                        @endif
                      </div>

                      @if($event->custom_fields && count($event->custom_fields) > 0)
                        <hr class="horizontal dark my-2">
                        <div class="row">
                          @foreach($event->custom_fields as $cf)
                            <?php 
                              $slug = \Illuminate\Support\Str::slug($cf['label'], '_');
                              $isKhususPegawai = stripos($cf['label'], 'khusus pegawai') !== false;
                              $isKhususTamu = stripos($cf['label'], 'khusus tamu') !== false || stripos($cf['label'], 'khusus masyarakat') !== false || stripos($cf['label'], 'khusus umum') !== false;
                            ?>
                            <div class="col-md-6 mb-2 custom-field-item" 
                                 {{ ($isKhususPegawai && $event->audience_type === 'semua') ? 'data-khusus-pegawai=true' : '' }}
                                 {{ ($isKhususTamu && $event->audience_type === 'semua') ? 'data-khusus-tamu=true' : '' }}>
                              <label class="form-control-label text-xs">
                                <span class="field-label-text">{{ $cf['label'] }}</span>
                                <span class="text-danger field-asterisk">*</span>
                              </label>
                              <input type="{{ $cf['type'] }}" name="{{ $slug }}" id="cf-{{ $slug }}" required placeholder="Ketik {{ $cf['label'] }}" class="form-control form-control-sm">
                            </div>
                          @endforeach
                        </div>
                      @endif
                    </div>

                    <!-- Kolom Kanan: Bukti Media & Submit -->
                    <div class="col-lg-6 ps-lg-4 d-flex flex-column justify-content-between">
                      <div>
                        @if($hasPhoto)
                          <span class="ep-section-label">Capture Foto Wajah</span>
                          <div class="row align-items-center mt-1 mb-2">
                            <div class="col-sm-6 col-12 mb-2 mb-sm-0">
                              <div class="ep-media-frame position-relative" style="height: 180px; width: 100%;">
                                <video id="webcam-preview" autoplay playsinline class="w-100 h-100 d-none" style="object-fit: cover;"></video>
                                <div id="webcam-fallback" class="d-flex flex-column align-items-center justify-content-center h-100 text-center text-white p-1">
                                  <i class="fas fa-video-slash text-xs mb-1 opacity-8"></i>
                                  <span class="text-xxs font-weight-bold">Kamera off</span>
                                </div>
                                <canvas id="captured-canvas" class="d-none position-absolute top-0 start-0 w-100 h-100" style="object-fit: cover;"></canvas>
                                <!-- Indikator Deteksi Wajah Real-Time (Tanpa Bar Hitam) -->
                                <div id="face-detection-badge" class="position-absolute bottom-0 start-0 w-100 mb-2 text-center d-none" style="z-index: 5; pointer-events: none;">
                                  <span id="face-status-text" class="text-xxs font-weight-bold text-white">
                                    <i class="fas fa-spinner fa-spin me-1"></i> Mendeteksi Wajah...
                                  </span>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-6 col-12 col-md-5">
                              <button type="button" id="btn-open-camera" onclick="activateWebcam()" class="btn btn-xs btn-outline-primary w-100 mb-1 py-1">
                                Buka Kamera
                              </button>
                              <button type="button" id="btn-snap-photo" onclick="snapPhoto()" class="btn btn-xs bg-gradient-danger text-white w-100 mb-1 py-1 opacity-5" disabled>
                                Capture Foto
                              </button>
                              <button type="button" id="btn-retake-photo" onclick="resetWebcamCapture()" class="btn btn-xs btn-outline-secondary w-100 mb-0 d-none py-1">
                                Ambil Ulang
                              </button>
                            </div>
                          </div>
                        @endif

                        @if($hasSignature)
                          @if($hasPhoto) <hr class="horizontal dark my-2"> @endif
                          <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="ep-section-label">Tanda Tangan Digital</span>
                            <button type="button" onclick="resetSignaturePad()" class="btn btn-xs btn-outline-secondary mb-0 py-0 px-2" style="font-size: 10px; min-height: 20px;">
                              Bersihkan
                            </button>
                          </div>
                          <div class="ep-signature-frame overflow-hidden mb-1">
                            <canvas id="signature-canvas" class="w-100 bg-white cursor-crosshair" style="height: 170px; touch-action: none;"></canvas>
                          </div>
                        @endif
                      </div>

                      <div class="text-end pt-2 border-top mt-2">
                        <button type="submit" class="btn bg-gradient-success w-100 mb-0 shadow py-2 font-weight-bolder">
                          <i class="fas fa-check-circle me-1"></i> Kirim Data Presensi
                        </button>
                      </div>
                    </div>
                  </div>

                @else
                  <!-- Tampilan Default Single Column jika tidak ada Photo dan Signature -->
                  <div class="row justify-content-center">
                    <div class="col-lg-8">
                       <!-- Kategori Kehadiran -->
                      @if($event->audience_type === 'semua')
                        <div class="mb-3">
                          <label class="form-control-label text-xs">Kategori Kehadiran <span class="text-danger">*</span></label>
                          <div class="d-flex gap-3 mt-1">
                            <div class="form-check">
                              <input class="form-check-input" type="radio" name="tipe_peserta" id="tipe_peserta_umum" value="umum" @checked(old('tipe_peserta', 'umum') === 'umum') onchange="toggleParticipantType()">
                              <label class="form-check-label text-xs font-weight-bold" for="tipe_peserta_umum" style="cursor: pointer;">
                                Masyarakat Umum (Warga Biasa)
                              </label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" name="tipe_peserta" id="tipe_peserta_pegawai" value="pegawai" @checked(old('tipe_peserta') === 'pegawai') onchange="toggleParticipantType()">
                              <label class="form-check-label text-xs font-weight-bold" for="tipe_peserta_pegawai" style="cursor: pointer;">
                                Pegawai Pemerintah (ASN/Non-ASN)
                              </label>
                            </div>
                          </div>
                        </div>
                      @elseif($event->audience_type === 'pegawai')
                        <input type="hidden" name="tipe_peserta" id="tipe_peserta" value="pegawai">
                      @else
                        <input type="hidden" name="tipe_peserta" id="tipe_peserta" value="umum">
                      @endif

                      <!-- Integrasi Data Pegawai -->
                      <div id="nip-card-wrapper" style="display: {{ $event->audience_type === 'pegawai' ? 'block' : 'none' }};">
                        <div class="card ep-card bg-gray-100 border-0 mb-3">
                          <div class="card-body p-3">
                            <div class="d-flex align-items-center mb-2">
                              <div class="icon icon-shape bg-gradient-info shadow text-center rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; min-width: 28px;">
                                <i class="fas fa-id-card text-white" style="font-size: 12px;"></i>
                              </div>
                              <div>
                                <h6 class="text-xs mb-0 font-weight-bold">Integrasi Data Pegawai</h6>
                              </div>
                            </div>
                            <div class="d-flex align-items-end gap-2">
                              <div class="flex-grow-1">
                                <label class="form-control-label text-xxs">NIP Pegawai <span class="text-danger">*</span></label>
                                <input type="text" name="nip" id="form-nip" value="{{ old('nip') }}" placeholder="18 digit NIP" class="form-control form-control-sm" maxlength="18" autocomplete="on">
                              </div>
                              <div>
                                <button type="button" onclick="fetchEmployeeApi(this)" class="btn btn-xs bg-gradient-info mb-0 shadow" style="min-width: 90px;">
                                  Cari NIP
                                </button>
                              </div>
                            </div>
                            <!-- Indikator Loading NIP -->
                            <div class="nip-loading-indicator mt-2 text-start" style="display: none;">
                              <div class="d-flex align-items-center gap-2">
                                <div class="spinner-border text-info" role="status" style="width: 12px; height: 12px; border-width: 1.5px; margin: 0;"></div>
                                <span class="text-xxs text-info font-weight-bold">Sedang memverifikasi data NIP, mohon tunggu sebentar...</span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <span class="ep-section-label">Identitas Peserta</span>
                      <div class="row mt-2">
                        <div class="col-12 mb-3">
                          <label class="form-control-label text-xs">Nama Lengkap <span class="text-danger">*</span></label>
                          <input type="text" name="name" id="form-name" required placeholder="Nama lengkap beserta gelar" class="form-control form-control-sm" value="{{ old('name') }}" autocomplete="name">
                        </div>

                        @if(in_array('sc-phone', $event->fields ?? []))
                          <div class="col-md-6 mb-3">
                            <label class="form-control-label text-xs">Nomor WhatsApp <span class="text-danger">*</span></label>
                            <input type="tel" name="phone" id="form-phone" required placeholder="Contoh: 081234567890" class="form-control form-control-sm" value="{{ old('phone') }}" autocomplete="tel">
                          </div>
                        @endif

                        @if(in_array('sc-gender', $event->fields ?? []))
                          <div class="col-md-6 mb-3">
                            <label class="form-control-label text-xs d-block">Jenis Kelamin</label>
                            <div class="d-flex gap-3 mt-1 align-items-center" style="min-height: 40px;">
                              <div class="form-check mb-0">
                                <input class="form-check-input" type="radio" name="gender" id="gender-um-l" value="Laki-Laki" @checked(old('gender') === 'Laki-Laki' || !old('gender')) required>
                                <label class="form-check-label text-xs font-weight-bold cursor-pointer" for="gender-um-l">Laki-Laki</label>
                              </div>
                              <div class="form-check mb-0">
                                <input class="form-check-input" type="radio" name="gender" id="gender-um-p" value="Perempuan" @checked(old('gender') === 'Perempuan') required>
                                <label class="form-check-label text-xs font-weight-bold cursor-pointer" for="gender-um-p">Perempuan</label>
                              </div>
                            </div>
                          </div>
                        @endif

                        @if(in_array('sc-institution', $event->fields ?? []))
                          <div class="col-md-6 mb-3">
                            <label class="form-control-label text-xs">Instansi / Lembaga <span class="text-danger">*</span></label>
                            <input type="text" name="institution" id="form-institution" required placeholder="Nama instansi/lembaga" class="form-control form-control-sm" value="{{ old('institution') }}" autocomplete="organization">
                          </div>
                        @endif

                        @if(in_array('sc-email', $event->fields ?? []))
                          <div class="col-md-6 mb-3">
                            <label class="form-control-label text-xs">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="form-email" required placeholder="Contoh: nama@domain.com" class="form-control form-control-sm" value="{{ old('email') }}" autocomplete="email">
                          </div>
                        @endif
                      </div>

                      @if($event->custom_fields && count($event->custom_fields) > 0)
                        <hr class="horizontal dark my-3">
                        <div class="row">
                          @foreach($event->custom_fields as $cf)
                            <?php 
                              $slug = \Illuminate\Support\Str::slug($cf['label'], '_');
                              $isKhususPegawai = stripos($cf['label'], 'khusus pegawai') !== false;
                              $isKhususTamu = stripos($cf['label'], 'khusus tamu') !== false || stripos($cf['label'], 'khusus masyarakat') !== false || stripos($cf['label'], 'khusus umum') !== false;
                            ?>
                            <div class="col-md-6 mb-3 custom-field-item" 
                                 {{ ($isKhususPegawai && $event->audience_type === 'semua') ? 'data-khusus-pegawai=true' : '' }}
                                 {{ ($isKhususTamu && $event->audience_type === 'semua') ? 'data-khusus-tamu=true' : '' }}>
                              <label class="form-control-label text-xs">
                                <span class="field-label-text">{{ $cf['label'] }}</span>
                                <span class="text-danger field-asterisk">*</span>
                              </label>
                              <input type="{{ $cf['type'] }}" name="{{ $slug }}" id="cf-{{ $slug }}" required placeholder="Ketik {{ $cf['label'] }}" class="form-control form-control-sm">
                            </div>
                          @endforeach
                        </div>
                      @endif

                      <div class="text-end pt-3 mt-3 border-top">
                        <button type="submit" class="btn bg-gradient-success px-5 mb-0 shadow py-2 font-weight-bolder">
                          <i class="fas fa-check-circle me-1"></i> Kirim Data Presensi
                        </button>
                      </div>
                    </div>
                  </div>
                @endif
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.js"></script>
  <script src="{{ asset($argon . '/js/core/popper.min.js') }}"></script>
  <script src="{{ asset($argon . '/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset($argon . '/js/argon-dashboard.min.js') }}"></script>

  <script>
    function toggleParticipantType() {
      let tipePeserta = 'umum';
      const radioPegawai = document.getElementById('tipe_peserta_pegawai');
      const radioUmum = document.getElementById('tipe_peserta_umum');
      
      if (radioPegawai && radioPegawai.checked) {
        tipePeserta = 'pegawai';
      } else if (radioUmum && radioUmum.checked) {
        tipePeserta = 'umum';
      } else {
        const hiddenTipe = document.getElementById('tipe_peserta');
        if (hiddenTipe) {
          tipePeserta = hiddenTipe.value;
        } else {
          return;
        }
      }
      
      const nipWrapper = document.getElementById('nip-card-wrapper');
      const nipInput = document.getElementById('form-nip');
      
      if (tipePeserta === 'pegawai') {
        nipWrapper.style.display = 'block';
        nipInput.setAttribute('required', 'required');
      } else {
        nipWrapper.style.display = 'none';
        nipInput.removeAttribute('required');
        nipInput.value = '';
      }

      const khususPegawaiFields = document.querySelectorAll('[data-khusus-pegawai="true"]');
      khususPegawaiFields.forEach(field => {
        const input = field.querySelector('input');
        const asterisk = field.querySelector('.field-asterisk');
        
        if (tipePeserta === 'pegawai') {
          field.style.display = 'block';
          if (input) input.setAttribute('required', 'required');
          if (asterisk) asterisk.style.display = 'inline';
        } else {
          field.style.display = 'none';
          if (input) {
            input.removeAttribute('required');
            input.value = '';
          }
          if (asterisk) asterisk.style.display = 'none';
        }
      });

      const khususTamuFields = document.querySelectorAll('[data-khusus-tamu="true"]');
      khususTamuFields.forEach(field => {
        const input = field.querySelector('input');
        const asterisk = field.querySelector('.field-asterisk');
        
        if (tipePeserta === 'umum') {
          field.style.display = 'block';
          if (input) input.setAttribute('required', 'required');
          if (asterisk) asterisk.style.display = 'inline';
        } else {
          field.style.display = 'none';
          if (input) {
            input.removeAttribute('required');
            input.value = '';
          }
          if (asterisk) asterisk.style.display = 'none';
        }
      });
    }

    document.addEventListener('DOMContentLoaded', () => {
      toggleParticipantType();
    });

    function fetchEmployeeApi(btn) {
      const nipInput = document.getElementById('form-nip');
      if (!nipInput) return;
      const nip = nipInput.value.trim();
      if(!nip) {
        alert('Masukkan nomor NIP pegawai terlebih dahulu!');
        return;
      }

      const parentCard = nipInput.closest('.card-body');
      const loadingIndicator = parentCard ? parentCard.querySelector('.nip-loading-indicator') : null;
      
      if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Mencari...';
      }
      if (loadingIndicator) {
        loadingIndicator.style.display = 'block';
      }

      fetch(`/api/pegawai/${nip}`)
        .then(res => res.json())
        .then(data => {
          if (btn) {
            btn.disabled = false;
            btn.innerHTML = 'Cari NIP';
          }
          if (loadingIndicator) {
            loadingIndicator.style.display = 'none';
          }

          if(data.success) {
            document.getElementById('form-name').value = data.data.name;
            if(document.getElementById('form-phone')) document.getElementById('form-phone').value = data.data.phone;
            if(document.getElementById('form-institution')) document.getElementById('form-institution').value = data.data.institution;
            alert('Integrasi API berhasil. Data pegawai terisi otomatis.');
          } else {
            alert('Identitas pegawai tidak ditemukan di pangkalan data Kota Malang.');
          }
        })
        .catch(err => {
          if (btn) {
            btn.disabled = false;
            btn.innerHTML = 'Cari NIP';
          }
          if (loadingIndicator) {
            loadingIndicator.style.display = 'none';
          }
          alert('Terjadi kesalahan jaringan atau server saat mencari NIP.');
        });
    }

    let activeStream = null;
    let faceModelsLoaded = false;
    let faceCheckInterval = null;
    let isFacePresent = false;

    async function initFaceDetector() {
      if (window.FaceDetector) return true;
      if (typeof faceapi !== 'undefined' && !faceModelsLoaded) {
        try {
          const modelUrl = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api/model/';
          await faceapi.nets.tinyFaceDetector.loadFromUri(modelUrl);
          faceModelsLoaded = true;
          return true;
        } catch(e) {
          console.warn('FaceAPI CDN model failed to load:', e);
        }
      }
      return false;
    }

    function startFaceDetection(video) {
      const badge = document.getElementById('face-detection-badge');
      const badgeText = document.getElementById('face-status-text');
      const btnSnap = document.getElementById('btn-snap-photo');

      if(badge) badge.classList.remove('d-none');
      if(btnSnap) {
        btnSnap.disabled = true;
        btnSnap.classList.add('opacity-5');
      }
      isFacePresent = false;

      initFaceDetector().then(() => {
        if (faceCheckInterval) clearInterval(faceCheckInterval);

        faceCheckInterval = setInterval(async () => {
          if (!activeStream || video.paused || video.ended || video.classList.contains('d-none')) {
            clearInterval(faceCheckInterval);
            return;
          }

          let faceDetected = false;

          try {
            if (window.FaceDetector) {
              const detector = new window.FaceDetector({ fastMode: true, maxFaces: 5 });
              const faces = await detector.detect(video);
              faceDetected = faces && faces.length > 0;
            } else if (typeof faceapi !== 'undefined' && faceModelsLoaded) {
              const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.35 }));
              faceDetected = detections && detections.length > 0;
            } else {
              faceDetected = analyzeFaceSkinContour(video);
            }
          } catch(e) {
            faceDetected = analyzeFaceSkinContour(video);
          }

          isFacePresent = faceDetected;

          if (faceDetected) {
            if (badgeText) {
              badgeText.innerHTML = '<span class="badge bg-success py-1 px-2 text-white"><i class="fas fa-user-check me-1"></i> Wajah Terdeteksi</span>';
            }
            if (btnSnap) {
              btnSnap.disabled = false;
              btnSnap.classList.remove('opacity-5');
            }
          } else {
            if (badgeText) {
              badgeText.innerHTML = '<span class="badge bg-danger py-1 px-2 text-white"><i class="fas fa-user-slash me-1"></i> Wajah Tidak Terdeteksi</span>';
            }
            if (btnSnap) {
              btnSnap.disabled = true;
              btnSnap.classList.add('opacity-5');
            }
          }
        }, 300);
      });
    }

    function analyzeFaceSkinContour(video) {
      const canvas = document.createElement('canvas');
      canvas.width = 160;
      canvas.height = 120;
      const ctx = canvas.getContext('2d');
      ctx.drawImage(video, 0, 0, 160, 120);
      const imgData = ctx.getImageData(0, 0, 160, 120);
      const data = imgData.data;

      let skinPixels = 0;
      for (let i = 0; i < data.length; i += 4) {
        const r = data[i], g = data[i+1], b = data[i+2];
        const cb = 128 - 0.168736 * r - 0.331264 * g + 0.5 * b;
        const cr = 128 + 0.5 * r - 0.418688 * g - 0.081312 * b;
        if (r > 60 && g > 40 && b > 20 && (r - Math.min(g, b)) > 15 && Math.abs(r - g) > 15 && cr > 135 && cr < 180 && cb > 85 && cb < 135) {
          skinPixels++;
        }
      }
      const ratio = skinPixels / (160 * 120);
      return ratio >= 0.08 && ratio <= 0.70;
    }

    function activateWebcam() {
      const video = document.getElementById('webcam-preview');
      const fallback = document.getElementById('webcam-fallback');
      const btnOpen = document.getElementById('btn-open-camera');
      if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false })
          .then(stream => {
            activeStream = stream;
            video.srcObject = stream;
            video.classList.remove('d-none');
            fallback.classList.add('d-none');
            if (btnOpen) btnOpen.classList.add('d-none');
            startFaceDetection(video);
          }).catch(() => {
            alert('Akses kamera diblokir browser. Pastikan izin kamera aktif.');
          });
      }
    }

    function snapPhoto() {
      const canvas = document.getElementById('captured-canvas');
      const video = document.getElementById('webcam-preview');
      const btnOpen = document.getElementById('btn-open-camera');
      const btnRetake = document.getElementById('btn-retake-photo');
      const btnSnap = document.getElementById('btn-snap-photo');
      const badge = document.getElementById('face-detection-badge');

      if (activeStream && !video.classList.contains('d-none')) {
        if (!isFacePresent) {
          alert('Peringatan: Wajah tidak terdeteksi oleh kamera! Harap posisikan wajah Anda tepat di depan kamera.');
          return;
        }
      }

      const ctx = canvas.getContext('2d');
      const targetWidth = 640;
      const targetHeight = 480;
      canvas.width = targetWidth;
      canvas.height = targetHeight;

      if(activeStream && !video.classList.contains('d-none')) {
        const videoWidth = video.videoWidth;
        const videoHeight = video.videoHeight;

        if (videoWidth && videoHeight) {
          const videoRatio = videoWidth / videoHeight;
          const targetRatio = targetWidth / targetHeight;

          let sx, sy, sWidth, sHeight;

          if (videoRatio > targetRatio) {
            // Video lebih lebar daripada target canvas (landscape) -> potong bagian kanan & kiri
            sHeight = videoHeight;
            sWidth = videoHeight * targetRatio;
            sx = (videoWidth - sWidth) / 2;
            sy = 0;
          } else {
            // Video lebih tinggi daripada target canvas (portrait HP) -> potong bagian atas & bawah
            sWidth = videoWidth;
            sHeight = videoWidth / targetRatio;
            sx = 0;
            sy = (videoHeight - sHeight) / 2;
          }

          ctx.drawImage(video, sx, sy, sWidth, sHeight, 0, 0, targetWidth, targetHeight);
        } else {
          // Fallback jika dimensi asli video belum siap
          ctx.drawImage(video, 0, 0, targetWidth, targetHeight);
        }
        video.classList.add('d-none');
      } else {
        ctx.fillStyle = '#172b4d';
        ctx.fillRect(0, 0, 640, 480);
        ctx.fillStyle = '#ffffff';
        ctx.beginPath(); ctx.arc(320, 190, 80, 0, Math.PI * 2); ctx.fill();
        ctx.fillStyle = '#11cdef';
        ctx.font = 'bold 24px Open Sans, sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('FOTO PRESENSI TERCAPTURE', 320, 340);
      }
      canvas.classList.remove('d-none');
      document.getElementById('photo-base64').value = canvas.toDataURL('image/jpeg');

      if(badge) badge.classList.add('d-none');
      if(btnOpen) btnOpen.classList.add('d-none');
      if(btnSnap) btnSnap.classList.add('d-none');
      if(btnRetake) btnRetake.classList.remove('d-none');
    }

    function resetWebcamCapture() {
      const canvas = document.getElementById('captured-canvas');
      const video = document.getElementById('webcam-preview');
      const input = document.getElementById('photo-base64');
      const btnOpen = document.getElementById('btn-open-camera');
      const btnRetake = document.getElementById('btn-retake-photo');
      const btnSnap = document.getElementById('btn-snap-photo');
      const badge = document.getElementById('face-detection-badge');

      const ctx = canvas.getContext('2d');
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      canvas.classList.add('d-none');
      input.value = '';

      if(btnOpen) btnOpen.classList.add('d-none');
      if(btnRetake) btnRetake.classList.add('d-none');
      if(btnSnap) btnSnap.classList.remove('d-none');
      if(video) {
        video.classList.remove('d-none');
        startFaceDetection(video);
      }
    }

    let isWriting = false;
    let canvasCtx = null;
    let sigCanvas = document.getElementById('signature-canvas');

    if(sigCanvas) {
      canvasCtx = sigCanvas.getContext('2d');

      sigCanvas.addEventListener('pointerdown', e => {
        isWriting = true;
        const box = sigCanvas.getBoundingClientRect();
        canvasCtx.beginPath();
        canvasCtx.moveTo(e.clientX - box.left, e.clientY - box.top);
        sigCanvas.setPointerCapture(e.pointerId);
      });

      sigCanvas.addEventListener('pointermove', e => {
        if(!isWriting) return;
        const box = sigCanvas.getBoundingClientRect();
        canvasCtx.lineTo(e.clientX - box.left, e.clientY - box.top);
        canvasCtx.strokeStyle = '#172b4d';
        canvasCtx.lineWidth = 3.2;
        canvasCtx.lineCap = 'round';
        canvasCtx.stroke();
      });

      sigCanvas.addEventListener('pointerup', e => {
        isWriting = false;
        sigCanvas.releasePointerCapture(e.pointerId);
      });

      sigCanvas.addEventListener('pointercancel', () => {
        isWriting = false;
      });

      window.addEventListener('resize', fitCanvasSize);
      setTimeout(fitCanvasSize, 200);
    }

    function fitCanvasSize() {
      if(!sigCanvas) return;
      const box = sigCanvas.getBoundingClientRect();
      sigCanvas.width = box.width;
      sigCanvas.height = 170;
    }

    function resetSignaturePad() {
      if(sigCanvas) canvasCtx.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
    }

    document.getElementById('presence-main-form').addEventListener('submit', (e) => {
      const hasPhotoField = {{ $hasPhoto ? 'true' : 'false' }};
      const photoVal = document.getElementById('photo-base64').value;
      if (hasPhotoField && !photoVal) {
        e.preventDefault();
        alert('Harap ambil (capture) foto wajah Anda terlebih dahulu sebelum mengirim presensi!');
        return false;
      }
      if(sigCanvas) {
        document.getElementById('signature-base64').value = sigCanvas.toDataURL('image/png');
      }
      if(activeStream) {
        activeStream.getTracks().forEach(track => track.stop());
      }
    });
  </script>
  <script src="{{ asset('js/custom-events.js') }}"></script>
</body>

</html>
