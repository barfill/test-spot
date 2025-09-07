<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\DashboardSeeder;
use App\Models\Dashboard;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(20)->create();
        $users->push(User::factory()->create([
            'first_name' => 'Bartosz',
            'last_name' => 'Filipczak',
            'email' => 'bartek@example.com',
        ]));

        $this->call(DashboardSeeder::class);
        $dashboards = Dashboard::all();

        foreach ($users as $user) {
            $count = rand(4, 10);
            $user->userDashboards()->syncWithoutDetaching(
                $dashboards->random($count)->pluck('id')
            );
        }
    }
}
