<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'method' => ['required', 'string', 'in:GET,POST'],
            'action' => ['nullable', 'string', 'max:255'],
            'configuration' => ['required', 'array'],
            'configuration.fields' => ['required', 'array', 'min:1'],
            'configuration.fields.*.type' => ['required', 'string'],
            'configuration.fields.*.name' => ['required', 'string'],
            'configuration.fields.*.label' => ['nullable', 'string'],
            'configuration.fields.*.placeholder' => ['nullable', 'string'],
            'configuration.fields.*.required' => ['nullable', 'boolean'],
        ];
    }
}
