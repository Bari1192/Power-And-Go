<?php

namespace App\Policies;

use App\Mail\RentalSummaryMail;
use App\Models\Bill;
use App\Models\Car;
use App\Models\User;
use App\Observers\BillObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use stdClass;

class BillService
{
    private CarRefreshService $carRefreshService;

    public function __construct(CarRefreshService $carRefreshService)
    {
        $this->carRefreshService = $carRefreshService;
    }

    public static array $chargingFines = [
        1 => ['buntetes' => 30_000],
        2 => ['buntetes' => 50_000],
        3 => ['buntetes' => 30_000],
        4 => ['buntetes' => 50_000],
        5 => ['buntetes' => 50_000],
    ];

    /**
     * Bérlés számla létrehozása
     */
    public function createRentBill(Car $car, User $user, $rental): ?Bill
    {
        $bill = null;

        DB::transaction(function () use ($car, $user, $rental, &$bill) {
            $charges = DB::table('car_user_rent_charges')
                ->where('rent_id', $rental->id)
                ->selectRaw('SUM(credits) as credits, SUM(charged_kw) as charged_kw')
                ->first();

            $bill = Bill::create([
                'rent_id' => $rental->id,
                'bill_type' => 'rental',
                'user_id' => $user->id,
                'person_id' => $user->person_id,
                'car_id' => $car->id,
                'rent_start' => Carbon::parse($rental->rent_start)->format('Y-m-d H:i:s'),
                'rent_close' => Carbon::parse($rental->rent_close)->format('Y-m-d H:i:s'),
                'distance' => $rental->distance ?? 0,
                'parking_minutes' => $rental->parking_minutes ?? 0,
                'driving_minutes' => $rental->driving_minutes ?? 0,
                'total_cost' => $rental->rental_cost ?? 0,
                'credits' => $charges->credits ?? 0,
                'charged_kw' => $charges->charged_kw ?? 0,
                'invoice_date' => Carbon::now()->format('Y-m-d H:i:s'),
                'invoice_status' => 'pending'
            ]);
        });
        return $bill;
    }

    public function createChargingFine(Car $car, User $user, $rental)
    {
        $minCharge = $this->carRefreshService->chargingCategories[$car->category_id]['min_toltes'] ?? 0;
        if ($car->status !== 7 || !($car->power_percent < $minCharge)) {
            return [];
        }
        DB::transaction(function () use ($car, $user, $rental, &$bill) {
            $bill = Bill::create([
                'bill_type' => 'charging_penalty',
                'user_id' => $user->id,
                'person_id' => $user->person_id,
                'car_id' => $car->id,
                'rent_id' => $rental->id,
                'rent_start' => Carbon::parse($rental->rent_start)->format('Y-m-d H:i:s'),
                'rent_close' => Carbon::parse($rental->rent_close)->format('Y-m-d H:i:s'),
                'total_cost' => self::$chargingFines[$car->category_id]['buntetes'] ?? 30000,
                'invoice_date' => Carbon::now()->format('Y-m-d H:i:s'),
                'invoice_status' => 'pending'
            ]);
        });
        return $bill;
    }

    public function createBulkBills(SupportCollection $rentals, Collection $cars, Collection $users): array
    {
        $rentalBills = [];
        $penaltyBills = [];

        foreach ($rentals as $rental) {
            if (!isset($users[$rental->user_id]) || !isset($cars[$rental->car_id])) {
                continue;
            }
            $user = $users[$rental->user_id];
            $car = $cars[$rental->car_id];
            $charges = $this->getCharges($rental->id);

            ## Sima bérlések zárása
            $rentalBills[] = $this->prepareBillData($rental, $user, $car, $charges);

            ## Büntetések számla
            $penaltyBill = $this->preparePenaltyBillData($rental, $user, $car);
            if ($penaltyBill) {
                $penaltyBills[] = $penaltyBill;
            }
        }

        return [
            'rentalBills' => $rentalBills,
            'penaltyBills' => $penaltyBills
        ];
    }
    private function getCharges(int $rentId): stdClass
    {
        return DB::table('car_user_rent_charges')
            ->where('rent_id', $rentId)
            ->selectRaw('COALESCE(SUM(credits), 0) as credits, COALESCE(SUM(charged_kw), 0) as charged_kw')
            ->first() ?? (object)['credits' => 0, 'charged_kw' => 0];
    }
    public function prepareBillData($rental, User $user, Car $car, stdClass $charges): array
    {
        return [
            'rent_id' => $rental->id,
            'bill_type' => 'rental',
            'user_id' => $user->id,
            'person_id' => $user->person_id,
            'car_id' => $car->id,
            'rent_start' => Carbon::parse($rental->rent_start)->format('Y-m-d H:i:s'),
            'rent_close' => Carbon::parse($rental->rent_close)->format('Y-m-d H:i:s'),
            'distance' => $rental->distance ?? 0,
            'parking_minutes' => $rental->parking_minutes ?? 0,
            'driving_minutes' => $rental->driving_minutes ?? 0,
            'total_cost' => $rental->rental_cost ?? 0,
            'credits' => $charges->credits ?? 0,
            'charged_kw' => $charges->charged_kw ?? 0,
            'invoice_date' => Carbon::now()->format('Y-m-d H:i:s'),
            'invoice_status' => 'pending'
        ];
    }
    private function preparePenaltyBillData($rental, User $user, Car $car)
    {
        $minCharge = $this->carRefreshService->chargingCategories[$car->category_id]['min_toltes'];
        if (!($car->status == 7 && $car->power_percent < $minCharge)) {
            return null;
        } else {

            $billData = [
                'bill_type' => 'charging_penalty',
                'user_id' => $user->id,
                'person_id' => $user->person_id,
                'car_id' => $car->id,
                'rent_id' => $rental->id,
                'rent_start' => Carbon::parse($rental->rent_start)->format('Y-m-d H:i:s'),
                'rent_close' => Carbon::parse($rental->rent_close)->format('Y-m-d H:i:s'),
                'total_cost' => self::$chargingFines[$car->category_id]['buntetes'] ?? 30000,
                'invoice_date' => Carbon::now()->format('Y-m-d H:i:s'),
                'invoice_status' => 'pending',
            ];
            return $billData;
        }
    }
}
