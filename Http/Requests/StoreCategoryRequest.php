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
            'slug' => 'required',
        ];
    }

    public function rules()
    {
        return [];
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
