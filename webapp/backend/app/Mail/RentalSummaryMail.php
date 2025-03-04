<?php

namespace App\Mail;

use App\Models\Bill;
use App\Providers\TimeFormatProvider;
use Carbon\Carbon;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;

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

    public function __construct($bill)
    {
        $this->bill = $bill;

        try {
            $rentStart = is_object($bill) && method_exists($bill, 'getAttribute')
                ? $bill->getAttribute('rent_start')
                : ($bill->rent_start ?? null);

            $rentClose = is_object($bill) && method_exists($bill, 'getAttribute')
                ? $bill->getAttribute('rent_close')
                : ($bill->rent_close ?? null);

            if ($rentStart && $rentClose) {
                $getMinutes = TimeFormatProvider::calculateTimes(
                    Carbon::parse($rentStart),
                    Carbon::parse($rentClose)
                )['minutes'];
                $this->totalMinutes = $getMinutes;
            } else {
                $this->totalMinutes = 0;
            }

            // Handle different types of input
            if ($bill instanceof Bill) {
                // Handle proper Bill model instance
                $bill->load(['users', 'persons', 'cars']);
                $this->lastname = $bill->users->person->lastname;
                $this->carPlate = $bill->cars->plate;
                $this->rentStart = $bill->rent_start;
                $this->rentClose = $bill->rent_close;
                $this->totalMinutes = $bill->driving_minutes + $bill->parking_minutes;
                $this->driving = $bill->driving_minutes;
                $this->distance = $bill->distance;
                $this->parking = $bill->parking_minutes;
                $this->usedCredits = $bill->credits;
                $this->charge = $bill->charged_kw;
                $this->credits = $bill->credits;
                $this->cost = $bill->total_cost;
            }
            $this->lastname = $bill->users->person->lastname;
            $this->carPlate = $bill->cars->plate;
            $this->rentStart = $bill->rent_start;
            $this->rentClose = $bill->rent_close;
            $this->totalMinutes = $bill->driving_minutes + $bill->parking_minutes;
            $this->driving = $bill->driving_minutes;
            $this->distance = $bill->distance;
            $this->parking = $bill->parking_minutes;
            $this->usedCredits = $bill->credits;
            $this->charge = $bill->charged_kw;
            $this->credits = $bill->credits;
            $this->cost = $bill->total_cost;
        
        } catch (\Exception $e) {
            Log::error('Error preparing RentalSummaryMail: ' . $e->getMessage(), [
                'bill' => $bill,
                'trace' => $e->getTraceAsString()
            ]);
        }
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

        return $this->subject('Bérlés összefoglaló')
            ->html((string) $htmlContent);
    }
}
