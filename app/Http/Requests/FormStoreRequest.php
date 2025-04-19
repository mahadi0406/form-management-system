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
            'configuration.title' => ['required', 'string', 'max:255'],
            'configuration.method' => ['required', 'string', 'in:GET,POST'],
            'configuration.action' => ['nullable', 'string', 'max:255'],
            'configuration.fields' => ['required', 'array', 'min:1'],
            'configuration.fields.*.type' => ['required', 'string'],
            'configuration.fields.*.name' => ['required', 'string'],
            'configuration.fields.*.label' => ['nullable', 'string'],
            'configuration.fields.*.placeholder' => ['nullable', 'string'],
            'configuration.fields.*.required' => ['nullable', 'boolean'],
            'configuration.fields.*.options' => ['nullable', 'array'],
            'configuration.fields.*.options.*.label' => ['required_with:configuration.fields.*.options', 'string'],
            'configuration.fields.*.options.*.value' => ['required_with:configuration.fields.*.options'],
            'configuration.fields.*.validation' => ['nullable', 'array'],
            'configuration.fields.*.defaultValue' => ['nullable'],
        ];
    }
}
