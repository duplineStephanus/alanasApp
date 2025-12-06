<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;

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
        $email = $request->email;
        $exists = User::whereRaw('LOWER(email) = ?', [strtolower($email)])->exists();

        return response()->json(['exists' => $exists]);
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
            'password' => 'required|confirmed|min:6'
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
        $otp = rand(100000, 999999);
        cache(["otp_{$request->email}" => $otp], now()->addMinutes(10));

        // TEMPORARY: log OTP to Laravel log for testing
        logger("OTP for {$request->email}: {$otp}");

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

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
    
}
