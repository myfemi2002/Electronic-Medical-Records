@extends('admin.admin_master')
@section('title', 'Two-Factor Authentication Setup')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="page-title">Two-Factor Authentication</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                    <li class="breadcrumb-item active">2FA Setup</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        @if($user->two_factor_enabled)
                            <i class="fa fa-shield-alt text-success"></i> Two-Factor Authentication Enabled
                        @else
                            <i class="fa fa-shield-alt text-warning"></i> Enable Two-Factor Authentication
                        @endif
                    </h4>
                </div>
                <div class="card-body">
                    @if(!$user->two_factor_enabled)
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i>
                            <strong>Two-factor authentication</strong> adds an extra layer of security to your account. 
                            You'll need a mobile app like Google Authenticator, Authy, or Microsoft Authenticator.
                        </div>

                        <div class="row">
                            <!-- Left Column: Setup Instructions -->
                            <div class="col-md-6">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0"><i class="fa fa-mobile-alt"></i> Setup Instructions</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="setup-steps">
                                            <div class="step-item mb-4">
                                                <div class="step-number">1</div>
                                                <div class="step-content">
                                                    <h6>Download an Authenticator App</h6>
                                                    <div class="app-list">
                                                        <div class="app-item">
                                                            <i class="fa fa-google text-danger"></i>
                                                            <strong>Google Authenticator</strong>
                                                            <small class="text-muted d-block">Free • iOS & Android</small>
                                                        </div>
                                                        <div class="app-item">
                                                            <i class="fa fa-mobile-alt text-info"></i>
                                                            <strong>Authy</strong>
                                                            <small class="text-muted d-block">Multi-device support</small>
                                                        </div>
                                                        <div class="app-item">
                                                            <i class="fab fa-microsoft text-primary"></i>
                                                            <strong>Microsoft Authenticator</strong>
                                                            <small class="text-muted d-block">Free Microsoft app</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="step-item mb-4">
                                                <div class="step-number">2</div>
                                                <div class="step-content">
                                                    <h6>Add Account to Your App</h6>
                                                    <p class="mb-2">In your authenticator app:</p>
                                                    <ol class="setup-list">
                                                        <li>Tap <strong>"Add Account"</strong> or <strong>"+"</strong></li>
                                                        <li>Choose <strong>"Enter a setup key"</strong> or <strong>"Manual entry"</strong></li>
                                                        <li>Enter the account details from the right panel</li>
                                                        <li>Save the account</li>
                                                    </ol>
                                                </div>
                                            </div>

                                            <div class="step-item">
                                                <div class="step-number">3</div>
                                                <div class="step-content">
                                                    <h6>Verify Your Setup</h6>
                                                    <p>Enter the 6-digit code from your app below to complete setup.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column: Account Information & Verification -->
                            <div class="col-md-6">
                                <!-- Account Information -->
                                <div class="card border-success mb-4">
                                    <div class="card-header bg-success text-white">
                                        <h5 class="mb-0"><i class="fa fa-key"></i> Account Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Account Name:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control bg-light" value="{{ $user->email }}" readonly>
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-success" type="button" onclick="copyToClipboard('{{ $user->email }}', this)">
                                                        <i class="fa fa-copy"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="font-weight-bold">Secret Key:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control bg-light font-monospace" 
                                                       value="{{ $user->two_factor_secret }}" readonly
                                                       style="font-size: 12px; letter-spacing: 1px;">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-success" type="button" onclick="copyToClipboard('{{ $user->two_factor_secret }}', this)">
                                                        <i class="fa fa-copy"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="font-weight-bold">Issuer:</label>
                                            <input type="text" class="form-control bg-light" value="{{ config('app.name') }}" readonly>
                                        </div>

                                        <div class="alert alert-warning">
                                            <i class="fa fa-exclamation-triangle"></i>
                                            <small><strong>Important:</strong> Copy the secret key exactly as shown.</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Verification Form -->
                                <div class="card border-warning">
                                    <div class="card-header bg-warning text-dark">
                                        <h5 class="mb-0"><i class="fa fa-check-circle"></i> Verify Your Setup</h5>
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" action="{{ route('two-factor.enable') }}">
                                            @csrf
                                            <div class="form-group">
                                                <label for="code" class="font-weight-bold">Enter 6-digit verification code:</label>
                                                <input type="text" 
                                                       class="form-control form-control-lg text-center @error('code') is-invalid @enderror" 
                                                       name="code" 
                                                       id="code" 
                                                       maxlength="6" 
                                                       placeholder="000000"
                                                       style="font-size: 1.8em; letter-spacing: 0.3em; font-family: monospace;"
                                                       required autocomplete="off">
                                                @error('code')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                                <small class="form-text text-muted">
                                                    <i class="fa fa-clock"></i> The code changes every 30 seconds
                                                </small>
                                            </div>
                                            
                                            <button type="submit" class="btn btn-success btn-lg btn-block">
                                                <i class="fa fa-shield-alt"></i> Enable Two-Factor Authentication
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @else
                        <!-- 2FA Enabled State -->
                        <div class="alert alert-success">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <i class="fa fa-check-circle fa-2x text-success float-left mr-3"></i>
                                    <h5 class="mb-1">Two-Factor Authentication is Active</h5>
                                    <p class="mb-0">Your account is protected with an additional layer of security.</p>
                                </div>
                                <div class="col-md-4 text-right">
                                    <span class="badge badge-success badge-lg">
                                        <i class="fa fa-shield-alt"></i> PROTECTED
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-info h-100">
                                    <div class="card-body text-center">
                                        <i class="fa fa-key fa-3x text-info mb-3"></i>
                                        <h5 class="card-title">Recovery Codes</h5>
                                        <p class="card-text">Generate new backup codes in case you lose access to your authenticator app.</p>
                                        <form method="POST" action="{{ route('two-factor.recovery-codes') }}">
                                            @csrf
                                            <button type="submit" class="btn btn-info">
                                                <i class="fa fa-sync"></i> Generate New Recovery Codes
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-danger h-100">
                                    <div class="card-body text-center">
                                        <i class="fa fa-times-circle fa-3x text-danger mb-3"></i>
                                        <h5 class="card-title">Disable 2FA</h5>
                                        <p class="card-text">Remove two-factor authentication from your account.</p>
                                        <form method="POST" action="{{ route('two-factor.disable') }}">
                                            @csrf
                                            <div class="form-group">
                                                <input type="password" 
                                                       class="form-control @error('password') is-invalid @enderror" 
                                                       name="password" 
                                                       placeholder="Enter your password to confirm"
                                                       required>
                                                @error('password')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <button type="submit" class="btn btn-danger" 
                                                    onclick="return confirm('Are you sure you want to disable Two-Factor Authentication?\n\nThis will make your account less secure.')">
                                                <i class="fa fa-times"></i> Disable 2FA
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.setup-steps .step-item {
    display: flex;
    align-items: flex-start;
}

