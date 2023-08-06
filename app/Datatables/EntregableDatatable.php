<?php

namespace App\Datatables;

use App\Models\Entregable;

class EntregableDatatable extends Datatable
{
    protected string $model = Entregable::class;

    protected array $with = [];

    protected function map(): callable
    {
        return function (Entregable $entregable) {
            return [
                'id' => $entregable->id,
                'name' => $entregable->name,
                'extension' => $entregable->extension,
                'url' => $entregable->url
            ];
        };
    }
}
