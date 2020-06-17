<?php

namespace App\Daran\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RedirectionRequest extends FormRequest
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
            'name'              => 'required|max:255',
            'from_uri'          => 'required|max:255',
            'to_uri'            => 'required|max:255',
            'code'              => 'required|max:5',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'name'              => 'Nome',
            'from_url'          => 'Indirizzo di partenza',
            'to_url'            => 'Indirizzo di destinazione',
            'code'              => 'Codice Redirect'
        ];
    }
}
