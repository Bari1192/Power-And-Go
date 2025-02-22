<?php

namespace App\Mail;

use App\Models\Bill;
use App\Providers\TimeFormatProvider;
use Carbon\Carbon;
use Illuminate\Mail\Mailable;

class RentalSummaryMail extends Mailable
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

    public function __construct(Bill $bill)
    {
        $this->bill = $bill;

        $getMinutes = TimeFormatProvider::calculateTimes(
            Carbon::parse($bill->rent_start),
            Carbon::parse($bill->rent_close)
        )['minutes'];
        $this->lastname = $bill->persons->lastname;
        $this->carPlate = $bill->cars->plate;
        $this->rentStart = $bill->rent_start;
        $this->rentClose = $bill->rent_close;
        $this->totalMinutes = $getMinutes;
        $this->driving = $bill->driving_minutes;
        $this->distance = $bill->distance;
        $this->parking = $bill->parking_minutes;
        $this->usedCredits = $bill->credits;
        $this->charge = $bill->charged_kw;
        $this->credits = $bill->credits;
        $this->cost = $bill->total_cost;
    }

    public function build()
    {
        $htmlContent = view('emails.rental-summary', [
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
        ])->render();

        return $this->html((string) $htmlContent);
    }
}
