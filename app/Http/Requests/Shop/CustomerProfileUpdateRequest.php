<?php

namespace App\Http\Requests\Shop;

use App\Rules\Nationalcode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerProfileUpdateRequest extends FormRequest
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
        $id = auth()->guard('customer')->user()->id;

        return [
            'first_name'            => 'required',
            'last_name'             => 'required',
            'gender'                => 'required|in:Other,Male,Female',
            'date_of_birth'         => 'date|before:today',
            'email'                 => 'email|unique:customers,email,'.$id,
            'password'              => 'confirmed|min:6|required_with:oldpassword',
            'oldpassword'           => 'present',
            'password_confirmation' => 'required_with:password',
            'image.*'               => 'mimes:bmp,jpeg,jpg,png,webp',
            'national_code'         => ['filled',
                                        Rule::when(function ($input) {
                                            return !isset($input->is_foreign);
                                        },new Nationalcode)],
            'father_name'           => 'nullable',
            'education_field'       => 'nullable',
        ];
    }
}
