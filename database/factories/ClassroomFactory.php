<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Classroom>
 */
class ClassroomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'classroom_name' => $this->faker->numerify('I#'),
            'location' => $this->faker->localCoordinates(),
            'capacity' => $this->faker->numberBetween(20, 70),
            'type' => $this->faker->randomElement(['Laboratorio', 'Aula'])
        ];
    }
}
