<?php

namespace App\Daran\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
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
            'service_category_id' => 'required|exists:service_categories,id',
            'admin_id'          => 'nullable|exists:admins,id',
            'slider_id'        => 'nullable|exists:sliders,id',
            'locale'           => 'nullable',
            'locale_group'     => 'nullable',
            'title'            => 'required|max:255',
            'abstract'         => 'required|max:255',
            'content'          => 'required',
            'state'            => 'nullable',
            'image'            => 'nullable|file|image',
            'image_sm'         => 'nullable|file|image',
            'slug'             => 'nullable',
            'search'           => 'nullable|numeric',
            'seo'              => 'nullable|numeric',
            'home'             => 'nullable|numeric',
            'meta_title'       => 'nullable|max:255',
            'meta_description' => 'nullable|max:255',
            'og_title'         => 'nullable|max:255',
            'og_description'   => 'nullable|max:255',
            'published_at'     => 'nullable|date_format:d/m/Y',
            'ended_at'         => 'nullable|date_format:d/m/Y',
            'tags_string'      => 'nullable',
            'files'            => 'nullable',
            'tags'             => 'nullable',
            'attachment_title' => 'nullable',
            'attachment_file'  => 'nullable',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'service_category_id' => 'Categoria',
            'title'            => 'Titolo',
            'abstract'         => 'Anteprima',
            'content'          => 'Contenuto',
            'image'            => 'Immagine'
        ];
    }
}
