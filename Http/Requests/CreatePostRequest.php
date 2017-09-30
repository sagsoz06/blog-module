<?php

namespace Modules\Blog\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;

class CreatePostRequest extends BaseFormRequest
{
    protected $translationsAttributesKey = 'blog::post.form';

    public function rules()
    {
        return [
            'category_id' => 'required',
            "created_at"  => "required|date_format:d.m.Y H:i"
        ];
    }

    public function attributes()
    {
        return trans('blog::post.form');
    }

    public function translationRules()
    {
        return [
            'title' => 'required',
            'intro' => 'required',
            'slug'  => "required|unique:blog__post_translations,slug,null,post_id,locale,$this->localeKey",
        ];
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return trans('validation');
    }
}
