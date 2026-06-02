<?php

namespace Database\Factories;

use App\Enums\Priority;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'      => fake()->unique()->words(3, true),
            'priority'  => Priority::MEDIA,
            'sla_hours' => 72,
            'is_active' => true,
        ];
    }

    public function urgente(): static
    {
        return $this->state(['priority' => Priority::URGENTE, 'sla_hours' => 4]);
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }
}
