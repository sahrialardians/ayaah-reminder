<?php

namespace Database\Factories;

use App\Models\AyahRead;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AyahRead>
 */
class AyahReadFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AyahRead::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'surah_number' => $this->faker->numberBetween(1, 114),
            'start_ayah' => 1,
            'end_ayah' => $this->faker->numberBetween(1, 10), // Example range
            'read_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
