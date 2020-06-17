<?php

namespace App\Daran\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LandingPageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'landing_page_template_id' => 'required|exists:landing_page_templates,id',
            'user_id'          => 'nullable|exists:admins,id',
            'slider_id'        => 'nullable|exists:sliders,id',
            'form_id'          => 'nullable|exists:forms,id',
            'locale'           => 'nullable',
            'locale_group'     => 'nullable',
            'title'            => 'required|max:255',
            'abstract'         => 'required|max:255',
            'content'          => 'nullable',
            'state'            => 'nullable',
            'image'            => 'nullable|file|image',
            'image_sm'         => 'nullable|file|image',
            'slug'             => 'nullable',
            'search'           => 'nullable|boolean',
            'seo'              => 'nullable|boolean',
            'meta_title'       => 'nullable|max:255',
            'meta_description' => 'nullable|max:255',
            'og_title'         => 'nullable|max:255',
            'og_description'   => 'nullable|max:255',
            'published_at'     => 'nullable|date_format:d/m/Y',
            'ended_at'         => 'nullable|date_format:d/m/Y',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'landing_page_category_id' => 'Categoria Pagina',
            'landing_page_template_id' => 'Template Pagina',
            'slider_id'        => 'Slider',
            'form_id'          => 'Form',
            'title'            => 'Titolo',
            'abstract'         => 'Anteprima',
            'content'          => 'Contenuto',
            'image'            => 'Immagine'
        ];
    }
}
