<?php

namespace App\Http\Services;

use App\Http\Requests\FormStoreRequest;
use App\Models\Form;
use Illuminate\Database\Eloquent\Collection;

class FormService
{
    /**
     * @param FormStoreRequest $request
     * @return array
     */
    public function prepareParams(FormStoreRequest $request): array
    {
        return [
            'title' => $request->input('title'),
            'method' => $request->input('method'),
            'action' => $request->input('action'),
            'configuration' => $request->json('configuration'),
        ];
    }

    /**
     * @param array $data
     * @return Form
     */
    public function store(array $data): Form
    {
        return Form::create($data);
    }

    public function getCount(): int
    {
        return Form::count();
    }


    public function getLatest(): Collection
    {
        return Form::latest()->take(5)->get();
    }



}
