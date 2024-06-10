<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;

class PostRequest extends FormRequest
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
        $request = $request->all();
        $url = $this->route()[1]['as'];

        if($url == 'post.store'){
            return [
                'title'       => 'required|string|max:55|unique:posts,title',
                'category_id' => 'required|integer',
                'meta_title'  => 'string|max:55',
                'slug'        => 'required|string|max:55|unique:posts,slug',
                'summary'      => 'string|max:255',
                'content'     => 'required|string',
                'image'       => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ];
        } else {
            return [
                'title'       => 'string|max:55',
                'category_id' => 'integer',
                'meta_title'  => 'string|max:55',
                'slug'        => 'string|max:55',
                'summary'      => 'string|max:255',
                'content'     => 'string',
            ];
        }
    }
}
