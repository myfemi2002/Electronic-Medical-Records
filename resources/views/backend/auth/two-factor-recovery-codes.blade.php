@extends('admin.admin_master')
@section('title', 'Recovery Codes')
@section('admin')

<div class="content container-fluid pb-0">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-key"></i> Recovery Codes
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Important:</strong> Store these recovery codes in a safe place. 
                        Each code can only be used once and will allow you to access your account if you lose your authenticator device.
                    </div>

                    <div class="row">
                        <div class="col-md-8 offset-md-2">
                            <div class="recovery-codes-container p-3 border rounded bg-light">
                                @foreach($recoveryCodes as $code)
                                    <div class="recovery-code">
                                        <code class="h5 font-monospace">{{ $code }}</code>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-secondary" onclick="printCodes()">
                            <i class="fas fa-print"></i> Print Codes
                        </button>
                        <button type="button" class="btn btn-info" onclick="downloadCodes()">
                            <i class="fas fa-download"></i> Download as Text
                        </button>
                        <a href="{{ route('two-factor.setup') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Back to 2FA Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.recovery-codes-container {
    background-color: #f8f9fa !important;
}

.recovery-code {
    margin-bottom: 10px;
    text-align: center;
}

@media print {
    body * {
        visibility: hidden;
    }
    .recovery-codes-container, .recovery-codes-container * {
        visibility: visible;
    }
    .recovery-codes-container {
        position: absolute;
        left: 0;
        top: 0;
    }
}
</style>
@endpush

@push('scripts')
<script>
function printCodes() {
    window.print();
}

function downloadCodes() {
    const codes = @json($recoveryCodes);
    const content = `Two-Factor Authentication Recovery Codes\n` +
                   `Generated: ${new Date().toLocaleString()}\n` +
                   `Account: {{ Auth::user()->email }}\n\n` +
                   `Recovery Codes:\n` +
                   codes.join('\n') +
                   `\n\nIMPORTANT: Keep these codes secure and use them only when you cannot access your authenticator app.`;
    
    const element = document.createElement('a');
    element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(content));
    element.setAttribute('download', '2fa-recovery-codes.txt');
    element.style.display = 'none';
    document.body.appendChild(element);
    element.click();
    document.body.removeChild(element);
}
</script>
@endpush

@endsection