<?php

namespace App\Datatables;

use App\Models\Acta;

class ActaDatatable extends Datatable
{
    protected string $model = Acta::class;

    protected array $with = [];

    protected function map(): callable
    {
        return function (Acta $acta) {
            return [
                'id' => $acta->id,
                'numero_sesion' => $acta->numero_sesion,
                'fecha' => $acta->fecha,
                'hora_inicio' => $acta->hora_inicio,
                'hora_finalizacion' => $acta->hora_finalizacion,
                'modalidad_encuentro' => $acta->modalidad_encuentro,
                'asistentes' => $acta->asistentes,
                'temas' => $acta->temas,
            ];
        };
    }
}
