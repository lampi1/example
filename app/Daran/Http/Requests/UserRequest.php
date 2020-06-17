<?php

namespace App\Daran\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|max:255',
            'business' => 'required|max:255',
            'email' => 'required|email|max:255',
            'password' => 'nullable|confirmed|max:25',
            'active' => 'boolean',
            'locale' => 'required|max:2'
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'code' => 'Codice',
            'priority' => 'Ordinamento',
            'image' => 'Immagine',
        ];
    }
}
