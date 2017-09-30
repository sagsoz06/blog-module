<?php

namespace Modules\Blog\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;

class UpdatePostRequest extends BaseFormRequest
{
    protected $translationsAttributesKey = 'blog::post.form';

    public function rules()
    {
        return [
            'category_id' => 'required',
            "created_at"  => "required|date_format:d.m.Y H:i"
        ];
    }

    public function translationRules()
    {
        $id = $this->route()->parameter('blogPost')->id;

        return [
            "title" => "required",
            "slug" => "required|unique:blog__post_translations,slug,$id,post_id,locale,$this->localeKey"
        ];
    }

    public function attributes()
    {
        return trans('blog::post.form');
    }

    public function authorize()
    {
        return true;
    }

    public function translationMessages()
    {
        return [];
    }
}
