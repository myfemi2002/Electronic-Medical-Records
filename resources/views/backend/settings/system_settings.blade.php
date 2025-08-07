@extends('admin.admin_master')
@section('title', 'System Settings')
@section('admin')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">System Settings</h4>
                        <form method="post" action="{{ route('system.settings.update') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Logo (200*60)</label>
                                <div class="col-sm-10">
                                    <input type="file" name="logo" class="form-control" id="logo">
                                    @if($setting && $setting->logo)
                                        <img src="{{ asset('upload/logo/'.$setting->logo) }}" alt="logo" class="mt-2" style="max-height: 60px;">
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Footer Description</label>
                                <div class="col-sm-10">
                                    <textarea name="footer_description" class="form-control" rows="4">{{ $setting->footer_description ?? '' }}</textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Social Media Links</label>
                                <div class="col-sm-10">
                                    <input type="text" name="facebook_url" class="form-control mb-2" placeholder="Facebook URL" value="{{ $setting->facebook_url ?? '' }}">
                                    <input type="text" name="google_url" class="form-control mb-2" placeholder="Google+ URL" value="{{ $setting->google_url ?? '' }}">
                                    <input type="text" name="twitter_url" class="form-control mb-2" placeholder="Twitter URL" value="{{ $setting->twitter_url ?? '' }}">
                                    <input type="text" name="skype_url" class="form-control mb-2" placeholder="Skype URL" value="{{ $setting->skype_url ?? '' }}">
                                    <input type="text" name="linkedin_url" class="form-control" placeholder="LinkedIn URL" value="{{ $setting->linkedin_url ?? '' }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Working Hours</label>
                                <div class="col-sm-10">
                                    <input type="text" name="working_hours" class="form-control" value="{{ $setting->working_hours ?? '' }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Phone</label>
                                <div class="col-sm-10">
                                    <input type="text" name="phone" class="form-control" value="{{ $setting->phone ?? '' }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" name="email" class="form-control" value="{{ $setting->email ?? '' }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Address</label>
                                <div class="col-sm-10">
                                    <textarea name="address" class="form-control" rows="2">{{ $setting->address ?? '' }}</textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-info waves-effect waves-light">Update Settings</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection