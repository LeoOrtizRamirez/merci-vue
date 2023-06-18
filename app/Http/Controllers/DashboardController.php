<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $loans = 0;
        $investment = 0;
        $gain =  0;
        $current_balance = 0;

        return Inertia::render('Dashboard',compact('investment', 'loans', 'gain', 'current_balance'));
    }
}
