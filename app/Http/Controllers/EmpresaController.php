<?php

namespace App\Http\Controllers;

use App\Datatables\EmpresaDatatable;
use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Estado;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Inertia\Response;
use Inertia\Inertia;

class EmpresaController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Empresa/Index');
    }

    public function datatable(
        Request       $request,
        EmpresaDatatable $datatable
    ): JsonResponse
    {
        $data = $datatable->make($request);

        return response()->json($data);
    }
    
    public function create(): Response
    {
        $estados = Estado::where('tipo', 1)->get();
        return Inertia::render('Empresa/Create', [
            'estados' => $estados
        ]);
    }

    public function store(Request $request)
    {
        $empresa = new Empresa;
        $empresa->name = $request->name;
        $empresa->nit = $request->nit;
        $empresa->estado_id = $request['estado']['id'];
        $empresa->save();
        return redirect()->route('empresas.index');
    }

    public function show(Empresa $empresa)
    {
        return Inertia::render('Empresa/Show', compact('empresa'));
    }

    public function edit(Empresa $empresa)
    {
        $estados = Estado::where('tipo', 1)->get();
        return Inertia::render('Empresa/Edit', [
            'empresa' => $empresa,
            'estados' => $estados
        ]);
    }

    public function update(Request $request, Empresa $empresa)
    {
        $empresa = Empresa::find($empresa->id);
        $empresa->name = $request->name;
        $empresa->nit = $request->nit;
        $empresa->estado_id = $request['estado']['id'];
        $empresa->save();
        return redirect()->route('empresas.index');
    }

    public function destroy(Empresa $empresa)
    {
        $empresa->delete();
        return redirect()->back();
    }
}
