<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DashboardPage>
 */
class DashboardPageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(3);
        return [
            'nama_dashboard' => $title,
            'slug' => Str::slug($title),
            'platform' => fake()->randomElement(['looker', 'tableau']),
            'embed_link' => fake()->url(),
            'thumbnail' => 'thumbnails/default.jpg',
            'dibuat_oleh' => \app\Models\User::inRandomOrder()->first()?->id ?? 1
        ];
    }
}
