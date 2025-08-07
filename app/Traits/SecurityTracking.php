<?php

namespace App\Traits;

use Illuminate\Support\Facades\Request;
use Jenssegers\Agent\Agent;

trait SecurityTracking
{
    public function updateLoginInfo()
    {
        $agent = new Agent();
        
        $this->update([
            'last_login_ip' => Request::ip(),
            'last_login_at' => now(),
            'last_login_browser' => $agent->browser(),
            'last_login_device' => $agent->device(),
            'last_login_os' => $agent->platform(),
            'last_login_location' => $this->getLocation(Request::ip()),
            'is_logged_in' => true,
            'user_agent' => Request::header('User-Agent'),
        ]);
    }

    protected function getLocation($ip)
    {
        try {
            $response = file_get_contents("http://ip-api.com/json/{$ip}");
            $data = json_decode($response);
            return $data->status === 'success' ? "{$data->city}, {$data->country}" : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function incrementFailedLoginAttempts()
    {
        $this->update([
            'failed_login_attempts' => $this->failed_login_attempts + 1,
            'last_failed_login_at' => now(),
        ]);
    }

    public function resetFailedLoginAttempts()
    {
        $this->update([
            'failed_login_attempts' => 0,
            'last_failed_login_at' => null,
        ]);
    }

    public function logoutUser()
    {
        $this->update([
            'is_logged_in' => false,
        ]);
    }
}