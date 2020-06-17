<?php

namespace App\Daran\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
            'post_category_id' => 'required|exists:post_categories,id',
            'user_id'          => 'nullable|exists:admins,id',
            'locale'           => 'nullable',
            'locale_group'     => 'nullable',
            'title'            => 'required|max:255',
            'abstract'         => 'required|max:255',
            'content'          => 'required',
            'state'            => 'nullable',
            'image'            => 'nullable|file|image',
            'image_sm'          => 'nullable|file|image',
            'slug'             => 'nullable',
            'search'           => 'nullable|boolean',
            'seo'              => 'nullable|boolean',
            'featured'         => 'nullable|boolean',
            'meta_title'       => 'nullable|max:255',
            'meta_description' => 'nullable|max:255',
            'og_title'         => 'nullable|max:255',
            'og_description'   => 'nullable|max:255',
            'published_at'     => 'nullable|date_format:d/m/Y',
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
            'post_category_id' => 'Categoria Articolo',
            'page_template_id' => 'Template Pagina',
            'slider_id'        => 'Slider',
            'title'            => 'Titolo',
            'abstract'         => 'Anteprima',
            'content'          => 'Contenuto',
            'image'            => 'Immagine',
            'author'           => 'Titolo',
            'author_abstract'  => 'Anteprima',
        ];
    }
}
