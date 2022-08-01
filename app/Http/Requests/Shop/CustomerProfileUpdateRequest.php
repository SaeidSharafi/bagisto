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
            'national_code'         => ['filled','unique:customers,national_code',
                                        Rule::when(function ($input) {
                                            return !isset($input->is_foreign);
                                        },new Nationalcode)],
            'father_name'           => 'nullable',
            'education_field'       => 'nullable',
        ];

    }

    public function attributes()
    {
        return [
            'first_name'            => __('shop::app.customer.account.profile.fname'),
            'last_name'             => __('shop::app.customer.account.profile.lname'),
            'gender'                => __('shop::app.customer.account.profile.gender'),
            'date_of_birth'         => __('shop::app.customer.account.profile.dob'),
            'email'                 => __('shop::app.customer.account.profile.email'),
            'password'              => __('velocity::app.shop.general.new-password'),
            'oldpassword'           => __('velocity::app.shop.general.enter-current-password'),
            'password_confirmation' => __('velocity::app.shop.general.confirm-new-password'),
            'image.*'               => __('admin::app.catalog.categories.image'),
            'national_code'         => __('app.customer.account.profile.national_code'),
            'father_name'           => __('app.customer.account.profile.father_name'),
            'education_field'       => __('app.customer.account.profile.education_field') ,
        ];
    }
}
