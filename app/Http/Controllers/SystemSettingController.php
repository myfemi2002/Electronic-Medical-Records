<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SystemSettingController extends Controller
{
    protected $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    public function index()
    {
        $setting = SystemSetting::first();
        return view('backend.settings.system_settings', compact('setting'));
    }


    public function update(Request $request)
    {
        $request->validate([
            'footer_description' => 'required',
            'working_hours' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $setting = SystemSetting::first();
        if (!$setting) {
            $setting = new SystemSetting();
        }

        if ($request->hasFile('logo')) {
            // Create directory if it doesn't exist
            $path = public_path('upload/logo');
            if(!File::isDirectory($path)){
                File::makeDirectory($path, 0777, true, true);
            }

            // Delete old logo if it exists
            if ($setting->logo) {
                $oldLogo = public_path('upload/logo/' . $setting->logo);
                if (File::exists($oldLogo)) {
                    File::delete($oldLogo);
                }
            }

            // Process new logo
            $image = $request->file('logo');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            
            $img = $this->manager->read($image);
            $img->resize(200, 60, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save(public_path('upload/logo/' . $name_gen));
            
            $setting->logo = $name_gen;
        }

        $setting->footer_description = $request->footer_description;
        $setting->facebook_url = $request->facebook_url;
        $setting->google_url = $request->google_url;
        $setting->twitter_url = $request->twitter_url;
        $setting->skype_url = $request->skype_url;
        $setting->linkedin_url = $request->linkedin_url;
        $setting->working_hours = $request->working_hours;
        $setting->phone = $request->phone;
        $setting->email = $request->email;
        $setting->address = $request->address;
        $setting->save();

        $notification = array(
            'message' => 'System Settings Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
