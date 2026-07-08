@extends('layouts.app')

@section('title', 'Form Presensi - E-Presensi')
@section('breadcrumb', 'Form Presensi')
@section('page-title', 'Presensi Mandiri')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="ep-page-hero ep-bg-mesh mb-4">
      <div class="card-body position-relative z-index-2 p-4 p-lg-5">
        <div class="row align-items-center">
          <div class="col-lg-8">
            <span class="badge bg-white text-primary shadow-sm mb-3">Portal Presensi Mandiri</span>
            <h2 class="text-white font-weight-bolder mb-2">{{ $event->name }}</h2>
            <p class="text-white opacity-8 mb-0">
              {{ \Carbon\Carbon::parse($event->date)->translatedFormat('d F Y') }} ·
              {{ \Carbon\Carbon::parse($event->time_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($event->time_end)->format('H:i') }} WIB
            </p>
          </div>
          <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card bg-white shadow-lg border-0">
              <div class="card-body p-3">
                <div class="d-flex align-items-center">
                  <div class="icon icon-shape bg-gradient-success shadow text-center rounded-circle me-3">
                    <i class="ni ni-check-bold text-lg opacity-10"></i>
                  </div>
                  <div>
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Bukti Hadir</p>
                    <h5 class="font-weight-bolder mb-0">Foto & TTD Digital</h5>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row justify-content-center">
  <div class="col-xl-9 col-lg-10">
    @if($isBypassed)
      <div class="alert alert-success text-white shadow-lg border-0 d-flex align-items-center p-3 mb-4" role="alert">
        <div class="icon icon-shape bg-white shadow text-center border-radius-md me-3">
          <i class="ni ni-badge text-success"></i>
        </div>
        <div>
          <span class="font-weight-bolder d-block">Mode Uji Coba Aktif</span>
          <span class="text-sm opacity-9">Anda terdeteksi sebagai pembuat event atau admin, sehingga validasi waktu dan password dilewati.</span>
        </div>
      </div>
    @endif

    <div class="card ep-card ep-form-card mb-5">
      <div class="card-header bg-transparent pb-0">
        <div class="d-flex align-items-center">
          <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md me-3">
            <i class="ni ni-single-copy-04 text-white"></i>
          </div>
          <div>
            <h5 class="mb-0">Formulir Kehadiran</h5>
            <p class="text-sm text-muted mb-0">Lengkapi data sesuai konfigurasi event.</p>
          </div>
        </div>
      </div>
      <div class="card-body p-4">
        <form action="{{ route('presence.form', $event->id) }}" method="POST" id="presence-main-form">
          @csrf
          <input type="hidden" name="photo" id="photo-base64">
          <input type="hidden" name="signature" id="signature-base64">

          <!-- Kategori Kehadiran -->
          @if($event->audience_type !== 'pegawai')
            <div class="mb-4">
              <label class="form-control-label text-xs">Kategori Kehadiran <span class="text-danger">*</span></label>
              <select name="tipe_peserta" id="tipe_peserta" class="form-control" onchange="toggleParticipantType()">
                <option value="umum" @selected(old('tipe_peserta') === 'umum')>Masyarakat Umum (Warga Biasa)</option>
                <option value="pegawai" @selected(old('tipe_peserta') === 'pegawai')>Pegawai Pemerintah (ASN/Non-ASN)</option>
              </select>
            </div>
          @else
            <input type="hidden" name="tipe_peserta" id="tipe_peserta" value="pegawai">
          @endif

          <!-- Integrasi Data Pegawai (ASN/Non-ASN) -->
          <div id="nip-card-wrapper" style="display: {{ $event->audience_type === 'pegawai' ? 'block' : 'none' }};">
            <div class="card ep-card bg-gray-100 border-0 mb-4">
              <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                  <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md me-3">
                    <i class="ni ni-circle-08 text-white"></i>
                  </div>
                  <div>
                    <h6 class="mb-0">Integrasi Data Pegawai</h6>
                    <p class="text-xs text-muted mb-0">NIP valid akan mengisi nama, instansi, dan kontak otomatis.</p>
                  </div>
                </div>
                <div class="row align-items-end">
                  <div class="col-md-8 mb-3 mb-md-0">
                    <label class="form-control-label text-xs">NIP Pegawai <span class="text-danger">*</span></label>
                    <input type="text" name="nip" id="form-nip" placeholder="Masukkan 18 digit NIP" class="form-control" maxlength="18">
                  </div>
                  <div class="col-md-4">
                    <button type="button" onclick="fetchEmployeeApi()" class="btn bg-gradient-info w-100 mb-0 shadow">
                      <i class="fas fa-search me-1"></i> Hubungkan API
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <span class="ep-section-label">Identitas Peserta</span>
          <div class="row mt-3">
            <div class="col-md-6 mb-3">
              <label class="form-control-label text-xs">NIK (Nomor Induk Kependudukan) <span class="text-danger">*</span></label>
              <input type="text" name="nik" id="form-nik-field" required placeholder="Masukkan 16 digit NIK" class="form-control" maxlength="16" pattern="\d{16}" title="NIK harus terdiri dari 16 digit angka" value="{{ old('nik') }}">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-control-label text-xs">Nama Lengkap <span class="text-danger">*</span></label>
              <input type="text" name="name" id="form-name" required placeholder="Nama lengkap beserta gelar" class="form-control" value="{{ old('name') }}">
            </div>

            @if(in_array('sc-phone', $event->fields ?? []))
              <div class="col-md-6 mb-3">
                <label class="form-control-label text-xs">Nomor WhatsApp <span class="text-danger">*</span></label>
                <input type="tel" name="phone" id="form-phone" required placeholder="Contoh: 081234567890" class="form-control">
              </div>
            @endif

            @if(in_array('sc-gender', $event->fields ?? []))
              <div class="col-md-6 mb-3">
                <label class="form-control-label text-xs">Jenis Kelamin</label>
                <select name="gender" class="form-control">
                  <option value="Laki-Laki">Laki-Laki</option>
                  <option value="Perempuan">Perempuan</option>
                </select>
              </div>
            @endif

            @if(in_array('sc-institution', $event->fields ?? []))
              <div class="col-md-6 mb-3">
                <label class="form-control-label text-xs">Asal Instansi / Unit Kerja <span class="text-danger">*</span></label>
                <input type="text" name="institution" id="form-institution" required placeholder="Nama instansi/lembaga" class="form-control">
              </div>
            @endif

            @if(in_array('sc-address', $event->fields ?? []))
              <div class="col-12 mb-3">
                <label class="form-control-label text-xs">Alamat Domisili</label>
                <input type="text" name="address" placeholder="Alamat jalan, RT/RW, dan kota tinggal" class="form-control">
              </div>
            @endif
          </div>

          @if($event->custom_fields && count($event->custom_fields) > 0)
            <hr class="horizontal dark my-4">
            <span class="ep-section-label">Input Khusus Penyelenggara</span>
            <div class="row mt-3">
              @foreach($event->custom_fields as $cf)
                @php $slug = \Illuminate\Support\Str::slug($cf['label'], '_') @endphp
                <div class="col-md-6 mb-3">
                  <label class="form-control-label text-xs">{{ $cf['label'] }} <span class="text-danger">*</span></label>
                  <input type="{{ $cf['type'] }}" name="{{ $slug }}" required placeholder="Ketik {{ $cf['label'] }}" class="form-control">
                </div>
              @endforeach
            </div>
          @endif

          @if(in_array('sc-photo', $event->fields ?? []))
            <hr class="horizontal dark my-4">
            <div class="row align-items-center">
              <div class="col-lg-6 mb-3 mb-lg-0">
                <span class="ep-section-label">Capture Foto Wajah</span>
                <p class="text-sm text-muted mt-2 mb-3">Izinkan kamera browser, posisikan wajah menghadap layar, lalu ambil foto.</p>
                <div class="ep-media-frame position-relative" style="aspect-ratio: 4 / 3; min-height: 230px;">
                  <video id="webcam-preview" autoplay playsinline class="w-100 h-100 d-none" style="object-fit: cover;"></video>
                  <div id="webcam-fallback" class="d-flex flex-column align-items-center justify-content-center h-100 text-center text-white p-3">
                    <i class="fas fa-video-slash fa-2x mb-3 opacity-8"></i>
                    <span class="text-sm font-weight-bold">Kamera belum dinyalakan</span>
                  </div>
                  <canvas id="captured-canvas" class="d-none position-absolute top-0 start-0 w-100 h-100" style="object-fit: cover;"></canvas>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="card bg-gray-100 border-0 shadow-none">
                  <div class="card-body p-4">
                    <h6 class="mb-2">Kontrol Kamera</h6>
                    <p class="text-sm text-muted">Foto wajah menjadi bukti presensi visual dan akan tersimpan bersama data kehadiran.</p>
                    <button type="button" onclick="activateWebcam()" class="btn btn-outline-primary mb-2 me-2">
                      <i class="fas fa-power-off me-1"></i> Buka Kamera
                    </button>
                    <button type="button" onclick="snapPhoto()" class="btn bg-gradient-danger text-white mb-2">
                      <i class="fas fa-camera me-1"></i> Capture Wajah
                    </button>
                  </div>
                </div>
              </div>
            </div>
          @endif

          @if(in_array('sc-signature', $event->fields ?? []))
            <hr class="horizontal dark my-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
              <div>
                <span class="ep-section-label">Tanda Tangan Digital</span>
                <p class="text-sm text-muted mb-md-0 mt-2">Gunakan jari pada layar sentuh atau mouse pada desktop.</p>
              </div>
              <button type="button" onclick="resetSignaturePad()" class="btn btn-sm btn-outline-secondary mb-0">
                <i class="fas fa-eraser me-1"></i> Bersihkan
              </button>
            </div>
            <div class="ep-signature-frame overflow-hidden mb-2">
              <canvas id="signature-canvas" class="w-100 bg-white cursor-crosshair" style="height: 190px; touch-action: none;"></canvas>
            </div>
          @endif

          <div class="text-end pt-4 mt-4 border-top">
            <button type="submit" class="btn bg-gradient-success px-5 mb-0 shadow">
              <i class="fas fa-check-circle me-1"></i> Kirim Data Presensi
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  function toggleParticipantType() {
    const tipePesertaSelect = document.getElementById('tipe_peserta');
    if (!tipePesertaSelect) return;
    const tipePeserta = tipePesertaSelect.value;
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
  }

  document.addEventListener('DOMContentLoaded', () => {
    toggleParticipantType();
  });

  function fetchEmployeeApi() {
    const nip = document.getElementById('form-nip').value.trim();
    if(!nip) {
      alert('Masukkan nomor NIP pegawai terlebih dahulu!');
      return;
    }

    fetch(`/api/pegawai/${nip}`)
      .then(res => res.json())
      .then(data => {
        if(data.success) {
          document.getElementById('form-name').value = data.data.name;
          if(document.getElementById('form-phone')) document.getElementById('form-phone').value = data.data.phone;
          if(document.getElementById('form-institution')) document.getElementById('form-institution').value = data.data.institution;
          alert('Integrasi API berhasil. Data pegawai terisi otomatis.');
        } else {
          alert('Identitas pegawai tidak ditemukan di pangkalan data Kota Malang.');
        }
      });
  }

  let activeStream = null;
  function activateWebcam() {
    const video = document.getElementById('webcam-preview');
    const fallback = document.getElementById('webcam-fallback');
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
      navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false })
        .then(stream => {
          activeStream = stream;
          video.srcObject = stream;
          video.classList.remove('d-none');
          fallback.classList.add('d-none');
        }).catch(() => {
          alert('Akses kamera diblokir browser. Pastikan izin kamera aktif.');
        });
    }
  }

  function snapPhoto() {
    const canvas = document.getElementById('captured-canvas');
    const video = document.getElementById('webcam-preview');
    const ctx = canvas.getContext('2d');
    canvas.width = 640;
    canvas.height = 480;

    if(activeStream && !video.classList.contains('d-none')) {
      ctx.drawImage(video, 0, 0, 640, 480);
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
    sigCanvas.height = 190;
  }

  function resetSignaturePad() {
    if(sigCanvas) canvasCtx.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
  }

  document.getElementById('presence-main-form').addEventListener('submit', () => {
    if(sigCanvas) {
      document.getElementById('signature-base64').value = sigCanvas.toDataURL('image/png');
    }
    if(activeStream) {
      activeStream.getTracks().forEach(track => track.stop());
    }
  });
</script>
@endsection
