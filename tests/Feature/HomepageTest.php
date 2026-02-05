<?php

use Database\Seeders\ProductSeeder;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

it('displays seeded products on the home page', function () {
    // 1. Run the specific seeder
    $this->seed(ProductSeeder::class);

    // 2. Visit the home route defined in web.php
    $response = $this->get('/');

    // 3. Assertions based on data inside ProductSeeder.php
    $response->assertStatus(200);
    
    // Check for the specific products created in the seeder
    $response->assertSee('Pure Tamanu Oil');
    $response->assertSee('Fragrant Tamanu Oil');

    // Verify the controller passed the products to the view
    $response->assertViewHas('products', function ($products) {
        return $products->count() === 2; // Based on the two products in your seeder
    });
});