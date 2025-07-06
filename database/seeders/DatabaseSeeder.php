<?php

namespace Database\Seeders;

use App\Models\Role;
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
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        Role::insert([
            [
                'name' => 'admin'
            ],
            [
                'name' => 'customer'
            ],
            [
                'name' => 'marketing'
            ],
            [
                'name' => 'penyedia sampling'
            ],
            [
                'name' => 'koor teknis'
            ],
        ]);

        User::insert([
            [

                "username" => "customer",
                "password" => "customer",
                "role_id" => 2
            ],
            [

                "username" => "marketing",
                "password" => "marketing",
                "role_id" => 3
            ],
            [

                "username" => "penyedia sampling",
                "password" => "penyediasampling",
                "role_id" => 4
            ],
            [

                "username" => "admin",
                "password" => "admin",
                "role_id" => 1
            ],
            
        ]);

    }
}
