<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use App\Models\User;
use App\Models\Loan;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

use App\Datatables\InvestmentDatatable;
use Inertia\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
class InvestmentController extends Controller
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
        return Inertia::render('Investment/Index', compact('investment', 'loans', 'gain', 'current_balance'));
    }

    public function datatable(Request $request,InvestmentDatatable $datatable): JsonResponse
    {
        $data = $datatable->make($request);
        return response()->json($data);
    }

    /*
    public function index()
    {
        $loans = (int) Loan::sum('amount');
        $investment = (int) Investment::sum('amount');

        $balance = (int) Loan::sum('balance');
        $amount = (int) Loan::sum('amount');
        $gain =  $balance - $amount;

        $investments = Investment::with('user')->get();
        return Inertia::render('Investment/Index', compact('investments', 'investment', 'loans', 'gain'));
    }
*/
    public function create()
    {
        $users = User::all();
        return Inertia::render('Investment/Create', compact('users'));
    }

    public function store(Request $request)
    {
        $investment = new Investment;
        $investment->user_id = $request->user_id;
        $investment->amount = $request->amount;
        $investment->save();
        return redirect()->route('investments.index');
    }

    public function show(Investment $investment)
    {
        return Inertia::render('Investment/Show', compact('investment'));
    }

    public function edit(Investment $investment)
    {
        $users = User::all();
        return Inertia::render('Investment/Edit', compact('investment','users'));
    }

    public function update(Request $request, Investment $investment)
    {

        $investment = Investment::find($investment->id);
        $investment->user_id = $request->user_id;
        $investment->amount = $request->amount;
        $investment->save();
        return Inertia::render('Investment/Index');
    }

    public function destroy(Investment $investment)
    {
        $investment->delete();
        return redirect()->back();
    }
}
