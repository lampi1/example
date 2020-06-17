<?php

namespace App\Daran\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'family_id' => 'required|exists:families,id',
            'category_id' => 'nullable|exists:categories,id',
            'type_id' => 'nullable|exists:types,id',
            'code' => 'nullable|max:15',
            'last' => 'nullable|max:25',
            'name' => 'required|max:255',
            'riporto' => 'nullable|max:50',
            'sole_last' => 'nullable|max:15',
            'sole_height' => 'nullable|max:15',
            'rotella' => 'nullable|max:15',
            'priority' => 'nullable|numeric',
            'published' => 'nullable|boolean',
            'featured' => 'nullable|boolean',
            'price' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
            'stock' => 'nullable|numeric',
            'minimun' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
        ];

        $langs = config('app.available_translations');
        foreach ($langs as $lang) {
            $rules['description_'.$lang] = 'nullable';
            $rules['meta_title_'.$lang] = 'nullable|max:255';
            $rules['meta_description_'.$lang] = 'nullable|max:255';
            $rules['og_title_'.$lang] = 'nullable|max:255';
            $rules['og_description_'.$lang] = 'nullable|max:255';
            $rules['material_'.$lang] = 'nullable|max:255';
            $rules['material_internal_'.$lang] = 'nullable|max:255';
            $rules['sole_'.$lang] = 'nullable|max:255';
            $rules['color_'.$lang] = 'nullable|max:255';
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
            'family_id' => 'Famiglia',
            'discount' => 'Sconto',
        ];
    }
}
