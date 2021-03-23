<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PostFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Auth::check()) return true;
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'content' => 'required',
            'images' => 'array',
            'images.*' => 'mimes:jpg,bmp,png,jpeg',
            'tags' => 'array',
            'tags.*' => 'numeric',
            'deleteImage' => 'array',
            'deleteImage.*' => 'numeric'
        ];
    }

    public function messages()
    {
        return parent::messages();
    }
}
