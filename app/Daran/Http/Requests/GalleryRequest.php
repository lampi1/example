<?php

namespace App\Daran\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GalleryRequest extends FormRequest
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
            'user_id'          => 'nullable|exists:admins,id',
            'gallery_category_id' => 'required|exists:gallery_categories,id',
            'locale'           => 'nullable',
            'locale_group'     => 'nullable',
            'title'            => 'required|max:255',
            'abstract'         => 'required|max:255',
            'content'          => 'nullable',
            'state'            => 'nullable',
            'image'            => 'nullable|file|image',
            'image_sm'          => 'nullable|file|image',
            'slug'             => 'nullable',
            'search'           => 'nullable|boolean',
            'seo'              => 'nullable|boolean',
            'meta_title'       => 'nullable|max:255',
            'meta_description' => 'nullable|max:255',
            'og_title'         => 'nullable|max:255',
            'og_description'   => 'nullable|max:255',
            'published_at'     => 'nullable|date_format:d/m/Y',
            'tags_string'      => 'nullable',
            'tags'             => 'nullable',
            'year'             => 'nullable|max:10',
            'owner'            => 'nullable|max:255',
            'place'            => 'nullable|max:255',
            'type'             => 'nullable|max:255',
            'date'             => 'nullable|max:25',
            'equipment'        => 'nullable',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'title'            => 'Titolo',
            'abstract'         => 'Anteprima',
            'content'          => 'Contenuto',
            'image'            => 'Immagine',
            'year'             => 'Anno',
            'owner'            => 'Committente',
            'place'            => 'Luogo',
            'type'             => 'Tipologia',
            'date'             => 'Data',
            'equipment'        => 'Attrezzature',
        ];
    }
}
