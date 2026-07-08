@if(session('success'))
  <div class="alert alert-success text-white shadow-sm">{{ session('success') }}</div>
@endif

@if(session('warning'))
  <div class="alert alert-warning text-white shadow-sm">{{ session('warning') }}</div>
@endif

@if(session('info'))
  <div class="alert alert-info text-white shadow-sm">{{ session('info') }}</div>
@endif

@if($errors->any())
  <div class="alert alert-danger text-white shadow-sm">
    {{ $errors->first() }}
  </div>
@endif
