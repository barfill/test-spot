<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\DashboardSeeder;
use App\Models\Dashboard;
use App\Models\Assignment;
use App\Models\AssignmentUser;

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
                'title' => 'Mgr',
                'type' => 'teacher',
                'password' => 'password',
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

        // Create assignment submissions for students
        $students = User::where('type', 'student')->get();
        $assignments = Assignment::all();

        foreach ($assignments as $assignment) {
            $dashboardStudents = $assignment->dashboard->users()->where('type', 'student')->get();

            foreach ($dashboardStudents as $student) {
                if (rand(1, 100) <= 80) {
                    $statusChance = rand(1, 100);

                    if ($statusChance <= 30) {
                        AssignmentUser::factory()->inProgress()->create([
                            'assignment_id' => $assignment->id,
                            'user_id' => $student->id,
                        ]);
                    } elseif ($statusChance <= 50) {
                        AssignmentUser::factory()->pending()->create([
                            'assignment_id' => $assignment->id,
                            'user_id' => $student->id,
                        ]);
                    } elseif ($statusChance <= 85) {
                        AssignmentUser::factory()->gradedPassed()->create([
                            'assignment_id' => $assignment->id,
                            'user_id' => $student->id,
                        ]);
                    } else {
                        AssignmentUser::factory()->gradedFailed()->create([
                            'assignment_id' => $assignment->id,
                            'user_id' => $student->id,
                        ]);
                    }
                }
            }
        }

    }
}
