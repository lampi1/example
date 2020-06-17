<?php

namespace App\Daran\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
            'event_category_id' => 'required|exists:event_categories,id',
            'user_id'           => 'nullable|exists:admins,id',
            'locale'            => 'nullable',
            'locale_group'      => 'nullable',
            'title'             => 'required|max:255',
            'abstract'          => 'required|max:255',
            'content'           => 'nullable',
            'state'             => 'nullable',
            'image'             => 'nullable|file|image',
            'image_sm'          => 'nullable|file|image',
            'slug'              => 'nullable',
            'search'            => 'nullable|boolean',
            'seo'               => 'nullable|boolean',
            'meta_title'        => 'nullable|max:255',
            'meta_description'  => 'nullable|max:255',
            'og_title'          => 'nullable|max:255',
            'og_description'    => 'nullable|max:255',
            'date_start'        => 'required|date_format:d/m/Y H:i',
            'date_end'          => 'required|date_format:d/m/Y H:i|after:date_start',
            'attachment_title'  => 'nullable',
            'attachment_file'   => 'nullable',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'event_category_id' => 'Categoria Evento',
            'province_id'       => 'Provincia',
            'title'             => 'Titolo',
            'location'          => 'Luogo',
            'abstract'          => 'Anteprima',
            'content'           => 'Contenuto',
            'image'             => 'Immagine',
            'date_start'        => 'Data Pubblicazione',
            'date_end'          => 'Data Scadenza'
        ];
    }
}
