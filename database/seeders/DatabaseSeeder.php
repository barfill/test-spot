<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\DashboardSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(20)->create();
        User::factory()->create([
            'first_name' => 'Bartosz',
            'last_name' => 'Filipczak',
            'email' => 'bartek@example.com',
        ]);

        $this->call(DashboardSeeder::class);
    }
}
