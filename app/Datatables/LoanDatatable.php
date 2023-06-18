<?php

namespace App\Datatables;

use App\Models\Loan;

class LoanDatatable extends Datatable
{
    protected string $model = Loan::class;

    protected array $with = [];

    protected function map(): callable
    {
        
        return function (Loan $loan) {
            return [
                'id' => $loan->id,
                'customer' => $loan->customer->name,
                'user' => $loan->user->name,
                'interest' => $loan->interest,
                'amount' => $loan->amount,
                'balance' => $loan->balance,
                'total_fee' => $loan->total_fee,
                'way_to_pay' => $loan->way_to_pay,
                'payment_date' => $loan->payment_date,
                'status' => $loan->status->name,
                'status_color' => $loan->status->color,
                'status_background_color' => $loan->status->background_color,
            ];
        };
    }
}
