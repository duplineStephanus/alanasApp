<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

// Uses the RefreshDatabase trait to ensure the DB is wiped and migrated for every test run
uses(RefreshDatabase::class);

it('identifies an existing user by email', function () {
    User::factory()->create(['email' => 'thisguyisdope@mail.com']);

    $this->postJson('/check-email', ['email' => 'THISGUYISDOPE@mail.com']) // Testing the strtolower/LOWER logic
        ->assertOk()
        ->assertJson(['exists' => true]);
});

it('returns exists false for a new email', function () {
    $this->postJson('/check-email', ['email' => 'anotherdopeguy@mail.com'])
        ->assertOk()
        ->assertJson(['exists' => false]);
});

it('fails validation if email is invalid', function () {
    $this->postJson('/check-email', ['email' => 'not-an-email'])
        ->assertStatus(422) // Validation error
        ->assertJsonValidationErrors(['email']);
});