<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCarStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status_name' => ['required', Rule::in('Szabad', 'Foglalva', 'Bérlés alatt', 'Szervízre vár', 'Tisztításra vár', 'Kritikus töltés')],
            'status_descrip' => ['required', 'between:10,255'],
        ];
    }
}
