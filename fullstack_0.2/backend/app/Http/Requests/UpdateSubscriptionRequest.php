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
            "elofiz_nev" => ["required", "string", "exists:subscriptions,elofiz_nev"],
            "havi_dij" => ["nullable", "integer", "min:0", "maximum:10000"],
            "eves_dij" => ["nullable", "integer", "min:0", "maximum:10000"],
        ];
    }
}
