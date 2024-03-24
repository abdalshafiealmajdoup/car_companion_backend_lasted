<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition()
    {
        return [
            // Define your fake data structure here
            // For example:
            'name' => $this->faker->word,
            // Add other fields as needed
        ];
    }
}
