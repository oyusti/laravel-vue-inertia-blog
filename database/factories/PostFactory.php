<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'slug' => fake()->unique()->slug(),
            'extract' => fake()->text(200),
            'content' => fake()->text(3000),
            'is_published' => true,
            'published_at' => now(),
            'user_id' => 1,
            'category_id' => Category::all()->random()->id
        ];
    }
}
