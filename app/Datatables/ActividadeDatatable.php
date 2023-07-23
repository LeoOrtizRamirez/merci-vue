<?php

namespace App\Datatables;

use App\Models\Actividade;

class ActividadDatatable extends Datatable
{
    protected string $model = Actividade::class;

    protected array $with = [];

    protected function map(): callable
    {
        return function (Actividade $actividade) {
            return [
                'id' => $actividade->id,
                'categoria_name' => $actividade->categoria->name,
                'name' => $actividade->name,
                'visible' => $actividade->hasRegistros($actividade->id),
            ];
        };
    }
}
