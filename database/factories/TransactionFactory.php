<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => fn() => mt_rand(-1, 1) > 0 ? 'qris' : 'cash',
            'name' => $this->faker->name(),
            'amount' => $this->faker->numberBetween(10000, 1000000),
            'date' => $this->faker->date(max: '-10 day')
        ];
    }
}
