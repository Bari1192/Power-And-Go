<?php

namespace App\Http\Requests;

use App\Rules\TenYearsDifferenceInDrivingLicence;
use Illuminate\Foundation\Http\FormRequest;

class StorePersonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            "person_password" => ["required", "string", "min:8"],
            "id_card" => ["required", "string", "min:8", "max:12", "unique:persons,id_card"],
            "driving_license" => ["nullable", "string", "size:8", "unique:persons,driving_license","required_with:license_start_date"],
            "firstname" => ["required", "string", "max:50"],
            "lastname" => ["required", "string", "max:25"],
            "phone" => ["required", "string", "starts_with:+36,0036", "min:12", "max:15", "unique:persons,phone"],
            "email" => ["required", "string", "email", "max:80", "unique:persons,email"],
            "birth_date" => [
                "required",
                "date",
                "before_or_equal:" . now()->subYears(18)->format('Y-m-d'),
            ],
            "license_start_date" => ["nullable", "date", "required_with:license_end_date"],
            "license_end_date" => [
                "nullable",
                "date",
                "required_with:license_start_date",
                new TenYearsDifferenceInDrivingLicence($this->input('license_start_date')),
            ],
        ];
    }
    public function messages(): array
    {
        return [
            "birth_date.before_or_equal" => "A születési dátumnak legalább 18 évvel korábbinak kell lennie.",
            "license_start_date.required_with" => "A kezdési dátum megadása kötelező, ha az érvényességi dátum meg van adva.",
            "license_end_date.required_with" => "Az érvényességi dátum megadása kötelező, ha a kezdési dátum meg van adva.",
        ];
    }
}
