<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sermon>
 */
class SermonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(6);
        return [
            'title' => $title,
            'slug' => \Illuminate\Support\Str::slug($title),
            'speaker_name' => fake()->name(),
            'date_preached' => fake()->dateTimeBetween('-2 years', 'now'),
            'youtube_url' => fake()->optional(0.7)->url(),
            'audio_url' => fake()->optional(0.5)->url(),
            'description' => fake()->optional()->paragraph(3),
        ];
    }
}
