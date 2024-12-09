<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFleetRequest extends FormRequest
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
            "gyarto" => ['required', 'string', 'between:2,40'],
            "tipus" => ['required', 'string', 'between:2,40'],
            "teljesitmeny" => ['required', 'integer', 'min:18', 'max:500'],
            "vegsebesseg" => ['required', 'integer', 'min:100', 'max:300'],
            "gumimeret" => ['required', 'string',],
            "hatotav" => ['required', 'integer', 'min:100', 'maximum:1000'],
        ];
    }
}
