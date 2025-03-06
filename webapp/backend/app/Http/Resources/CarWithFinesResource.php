<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarWithFinesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $bill = $this->bills->first();

        if (!$bill) {
            return [
                'error' => 'Fine has not found for this car',
            ];
        }
        return [
            'id' => $this->bills?->pluck('id')->first(),
            'car_id' => $this->id,
            'person' => $this->bills->first()->users->person->firstname . ' ' . $this->bills->first()->users->person->lastname,
            'email' => $this->bills->first()->users->person->email,
            'email_sent' => $this->bills->pluck('email_sent')->first() ? 'yes' : 'no',
            'fine_types' => $this->bills->pluck('bill_type')->first(),
            'user_id' => $this->bills->pluck('user_id')->first(),
            'end_percent' => $this->power_percent,
            'total_cost' => $this->bills->pluck('total_cost')->first(),
            'rent_start' => $this->bills->pluck('rent_start')->first(),
            'rent_close' => $this->bills->pluck('rent_close')->first(),
            'invoice_date' => $this->bills->pluck('invoice_date')->first(),
        ];
    }
}
