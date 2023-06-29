<?php

namespace App\Http\Controllers;

use App\Datatables\ActividadDatatable;
use App\Http\Controllers\Controller;
use App\Models\Actividade;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Inertia\Response;
use Inertia\Inertia;

class ActividadeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Actividad/Index');
    }

    public function datatable(
        Request       $request,
        ActividadDatatable $datatable
    ): JsonResponse {
        $data = $datatable->make($request);

        return response()->json($data);
    }

    public function create(): Response
    {
        $categorias = Categoria::all();
        return Inertia::render('Actividad/Create', ['categorias' => $categorias]);
    }

    public function store(Request $request)
    {
        $actividade = new Actividade;
        $actividade->name = $request->name;
        $actividade->categoria_id = $request["categoria"]["id"];
        $actividade->save();
        return redirect()->route('actividades.index');
    }

    public function show(Actividade $actividade)
    {
        return Inertia::render('Actividad/Show', compact('actividade'));
    }

    public function edit(Actividade $actividade)
    {
        $categorias = Categoria::all();
        return Inertia::render('Actividad/Edit', compact('actividade', 'categorias'));
    }

    public function update(Request $request, Actividade $actividade)
    {
        $actividade = Actividade::find($actividade->id);
        $actividade->name = $request->name;
        $actividade->categoria_id = $request["categoria"]["id"];
        $actividade->save();
        return redirect()->route('actividades.index');
    }

    public function destroy(Actividade $actividade)
    {
        $actividade->delete();
        return redirect()->back();
    }
}
