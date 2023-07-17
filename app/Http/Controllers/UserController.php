<?php

namespace App\Http\Controllers;

use App\Actions\Users\CreateUser;
use App\Actions\Users\UpdateUser;
use App\Datatables\UserDatatable;
use App\DTOs\UserDTO;
use App\Models\Empresa;
use App\Models\Estado;
use App\Models\Indicadore;
use App\Models\Role;
use App\Models\User;
use App\Models\UserIndicadore;
use App\Models\UsersIndicadoresDato;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('User/Index');
    }

    public function datatable(Request $request, UserDatatable $datatable): JsonResponse
    {
        $data = $datatable->make($request);
        return response()->json($data);
    }

    public function create(): Response
    {
        $indicadores = Indicadore::all();
        $estados = Estado::where('tipo', 1)->get();
        $empresas = Empresa::all();
        $roles = Role::all();
        return Inertia::render('User/Create', [
            'indicadores' => $indicadores,
            'estados' => $estados,
            'empresas' => $empresas,
            'roles' => $roles,
        ]);
    }

    public function store(Request $request, CreateUser $createUser): RedirectResponse
    {
        //dd($request);
        /* abort_if(!auth()->user()->admin, 403); */

        $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string']
        ]);

        $user = $createUser->execute(new UserDTO([
            'name' => $request['name'],
            'email' => $request['email'],
            'empresa_id' => $request['empresa']['id'],
            'password' => Hash::make($request['password'])
        ]));

        //Agregar Rol
        $user->assignRole($request->rol["name"]);

        //Guardar Indicadores
        foreach ($request->indicadores as $key => $indicador) {
            $user_indicador = new UserIndicadore;
            $user_indicador->indicador_id = $indicador["id"];
            $user_indicador->user_id = $user->id;
            $user_indicador->save();
        }


        return redirect()->route('users.index');
    }

    public function show(User $user)
    {
        $user = User::where('id', $user->id)->with('empresa')->first();
        $user->role_name = $user->getRoleNames()[0];

        $user_indicadores = $user->indicadores;
        $user_indicadores_ids = [];
        foreach ($user_indicadores as $key => $item) {
            $user_indicadores_ids[] = $item->indicador->id;
        }

        $users = User::all();
        $indicadores = Indicadore::whereIn('id', $user_indicadores_ids)->get();

        $arbol = [];
        foreach ($user->indicadores as $index => $item) {
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
                                "size" => "VENTAS: " . $dato->data_1,
                                "type" => "PRESUPUESTO: " . $dato->data_2
                            ]
                        ];
                        break;
                    case 2:
                        $arbol[$index]["children"][$key] = [
                            "key" => $index . "-" . $key,
                            "data" => [
                                "name" => "MES: " . $dato->mes,
                                "size" => "TTL COTIZACIONES: " . $dato->data_1,
                                "type" => "N COTIZACIONES: " . $dato->data_2
                            ]
                        ];
                        break;
                    case 3:
                        $arbol[$index]["children"][$key] = [
                            "key" => $index . "-" . $key,
                            "data" => [
                                "name" => "MES: " . $dato->mes,
                                "size" => "PORCENTAJE: " . $dato->data_1,
                                "type" => ""
                            ]
                        ];
                        break;
                    case 4:
                        $arbol[$index]["children"][$key] = [
                            "key" => $index . "-" . $key,
                            "data" => [
                                "name" => "MES: " . $dato->mes,
                                "size" => "CLIENTES: " . $dato->data_1,
                                "type" => ""
                            ]
                        ];
                        break;
                }
            }
        }

        return Inertia::render('User/Show', compact('user', 'users', 'indicadores', 'arbol'));
    }

    public function saveIndicador(Request $request)
    {
        $user = User::find($request->user["id"]);
        $user_indicadores = UserIndicadore::where('user_id', $request->user["id"])
            ->where('indicador_id', $request->indicador["id"])->first();

        if ($user_indicadores) {
            $users_indicadores_dato = new UsersIndicadoresDato;
            $users_indicadores_dato->mes = $request->mes;
            $users_indicadores_dato->data_1 = $request->data_1;
            $users_indicadores_dato->data_2 = $request->data_2;
            $users_indicadores_dato->user_indicadore_id = $user_indicadores->id;
            $users_indicadores_dato->save();
        }

        $arbol = [];
        foreach ($user->indicadores as $index => $item) {
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
                                "size" => "VENTAS: " . $dato->data_1,
                                "type" => "PRESUPUESTO: " . $dato->data_2
                            ]
                        ];
                        break;
                    case 2:
                        $arbol[$index]["children"][$key] = [
                            "key" => $index . "-" . $key,
                            "data" => [
                                "name" => "MES: " . $dato->mes,
                                "size" => "TTL COTIZACIONES: " . $dato->data_1,
                                "type" => "N COTIZACIONES: " . $dato->data_2
                            ]
                        ];
                        break;
                    case 3:
                        $arbol[$index]["children"][$key] = [
                            "key" => $index . "-" . $key,
                            "data" => [
                                "name" => "MES: " . $dato->mes,
                                "size" => "PORCENTAJE: " . $dato->data_1,
                                "type" => ""
                            ]
                        ];
                        break;
                    case 4:
                        $arbol[$index]["children"][$key] = [
                            "key" => $index . "-" . $key,
                            "data" => [
                                "name" => "MES: " . $dato->mes,
                                "size" => "CLIENTES: " . $dato->data_1,
                                "type" => ""
                            ]
                        ];
                        break;
                }
            }
        }
        return json_encode($arbol);
    }

    public function edit(User $user): Response
    {
        /* abort_if(!auth()->user()->admin, 403); */

        $indicadores = Indicadore::all();
        $empresas = Empresa::all();
        $roles = Role::all();
        $current_user = $user;
        $rol = $user->getRoleNames()[0];
        $user_indicadores = $user->indicadores;
        $user_indicadores_ids = [];
        foreach ($user_indicadores as $key => $user_indicador) {
            $user_indicadores_ids[] = $user_indicador->indicador_id;
        }
        return Inertia::render('User/Edit', compact('indicadores', 'empresas', 'roles', 'rol', 'current_user', 'user_indicadores_ids'));
    }

    public function update(Request $request, User $user, UpdateUser $updateUser): RedirectResponse
    {
        /* abort_if(!auth()->user()->admin, 403); */

        $user = User::find($request->id);
        $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string']
        ]);

        $updateUser->execute($user, new UserDTO([
            'name' => $request['name'],
            'email' => $request['email'],
            'empresa_id' => $request['empresa']['id'],
            'password' => $request['password'] ?
                Hash::make($request['password']) : null,
        ]));

        //Eliminar Rol
        $current_role = $user->getRoleNames()[0];
        $user->removeRole($current_role);
        //Agregar Rol
        $user->assignRole($request->rol["name"]);

        //Eliminar Indicadores
        foreach ($user->indicadores as $key => $indicador) {
            $indicador->delete();
        }

        //Guardar Indicadores
        foreach ($request->indicadores as $key => $indicador) {
            $user_indicador = new UserIndicadore;
            $user_indicador->indicador_id = $indicador["id"];
            $user_indicador->user_id = $user->id;
            $user_indicador->save();
        }

        return redirect()->route('users.index');
    }

    public function destroy(User $user): RedirectResponse
    {
        /* abort_if(!auth()->user()->admin, 403); */

        //Eliminar Indicadores
        foreach ($user->indicadores as $key => $indicador) {
            $indicador->delete();
        }

        $user->delete();
        return redirect()->back();
    }

    /*     public function getPermissions(){
        foreach (Auth::user()->getAllPermissions() as $permission) {
            $permissions[] = $permission->name;
        }
        return $permissions;
    } */
}
