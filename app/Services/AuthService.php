<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use App\Services\CartService;
use App\Services\AuthAttemptService;

class AuthService
{
    protected AuthAttemptService $attempts;
    protected CartService $cartService;
  
    public function __construct(
        AuthAttemptService $attempts,
        CartService $cartService
      
        )
    {
        $this->attempts = $attempts;
        $this->cartService = $cartService;
      
    }

    public function signin(Request $request)
    {
        $email = strtolower($request->email);
        $password = $request->password;

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'invalid',
                'message' => 'Invalid credentials'
            ]);
        }

        $userId = $user->id; // for attempt tracking

        // 1. Check password attempts lockout
        if ($this->attempts->isMaxed('password', $userId)) {
            return response()->json([
                'status' => 'locked',
                'retry_after' => 24
            ]);
        }

        // 2. Verify password
        if (!Hash::check($password, $user->password)) {
            //record failed attempt
            $this->attempts->record('password', $userId);
            return response()->json([
                'status' => 'invalid',
                'message' => 'Invalid credentials'
            ]);
        }

        // 3. Password correct → reset attempts
        $this->attempts->reset('password', $userId);

        // 4. Check OTP verification
        if (!$user->email_verified_at) {
            return response()->json([
                'status' => 'otp_required'
            ]);
        }

        // 5. Fully authenticated → now login
        Auth::login($user);
        $this->cartService->migrateCart();

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function register(Request $request)
    {        
        //normalize email
        $email = strtolower($request->email);

        // Save user temporarily unverified
        $user = User::create([
            'name' => $request->name,
            'email' => $email,
            'password' => Hash::make($request->password),
            'email_verified_at' => null,
        ]);

        // Generate OTP
        $otp = strval(rand(100000, 999999));

        // Store OTP in cache, keyed by email, expiring in 10 minutes
        Cache::put('otp_' . $email, $otp, now()->addMinutes(10));

        // TEMPORARY: log OTP to Laravel log for testing
        logger("OTP for {$email}: {$otp}");

        // (Future Setup) send OTP via email
        // Mail::raw("Your verification code is: {$otp}", function($msg) use ($request) {
        //     $msg->to($request->email)->subject('Verify your email');
        // });

        return response()->json(['status' => 'otp_sent', 'message' => 'OTP sent successfully']);
    }
}