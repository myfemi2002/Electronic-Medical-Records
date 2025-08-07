<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TwoFactorController extends Controller
{
    /**
     * Show 2FA setup page
     */
    public function setup()
    {
        $user = Auth::user();
        
        // Only admins can access 2FA setup
        if ($user->role !== 'admin') {
            return redirect()->back()->with('message', 'Access denied')->with('alert-type', 'error');
        }

        if (!$user->two_factor_secret) {
            $user->generateTwoFactorSecret();
        }

        $qrCodeUrl = $user->getTwoFactorQrCodeUrl();

        return view('backend.auth.two-factor-setup', compact('user', 'qrCodeUrl'));
    }

    /**
     * Enable 2FA
     */
    public function enable(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric|digits:6'
        ]);

        $user = Auth::user();

        if (!$user->verifyTwoFactorCode($request->code)) {
            return redirect()->back()->with('message', 'Invalid verification code')->with('alert-type', 'error');
        }

        $user->enableTwoFactor();
        $recoveryCodes = $user->two_factor_recovery_codes;

        return view('backend.auth.two-factor-recovery-codes', compact('recoveryCodes'))
            ->with('message', '2FA enabled successfully')
            ->with('alert-type', 'success');
    }

    /**
     * Disable 2FA
     */
    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password'
        ]);

        $user = Auth::user();
        $user->disableTwoFactor();

        return redirect()->back()->with('message', '2FA disabled successfully')->with('alert-type', 'success');
    }

    /**
     * Show 2FA verification form
     */
    public function verify()
    {
        return view('backend.auth.two-factor-verify');
    }

    /**
     * Verify 2FA code during login
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $user = Auth::user();
        $code = $request->code;

        // Check if it's a recovery code
        if (strlen($code) === 10 && $user->useRecoveryCode($code)) {
            session()->forget('2fa_required');
            $request->session()->regenerate();

            return redirect()->intended('admin/dashboard')->with('message', 'Login successful using recovery code')->with('alert-type', 'success');
        }

        // Check if it's a 2FA code
        if (strlen($code) === 6 && $user->verifyTwoFactorCode($code)) {
            session()->forget('2fa_required');
            $request->session()->regenerate();

            return redirect()->intended('admin/dashboard')->with('message', 'Login successful')->with('alert-type', 'success');
        }

        return redirect()->back()->with('message', 'Invalid verification code')->with('alert-type', 'error');
    }

    /**
     * Generate new recovery codes
     */
    public function generateRecoveryCodes()
    {
        $user = Auth::user();
        $recoveryCodes = $user->generateRecoveryCodes();

        return view('backend.auth.two-factor-recovery-codes', compact('recoveryCodes'))
            ->with('message', 'New recovery codes generated')
            ->with('alert-type', 'success');
    }
}
