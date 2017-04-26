<?php

namespace Modules\Blog\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;

class UpdatePostRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            "created_at" => "required|date_format:d.m.Y H:i"
        ];
    }

    public function translationRules()
    {
        $id = $this->route()->parameter('post')->id;

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
        return [
            'title.required' => trans('blog::messages.title is required'),
            'slug.required' => trans('blog::messages.slug is required'),
            'slug.unique' => trans('blog::messages.slug is unique'),
        ];
    }
}
