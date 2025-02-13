<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $user = User::create([
            'name' => 'Test',
            'email' => 'test@test.com',
            'password' => bcrypt('test'),
        ]);

        $tenant = Tenant::create([
            'name' => 'My Tenant',
            'email' => 'tenant@gmail.com',
            'contact' => '1234567890',
        ]);


        $tenant->users()->attach($user);



    }
}
