<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DashboardView>
 */
class DashboardViewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dashboard_id' => \App\Models\DashboardPage::inRandomOrder()->first()?->id,
            'user_id' => fake()->boolean(50)
            ? \App\Models\User::inRandomOrder()->first()?->id
            : null,
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'created_at' => Carbon::now()->subMonths(rand(0, 12)),
            'updated_at' => now()
        ];
    }
}
