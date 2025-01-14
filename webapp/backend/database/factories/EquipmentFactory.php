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
            'reversing_camera' => fake()->boolean(50),
            'lane_keep_assist' => fake()->boolean(50),
            'adaptive_cruise_control' => fake()->boolean(50),
            'parking_sensors' => fake()->boolean(50),
            'multifunction_wheel' => fake()->boolean(50),
        ];
    }
}
