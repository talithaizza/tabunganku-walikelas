<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|unique:books,title',
            'author_id' => 'required|exists:authors,id',
            'amount' => 'numeric',
            'cover' => 'image|max:1024'
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
            'title.required' => 'Judul buku masih kosong',
            'title.unique'  => 'Buku sudah ada',
            'author_id.required' => 'Penulis masih kosong',
            'author_id.exists' => 'Penulis tidak ada',
            'amount.numeric' => 'Jumlah buku harus angka',
            'cover.image' => 'Cover buku harus format gambar',
            'cover.max' => 'Size cover buku terlalu besar'
        ];
    }
}
