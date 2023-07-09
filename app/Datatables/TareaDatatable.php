<?php

namespace App\Datatables;

use App\Models\Tarea;

class TareaDatatable extends Datatable
{
    protected string $model = Tarea::class;

    protected array $with = ["estado"];

    protected function map(): callable
    {
        return function (Tarea $tarea) {
            return [
                'id' => $tarea->id,
                'descripcion' => $tarea->descripcion,
                'responsable' => $tarea->responsable,
                'fecha_inicio' => $tarea->fecha_inicio,
                'fecha_fin' => $tarea->fecha_fin,
                'fecha_finalizacion' => $tarea->fecha_finalizacion,
                'estado_name' => $tarea->estado->name,
                'estado_backgroundColor' => $tarea->estado->backgroundColor,
                'actividad_name' => $tarea->actividad->name,
                'categoria_name' => $tarea->actividad->categoria->name,
                'acta_id' => $tarea->acta_id,
            ];
        };
    }
}
