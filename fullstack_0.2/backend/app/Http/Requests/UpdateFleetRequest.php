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
                'flotta_id' => $this->flotta_id,
                'gyarto' => $this->gyarto,
                'tipus' => $this->tipus,
                'teljesitmeny' => $this->teljesitmeny,
                'vegsebesseg' => $this->vegsebesseg,
                'gumimeret' => $this->gumimeret,
                'hatotav' => $this->hatotav,
            ];
    }
}
