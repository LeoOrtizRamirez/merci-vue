<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\StoreUserPost;
use App\Http\Requests\UpdateUserPut;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return Inertia::render('User/Index', compact('users'));
    }
    
    public function datatable(
        Request       $request,
        UserDatatable $datatable
    ): JsonResponse
    {
        $data = $datatable->make($request);

        return response()->json($data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('User/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserPost $request)
    {
        $user = User::create($request->validated());
        $user->assignRole('admin');
        return Redirect::route('user.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $person)
    {
        return Inertia::render('User/Show', compact('person'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $person)
    {
        return Inertia::render('User/Edit', compact('person'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserPut $request, User $person)
    {
        $person->update($request->validated());
        $users = User::all();
        return Inertia::render('User/Index', compact('users'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $person)
    {
        $person->delete();
        return Redirect::route('user.index');
    }

    public function getPermissions(){
        foreach (Auth::user()->getAllPermissions() as $permission) {
            $permissions[] = $permission->name;
        }
        return $permissions;
    }
}
