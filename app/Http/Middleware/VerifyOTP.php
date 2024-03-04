<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyOTP
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah pengguna telah memasukkan kode OTP yang benar
        if (!$request->session()->has('email') || !$request->session()->has('otp_verified')) {
            // Redirect ke halaman OTP jika kode OTP belum diverifikasi
            return redirect()->route('otp');
        }

        return $next($request);
    }
}
