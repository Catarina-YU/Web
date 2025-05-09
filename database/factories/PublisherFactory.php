<?php

namespace Database\Factories;

use App\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Publisher>
 */
class PublisherFactory extends Factory
{
    public $model = Publisher::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique->company(),
            'address' => $this->faker->address()
        ];
    }
}
