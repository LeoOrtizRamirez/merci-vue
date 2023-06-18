<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Loan;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function setToPay($id, $view){
        $payment = Payment::find($id);
        $payment->status_id=2;
        $payment->save();
        $loan = Loan::find($payment->loan_id);
        $all_payments = Payment::where('loan_id', $payment->loan_id)->get();
        $validator = true;
        foreach ($all_payments as $key => $value) {
            if($value->status_id != 2){
                $validator = false;
            }
        }
        if($validator){
            $loan->status_id = 2;
            $loan->save();
        }

        $payments = Payment::with('status')->where('loan_id',$payment->loan_id)->get();

        if($view=="show"){
            return Redirect::route('loans.show',$payment->loan_id);
        }
        return Redirect::route('payment.index');
    }

    public function index()
    {   
        $payments = Payment::select('payments.*','customers.name')
        ->join('loans','loans.id','payments.loan_id')
        ->join('customers','customers.id','loans.customer_id')
        ->orderBy('payment_date','ASC')
        ->get();
        return Inertia::render('Payment/Index', compact('payments'));
    }
}