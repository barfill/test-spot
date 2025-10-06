<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\DashboardSeeder;
use App\Models\Dashboard;
use App\Models\Assignment;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(20)->create();

        $me = User::firstOrCreate(
            ['email' => 'bartek@example.com'],
            [
                'first_name' => 'Bartosz',
                'last_name' => 'Filipczak',
            ]
        );

        $users->push($me);

        $this->call(DashboardSeeder::class);
        $dashboards = Dashboard::all();

        foreach ($users as $user) {
            $count = rand(4, 10);
            $user->userDashboards()->syncWithoutDetaching(
                $dashboards->random($count)->pluck('id')
            );
        }

        foreach ($dashboards as $dashboard) {
            $count = rand(1, 5);
            for ($i = 0; $i < $count; $i++) {
                Assignment::factory()->create([
                    'dashboard_id' => $dashboard->id,
                ]);
            }
        }
    }
}
