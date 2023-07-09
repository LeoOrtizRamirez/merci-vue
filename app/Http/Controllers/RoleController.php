<?php

namespace App\Http\Controllers;

use App\Datatables\RoleDatatable;
use App\Datatables\RoleHasPermissionsDatatable;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\RoleHasPermission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;
use Inertia\Inertia;

class RoleController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Rol/Index');
    }

    public function datatable(Request $request, RoleDatatable $datatable): JsonResponse
    {
        $data = $datatable->make($request);
        return response()->json($data);
    }

    public function rolePermissionsDatatable(Request $request, RoleHasPermissionsDatatable $datatable): JsonResponse
    {
        $data = $datatable->make($request);
        return response()->json($data);
    }

    public function create(): Response
    {
        return Inertia::render('Rol/Create');
    }

    public function store(Request $request)
    {
        $role = new Role;
        $role->name = $request->name;
        $role->guard_name = "web";
        $role->save();
        return redirect()->route('roles.index');
    }

    public function show(Role $role)
    {
        $role_has_permissions = RoleHasPermission::where('role_id', $role->id)->get()->pluck('permission_id')->toArray();
        return Inertia::render('Rol/Show', compact('role', 'role_has_permissions'));
    }

    public function edit(Role $role)
    {
        $role_has_permissions = RoleHasPermission::where('role_id', $role->id)->get()->pluck('permission_id')->toArray();
        return Inertia::render('Rol/Edit', compact('role', 'role_has_permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $role = Role::find($role->id);
        $role->name = $request->name;
        $role->save();
        return redirect()->route('roles.index');
    }

    public function destroy(Role $role)
    {
        $role->syncPermissions([]);
        $role->delete();
        return redirect()->back();
    }

    public function rolePermissionsToggle(Request $request)
    {
        $role = Role::find($request->role_id);
        $permission = Permission::find($request->permission_id);
        if($role->hasPermissionTo($permission->name)) {
            //dd("si");
            $role->revokePermissionTo($permission);
        }else{
            $role->givePermissionTo($permission);
        }
        $permissions = $role->permissions->pluck('id')->toArray();
        return $permissions;
    }
}
