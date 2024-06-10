<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    protected function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules(): array
    {
        $request = $this->request;
        $url = $this->route()[1]['as'];

        if($url == 'category.store'){
            return [
                'title'      => 'required|string|max:55|unique:categories,title',
                'meta_title' => 'string|max:55',
                'slug'       => 'required|string|max:55|unique:categories,slug',
                'parent_id' => 'integer|nullable'
            ];
        } else {
            return [
                'title'      => 'required|string|max:55',
                'meta_title' => 'string|max:55',
                'slug'       => 'required|string|max:55',
                'parent_id' => 'integer|nullable'
            ];
        }
    }
}
