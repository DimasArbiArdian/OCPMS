<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CandidateProgress>
 */
class CandidateProgressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dp_status' => $this->faker->randomElement(['done', 'pending']),
            'medical_status' => $this->faker->randomElement(['done', 'not_yet']),
            'visa_status' => $this->faker->randomElement(['process', 'approved', 'rejected']),
            'ticket_status' => $this->faker->randomElement(['booked', 'not_yet']),
            'departure_date' => $this->faker->optional()->dateTimeBetween('now', '+1 year'),
            'remarks' => $this->faker->optional()->sentence(),
        ];
    }
}
