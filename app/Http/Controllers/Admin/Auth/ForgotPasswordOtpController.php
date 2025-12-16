<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Admin;
use App\Models\PasswordResetOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Mail\ForgotPasswordOtpMail;


class ForgotPasswordOtpController extends AdminController
{
    public function showEmailForm()
    {
        return view('admin.auth.forgot-password');
    }
    public function sendotp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        $user = Admin::where('email',$request->email)->first();

        if(!$user){
            return back()->withErrors(['email' => 'User not found']);
        }

        PasswordResetOtp::where('user_id',$user->id)->update(['is_used'=> true]);

        $otp = random_int(100000, 999999);

        PasswordResetotp::create([
            'user_id'       =>    $user->id,
            'otp_hash'      =>    Hash::make($otp),
            'expires_at'    =>    now()->addMinutes(5),
            'ip_address'    =>    $request->ip(),
            'user_agent'    =>    $request->userAgent(),
        ]);

        Mail::to($user->email)->send(new ForgotPasswordOtpMail($otp));

    Session::put('password_reset_user', $user->id);

        return redirect('/admin/verify-otp')->with('success', 'OTP sent to your email');
    }

    /* STEP 3: Show OTP Form */
    public function showOtpForm()
    {
        abort_if(!Session::has('password_reset_user'), 403);
        return view('admin.auth.verify-otp');
    }

    /* STEP 4: Verify OTP */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6'
        ]);

        $otpRecord = PasswordResetOtp::where('user_id', Session::get('password_reset_user'))
            ->where('is_used', false)
            ->latest()
            ->first();

        if (!$otpRecord || now()->gt($otpRecord->expires_at)) {
            return back()->withErrors(['otp' => 'OTP expired or invalid']);
        }

        if (!Hash::check($request->otp, $otpRecord->otp_hash)) {
            return back()->withErrors(['otp' => 'Incorrect OTP']);
        }

        $otpRecord->update(['is_used' => true]);

        Session::put('otp_verified', true);

        return redirect('/admin/reset-password');
    }

    /* STEP 5: Show Reset Form */
    public function showResetForm()
    {
        abort_if(!Session::get('otp_verified'), 403);
        return view('admin.auth.reset-password');
    }

    /* STEP 6: Reset Password */
    public function resetPassword(Request $request)
    {
        abort_if(!Session::get('otp_verified'), 403);

        $request->validate([
            'password' => 'required|min:8|confirmed'
        ]);

        $user = Admin::findOrFail(Session::get('password_reset_user'));

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        Session::forget(['password_reset_user', 'otp_verified']);

        return redirect('/admin/login')->with('success', 'Password reset successful');
    }
}