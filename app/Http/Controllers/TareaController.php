<?php

namespace App\Http\Controllers;

use App\Datatables\TareaDatatable;
use App\Http\Controllers\Controller;
use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Inertia\Response;
use Inertia\Inertia;

class TareaController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Tarea/Index');
    }

    public function datatable(Request $request, TareaDatatable $datatable): JsonResponse {
        //dd($request);
        $data = $datatable->make($request);
        return response()->json($data);
    }

    public function create(): Response
    {
        return Inertia::render('Tarea/Create');
    }

    public function store(Request $request)
    {
        $tarea = new Tarea;
        $tarea->acta_id = $request->acta;
        $tarea->descripcion = $request->descripcion;
        $tarea->responsable = $request->responsable;
        $tarea->fecha_inicio = $request->fecha_inicio;
        $tarea->fecha_fin = $request->fecha_fin;
        $tarea->fecha_finalizacion = $request->fecha_finalizacion;
        $tarea->estado_id = 4;
        $tarea->actividad_id = $request["actividad"]["id"];
        $tarea->save();

        $tareas = Tarea::where('acta_id', $request->acta)->with("estado", "actividad")->get();
        foreach ($tareas as $key => $item) {
            $color = $item->estado->backgroundColor;
            $estado = $item->estado->name;
            $actividad = $item->actividad->name;
            $categoria = $item->actividad->categoria->name;
            $item->estado_name = $estado;
            $item->estado_backgroundColor = $color;
            $item->actividad_name = $actividad;
            $item->categoria_name = $categoria;
        }
        return json_encode($tareas);
    }

    public function show(Tarea $tarea)
    {
        return Inertia::render('Tarea/Show', compact('tarea'));
    }

    public function edit(Tarea $tarea)
    {
        return Inertia::render('Tarea/Edit', compact('tarea'));
    }

    public function update(Request $request, Tarea $tarea)
    {
        //dd($request);
        $tarea = Tarea::find($tarea->id);
        //$tarea = new Tarea;
        $tarea->acta_id = $request->acta_id;
        $tarea->descripcion = $request->descripcion;
        $tarea->responsable = $request->responsable;
        $tarea->fecha_inicio = $request->fecha_inicio;
        $tarea->fecha_fin = $request->fecha_fin;
        $tarea->fecha_finalizacion = $request->fecha_finalizacion;
        $tarea->estado_id = $request["estado"]["id"];
        $tarea->actividad_id = $request["actividad"]["id"];
        $tarea->save();

        $tareas = Tarea::where('acta_id', $request->acta_id)->with("estado", "actividad")->get();
        foreach ($tareas as $key => $item) {
            $color = $item->estado->backgroundColor;
            $estado = $item->estado->name;
            $actividad = $item->actividad->name;
            $categoria = $item->actividad->categoria->name;
            $item->estado_name = $estado;
            $item->estado_backgroundColor = $color;
            $item->actividad_name = $actividad;
            $item->categoria_name = $categoria;
        }
        return json_encode($tareas);
    }

    public function destroy(Tarea $tarea)
    {
        $tarea->delete();
        return redirect()->back();
    }
}
