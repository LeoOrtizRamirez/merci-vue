<?php

namespace App\Http\Controllers;

use App\Datatables\ActaDatatable;
use App\Http\Controllers\Controller;
use App\Models\Acta;
use App\Models\Actividad;
use App\Models\Estado;
use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Inertia\Response;
use Inertia\Inertia;

class ActaController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Acta/Index');
    }

    public function datatable(
        Request       $request,
        ActaDatatable $datatable
    ): JsonResponse {
        $data = $datatable->make($request);

        return response()->json($data);
    }

    public function create(): Response
    {
        return Inertia::render('Acta/Create');
    }

    public function store(Request $request)
    {
        $acta = new Acta;
        $acta->numero_sesion = $request->numero_sesion;
        $acta->fecha = $request->fecha;
        $acta->hora_inicio = $request->hora_inicio;
        $acta->hora_finalizacion = $request->hora_finalizacion;
        $acta->modalidad_encuentro = $request->modalidad_encuentro;
        $acta->asistentes = $request->asistentes;
        $acta->temas = $request->temas;
        $acta->save();
        return redirect()->route('actas.show', $acta->id);
    }

    public function show(Acta $acta)
    {
        $estados = Estado::where('tipo', 2)->get();
        $actividades = Actividad::all();
        return Inertia::render('Acta/Show', compact('acta', 'actividades', 'estados'));
    }

    public function edit(Acta $acta)
    {
        return Inertia::render('Acta/Edit', compact('acta'));
    }

    public function update(Request $request, Acta $acta)
    {
        $acta = Acta::find($acta->id);
        $acta->numero_sesion = $request->numero_sesion;
        $acta->fecha = $request->fecha;
        $acta->hora_inicio = $request->hora_inicio;
        $acta->hora_finalizacion = $request->hora_finalizacion;
        $acta->modalidad_encuentro = $request->modalidad_encuentro;
        $acta->asistentes = $request->asistentes;
        $acta->temas = $request->temas;
        $acta->save();
        return redirect()->route('actas.index');
    }

    public function destroy(Acta $acta)
    {
        //Buscar tareas de Acta y eliminar
        $tareas = Tarea::where("acta_id", $acta->id)->get();
        foreach ($tareas as $key => $tarea) {
            $tarea->delete();
        }
        $acta->delete();
        return redirect()->back();
    }
}
