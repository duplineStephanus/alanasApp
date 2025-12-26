<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class UserController extends Controller
{

    protected function ResetAttempts(string $type): void
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

        $userId = auth()->id();

        // 2. Reset attempts if record exists
        DB::table($table)
            ->where('user_id', $userId)
            ->update([
                'failed_attempts' => 0,
                'last_failed_at' => null,
                'updated_at' => now(),
            ]);
    }

    protected function RecordFailedAttempt(string $type): void
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

        $userId = auth()->id();

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


    protected function AttemptsMaxed(string $type): bool
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

        $userId = auth()->id();

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
        $user = User::whereRaw('LOWER(email) = ?', [$email])->first();
        if ($user) {
            return response()->json([
                'exists' => true,
                'is_verified' => $user->email_verified_at !== null,
            ]);
        }
        return response()->json(['exists' => false]);

    }

    public function signin (Request $request)
    {

        $credentials = [
            'email' => strtolower($request->email),
            'password' => $request->password
        ];
        //change to check if otp_verified && Auth::attempt($credentials) then log user in
        if (Auth::attempt($credentials)) {
            return response()->json(['success' => true]);
        }

        // run AttemptsMaxed method

        //if AttemptsMaxed returns true
        //return response()->json(['success' => false, 'message' => 'Attempts maxed']);
        
        //else return response()->json(['success' => false, 'message' => 'Invalid credentials']);

        //return response()->json(['success' => false, ']);
        return response()->json(['success' => false]);
    }

    public function register(Request $request)
    {

        // Force email to lowercase
        $request->merge([
            'email' => strtolower($request->email)
        ]);

        $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string|max:255',
            'password' => [
                'required',
                'confirmed',
                'min:8',
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

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6', // Adjust size based on your OTP format
        ]);

        $email = strtolower($request->email);
        $code = $request->code;

        // Retrieve stored OTP (example using Cache; replace with your storage method, e.g., session('otp_' . $email))
        $storedOtp = (string) Cache::get('otp_' . $email);

        if (!$storedOtp || $storedOtp !== $code) {
            return response()->json(['verified' => false, 'message' => 'Invalid OTP']);
        }

        // Clear the OTP after successful use
        Cache::forget('otp_' . $email);

        // Retrieve the user (assuming created during /register and marked as unverified)
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['verified' => false, 'message' => 'User not found']);
        }

        // Mark user as email-verified if you have a 'email_verified_at' column
        $user->email_verified_at = now();
        $user->save();

        // Auto-login the user
        Auth::login($user);

        return response()->json(['verified' => true, 'message' => 'Account verified and logged in successfully']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
    
}