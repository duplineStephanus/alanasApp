<?php

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;

// Uses the RefreshDatabase trait to ensure the DB is wiped and migrated for every test run
uses(RefreshDatabase::class);

it('verifies a correct OTP and logs in the user', function () {
    // 1. Setup: Create a user and a valid cached OTP
    $user = User::factory()->create([
        'email' => 'otp-test@mail.com',
        'email_verified_at' => null,
    ]);
    
    $validOtp = '123456';
    Cache::put('otp_otp-test@mail.com', $validOtp, now()->addMinutes(10));

    // 2. Action: Submit correct OTP
    $response = $this->postJson('/verify-otp', [
        'email' => 'otp-test@mail.com',
        'code'  => $validOtp,
    ]);

    // 3. Assertion: Status is verified, user is logged in, and email_verified_at is set
    $response->assertOk()->assertJson(['status' => 'verified']);
    
    $this->assertAuthenticatedAs($user);
    expect($user->fresh()->email_verified_at)->not->toBeNull();
    expect(Cache::has('otp_otp-test@mail.com'))->toBeFalse(); // Cache should be cleared
});

it('locks the account after 5 failed OTP attempts', function () {
    // 1. Setup: Create a user and set an OTP in cache
    $user = User::factory()->create(['email' => 'lockout-test@mail.com']);
    Cache::put('otp_lockout-test@mail.com', '123456', now()->addMinutes(10));

    // 2. Action: Fail 5 times (The limit defined in AuthAttemptService)
    foreach (range(1, 5) as $attempt) {
        $this->postJson('/verify-otp', [
            'email' => 'lockout-test@mail.com',
            'code'  => '000000', // Wrong code
        ])->assertJson(['status' => 'invalid']);
    }

    // 3. Assertion: The 6th attempt should return 'locked'
    $response = $this->postJson('/verify-otp', [
        'email' => 'lockout-test@mail.com',
        'code'  => '123456', // Even the right code should fail now
    ]);

    $response->assertJson([
        'status' => 'locked',
        'retry_after' => 1 // Defined as 1 hour in AuthAttemptService
    ]);
});

it('fails verification if the user does not exist', function () {
    $this->postJson('/verify-otp', [
        'email' => 'ghost@mail.com',
        'code'  => '123456',
    ])->assertJson(['status' => 'invalid']);
});