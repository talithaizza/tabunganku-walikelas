<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
        ];
    }

    /**
    * Get the error messages for the defined validation rules.
    *
    * @return array
    */
    public function messages()
    {
        return [
            'name.required' => 'Nama member masih kosong',
            'name.max' => 'Nama member tidak boleh lebih 255 huruf',
            'email.required' => 'Email member masih kosong',
            'email.email' => 'Email member tidak valid',
            'email.max' => 'Email member maksimal 255 huruf',
            'email.unique' => 'Email member sudah terdaftar'

        ];
    }
}
