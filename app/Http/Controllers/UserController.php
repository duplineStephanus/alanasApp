<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;


class UserController extends Controller
{

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

        if (Auth::attempt($credentials)) {
            return response()->json(['success' => true]);
        }

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