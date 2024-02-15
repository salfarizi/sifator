<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Consumer>
 */
class ConsumerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'unique' => uniqid(),
            'penjual' => fake()->name(),
            'nik' => fake()->name(),
            'nama' => fake()->name(),
            'dealer' => fake()->name(),
            'alamat' => fake()->name(),
            'no_telepon' => fake()->name(),
            'photo_ktp' => fake()->name(),
        ];
    }
}
