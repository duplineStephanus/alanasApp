<?php

namespace App\Http\Controllers;

use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class UserController extends Controller
{

    protected function ResetAttempts(string $type, int $userId): void
    {
        // 1. Determine which table to use
        switch ($type) {
            case 'password':
                $table = 'password_attempts';
                break;

            case 'otp':
                $table = 'otp_attempts';
                break;

            default:
                // Abort immediately if invalid type is provided
                throw new BadRequestHttpException('Invalid attempt type.');
        }

        $userId = $userId;

        // 2. Reset attempts if record exists
        DB::table($table)
            ->where('user_id', $userId)
            ->update([
                'failed_attempts' => 0,
                'last_failed_at' => null,
                'updated_at' => now(),
            ]);
    }

    protected function RecordFailedAttempt(string $type, int $userId): void
    {
        // 1. Determine which table to use
        switch ($type) {
            case 'password':
                $table = 'password_attempts';
                break;

            case 'otp':
                $table = 'otp_attempts';
                break;

            default:
                // Abort immediately on invalid type
                throw new BadRequestHttpException('Invalid attempt type.');
        }

        $userId = $userId;

        // 2. Ensure a record exists (create if missing)
        $attempt = DB::table($table)
            ->where('user_id', $userId)
            ->first();

        if (!$attempt) {
            DB::table($table)->insert([
                'user_id' => $userId,
                'failed_attempts' => 1,
                'last_failed_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return;
        }

        // 3. Increment failed attempts
        DB::table($table)
            ->where('user_id', $userId)
            ->update([
                'failed_attempts' => $attempt->failed_attempts + 1,
                'last_failed_at' => now(),
                'updated_at' => now(),
            ]);
    }


    protected function AttemptsMaxed(string $type, int $userId): bool
    {
        // 1. Determine model, limits, and lockout window
        switch ($type) {
            case 'password':
                $table = 'password_attempts';
                $maxAttempts = 3;
                $lockoutWindow = 24; // hours
                break;

            case 'otp':
                $table = 'otp_attempts';
                $maxAttempts = 5;
                $lockoutWindow = 1; // hours
                break;

            default:
                // Abort immediately if invalid type is sent
                throw new BadRequestHttpException('Invalid attempt type.');
        }

        $userId = $userId;

        // 2. Fetch attempt record
        $attempt = DB::table($table)
            ->where('user_id', $userId)
            ->first();

        // 3. If no record exists, user is not locked
        if (!$attempt || $attempt->failed_attempts < $maxAttempts) {
            return false;
        }

        // 4. Check if lockout window has expired
        $lockoutExpiresAt = Carbon::parse($attempt->last_failed_at)
            ->addHours($lockoutWindow);

        if (now()->greaterThanOrEqualTo($lockoutExpiresAt)) {
            // Lock expired → reset attempts
            DB::table($table)
                ->where('user_id', $userId)
                ->update([
                    'failed_attempts' => 0,
                    'last_failed_at' => null,
                    'updated_at' => now(),
                ]);

            return false;
        }

        // 5. Still locked
        return true;
    }


    public function home () {
        
         //if user/guest show all products using index function 
         $products = Product::with('variants')->get();

         if(!auth()->user() || !auth()->user()->is_admin) {
             return view('shop.index', compact('products'));
         }
         
         // if admin show dashboard page
         //return view('admin.dashboard');

    }

    public function signinEmail () {
        return view('components.signin-modal');
    }

    public function checkEmail(Request $request)
    {
        $email = strtolower($request->email);

        // Step 1: Validate email format only (RFC + DNS)
        $validator = Validator::make($request->all(), [
            'email' => 'required|email:rfc,dns',
        ]);

        if ($validator->fails()) {
            // Invalid email format: stop here, no DB check
            return response()->json([
                'exists' => false,
                'error' => 'Please enter a valid email.',
            ], 422);
        }

        // Step 2: Check if email exists in DB
        $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

        if ($user) {
            return response()->json([
                'exists' => true,
                'is_verified' => $user->email_verified_at !== null,
            ]);
        }

        return response()->json([
            'exists' => false,
        ]);
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
        if ($this->AttemptsMaxed('password', $userId)) {
            return response()->json([
                'status' => 'locked',
                'retry_after' => 24
            ]);
        }

        // 2. Verify password
        if (!Hash::check($password, $user->password)) {
            $this->RecordFailedAttempt('password', $userId);
            return response()->json([
                'status' => 'invalid',
                'message' => 'Invalid credentials'
            ]);
        }

        // 3. Password correct → reset attempts
        $this->ResetAttempts('password', $userId);

        // 4. Check OTP verification
        if (!$user->email_verified_at) {
            return response()->json([
                'status' => 'otp_required'
            ]);
        }

        // 5. Fully authenticated → now login
        Auth::login($user);

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function register(Request $request)
    {

        // Force email to lowercase
        $request->merge([
            'email' => strtolower($request->email)
        ]);

        $request->validate([
            'email' => 'required|email:rfc,dns|unique:users,email',
            'name' => [
                'required',
                'string',
                'max:225',
                'min:4'
            ],
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'max:25',
                'regex:/[a-z]/',    // at least one lowercase
                'regex:/[A-Z]/',    // at least one uppercase
                'regex:/[0-9]/',    // at least one number
                'regex:/[@$!%*?&]/' // at least one special character
            ],
        ]);
        
        //normalize email
        $email = strtolower($request->email);

        // Save user temporarily unverified
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => null,
        ]);

        // Generate OTP
        $otp = strval(rand(100000, 999999));

        // Store OTP in cache, keyed by email, expiring in 10 minutes
        Cache::put('otp_' . $request->email, $otp, now()->addMinutes(10));

        // TEMPORARY: log OTP to Laravel log for testing
        logger("OTP for {$request->email}: {$otp}");

        // (Future Setup) send OTP via email
        // Mail::raw("Your verification code is: {$otp}", function($msg) use ($request) {
        //     $msg->to($request->email)->subject('Verify your email');
        // });

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
                'status' => 'otp_ready',
                'message' => 'Your previous One Time Password expired. A new one has been sent to your email, '
            ]);
        }

        return response()->json([
            'status' => 'otp_ready',
            'message' => 'To verify your email address we have sent a One Time Password to your email, '
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

        Auth::login($user);

        if ($this->AttemptsMaxed('otp', $userId)) {
            Auth::logout();

            return response()->json([
                'status' => 'locked',
                'retry_after' => 1
            ]);
        }

        $storedOtp = Cache::get('otp_' . $email);

        //If OTP does not match
        if ($storedOtp !== $code) {
            $this->RecordFailedAttempt('otp', $userId);
            Auth::logout();

            return response()->json([
                'status' => 'invalid',
                'message' => 'Invalid OTP. Please try again.'
            ]);
        }

        // OTP correct
        Cache::forget('otp_' . $email);
        $this->ResetAttempts('otp', $userId);

        $user->email_verified_at = now();
        $user->save();

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

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
    
}