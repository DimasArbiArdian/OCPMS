<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['passport', 'medical', 'visa', 'ticket']),
            'file_path' => 'documents/' . $this->faker->uuid() . '.pdf',
            'uploaded_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
