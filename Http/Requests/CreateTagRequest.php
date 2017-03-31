<?php

namespace Modules\Blog\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;

class CreateTagRequest extends BaseFormRequest
{
    protected $translationsAttributesKey = 'blog::tag.form';
    public function translationRules()
    {
        return [
            'name' => 'required|max:100',
            'slug' => 'required|max:100'
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

    public function messages()
    {
        return trans('validation');
    }

    public function translationMessages()
    {
        return [];
    }
}
