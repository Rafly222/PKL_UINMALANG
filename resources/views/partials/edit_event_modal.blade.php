<!-- Modal Edit Event -->
<div class="modal fade" id="editEventModal-{{ $event->id }}" tabindex="-1" aria-labelledby="editEventModalLabel-{{ $event->id }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-radius-xl">
      <div class="modal-header bg-gradient-primary">
        <h6 class="modal-title text-white" id="editEventModalLabel-{{ $event->id }}">Edit Event Presensi</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4 text-start">
        <form action="{{ route('event.update', $event->id) }}" method="POST">
          @csrf
          @method('PUT')
          
          <span class="ep-section-label">Informasi Event</span>
          <div class="mb-3 mt-2">
            <label class="form-control-label text-xs">Nama kegiatan</label>
            <input type="text" name="name" value="{{ $event->name }}" class="form-control" placeholder="Contoh: Rapat Koordinasi Smart City" required>
          </div>

          <div class="row">
            <div class="col-md-3 mb-3">
              <label class="form-control-label text-xs">Mulai Tanggal</label>
              <input type="date" name="date" value="{{ $event->date }}" class="form-control" required>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-control-label text-xs">Selesai Tanggal</label>
              <input type="date" name="date_end" value="{{ $event->date_end ?? $event->date }}" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-control-label text-xs">Jam Mulai</label>
              <input type="time" name="time_start" value="{{ \Carbon\Carbon::parse($event->time_start)->format('H:i') }}" class="form-control" required>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-control-label text-xs">Jam Selesai</label>
              <input type="time" name="time_end" value="{{ \Carbon\Carbon::parse($event->time_end)->format('H:i') }}" class="form-control" required>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-control-label text-xs">Kategori peserta</label>
              <select name="audience_type" class="form-control" required>
                <option value="semua" @selected($event->audience_type === 'semua')>Semua</option>
                <option value="pegawai" @selected($event->audience_type === 'pegawai')>Pegawai</option>
                <option value="umum" @selected($event->audience_type === 'umum')>Umum</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-control-label text-xs">Akses event</label>
              <select name="access_type" id="edit-access-type-{{ $event->id }}" class="form-control edit-access-type" required>
                <option value="publik" @selected($event->access_type === 'publik')>Publik</option>
                <option value="privat" @selected($event->access_type === 'privat')>Privat</option>
              </select>
            </div>
          </div>

          <div class="mb-3" id="edit-password-wrapper-{{ $event->id }}" style="display: {{ $event->access_type === 'privat' ? 'block' : 'none' }};">
            <label class="form-control-label text-xs">Password event privat (Kosongkan jika tidak diubah)</label>
            <div class="input-group">
              @php
                $decryptedPassword = '';
                if ($event->password) {
                    try {
                        $decryptedPassword = decrypt($event->password);
                    } catch (\Exception $e) {
                        $decryptedPassword = '********';
                    }
                }
              @endphp
              <input type="password" name="password" id="edit-password-{{ $event->id }}" value="{{ $decryptedPassword }}" class="form-control" placeholder="Minimal 4 karakter" style="border-right: 0;">
              <span class="input-group-text bg-white cursor-pointer" id="toggle-edit-password-{{ $event->id }}" style="cursor: pointer; border-left: 0;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16" style="width: 16px; height: 16px;"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 4 8 4c2.12 0 3.879.668 5.168 1.957A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12 8 12c-2.12 0-3.879-.668-5.168-1.957A13.133 13.133 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/></svg>
              </span>
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
                  <input class="form-check-input" type="checkbox" name="{{ $value }}" value="1" id="edit-{{ $value }}-{{ $event->id }}" @checked(in_array($value, $event->fields ?? []))>
                  <label class="form-check-label text-sm" for="edit-{{ $value }}-{{ $event->id }}">{{ $label }}</label>
                </div>
              </div>
            @endforeach
          </div>

          <span class="ep-section-label">Custom Field</span>
          <div id="edit-custom-fields-{{ $event->id }}" class="mt-2 mb-3">
            @if($event->custom_fields)
              @foreach($event->custom_fields as $cf)
                <div class="row mb-2 align-items-center">
                  <div class="col-6">
                    <input type="text" name="custom_labels[]" value="{{ $cf['label'] }}" class="form-control" placeholder="Nama field" required>
                  </div>
                  <div class="col-3">
                    <select name="custom_types[]" class="form-control">
                      <option value="text" @selected($cf['type'] === 'text')>Text</option>
                      <option value="number" @selected($cf['type'] === 'number')>Number</option>
                      <option value="date" @selected($cf['type'] === 'date')>Date</option>
                      <option value="email" @selected($cf['type'] === 'email')>Email</option>
                    </select>
                  </div>
                  <div class="col-3 text-end">
                    <button type="button" class="btn btn-outline-danger btn-xs mb-0 remove-custom-field d-flex align-items-center justify-content-center gap-1 w-100" onclick="this.closest('.row').remove()">
                      <i class="ni ni-fat-remove me-1"></i>
                      <span class="d-none d-sm-inline">Hapus</span>
                    </button>
                  </div>
                </div>
              @endforeach
            @endif
          </div>
          <button type="button" class="btn btn-sm btn-outline-primary mb-3" id="edit-add-custom-field-{{ $event->id }}">
            <i class="ni ni-fat-add me-1"></i> Tambah Field
          </button>

          <button type="submit" class="btn bg-gradient-primary w-100 mb-0 shadow">Simpan Perubahan</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const editAccessType = document.getElementById('edit-access-type-{{ $event->id }}');
    const editPasswordWrapper = document.getElementById('edit-password-wrapper-{{ $event->id }}');
    
    if (editAccessType) {
      editAccessType.addEventListener('change', function() {
        editPasswordWrapper.style.display = this.value === 'privat' ? 'block' : 'none';
      });
    }

    const toggleEditPasswordBtn = document.getElementById('toggle-edit-password-{{ $event->id }}');
    const editPasswordInput = document.getElementById('edit-password-{{ $event->id }}');

    if (toggleEditPasswordBtn && editPasswordInput) {
      toggleEditPasswordBtn.addEventListener('click', function() {
        if (editPasswordInput.type === 'password') {
          editPasswordInput.type = 'text';
          toggleEditPasswordBtn.innerHTML = eyeSlashSvg;
        } else {
          editPasswordInput.type = 'password';
          toggleEditPasswordBtn.innerHTML = eyeSvg;
        }
      });
    }

    const addBtn = document.getElementById('edit-add-custom-field-{{ $event->id }}');
    if (addBtn) {
      addBtn.addEventListener('click', () => {
        const container = document.getElementById('edit-custom-fields-{{ $event->id }}');
        const row = document.createElement('div');
        row.className = 'row mb-2 align-items-center';
        row.innerHTML = `
          <div class="col-6">
            <input type="text" name="custom_labels[]" class="form-control" placeholder="Nama field" required>
          </div>
          <div class="col-3">
            <select name="custom_types[]" class="form-control">
              <option value="text">Text</option>
              <option value="number">Number</option>
              <option value="date">Date</option>
              <option value="email">Email</option>
            </select>
          </div>
          <div class="col-3 text-end">
            <button type="button" class="btn btn-outline-danger btn-xs mb-0 remove-custom-field d-flex align-items-center justify-content-center gap-1 w-100">
              <i class="ni ni-fat-remove me-1"></i>
              <span class="d-none d-sm-inline">Hapus</span>
            </button>
          </div>`;
        row.querySelector('.remove-custom-field').addEventListener('click', () => {
          row.remove();
        });
        container.appendChild(row);
      });
    }
  });
</script>
