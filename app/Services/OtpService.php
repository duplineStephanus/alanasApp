<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Services\AuthAttemptService;

class OtpService
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
    
    public function prepareForEmail(Request $request): \Illuminate\Http\JsonResponse
    {
        $email = strtolower($request->email);

        $storedOtp = Cache::get('otp_' . $email);

        if (!$storedOtp) {
            $newOtp = strval(rand(100000, 999999));
            Cache::put('otp_' . $email, $newOtp, now()->addMinutes(10));
            logger("New OTP for {$email}: {$newOtp}");

            return response()->json([
                'status'  => 'otp_ready',
                'message' => 'Your previous One Time Password expired. A new one has been sent.'
            ]);
        }

        return response()->json([
            'status'  => 'otp_ready',
            'message' => 'A One Time Password has been sent to your email.'
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $email = strtolower($request->email);
        $code = $request->code;

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'invalid'
            ]);
        }

        $userId = $user->id;

        //Auth::login($user);
        //1. if user maxed out on their attempts kick them out 
        if ($this->attempts->isMaxed('otp', $userId)) {
            //Auth::logout();

            return response()->json([
                'status' => 'locked',
                'retry_after' => 1
            ]);
        }

        $storedOtp = Cache::get('otp_' . $email);

        //2. If OTP does not match kick them out 
        if ($storedOtp !== $code) {
            //record failed attempts
            $this->attempts->record('otp', $userId);
            //Auth::logout();

            return response()->json([
                'status' => 'invalid',
                'message' => 'Invalid OTP. Please try again.'
            ]);
        }

        // 3. if OTP correct - login the user
        Auth::login($user);
        Cache::forget('otp_' . $email);
        //reset attempts 
        $this->attempts->reset('otp', $userId);

        $user->email_verified_at = now();
        $user->save();
        $this->cartService->migrateCart();

        return response()->json([
            'status' => 'verified'
        ]);
    }

    public function resendOtp(Request $request)
    {
        $email = strtolower($request->email);
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['status' => 'invalid']);
        }

        // Generate a new OTP
        $otp = strval(rand(100000, 999999));
        Cache::put('otp_' . $email, $otp, now()->addMinutes(10));

        // Optional: log OTP for testing
        logger("Resent OTP for {$email}: {$otp}");

        // (Future: send OTP via email)
        return response()->json(['status' => 'otp_resent', 'message' => 'OTP sent successfully']);
    }
}