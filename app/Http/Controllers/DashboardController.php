<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Investment;
use App\Models\Payment;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $loans = (int) Loan::sum('amount');
        $investment = (int) Investment::sum('amount');

        $balance = (int) Loan::sum('balance');
        $amount = (int) Loan::sum('amount');
        $gain =  $balance - $amount;

        $payments = Payment::where('status_id',2)->sum('amount');
        $current_balance = ($investment - $loans) + $payments;

        return Inertia::render('Dashboard',compact('investment', 'loans', 'gain', 'current_balance'));
    }
}
