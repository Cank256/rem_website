<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlogPost>
 */
class BlogPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(8);
        return [
            'title' => $title,
            'slug' => \Illuminate\Support\Str::slug($title),
            'author_id' => \App\Models\User::factory(),
            'content' => fake()->paragraphs(10, true),
            'published_at' => fake()->optional(0.8)->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
