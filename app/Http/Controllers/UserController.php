<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function home () {
    
        return view('welcome');

    }

    public function signin () {
        return view('components.signin-modal');
    }

    public function signinEmail () {
        return view('components.signin-modal');
    }

    public function sendEmail(Request $request)
    {
        $email = $request->input('email');

        // Validate the email if needed
        // ...

        // Process the email and perform any necessary actions
        // ...

        return response()->json(['message' => 'Email sent successfully']);
    }
}
