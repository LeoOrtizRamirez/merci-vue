<?php

namespace App\Http\Controllers;

use App\Datatables\EmpresaDatatable;
use App\Http\Controllers\Controller;
use App\Models\Acta;
use App\Models\Empresa;
use App\Models\Entregable;
use App\Models\Estado;
use App\Models\UserEmpresa;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;
use Inertia\Inertia;

class EmpresaController extends Controller
{
    public function index(): Response
    {
        $user = Auth::user();
        $user->role_name = $user->getRoleNames()[0];
        if ($user->role_name == "ADMIN") {
            $empresas = Empresa::with('estado')->get();
        } else {
            $empresas_ids = [];
            foreach ($user->empresas as $key => $value) {
                $empresas_ids[] = $value->empresa_id;
            }
            $empresas = Empresa::whereIn('id', $empresas_ids)->with('estado')->get();
        }
        return Inertia::render('Empresa/Index', compact('empresas', 'user'));
    }

    public function datatable(
        Request       $request,
        EmpresaDatatable $datatable
    ): JsonResponse {
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

        //Asignar empresa a role admin
        $admin_empresa = new UserEmpresa;
        $admin_empresa->user_id = 1;
        $admin_empresa->empresa_id = $empresa->id;
        $admin_empresa->save();

        return redirect()->route('empresas.index');
    }

    public function show(Empresa $empresa)
    {
        $user = Auth::user();
        $role_name = $user->getRoleNames()[0];

        $entregables = Entregable::where('empresa_id', $empresa->id)->get();
        if($role_name == "CLIENTE"){
            //Obtener empresa del cliente
            $user_empresa = UserEmpresa::where('user_id', Auth::user()->id)->first();
            $actas = Acta::where('empresa_id', $user_empresa->empresa_id)
            ->get();
        }else{
            $actas = Acta::where('empresa_id', $empresa->id)
            ->where('user_id', Auth::user()->id)
            ->get();
        }
        return Inertia::render('Empresa/Show', compact('empresa', 'actas', 'entregables'));
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
        //Eliminar empresa a role admin
        $admin_empresa = UserEmpresa::where('empresa_id', $empresa->id)
            ->where('user_id', 1)
            ->first();
        if($admin_empresa){
            $admin_empresa->delete();
        }

        $empresa->delete();
        return redirect()->back();
    }

    public function uploadImage(Request $request)
    {
        // validate the image
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        //dd($request->image->extension());
        // store the image
        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        // return the image path
        //return response()->json(['path' => '/images/' . $imageName]);

        $empresa = new Empresa;
        $empresa->name = $request->name;
        $empresa->nit = $request->nit;
        $empresa->estado_id = $request['estado'];

        $empresa->logo = $imageName;
        $empresa->save();

        //Asignar empresa a role admin
        $admin_empresa = new UserEmpresa;
        $admin_empresa->user_id = 1;
        $admin_empresa->empresa_id = $empresa->id;
        $admin_empresa->save();

        return redirect()->route('empresas.index');
    }
}