.setup-steps .step-number {
    background: #007bff;
    color: white;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-right: 15px;
    flex-shrink: 0;
}

.setup-steps .step-content {
    flex: 1;
}

.app-list .app-item {
    padding: 8px 0;
    border-bottom: 1px solid #eee;
}

.app-list .app-item:last-child {
    border-bottom: none;
}

.app-list .app-item i {
    width: 20px;
    margin-right: 10px;
}

.setup-list {
    margin-bottom: 0;
}

.setup-list li {
    margin-bottom: 5px;
}

.badge-lg {
    font-size: 0.9em;
    padding: 8px 12px;
}

.card.h-100 {
    height: 100% !important;
}
</style>
@endpush

@push('scripts')
<script>
function copyToClipboard(text, button) {
    // Create temporary textarea
    const textarea = document.createElement('textarea');
    textarea.value = text;
    document.body.appendChild(textarea);
    textarea.select();
    textarea.setSelectionRange(0, 99999);
    
    try {
        document.execCommand('copy');
        showCopySuccess(button);
    } catch (err) {
        console.error('Failed to copy: ', err);
        // Try modern clipboard API
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(() => {
                showCopySuccess(button);
            }).catch(err => {
                console.error('Clipboard API failed: ', err);
                // Show fallback message
                alert('Please select and copy the text manually.');
            });
        } else {
            alert('Please select and copy the text manually.');
        }
    }
    
    document.body.removeChild(textarea);
}

function showCopySuccess(button) {
    const originalHtml = button.innerHTML;
    const originalClass = button.className;
    
    button.innerHTML = '<i class="fa fa-check"></i>';
    button.className = button.className.replace('btn-outline-success', 'btn-success');
    
    setTimeout(() => {
        button.innerHTML = originalHtml;
        button.className = originalClass;
    }, 2000);
}

// Auto-format verification code input
document.addEventListener('DOMContentLoaded', function() {
    const codeInput = document.getElementById('code');
    if (codeInput) {
        // Only allow numbers
        codeInput.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });
        
        // Auto-submit when 6 digits entered (optional)
        codeInput.addEventListener('keyup', function(e) {
            if (e.target.value.length === 6) {
                // Optional: auto-submit form
                // e.target.form.submit();
            }
        });
        
        // Focus on input when page loads
        codeInput.focus();
    }
});
</script>
@endpush

@endsection