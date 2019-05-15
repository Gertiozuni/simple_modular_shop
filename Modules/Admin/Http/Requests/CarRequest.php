<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'attributes.*'  => 'required',
//            'tags'          => 'required',
            'tags.*.text'   => 'required|regex:/(^[A-Za-z0-9 ]+$)+/',
            'description'   => 'required|min:5',
            'title'         => 'required|min:5|max:40'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function attributes()
    {
        return [
            'attributes.*' => 'attribute',
            'tags.*.text' => 'tag',
            'attributes.make' => 'make',
            'attributes.model' => 'model',
            'attributes.registration' => 'registration',
            'attributes.engine_size' => 'engine size',
            'attributes.price' => 'price'
        ];
    }
}
