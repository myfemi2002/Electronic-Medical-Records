@extends('admin.admin_master')
@section('title', 'Two-Factor Authentication')
@section('admin')

<div class="content container-fluid pb-0">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="card-title">
                        <i class="fas fa-shield-alt"></i> Two-Factor Authentication
                    </h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <p class="text-muted">Please enter the 6-digit code from your authenticator app or use a recovery code.</p>
                    </div>

                    <form method="POST" action="{{ route('two-factor.verify.post') }}">
                        @csrf
                        <div class="form-group">
                            <label for="code">Authentication Code</label>
                            <input type="text" 
                                   class="form-control text-center @error('code') is-invalid @enderror" 
                                   name="code" 
                                   id="code" 
                                   placeholder="Enter 6-digit code or 10-character recovery code"
                                   required
                                   autocomplete="off">
                            @error('code')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-check"></i> Verify
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <small class="text-muted">
                            Having trouble? Use a recovery code instead of the 6-digit code.
                        </small>
                    </div>

                    <div class="text-center mt-3">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-link btn-sm">
                                <i class="fas fa-sign-out-alt"></i> Sign out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-format the code input
document.getElementById('code').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\s/g, '');
    
    // If it looks like a 6-digit code, only allow numbers
    if (value.length <= 6) {
        e.target.value = value.replace(/\D/g, '');
    } else {
        // For recovery codes, allow alphanumeric
        e.target.value = value.replace(/[^A-Za-z0-9]/g, '').toUpperCase();
    }
});

// Focus on the input field
document.getElementById('code').focus();
</script>
@endpush

@endsection