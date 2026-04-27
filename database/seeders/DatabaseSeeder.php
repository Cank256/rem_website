<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create users
        $users = User::factory(3)->create();

        // Create sermons
        \App\Models\Sermon::factory(10)->create();

        // Create events
        \App\Models\Event::factory(8)->create();

        // Create blog posts
        \App\Models\BlogPost::factory(15)->create([
            'author_id' => $users->random()->id,
        ]);
    }
}
