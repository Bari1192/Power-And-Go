<?php

namespace App\Http\Requests;

use App\Rules\TenYearsDifferenceInDrivingLicence;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePersonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            "person_password" => ["required", "string"],
            "id_card" => [
                "required",
                "string",
                "min:8",
                "max:12",
                "unique:persons,id_card," . $this->route('person'),
            ],
            "driving_license" => ["string", "size:8", "exists:persons,driving_license", "required_with:license_start_date"],
            "firstname" => ["required", "string", "min:3", "max:50"],
            "lastname" => ["required", "string", "min:3", "max:25"],
            "phone" => [
                "required",
                "string",
                "starts_with:+36,0036",
                "min:12",
                "max:15",
                "unique:persons,phone," . $this->route('person'),
            ],
            "email" => [
                "required",
                "string",
                "min:21",
                "max:80",
                "unique:persons,email," . $this->route('person'),
                "regex:/^[a-zA-Z0-9._%+-]+@(gmail\.com|yahoo\.com|outlook\.com)$/"
            ],
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
}
