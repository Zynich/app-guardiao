<?php

namespace Database\Factories;

use App\Enums\Priority;
use App\Enums\TicketStatus;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TicketFactory extends Factory
{
    public function definition(): array
    {
        return [
            'protocol'      => date('Y') . '-' . strtoupper(Str::random(6)),
            'citizen_name'  => fake()->name(),
            'citizen_email' => fake()->safeEmail(),
            'citizen_phone' => fake()->phoneNumber(),
            'category_id'   => Category::factory(),
            'status'        => TicketStatus::PENDENTE_TRIAGEM,
            'priority'      => Priority::MEDIA,
            'description'   => fake()->paragraph(),
            'address'       => fake()->streetAddress() . ', ' . fake()->city(),
        ];
    }

    public function emAndamento(): static
    {
        return $this->state(['status' => TicketStatus::EM_ANDAMENTO]);
    }

    public function resolvido(): static
    {
        return $this->state(['status' => TicketStatus::RESOLVIDO]);
    }

    public function comSlaVencido(): static
    {
        return $this->state(['due_date' => now()->subHours(1)]);
    }
}
