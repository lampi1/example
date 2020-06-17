<?php

namespace App\Daran\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FaqRequest extends FormRequest
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
            'faq_category_id' => 'required|exists:faq_categories,id',
            'user_id' => 'nullable|exists:admins,id',
            'locale' => 'nullable',
            'locale_group' => 'nullable',
            'title' => 'required|max:255',
            'abstract' => 'nullable|max:255',
            'content' => 'required',
            'state' => 'nullable',
            'slug' => 'nullable',
            'search' => 'nullable|boolean',
            'seo' => 'nullable|boolean',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable|max:255',
            'og_title' => 'nullable|max:255',
            'og_description' => 'nullable|max:255',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'title' => 'Titolo',
            'content' => 'Contenuto',
        ];
    }
}
