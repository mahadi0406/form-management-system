<?php

namespace App\Http\Controllers;

use App\Http\Services\FormService;
use App\Models\Form;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct(
        protected readonly FormService $formService
    ) {

    }
    /**
     * Display a listing of forms.
     */
    public function index(): \Inertia\Response
    {
        return Inertia::render('Dashboard', [
            'recentForms' => $this->formService->getLatest() ?? [],
            'totalForms' => $this->formService->getCount(),
        ]);
    }
}
