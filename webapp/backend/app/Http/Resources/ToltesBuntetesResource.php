<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ToltesBuntetesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'person_id' => $this->person_id,
            'user_id' => $this->user_id,
            'invoice_status' => $this->invoice_status,
            'bill_type' => "Minimum töltési ÁSZF megsértése.",
            'total_cost' => number_format($this->total_cost, 0, '', ' '), 
            'invoice_date' => date_format($this->invoice_date, "Y-m-d H:i:s"),
        ];
    }
}
