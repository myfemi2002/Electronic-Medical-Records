<?php

namespace App\Http\Controllers;

use App\Models\SmtpSetting;
use Illuminate\Http\Request;

class SmtpController extends Controller
{
    public function smtpSettings(){
        $smtp = SmtpSetting::find(1);
        return view('backend.smtp_setup.smtp_update', compact('smtp'));
    }
    
    public function smtpUpdate(Request $request)
    {
        // Validate the request.
        $request->validate([
            'mail_mailer' => 'required|string',
            'mail_host' => 'required|string',
            'mail_port' => 'required|integer',
            'mail_username' => 'required|string',
            'mail_password' => 'required|string',
            'mail_encryption' => 'required|string|in:tls,ssl',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
        ]);
    
        // Check if any SMTP settings exist
        $smtp = SmtpSetting::first(); // Fetch the first record
    
        if ($smtp) {
            // If SMTP settings exist, update the record
            $smtp->update([
                'mail_mailer' => $request->mail_mailer,
                'mail_host' => $request->mail_host,
                'mail_port' => $request->mail_port,
                'mail_username' => $request->mail_username,
                'mail_password' => $request->mail_password,
                'mail_encryption' => $request->mail_encryption,
                'mail_from_address' => $request->mail_from_address,
                'mail_from_name' => $request->mail_from_name,
            ]);
    
            // Prepare a success notification for the update
            $notification = [
                'message' => 'SMTP settings updated successfully',
                'alert-type' => 'success'
            ];
        } else {
            // If no SMTP settings exist, create a new record
            SmtpSetting::create([
                'mail_mailer' => $request->mail_mailer,
                'mail_host' => $request->mail_host,
                'mail_port' => $request->mail_port,
                'mail_username' => $request->mail_username,
                'mail_password' => $request->mail_password,
                'mail_encryption' => $request->mail_encryption,
                'mail_from_address' => $request->mail_from_address,
                'mail_from_name' => $request->mail_from_name,
            ]);
    
            // Prepare a success notification for the creation
            $notification = [
                'message' => 'SMTP settings created successfully',
                'alert-type' => 'success'
            ];
        }
    
        // Redirect back with the success notification
        return redirect()->back()->with($notification);
    }
}
