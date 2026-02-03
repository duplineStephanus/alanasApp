<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(ProductSeeder::class); 
        
        //to create 3 users using the UserFactory (no overinding data) 
        User::factory(3)->create();

        // to overide specific factory data pass an array  
        /*
        User::factory()->create([
            'name' => 'Jane Doe',
            'email' => 'Jane.Doe@gmail.com',
        ]);
        */
    }
}
