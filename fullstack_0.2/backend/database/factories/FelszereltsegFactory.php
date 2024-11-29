<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Felszereltseg;

class FelszereltsegFactory extends Factory
{
    protected $model = Felszereltseg::class;

    public function definition(): array
    {
        return [
            'tolatokamera' => fake()->boolean(50),
            'savtarto' => fake()->boolean(50),
            'tempomat' => fake()->boolean(50),
            'tolatoradar' => fake()->boolean(50),
            'multif_kormany' => fake()->boolean(50),
        ];
    }
}
