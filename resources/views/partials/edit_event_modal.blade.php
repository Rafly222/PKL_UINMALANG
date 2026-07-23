<!-- Modal Edit Event (Single Reusable) -->
<div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-radius-xl">
      <div class="modal-header bg-gradient-primary">
        <h6 class="modal-title text-white" id="editEventModalLabel">Edit Event Presensi</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4 text-start">
        <form id="edit-event-form" action="" method="POST">
          @csrf
          @method('PUT')
          
          <span class="ep-section-label">Informasi Event</span>
          <div class="mb-3 mt-2">
            <label class="form-control-label text-xs">Nama kegiatan</label>
            <input type="text" name="name" id="edit-event-name" class="form-control" placeholder="Contoh: Rapat Koordinasi Smart City" required>
          </div>

          <div class="row">
            <div class="col-md-3 mb-3">
              <label class="form-control-label text-xs">Mulai Tanggal</label>
              <input type="date" name="date" id="edit-event-date" class="form-control" required>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-control-label text-xs">Selesai Tanggal</label>
              <input type="date" name="date_end" id="edit-event-date-end" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-control-label text-xs">Jam Mulai</label>
              <input type="time" name="time_start" id="edit-event-time-start" class="form-control" required>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-control-label text-xs">Jam Selesai</label>
              <input type="time" name="time_end" id="edit-event-time-end" class="form-control" required>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-control-label text-xs">Kategori peserta</label>
              <select name="audience_type" id="edit-event-audience-type" class="form-control" required>
                <option value="semua">Semua</option>
                <option value="pegawai">Pegawai</option>
                <option value="umum">Umum</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-control-label text-xs">Akses event</label>
              <select name="access_type" id="edit-event-access-type" class="form-control" required>
                <option value="publik">Publik</option>
                <option value="privat">Privat</option>
              </select>
            </div>
          </div>

          <div class="mb-3" id="edit-event-password-wrapper" style="display: none;">
            <label class="form-control-label text-xs font-weight-bold">Password Event Privat</label>
            <div class="input-group">
              <input type="text" name="password" id="edit-event-password" class="form-control" placeholder="Password event privat" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
              <button type="button" class="btn bg-gradient-dark mb-0 px-3" id="btn-generate-edit-password" style="border-top-left-radius: 0; border-bottom-left-radius: 0; font-size: 11px;">
                <i class="fas fa-random me-1"></i> Generate Acak
              </button>
            </div>
          </div>

          <hr class="horizontal dark my-4">
          <span class="ep-section-label">Field Standar</span>
          <div class="row mt-2 mb-3">
            @foreach([
              'sc-phone' => 'No HP',
              'sc-gender' => 'Jenis kelamin',
              'sc-institution' => 'Instansi',
              'sc-email' => 'Email',
              'sc-nip' => 'NIP',
              'sc-photo' => 'Foto wajah',
              'sc-signature' => 'TTD digital',
            ] as $value => $label)
              <div class="col-md-6 mb-1">
                <div class="form-check form-switch">
                  <input class="form-check-input edit-field-checkbox" type="checkbox" name="{{ $value }}" value="1" id="edit-{{ $value }}">
                  <label class="form-check-label text-sm" for="edit-{{ $value }}">{{ $label }}</label>
                </div>
              </div>
            @endforeach
          </div>

          <span class="ep-section-label">Custom Field</span>
          <div id="edit-custom-fields" class="mt-2 mb-3"></div>
          <button type="button" class="btn btn-sm btn-outline-primary mb-3" id="edit-add-custom-field">
            <i class="ni ni-fat-add me-1"></i> Tambah Field
          </button>

          <button type="submit" class="btn bg-gradient-primary w-100 mb-0 shadow">Simpan Perubahan</button>
        </form>
      </div>
    </div>
  </div>
</div>
