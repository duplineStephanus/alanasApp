<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Services\CartService;
use App\Services\AuthAttemptService;

class AuthService
{
    protected CartService $cartService;
    protected AuthAttemptService $attempts;

    public function __construct(
        CartService $cartService, 
        AuthAttemptService $attempts
        )
    {
        $this->cartService = $cartService;
        $this->attempts = $attempts;
    }

    public function signin(Request $request)
    {
        $email = strtolower($request->email);
        $password = $request->password;

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['status' => 'invalid', 'message' => 'Invalid credentials']);
        }

        $userId = $user->id;

        if ($this->attempts->isMaxed('password', $userId)) {
            return response()->json(['status' => 'locked', 'retry_after' => 24]);
        }

        if (!Hash::check($password, $user->password)) {
            $this->attempts->record('password', $userId);
            return response()->json(['status' => 'invalid', 'message' => 'Invalid credentials']);
        }

        $this->attempts->reset('password', $userId);

        if (!$user->email_verified_at) {
            return response()->json(['status' => 'otp_required']);
        }

        Auth::login($user);
        $this->cartService->migrateCart();

        return response()->json(['status' => 'success']);
    }

    public function register(Request $request)
    {
        $request->merge(['email' => strtolower($request->email)]);

        $validator = Validator::make($request->all(), [
            'email'    => 'required|email:rfc,dns|unique:users,email',
            'name'     => 'required|string|max:225|min:4',
            'password' => [
                'required', 'confirmed', 'min:8', 'max:25',
                'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*?&]/'
            ],
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->messages());
        }

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'email_verified_at' => null,
        ]);

        $otp = strval(rand(100000, 999999));
        Cache::put('otp_' . $request->email, $otp, now()->addMinutes(10));

        logger("OTP for {$request->email}: {$otp}");

        return response()->json(['status' => 'otp_sent', 'message' => 'OTP sent successfully']);
    }

    public function prepareOtpForEmail(Request $request)
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
        $code  = $request->code;

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['status' => 'invalid']);
        }

        $userId = $user->id;

        if ($this->attempts->isMaxed('otp', $userId)) {
            return response()->json(['status' => 'locked', 'retry_after' => 1]);
        }

        $storedOtp = Cache::get('otp_' . $email);

        if ($storedOtp !== $code) {
            $this->attempts->record('otp', $userId);
            return response()->json([
                'status'  => 'invalid',
                'message' => 'Invalid OTP. Please try again.'
            ]);
        }

        Auth::login($user);
        Cache::forget('otp_' . $email);
        $this->attempts->reset('otp', $userId);

        $user->email_verified_at = now();
        $user->save();

        $this->cartService->migrateCart();

        return response()->json(['status' => 'verified']);
    }

    public function resendOtp(Request $request)
    {
        $email = strtolower($request->email);
        $user  = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['status' => 'invalid']);
        }

        $otp = strval(rand(100000, 999999));
        Cache::put('otp_' . $email, $otp, now()->addMinutes(10));

        logger("Resent OTP for {$email}: {$otp}");

        return response()->json(['status' => 'otp_resent', 'message' => 'OTP sent successfully']);
    }
}