<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function home () {
    
        return view('welcome');

    }

    public function signinEmail () {
        return view('components.signin-modal');
    }

    public function checkEmail(Request $request)
    {
        $email = $request->email;
        $exists = User::where('email', $email)->exists();

        return response()->json(['exists' => $exists]);
    }

    public function signin (Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        // Save user temporarily unverified
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => null,
        ]);

        // Generate OTP
        $otp = rand(100000, 999999);
        cache(["otp_{$request->email}" => $otp], now()->addMinutes(10));

        // (Optional) send OTP via email
        // Mail::raw("Your verification code is: {$otp}", function($msg) use ($request) {
        //     $msg->to($request->email)->subject('Verify your email');
        // });

        return response()->json(['status' => 'otp_sent']);
    }

    public function verifyOtp(Request $request)
    {
        $cachedOtp = cache("otp_{$request->email}");

        if ($cachedOtp && $cachedOtp == $request->code) {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                $user->email_verified_at = now();
                $user->save();
                cache()->forget("otp_{$request->email}");
                return response()->json(['verified' => true]);
            }
        }

        return response()->json(['verified' => false]);
    }
    
}
