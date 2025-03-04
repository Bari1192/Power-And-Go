<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Rentinprocess;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RentinProcessSeeder extends Seeder
{
    public function run(): void
    {
        $folyamatbanLevok = [];
        for ($i = 0; $i < 35; $i++) {
            $auto = Car::where('status', 1)->inRandomOrder()->first();
            if (!$auto) {
                continue;
            }

            $rental = Rentinprocess::factory()->make([
                'car_id'       => $auto->id,
                'category_id'  => $auto->category_id,
                'start_percent' => $auto->power_percent,
                'start_kw'     => $auto->power_kw,
                'rentstatus'   => 3,
            ])->toArray();

            $folyamatbanLevok[] = $rental;

            ## Az autó státusza módosuljon "folyamatban" (3) -ra!
            $auto->status = 3;
            $auto->save();
        }

        if (!empty($folyamatbanLevok)) {
            DB::table('car_user_rents')->insert($folyamatbanLevok);
        }
    }
}
