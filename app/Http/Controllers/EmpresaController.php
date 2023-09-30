<?php

namespace App\Http\Controllers;

use App\Datatables\EmpresaDatatable;
use App\Http\Controllers\Controller;
use App\Models\Acta;
use App\Models\Empresa;
use App\Models\Entregable;
use App\Models\Estado;
use App\Models\UserEmpresa;
use App\Models\EmpresaIndicadore;
use App\Models\EmpresasIndicadoresDato;
use App\Models\Indicadore;
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
        $indicadores = Indicadore::all();
        $estados = Estado::where('tipo', 1)->get();
        return Inertia::render('Empresa/Create', [
            'estados' => $estados,
            'indicadores' => $indicadores,
        ]);
    }

    public function store(Request $request)
    {
        $empresa = new Empresa;
        $empresa->name = $request->name;
        $empresa->nit = $request->nit;
        $empresa->estado_id = $request['estado']['id'];
        $empresa->save();

        //Guardar Indicadores
        foreach ($request->indicadores as $key => $indicador) {
            $empresa_indicador = new EmpresaIndicadore;
            $empresa_indicador->indicador_id = $indicador["id"];
            $empresa_indicador->empresa_id = $empresa->id;
            $empresa_indicador->save();
        }

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
        if ($role_name == "CLIENTE") {
            //Obtener empresa del cliente
            $user_empresa = UserEmpresa::where('user_id', Auth::user()->id)->first();
            $actas = Acta::where('empresa_id', $user_empresa->empresa_id)
                ->get();
        } else {
            $actas = Acta::where('empresa_id', $empresa->id)
                ->where('user_id', Auth::user()->id)
                ->get();
        }

        $empresa_indicadores = $empresa->indicadores;
        $empresa_indicadores_ids = [];
        foreach ($empresa_indicadores as $key => $item) {
            $empresa_indicadores_ids[] = $item->indicador->id;
        }

        $indicadores = Indicadore::whereIn('id', $empresa_indicadores_ids)->get();

        $arbol = $this->getArboldIndicadores($empresa);
        return Inertia::render('Empresa/Show', compact('empresa', 'actas', 'entregables', 'indicadores', 'arbol'));
    }

    public function getArboldIndicadores($empresa)
    {
        $arbol = [];
        foreach ($empresa->indicadores as $index => $item) {
            $arbol[$index] = [
                "key" => "" . $index . "",
                "data" => [
                    "name" => $item->indicador->name,
                ],
            ];
            foreach ($item->datos as $key => $dato) {
                switch ($item->indicador->id) {
                    case 1:
                        $arbol[$index]["children"][$key] = [
                            "key" => $index . "-" . $key,
                            "data" => [
                                "name" => "MES: " . $dato->mes,
                                "size" => "VENTAS: " . number_format($dato->data_1),
                                "type" => "PRESUPUESTO: " . number_format($dato->data_2),
                                "node" => $item,
                                "dato_1" => $dato->data_1,
                                "dato_2" => $dato->data_2,
                                "mes" => $dato->mes,
                                "id" => $dato->id
                            ]
                        ];
                        break;
                    case 2:
                        $arbol[$index]["children"][$key] = [
                            "key" => $index . "-" . $key,
                            "data" => [
                                "name" => "MES: " . $dato->mes,
                                "size" => "TTL COTIZACIONES: " . number_format($dato->data_1),
                                "type" => "N COTIZACIONES: " . number_format($dato->data_2),
                                "node" => $item,
                                "dato_1" => $dato->data_1,
                                "dato_2" => $dato->data_2,
                                "mes" => $dato->mes,
                                "id" => $dato->id
                            ]
                        ];
                        break;
                    case 3:
                        $arbol[$index]["children"][$key] = [
                            "key" => $index . "-" . $key,
                            "data" => [
                                "name" => "MES: " . $dato->mes,
                                "size" => "PORCENTAJE: " . $dato->data_1,
                                "type" => "",
                                "node" => $item,
                                "dato_1" => $dato->data_1,
                                "dato_2" => $dato->data_2,
                                "mes" => $dato->mes,
                                "id" => $dato->id
                            ]
                        ];
                        break;
                    case 4:
                        $arbol[$index]["children"][$key] = [
                            "key" => $index . "-" . $key,
                            "data" => [
                                "name" => "MES: " . $dato->mes,
                                "size" => "CLIENTES: " . number_format($dato->data_1),
                                "type" => "",
                                "node" => $item,
                                "dato_1" => $dato->data_1,
                                "dato_2" => $dato->data_2,
                                "mes" => $dato->mes,
                                "id" => $dato->id
                            ]
                        ];
                        break;
                }
            }
        }
        return $arbol;
    }

    public function edit(Empresa $empresa)
    {
        $indicadores = Indicadore::all();
        $current_empresa = $empresa;
        $empresa_indicadores = $empresa->indicadores;
        $empresa_indicadores_ids = [];
        foreach ($empresa_indicadores as $key => $user_indicador) {
            $empresa_indicadores_ids[] = $user_indicador->indicador_id;
        }

        $estados = Estado::where('tipo', 1)->get();
        return Inertia::render('Empresa/Edit', [
            'empresa' => $empresa,
            'estados' => $estados,
            'indicadores' => $indicadores,
            'empresa_indicadores_ids' => $empresa_indicadores_ids,
            'current_empresa' => $current_empresa,
        ]);
    }

    public function update(Request $request, Empresa $empresa)
    {
        $empresa = Empresa::find($empresa->id);
        $empresa->name = $request->name;
        $empresa->nit = $request->nit;
        $empresa->estado_id = $request['estado']['id'];
        $empresa->save();

        $ids_indicadores_actualizados = [];
        foreach ($request->indicadores as $element) {
            $ids_indicadores_actualizados[] = $element["id"];
        }
        $indicadores_actuales = EmpresaIndicadore::where('empresa_id', $empresa->id)->get();
        $ids_indicadores_actuales = [];
        foreach ($indicadores_actuales as $element) {
            $ids_indicadores_actuales[] = $element->indicador_id;
        }

        if (count(array_diff($ids_indicadores_actualizados, $ids_indicadores_actuales)) == 0 && count(array_diff($ids_indicadores_actuales, $ids_indicadores_actualizados)) == 0) {
        } else {
            //Eliminar Indicadores
            foreach ($empresa->indicadores as $key => $indicador) {
                //Eliminar datos de indicadores
                $empresas_indicadores_datos = EmpresasIndicadoresDato::where('empresa_indicadore_id', $indicador->id)->get();
                foreach ($empresas_indicadores_datos as $key => $value) {
                    $value->delete();
                }
                $indicador->delete();
            }
            //Guardar Indicadores
            foreach ($request->indicadores as $key => $indicador) {
                $empresa_indicador = new EmpresaIndicadore;
                $empresa_indicador->indicador_id = $indicador["id"];
                $empresa_indicador->empresa_id = $empresa->id;
                $empresa_indicador->save();
            }
        }

        return redirect()->route('empresas.show', $empresa->id);
    }

    public function destroy(Empresa $empresa)
    {
        //Eliminar empresa a role admin
        $admin_empresa = UserEmpresa::where('empresa_id', $empresa->id)
            ->where('user_id', 1)
            ->first();
        if ($admin_empresa) {
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

    public function saveIndicador(Request $request)
    {
        //$user = User::find($request->user["id"]);

        if (isset($request->id)) {
            $empresas_indicadores_dato = EmpresasIndicadoresDato::find($request->id);
            $empresas_indicadores_dato->mes = $request->mes;
            $empresas_indicadores_dato->data_1 = $request->data_1;
            $empresas_indicadores_dato->data_2 = $request->data_2;
            $empresas_indicadores_dato->save();
        } else {

            //Validar si hay un EmpresasIndicadoresDato en la fecha ingresada
            $empresa_id = $request["empresa"]["id"];
            $indicador_id = $request["empresa"]["id"];
            $empresa_indicador = EmpresaIndicadore::where('empresa_id', $empresa_id)
                ->where('indicador_id', $indicador_id)
                ->first();
            if (!empty($empresa_indicador)) {
                $empresa_indicador_dato = EmpresasIndicadoresDato::where('empresa_indicadore_id', $empresa_indicador->id)
                    ->where('mes', $request->mes)
                    ->first();

                if (!empty($empresa_indicador_dato)) {
                    return json_encode([
                        "code" => '404',
                        "message" => 'Esta fecha no esta disponible para este indicador!'
                    ]);
                }
            }

            $empresa_indicadores = EmpresaIndicadore::where('empresa_id', $request->empresa["id"])
                ->where('indicador_id', $request->indicador["id"])->first();
            if ($empresa_indicadores) {
                $empresas_indicadores_dato = new EmpresasIndicadoresDato;
                $empresas_indicadores_dato->mes = $request->mes;
                $empresas_indicadores_dato->data_1 = $request->data_1;
                $empresas_indicadores_dato->data_2 = $request->data_2;
                $empresas_indicadores_dato->empresa_indicadore_id = $empresa_indicadores->id;
                $empresas_indicadores_dato->save();
            }
        }

        $empresa = Empresa::find($request->empresa["id"]);

        $arbol = $this->getArboldIndicadores($empresa);
        return json_encode($arbol);
    }

    public function deleteIndicador(Request $request, $id)
    {
        $empresas_indicadores_dato = EmpresasIndicadoresDato::find($id);

        $empresa_indicador = EmpresaIndicadore::find($empresas_indicadores_dato->empresa_indicadore_id);
        $empresa = Empresa::find($empresa_indicador->empresa_id);
        if ($empresas_indicadores_dato) {
            $empresas_indicadores_dato->delete();
        }

        $arbol = $this->getArboldIndicadores($empresa);
        return json_encode($arbol);
    }
}
