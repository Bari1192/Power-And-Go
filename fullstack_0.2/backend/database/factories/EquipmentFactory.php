<?php

namespace Database\Factories;

use App\Models\Equipment;
use Illuminate\Database\Eloquent\Factories\Factory;

class EquipmentFactory extends Factory
{
    protected $model = Equipment::class;

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
