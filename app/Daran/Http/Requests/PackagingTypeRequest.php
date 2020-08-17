<?php

namespace App\Daran\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PackagingTypeRequest extends FormRequest
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
            'name' => 'required|max:255',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'name' => 'Nome',
        ];
    }
}
