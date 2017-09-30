<?php

namespace Modules\Blog\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;

class StoreCategoryRequest extends BaseFormRequest
{
    protected $translationsAttributesKey = 'blog::category.form';
    public function translationRules()
    {
        return [
            'name' => 'required',
            'slug' => "required|unique:blog__category_translations,slug,null,category_id,locale,$this->localeKey",
        ];
    }

    public function rules()
    {
        return [
            'ordering'=>'required'
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
