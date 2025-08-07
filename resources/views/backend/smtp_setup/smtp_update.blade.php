@extends('admin.admin_master')
@section('title', 'SMTP Settings')
@section('admin')

<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-header">
                <h4 class="fs-18 mb-0">@yield('title')</h4>
            </div>
            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('smtp-settings.update') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Mail Mailer</label>
                                <div class="form-group position-relative">
                                    <input type="text" name="mail_mailer" value="{{ $smtp ? $smtp->mail_mailer : '' }}" 
                                           class="form-control text-dark ps-5 h-55" required>
                                    <i class="ri-mail-line position-absolute top-50 start-0 translate-middle-y fs-20 ps-20"></i>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Mail Host</label>
                                <div class="form-group position-relative">
                                    <input type="text" name="mail_host" value="{{ $smtp ? $smtp->mail_host : '' }}" 
                                           class="form-control text-dark ps-5 h-55" required>
                                    <i class="ri-server-line position-absolute top-50 start-0 translate-middle-y fs-20 ps-20"></i>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Mail Username</label>
                                <div class="form-group position-relative">
                                    <input type="text" name="mail_username" value="{{ $smtp ? $smtp->mail_username : '' }}" 
                                           class="form-control text-dark ps-5 h-55" required>
                                    <i class="ri-user-line position-absolute top-50 start-0 translate-middle-y fs-20 ps-20"></i>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Mail From Address</label>
                                <div class="form-group position-relative">
                                    <input type="email" name="mail_from_address" value="{{ $smtp ? $smtp->mail_from_address : '' }}" 
                                           class="form-control text-dark ps-5 h-55" required>
                                    <i class="ri-at-line position-absolute top-50 start-0 translate-middle-y fs-20 ps-20"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Mail Port</label>
                                <div class="form-group position-relative">
                                    <input type="number" name="mail_port" value="{{ $smtp ? $smtp->mail_port : '' }}" 
                                           class="form-control text-dark ps-5 h-55" required>
                                    <i class="ri-door-lock-line position-absolute top-50 start-0 translate-middle-y fs-20 ps-20"></i>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Mail Password</label>
                                <div class="form-group position-relative">
                                    <input type="password" name="mail_password" value="{{ $smtp ? $smtp->mail_password : '' }}" 
                                           class="form-control text-dark ps-5 h-55" required>
                                    <i class="ri-lock-line position-absolute top-50 start-0 translate-middle-y fs-20 ps-20"></i>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Mail From Name</label>
                                <div class="form-group position-relative">
                                    <input type="text" name="mail_from_name" value="{{ $smtp ? $smtp->mail_from_name : '' }}" 
                                           class="form-control text-dark ps-5 h-55" required>
                                    <i class="ri-user-smile-line position-absolute top-50 start-0 translate-middle-y fs-20 ps-20"></i>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Mail Encryption</label>
                                <div class="form-group position-relative">
                                    <select name="mail_encryption" class="form-select form-control ps-5 h-55" required>
                                        <option value="tls" {{ ($smtp && $smtp->mail_encryption == 'tls') ? 'selected' : '' }}>TLS</option>
                                        <option value="ssl" {{ ($smtp && $smtp->mail_encryption == 'ssl') ? 'selected' : '' }}>SSL</option>
                                    </select>
                                    <i class="ri-lock-password-line position-absolute top-50 start-0 translate-middle-y fs-20 ps-20"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection