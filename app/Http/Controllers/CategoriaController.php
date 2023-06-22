<?php

namespace App\Http\Controllers;

use App\Datatables\CategoriaDatatable;
use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Inertia\Response;
use Inertia\Inertia;

class CategoriaController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Categoria/Index');
    }

    public function datatable(
        Request       $request,
        CategoriaDatatable $datatable
    ): JsonResponse {
        $data = $datatable->make($request);

        return response()->json($data);
    }

    public function create(): Response
    {
        return Inertia::render('Categoria/Create');
    }

    public function store(Request $request)
    {
        $categoria = new Categoria;
        $categoria->name = $request->name;
        $categoria->save();
        return redirect()->route('categorias.index');
    }

    public function show(Categoria $categoria)
    {
        return Inertia::render('Categoria/Show', compact('categoria'));
    }

    public function edit(Categoria $categoria)
    {
        return Inertia::render('Categoria/Edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria)
    {
        $categoria = Categoria::find($categoria->id);
        $categoria->name = $request->name;
        $categoria->save();
        return redirect()->route('categorias.index');
    }

    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return redirect()->back();
    }
}
