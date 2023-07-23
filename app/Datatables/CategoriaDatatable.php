<?php

namespace App\Datatables;

use App\Models\Categoria;

class CategoriaDatatable extends Datatable
{
    protected string $model = Categoria::class;

    protected array $with = [];

    protected function map(): callable
    {
        return function (Categoria $categoria) {
            return [
                'id' => $categoria->id,
                'name' => $categoria->name,
                'visible' => $categoria->hasRegistros($categoria->id),
            ];
        };
    }
}
