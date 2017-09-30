<?php

namespace Modules\Blog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    protected $translationsAttributesKey = 'blog::category.form';
    public function translationRules()
    {
        $id = $this->route()->parameter('blogCategory')->id;

        return [
            'name' => 'required',
            'slug' => "required|unique:blog__category_translations,slug,$id,category_id,locale,$this->localeKey",
        ];
    }

    public function rules()
    {
        return [
            'ordering' => 'required|integer'
        ];
    }

    public function attributes()
    {
        return trans('blog::category.form');
    }

    public function authorize()
    {
        return true;
    }

    public function translationMessages()
    {
        return trans('validation');
    }

    public function messages()
    {
        return [];
    }
}
