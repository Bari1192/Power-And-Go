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
            "sub_name" => ["required", "string","between:5,50", "unique:subscriptions"],
            "sub_monthly" => ["required", "integer", "min:0", "max:10000"],
            "sub_annual" => ["required", "integer", "min:0", "max:100000"],
        ];
    }
}
