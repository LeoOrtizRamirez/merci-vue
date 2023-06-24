<?php

namespace App\Actions\Users;

use App\DTOs\UserDTO;
use App\Models\User;

class CreateUser
{
    public function execute(UserDTO $data): User
    {
        $user = new User();
        $user->name = $data->name;
        $user->email = $data->email;
        $user->password = $data->password;
        $user->estado_id = $data->estado_id;
        $user->save();

        return $user;
    }
}
