<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Customer;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

use App\Datatables\LoanDatatable;
use App\Datatables\PaymentDatatable;
use Inertia\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class LoanController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Loan/Index');
    }

    public function datatable(Request $request,LoanDatatable $datatable): JsonResponse
    {
        $data = $datatable->make($request);
        return response()->json($data);
    }

    public function create()
    {
        $customers = Customer::all();
        $users = User::all();
        return Inertia::render('Loan/Create', compact('customers', 'users'));
    }

    public function store(Request $request)
    {
        $loan = new Loan;
        $loan->user_id = auth()->user()->id;
        $loan->customer_id = $request->customer_id;
        $loan->amount = $request->amount;
        $loan->interest = $request->interest;
        $loan->balance = $request->balance;
        $loan->total_fee = $request->total_fee;
        $loan->way_to_pay = $request->way_to_pay;
        $loan->payment_date = $request->payment_date;
        $loan->save();

        if(isset($request->total_fee)){
            for ($i=0; $i < $request->total_fee; $i++) { 
                $model = new Payment;
                $model->loan_id = $loan->id;
                $model->fee = $i+1;

                $day = date("d",strtotime($request->payment_date));
                if($i==0){
                    $date = date("Y-m-d",strtotime($request->payment_date));
                }else if($request->way_to_pay=="30"){
                    $date = date("Y-m-d",strtotime($request->payment_date."+ 1 month"));
                }else if($day=="15"){
                    $date = date("Y-m-d",strtotime($request->payment_date."+ ".$request->way_to_pay." days"));
                }else{
                    $date = date("Y-m-d",strtotime($request->payment_date."+ ".$request->way_to_pay." days"));
                }
                $model->payment_date = $date;
                $model->amount = $request->balance / $request->total_fee;
                $model->save();

                $request->payment_date = $date;
            }
        }
        return redirect()->route('loans.index');
    }
    
    
    public function show(Loan $loan): Response
    {
        $payments = Payment::where('loan_id',$loan->id)->get();
        $total = 0;
        foreach ($payments as $key => $value) {
            $total +=  (int) $value->amount;
        }
        $customer = $loan->customer->name;
        return Inertia::render('Loan/Show', compact('customer', 'payments', 'total'));
    }


    public function edit(Loan $loan)
    {
        return Inertia::render('Loan/Edit', compact('loan'));
    }

    public function update(Request $request, Loan $loan)
    {
        $loan->update($request);
        $loans = Loan::all();
        return Inertia::render('Loan/Index', compact('loans'));
    }

    public function destroy(Loan $loan)
    {
        $payments = Payment::where('loan_id', $loan->id)->get();
        if(isset($payments) && !is_null($payments)){
            foreach ($payments as $key => $value) {
                $value->delete();
            }
        }
        $loan->delete();
        return redirect()->back();
    }

    public function getLoan($loan_id)
    {
        $loan = Loan::find($loan_id);
        $payments = Payment::with('status')->where('loan_id',$loan_id)->get();
        $total = 0;
        foreach ($payments as $key => $value) {
            $total +=  (int) $value->amount;
        }
        return Inertia::render('Loan/Show', compact('loan', 'payments', 'total'));
    }
}
