<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('autheticate credetials and sign in user', function () {
    $email = 'test@example.com';
    $password = 'Password123!';

    User::factory()->create([
        'email'    => $email,
        'password' => Hash::make($password),
    ]);

    $this->postJson('/signin', [
        'email'    => $email,
        'password' => $password,
    ])
        ->assertOk()
        ->assertJson(['status' => 'success']);

});

it('verifies correct OTP and logs in user', function () {
    $email = 'test@example.com';
    $password = 'Password123!';

    $user = User::factory()->create([
        'email'    => $email,
        'password' => Hash::make($password),
    ]);

    $this->postJson('/signin', [
        'email'    => $email,
        'password' => $password,
    ]);

    $otp = Cache::get('otp_' . strtolower($email));

    $this->postJson('/verify-otp', [
        'email' => $email,
        'otp'   => $otp,
    ])
        ->assertOk()
        ->assertJson(['status' => 'verified']);

    $this->assertAuthenticatedAs($user);
});

it('rejects wrong password immediately', function () {
    $this->postJson('/signin', [
        'email'    => 'test@example.com',
        'password' => 'wrong!',
    ])
        ->assertOk()
        ->assertJson([
            'status'  => 'invalid',
            'message' => 'Invalid credentials',
        ]);
});

it('resends a new OTP', function () {
    $email = 'test@example.com';
    $password = 'Password123!';

    User::factory()->create([
        'email'    => $email,
        'password' => Hash::make($password),
    ]);

    $this->postJson('/signin', [
        'email'    => $email,
        'password' => $password,
    ]);

    $originalOtp = Cache::get('otp_' . strtolower($email));

    $this->postJson('/resend-otp', ['email' => $email])
        ->assertOk()
        ->assertJson(['status' => 'otp_resent']);

    $newOtp = Cache::get('otp_' . strtolower($email));

    expect($newOtp)->not->toEqual($originalOtp);
});