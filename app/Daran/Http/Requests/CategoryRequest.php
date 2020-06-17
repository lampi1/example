<?php

namespace App\Daran\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'code' => 'nullable|max:15',
            'image' => 'nullable|file|image',
            'image_sm' => 'nullable|file|image',
            'priority' => 'nullable|numeric',
            'family_id' => 'required|exists:families,id'
        ];

        $langs = config('app.available_translations');
        foreach ($langs as $lang) {
            $rules['name_'.$lang] = 'required|max:255';
            $rules['description_'.$lang] = 'nullable|max:255';
            $rules['meta_title_'.$lang] = 'nullable|max:255';
            $rules['meta_description_'.$lang] = 'nullable|max:255';
            $rules['og_title_'.$lang] = 'nullable|max:255';
            $rules['og_description_'.$lang] = 'nullable|max:255';
            $rules['slug_'.$lang] = 'nullable|max:255';
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'code' => 'Codice',
            'priority' => 'Ordinamento',
            'image' => 'Immagine',
            'family_id' => 'Famiglia'
        ];
    }
}
