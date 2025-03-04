<?php

namespace App\Mail;

use App\Models\Bill;
use App\Models\Car;
use App\Models\Person;
use App\Models\User;
use App\Policies\CarRefreshService;
use App\Providers\TimeFormatProvider;
use Carbon\Carbon;
use Illuminate\Mail\Mailable;

class ChargeFineMail extends Mailable
{
    public $bill;
    public $lastname;
    public $carPlate;
    public $rentStart;
    public $rentClose;
    public $driving;
    public $distance;
    public $parking;
    public $usedCredits;
    public $charge;
    public $credits;
    public $cost;
    public $totalMinutes;
    public $carRefreshService;
    public $mincharge;

    public function __construct(Bill $bill, User $user, Car $car, CarRefreshService $carRefreshService)
    {
        $this->bill = $bill;
        $this->bill->users = $user;
        $this->bill->cars = $car;

        $this->carRefreshService = $carRefreshService;

        $getMinutes = TimeFormatProvider::calculateTimes(
            Carbon::parse($bill->rent_start),
            Carbon::parse($bill->rent_close)
        )['minutes'];

        $this->lastname = $user->person->lastname;
        $this->carPlate = $car->plate;

        $this->rentStart = $bill->rent_start;
        $this->rentClose = $bill->rent_close;
        $this->totalMinutes = $getMinutes;
        $this->driving = $bill->driving_minutes;
        $this->distance = $bill->distance;
        $this->parking = $bill->parking_minutes;
        $this->usedCredits = 0;
        $this->charge = $car->power_percent;
        $this->credits = $bill->credits;
        $this->cost = $bill->total_cost;
    }

    public function build()
    {
        if (!isset($this->carRefreshService)) {
            $this->carRefreshService = new CarRefreshService();
        }

        $minCharge = 0;
        if (
            isset($this->bill->cars->category_id) &&
            isset($this->carRefreshService->chargingCategories[$this->bill->cars->category_id]['min_toltes'])
        ) {
            $minCharge = $this->carRefreshService->chargingCategories[$this->bill->cars->category_id]['min_toltes'];
        }
        ##Trélerezés
        $shippingCost = 15000;  
        ## Kiszállási díj
        $dispatchCost = 10000;  
        ## Admin. díj
        $adminCost = 5000;      

        $htmlContent = view('emails.charge-fine', [
            'lastname' => $this->lastname,
            'carPlate' => $this->carPlate,
            'rentStart' => $this->rentStart,
            'rentClose' => $this->rentClose,
            'totalMinutes' => $this->totalMinutes,
            'driving' => $this->driving,
            'distance' => $this->distance,
            'parking' => $this->parking,
            'usedCredits' => $this->usedCredits,
            'charge' => $this->charge,
            'credits' => $this->credits,
            'cost' => $this->cost,
            'shippingCost' => $shippingCost,
            'dispatchCost' => $dispatchCost,
            'adminCost' => $adminCost,
            'minCharge' => $minCharge
        ])->render();

        return $this->subject('Töltési Büntetés!')
            ->html((string) $htmlContent);
    }
}
