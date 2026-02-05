<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

// Uses the RefreshDatabase trait for all tests in this file
uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

it('returns otp_required if password is correct but email is not verified', function () {
    // 1. Setup: Create a user with a null email_verified_at
    $user = User::factory()->create([
        'email' => 'unverified@example.com',
        'password' => Hash::make('Password123!'),
        'email_verified_at' => null,
    ]);

    // 2. Action: Attempt to sign in
    $response = $this->postJson('/signin', [
        'email' => 'unverified@example.com',
        'password' => 'Password123!',
    ]);

    // 3. Assertion: Check for the 'otp_required' status
    $response->assertOk()
        ->assertJson(['status' => 'otp_required']);
});

it('locks the account after 3 failed password attempts', function () {
    // 1. Setup: Create a user
    $user = User::factory()->create([
        'email' => 'security@example.com',
        'password' => Hash::make('CorrectPassword123!'),
    ]);

    // 2. Action: Fail 3 times (Max attempts for password is 3)
    foreach (range(1, 3) as $attempt) {
        $this->postJson('/signin', [
            'email' => 'security@example.com',
            'password' => 'wrong-password',
        ]);
    }

    // 3. Assertion: The 4th attempt should return 'locked'
    $this->postJson('/signin', [
        'email' => 'security@example.com',
        'password' => 'wrong-password',
    ])
    ->assertJson([
        'status' => 'locked',
        'retry_after' => 24 // Based on your AuthAttemptService config
    ]);
});

it('logs out the user and clears the session', function () {
    // 1. Setup: Create and act as a user
    $user = User::factory()->create();

    // 2. Action: Hit the logout route
    $this->actingAs($user)
        ->post('/logout')
        ->assertRedirect('/');

    // 3. Assertion: User is now a guest
    $this->assertGuest();
});

it('fails login with invalid credentials', function () {
    // Action: Attempt login for non-existent user
    $this->postJson('/signin', [
        'email' => 'nonexistent@example.com',
        'password' => 'any-password',
    ])
    ->assertJson([
        'status' => 'invalid',
        'message' => 'Invalid credentials'
    ]);
});