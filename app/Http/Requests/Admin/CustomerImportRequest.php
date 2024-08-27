<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CustomerImportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'uploaded_file' => 'required_without:file_path|file|mimes:xls,xlsx,csv',
            'file_path' => 'nullable|string',
            'validated' => 'bool',
        ];
    }

    public function messages()
    {
        return [
            'uploaded_file.required_without' => __('validation.required', ['attribute' => __('admin::app.export.csv')]),
        ];
    }

    public function authorize(): bool
    {
        return auth('admin')->check();
    }
}
