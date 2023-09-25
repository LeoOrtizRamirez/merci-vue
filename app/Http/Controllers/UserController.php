<?php

namespace App\Http\Controllers;

use App\Actions\Users\CreateUser;
use App\Actions\Users\UpdateUser;
use App\Datatables\UserDatatable;
use App\DTOs\UserDTO;
use App\Models\Empresa;
use App\Models\Estado;
use App\Models\Role;
use App\Models\User;
use App\Models\UserEmpresa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;
use Mockery\Undefined;

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
        $estados = Estado::where('tipo', 1)->get();
        $empresas = Empresa::all();
        $roles = Role::all();
        return Inertia::render('User/Create', [
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
            'password' => Hash::make($request['password'])
        ]));

        //Guardar empresas
        $empresas = $request['empresa'];
        if (!isset($empresas[0])) {
            $empresas = [$empresas];
        }

        foreach ($empresas as $key => $empresa) {
            $user_empresa = new UserEmpresa;
            $user_empresa->user_id = $user->id;
            $user_empresa->empresa_id = $empresa["id"];
            $user_empresa->save();
        }

        //Agregar Rol
        $user->assignRole($request->rol["name"]);

        return redirect()->route('users.index');
    }

    public function show(User $user)
    {
        $user = User::where('id', $user->id)->first();
        $empresas = $user->getNameEmpresas();
        $user->empresas = $empresas;
        $user->role_name = $user->getRoleNames()[0];
        $users = User::all();

        return Inertia::render('User/Show', compact('user', 'users'));
    }

    public function edit(User $user): Response
    {
        /* abort_if(!auth()->user()->admin, 403); */

        $empresas = Empresa::all();
        $roles = Role::all();
        $current_user = $user;
        $rol = $user->getRoleNames()[0];
        $user_empresas = $user->empresas;
        $user_empresas_ids = [];
        foreach ($user_empresas as $key => $user_empresa) {
            $user_empresas_ids[] = $user_empresa->empresa_id;
        }
        return Inertia::render('User/Edit', compact('empresas', 'roles', 'rol', 'current_user', 'user_empresas_ids'));
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
            'password' => $request['password'] ?
                Hash::make($request['password']) : null,
        ]));

        //Eliminar Rol
        $current_role = $user->getRoleNames()[0];
        $user->removeRole($current_role);
        //Agregar Rol
        $user->assignRole($request->rol["name"]);

        //Eliminar Empresas
        foreach ($user->empresas as $key => $empresa) {
            $empresa->delete();
        }

        //Guardar empresas
        $empresas = $request['empresa'];
        if (!isset($empresas[0])) {
            $empresas = [$empresas];
        }

        foreach ($empresas as $key => $empresa) {
            $user_empresa = new UserEmpresa;
            $user_empresa->user_id = $user->id;
            $user_empresa->empresa_id = $empresa["id"];
            $user_empresa->save();
        }

        return redirect()->route('users.index');
    }

    public function destroy(User $user): RedirectResponse
    {
        /* abort_if(!auth()->user()->admin, 403); */

        //Eliminar Empresas
        foreach ($user->empresas as $key => $empresa) {
            $empresa->delete();
        }

        $user->delete();
        return redirect()->back();
    }

    public function getEmpresas(Request $request)
    {
        $user = User::find($request->user_id);
        $empresas_ids = $user->empresas->pluck('empresa_id')->toArray();
        $empresas = Empresa::whereIn('id', $empresas_ids)->get();
        return $empresas;
    }

    /*     public function getPermissions(){
        foreach (Auth::user()->getAllPermissions() as $permission) {
            $permissions[] = $permission->name;
        }
        return $permissions;
    } */
}
