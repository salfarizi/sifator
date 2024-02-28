<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\SendOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendOtp(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Email harus valid',
            'password.required' => 'Password tidak boleh kosong',
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt($credentials)) {
            // Retrieve the authenticated user
            $user = Auth::user();

            // Ensure $user is an instance of the User model
            if ($user instanceof User) {
                // Generate OTP
                $otp = rand(100000, 999999);

                // Update the user's OTP
                $user->update([
                    'otp' => $otp,
                ]);

                // Send OTP mail
                Mail::to($request->email)->send(new SendOtpMail($otp));
                session(['email' => $request->email]);

                // Regenerate session
                $request->session()->regenerate();

                // Redirect to intended page
                return redirect()->intended('otp');
            } else {
                // Handle the case where $user is not an instance of User
                return back()->with('error', 'Email atau Password Salah.');
            }
        }

        // Authentication failed
        return back()->with('error', 'Email atau Password Salah');
    }

    public function checkOtp(Request $request)
    {
        $user = User::where('otp', $request->otp)->where('email', session('email'))->first();
        if ($user) {
            $user->update([
                'otp' => '-',
            ]);
            return redirect()->route('home')->with('success', 'Login Berhasil');
        } else {
            return back()->with('error', 'OTP Salah');
        }
    }
    public function resendOtp(Request $request)
    {
        // Retrieve email from session
        $email = session('email');

        // Check if email exists in session
        if (!$email) {
            return back()->with('error', 'Email tidak ditemukan');
        }

        // Retrieve user by email
        $user = User::where('email', $email)->first();

        // Check if user exists
        if (!$user) {
            return back()->with('error', 'Pengguna tidak ditemukan');
        }

        // Generate new OTP
        $otp = rand(100000, 999999);

        // Update the user's OTP
        $user->update([
            'otp' => $otp,
        ]);

        // Resend OTP mail
        Mail::to($email)->send(new SendOtpMail($otp));

        return back()->with('success', 'Kode OTP telah berhasil dikirim ulang');
    }
}
