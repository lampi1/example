<?php

namespace App\Daran\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EcommerceRequest extends FormRequest
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
            'shipping_free_from' => 'required|numeric',
            'valid_from'     => 'required|date_format:d/m/Y',
            'valid_until'         => 'nullable|date_format:d/m/Y',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'shipping_free_from' => 'Minimo Gratuità',
            'valid_from' => 'Valido Dal',
            'valid_until' => 'Valido Al',
        ];
    }
}
