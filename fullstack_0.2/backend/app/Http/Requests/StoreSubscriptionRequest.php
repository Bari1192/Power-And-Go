<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            "elofiz_nev" => ["required","string","between:2,30"],
            "havi_dij" => ["required","integer","min:0","maximum:10000"],
            "eves_dij" => ["required","integer","min:0","maximum:10000"],

        ];
    }
}
