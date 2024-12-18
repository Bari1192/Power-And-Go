<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFleetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return
            [
                'id' => ["required"],
                'gyarto' => ["required"],
                'tipus' => ["required"],
                'teljesitmeny' => ["required"],
                'vegsebesseg' => ["required"],
                'gumimeret' => ["required"],
                'hatotav' => ["required"],
            ];
    }
}
