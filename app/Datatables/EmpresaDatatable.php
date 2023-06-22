<?php

namespace App\Datatables;

use App\Models\Empresa;

class EmpresaDatatable extends Datatable
{
    protected string $model = Empresa::class;

    protected array $with = [];

    protected function map(): callable
    {
        return function (Empresa $empresa) {
            return [
                'id' => $empresa->id,
                'name' => $empresa->name,
            ];
        };
    }
}
