<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Candidate>
 */
class CandidateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'position' => $this->faker->jobTitle(),
            'phone' => $this->faker->e164PhoneNumber(),
            'passport_number' => strtoupper($this->faker->bothify('??######')),
            'passport_expired' => $this->faker->dateTimeBetween('+1 year', '+10 years'),
            'country' => $this->faker->country(),
        ];
    }
}
