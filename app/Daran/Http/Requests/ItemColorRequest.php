<?php

namespace App\Daran\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemColorRequest extends FormRequest
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
            'image' => 'nullable|file'
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
