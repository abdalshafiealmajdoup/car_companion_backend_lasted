<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition()
    {
        return [
            'Name' => $this->faker->name,
            'Phone' => $this->faker->phoneNumber,
            'Email' => $this->faker->unique()->safeEmail,
            'Password' => bcrypt('password'),
        ];

    }
}
