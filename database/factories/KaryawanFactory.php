<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Karyawan>
 */
class KaryawanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $jobsiteOptions = ['BIB', 'MHU', 'BA'];

        return [
            'nrp' => fake()->unique()->randomNumber(),
            'nama' => fake()->name(),
            'departemen' => fake()->company(),
            'posisi' => fake()->jobTitle(),
            'jobsite' => fake()->randomElement($jobsiteOptions),
        ];
    }
}
