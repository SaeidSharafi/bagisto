<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CenterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title'   => ['required','string'],
            'address' => ['required', 'string'],
            'phone'   => ['required', 'string'],
            'link'    => ['required', 'url'],
            'order'   => ['required', 'numeric', 'min:0'],
        ];
    }

    public function authorize(): bool
    {
        return auth('admin')->check();
    }
}
