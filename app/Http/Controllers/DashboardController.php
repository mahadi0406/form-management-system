<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display a listing of forms.
     */
    public function index(): \Inertia\Response
    {
        return Inertia::render('Dashboard', [
            'recentForms' => $recentForms ?? [],
            'totalForms' => $totalForms ?? 12,
        ]);
    }
}
