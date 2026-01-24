<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckEmailRequest;
use App\Http\Requests\PrepareOtpRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use App\Models\Product;
use App\Services\AuthService;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected AuthService $authService;
    protected OtpService $otpService;

    public function __construct(
        AuthService $authService,
        OtpService $otpService

    )
    {
        $this->authService = $authService;
        $this->otpService = $otpService;
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

    public function checkEmail(CheckEmailRequest $request)
    {
        $email = strtolower($request->email);

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

    public function signin(Request $request){
        return $this->authService->signin($request);
    }

    public function register(RegisterUserRequest $request){
        return $this->authService->register($request);
    }

    public function prepareOtpForEmail(PrepareOtpRequest $request){
        return $this->otpService->prepareForEmail($request);
    }

    public function verifyOtp(Request $request){
        return $this->otpService->verifyOtp($request);
    }

    public function resendOtp(Request $request){
        return $this->otpService->resendOtp($request);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}