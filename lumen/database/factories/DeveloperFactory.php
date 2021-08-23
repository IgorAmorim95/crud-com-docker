<?php

namespace Database\Factories;

use App\Model;
use App\Models\Developer;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeveloperFactory extends Factory
{
    protected $model = Developer::class;

    public function definition(): array
    {
        return [
            'nome' => $this->faker->name,
            'sexo' => $this->faker->randomElement(['M', 'F', 'O']),
            'idade' => $this->faker->numberBetween(10, 100),
            'hobby' => $this->faker->sentence(3),
            'datanascimento' => $this->faker->date('Y-m-d', '-10years')
        ];
    }
}
