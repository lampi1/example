<?php

namespace App\Daran\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SlideRequest extends FormRequest
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
            'slider_id'        => 'required|exists:sliders,id',
            'title'            => 'required|max:255',
            'subtitle'         => 'nullable|max:255',
            'caption'          => 'nullable|max:255',
            'link'             => 'required_if:type,==,video|max:255',
            'image'            => 'nullable|file|image',
            'image_sm'          => 'nullable|file|image',
            
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'title'            => 'Titolo',
            'abstract'         => 'Anteprima',
            'content'          => 'Contenuto',
            'image'            => 'Immagine'
        ];
    }
}
