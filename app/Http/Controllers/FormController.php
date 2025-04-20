<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormStoreRequest;
use App\Http\Services\FormService;
use App\Models\Form;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class FormController extends Controller
{
    public function __construct(
        protected readonly FormService $formService
    ) {

    }

    /**
     * Display a listing of forms.
     */
    public function create(): \Inertia\Response
    {
        return Inertia::render('Forms/Create');
    }

    /**
     * Display a listing of forms.
     */
    public function index(): \Inertia\Response
    {
        $forms = Form::latest()->get();
        return Inertia::render('Forms/Index', compact('forms'));
    }

    /**
     * Store a newly created form.
     */
    public function store(FormStoreRequest $request)
    {
        $form = $this->formService->store(
            $this->formService->prepareParams($request)
        );

        // Redirect with Inertia
        return Inertia::location(route('forms.index'));
    }

    /**
     * Show the form for editing.
     */
    public function edit(int $id): \Inertia\Response
    {
        $form = Form::findOrFail($id);
        return Inertia::render('Forms/Edit', ['form' => $form]);
    }

    /**
     * Update the specified form.
     */
    public function update(FormStoreRequest $request, int $id)
    {
        $form = Form::findOrFail($id);
        $form->update($this->formService->prepareParams($request));

        return Inertia::location(route('forms.index'));
    }

    /**
     * Remove the specified form.
     */
    public function destroy(int $id)
    {
        $form = Form::findOrFail($id);
        $form->delete();

        return Inertia::location(route('forms.index'));
    }
}
