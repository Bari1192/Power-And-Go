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
            "field" => ["required", new EmployeeFieldSelect],
            "role" => ["required", new EmployeeRoleField($this->input('field'))],
            "position" => ["required", Rule::in(["Munkatárs", "Supervisor", "Főosztályvezető", "Felsővezető",])],
            "salary_type" => ["required","string",Rule::in(["fix","hourly"])],
            "salary" => ["integer","min:0", "max:1000000"],
            "start_date" => ["after_or_equal:today"],
        ];
    }
}
