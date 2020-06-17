<?php

namespace App\Daran\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
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
            'category_id' => 'nullable|exists:categories,id',
            'family_id' => 'nullable|exists:families,id',
            'user_id' => 'nullable|exists:users,id',
            'brand_id' => 'nullable|exists:brands,id',
            'name' => 'required|max:255',
            'code' => 'nullable|max:255',
            'amount' => 'required_if:discount,0|numeric',
            'discount' => 'required_if:amount,0|numeric',
            'min' => 'nullable|numeric',
            'date_start' => 'required|date_format:d/m/Y',
            'date_end' => 'nullable|date_format:d/m/Y',

        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'discount' => 'Sconto %',
            'amount' => 'Sconto €',
            'min' => 'Importo Minimo',
            'data_start' => 'Data Inizio',
            'data_end' => 'Data Fine',
        ];
    }
}
