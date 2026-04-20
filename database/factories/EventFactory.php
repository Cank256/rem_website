<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(4);
        $startDate = fake()->dateTimeBetween('now', '+6 months');
        return [
            'title' => $title,
            'slug' => \Illuminate\Support\Str::slug($title),
            'start_datetime' => $startDate,
            'end_datetime' => fake()->optional(0.8)->dateTimeBetween($startDate, $startDate->format('Y-m-d H:i:s') . ' +3 hours'),
            'location' => fake()->optional(0.9)->address(),
            'description' => fake()->paragraph(4),
        ];
    }
}
