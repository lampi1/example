<?php

namespace App\Daran\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CountryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'code' => 'required|value:2',
            'cost' => 'required|numeric'
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'title' => 'Titolo',
            'content' => 'Contenuto',
        ];
    }
}
