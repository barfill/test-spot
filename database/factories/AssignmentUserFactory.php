<?php

namespace Database\Factories;

use App\Models\Assignment;
use App\Models\AssignmentUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AssignmentUser>
 */
class AssignmentUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(AssignmentUser::$status);

        return [
            'assignment_id' => Assignment::inRandomOrder()->first()?->id ?? Assignment::factory(),
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'status' => $status,
            'file_path' => $status !== 'in_progress' ? 'assignments/' . $this->faker->uuid() . '.zip' : null,
            'submitted_at' => $status !== 'in_progress' ? $this->faker->dateTimeBetween('-2 weeks', 'now') : null,
            'grade' => in_array($status, ['graded_passed', 'graded_failed']) ? $this->faker->numberBetween(2, 5) : null,
            'review_comment' => in_array($status, ['graded_passed', 'graded_failed']) ? $this->faker->optional(0.7)->sentence() : null,
            'plagiarism_check_result' => $status === 'pending' || in_array($status, ['graded_passed', 'graded_failed'])
                ? $this->faker->randomElement(AssignmentUser::$checkResults) : null,
            'compilation_check_result' => $status === 'pending' || in_array($status, ['graded_passed', 'graded_failed'])
                ? $this->faker->randomElement(AssignmentUser::$checkResults) : null,
            'edge_cases_check_result' => $status === 'pending' || in_array($status, ['graded_passed', 'graded_failed'])
                ? $this->faker->randomElement(AssignmentUser::$checkResults) : null,
        ];
    }


    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in_progress',
            'file_path' => null,
            'submitted_at' => null,
            'grade' => null,
            'review_comment' => null,
            'plagiarism_check_result' => null,
            'compilation_check_result' => null,
            'edge_cases_check_result' => null,
        ]);
    }


    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'file_path' => 'assignments/' . $this->faker->uuid() . '.zip',
            'submitted_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'grade' => null,
            'review_comment' => null,
            'plagiarism_check_result' => $this->faker->randomElement(AssignmentUser::$checkResults),
            'compilation_check_result' => $this->faker->randomElement(AssignmentUser::$checkResults),
            'edge_cases_check_result' => $this->faker->randomElement(AssignmentUser::$checkResults),
        ]);
    }


    public function gradedPassed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'graded_passed',
            'file_path' => 'assignments/' . $this->faker->uuid() . '.zip',
            'submitted_at' => $this->faker->dateTimeBetween('-2 weeks', '-3 days'),
            'grade' => $this->faker->numberBetween(4, 5),
            'review_comment' => $this->faker->randomElement([
                'Doskonała praca! Kod jest czytelny i działa poprawnie.',
                'Bardzo dobra implementacja, wszystkie testy przeszły.',
                'Świetne rozwiązanie, brak błędów kompilacji.',
            ]),
            'plagiarism_check_result' => 'passed',
            'compilation_check_result' => 'passed',
            'edge_cases_check_result' => 'passed',
        ]);
    }

    public function gradedFailed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'graded_failed',
            'file_path' => 'assignments/' . $this->faker->uuid() . '.zip',
            'submitted_at' => $this->faker->dateTimeBetween('-2 weeks', '-3 days'),
            'grade' => $this->faker->numberBetween(2, 3),
            'review_comment' => $this->faker->randomElement([
                'Kod nie kompiluje się poprawnie. Sprawdź składnię.',
                'Wykryto podobieństwa z innymi pracami - możliwy plagiat.',
                'Rozwiązanie nie radzi sobie z przypadkami brzegowymi.',
                'Błędy logiczne w implementacji algorytmu.',
            ]),
            'plagiarism_check_result' => $this->faker->randomElement(AssignmentUser::$checkResults),
            'compilation_check_result' => $this->faker->randomElement(AssignmentUser::$checkResults),
            'edge_cases_check_result' => $this->faker->randomElement(AssignmentUser::$checkResults),
        ]);
    }
}
