<?php

namespace App\Http\Requests;

use App\Rules\EmployeeFieldSelect;
use App\Rules\EmployeeRoleField;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            "person_id" => ["required", "integer", "exists:persons,id"],
            "field" => ["required","string","max:128", new EmployeeFieldSelect],
            "role" => ["required","string","max:45", new EmployeeRoleField($this->input('field'))],
            "position" => ["required","string","max:45", Rule::in(["Munkatárs", "Supervisor", "Főosztályvezető", "Felsővezető",])],
            "salary_type" => ["required","string","string",Rule::in(["fix","hourly"])],
            "salary" => ["integer","min:0", "max:1000000"],
            "hire_date" => ["date_format:Y-m-d","after_or_equal:today"],
        ];
    }
}
