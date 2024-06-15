<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PartnerRequest extends FormRequest
{
    public function rules(): array
    {

        return [
            'title'               => 'required',
            'image'               => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'url'                 => 'nullable|url',
            'order'               => 'required|integer',
            'carousel_id' => 'required|exists:carousel_categories,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
