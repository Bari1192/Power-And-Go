<?php


namespace Database\Seeders;

use App\Models\Car;
use App\Models\Renthistory;
use App\Models\Rentinprocess;
use App\Policies\CarRefreshService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarUserRentsSeeder extends Seeder
{
    private CarRefreshService $carRefreshService;

    public function __construct()
    {
        $this->carRefreshService = new CarRefreshService();
    }
    private function saveParking(int $rentId, array $parkolas): void
    {
        DB::table('car_user_rent_parkings')->insert([
            'rent_id'         => $rentId,
            'parking_start'   => $parkolas['kezd'],
            'parking_end'     => $parkolas['veg'],
            'parking_minutes' => $parkolas['parking_minutes'],
            'parking_cost'    => $parkolas['total_cost'],
        ]);
    }
    public function run(): void
    {
        try {
            DB::beginTransaction();
            foreach (range(1, 1000) as $i) {
                $rentHistory = Renthistory::factory()->make();
                $rentData = $rentHistory->toArray();

                $parkolasok     = $rentData['parkolasok']     ?? [];
                $parkingRecords = $rentData['parkingRecords'] ?? [];
                $chargeData     = $rentData['chargeData']     ?? [];

                unset($rentData['parkolasok'], $rentData['parkingRecords'], $rentData['chargeData']);

                $rentId = DB::table('car_user_rents')->insertGetId($rentData);

                if (!empty($parkingRecords)) {
                    foreach ($parkingRecords as $parkolas) {
                        $this->saveParking($rentId, $parkolas);
                    }
                }

                if (!empty($parkolasok)) {
                    foreach ($parkolasok as $parkolas) {
                        $this->saveParking($rentId, $parkolas);
                    }
                }

                ## Ha van töltési esemény (charging), azt megy a "car_user_rent_charges" táblába
                if (!empty($chargeData)) {
                    DB::table('car_user_rent_charges')->insert([
                        'rent_id'             => $rentId,
                        'charging_start_date' => $chargeData['charging_start_date'],
                        'charging_end_date'   => $chargeData['charging_end_date'],
                        'charging_time'       => $chargeData['charging_time'],
                        'start_percent'       => $chargeData['start_percent'],
                        'end_percent'         => $chargeData['end_percent'],
                        'start_kw'            => $chargeData['start_kw'],
                        'end_kw'              => $chargeData['end_kw'],
                        'charged_kw'         => $chargeData['charged_kw'],
                        'credits'             => $chargeData['credits'],
                    ]);
                }
            }

            ## Létrehozni 50 "folyamatban lévő" bérlést pluszban
            $folyamatbanLevok = [];
            for ($i = 0; $i < 50; $i++) {
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

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
