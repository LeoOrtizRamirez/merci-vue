<?php

namespace App\Datatables;

use App\Models\Investment;

class InvestmentDatatable extends Datatable
{
    protected string $model = Investment::class;

    protected array $with = [];

    protected function map(): callable
    {
        return function (Investment $investment) {
            return [
                'id' => $investment->id,
                'user' => $investment->user->name,
                'amount' => $investment->amount,
            ];
        };
    }
}
