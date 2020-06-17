<?php

namespace App\Daran\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
            'admin_id'         => 'nullable|exists:admins,id',
            'locale'           => 'nullable',
            'locale_group'     => 'nullable',
            'title'            => 'required|max:255',
            'subtitle'         => 'required|max:255',
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
            'tags_string'      => 'nullable',
            'files'            => 'nullable',
            'tags'             => 'nullable',
            'video_mp4'        => 'nullable|file',
            'video_ogv'        => 'nullable|file',
            'video_webm'       => 'nullable|file',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'title'            => 'Titolo',
            'subtitle'            => 'Sotto Titolo',
            'abstract'         => 'Anteprima',
            'content'          => 'Contenuto',
            'image'            => 'Immagine'
        ];
    }
}
