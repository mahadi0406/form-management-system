<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormStoreRequest;
use App\Http\Services\FormService;
use App\Models\Form;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class FormController extends Controller
{
    public function __construct(
        protected readonly FormService $formService
    ) {

    }

    /**
     * Display a listing of forms.
     */
    public function index()
    {
        $forms = Form::select('id', 'title', 'created_at')->latest()->get();
        $totalForms = 12;
        return Inertia::render('Dashboard', [
            'recentForms' => $recentForms ?? [],
            'totalForms' => $totalForms,
        ]);
    }


    /**
     * @param FormStoreRequest $request
     * @return RedirectResponse
     */
    public function store(FormStoreRequest $request): RedirectResponse
    {
        $form = $this->formService->store(
            $this->formService->prepareParams($request)
        );

        return redirect()->route('forms.edit', $form->id)->with('success', 'Form created successfully.');
    }

}
