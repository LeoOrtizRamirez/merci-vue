<?php

namespace App\Datatables;

use App\Models\User;

class UserDatatable extends Datatable
{
    protected string $model = User::class;

    protected array $with = [];

    protected function map(): callable
    {
        return function (User $user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'estado' => $user?->estado?->name,
                'empresa' => $user?->empresa?->name,
                'backgroundColor' => $user?->estado?->backgroundColor,
                'admin' => $user->admin,
            ];
        };
    }
}
