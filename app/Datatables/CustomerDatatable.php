<?php

namespace App\Datatables;

use App\Models\Customer;

class CustomerDatatable extends Datatable
{
    protected string $model = Customer::class;

    protected array $with = [];

    protected function map(): callable
    {
        return function (Customer $customer) {
            return [
                'id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
            ];
        };
    }
}
