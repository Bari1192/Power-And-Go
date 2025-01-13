<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            "elofiz_nev" => ["required", "string","between:5,50", "unique:subscriptions"],
            "havi_dij" => ["required", "integer", "min:0", "max:10000"],
            "eves_dij" => ["required", "integer", "min:0", "max:100000"],
        ];
    }
}
