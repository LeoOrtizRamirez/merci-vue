<?php

namespace App\Actions\Users;

use App\DTOs\UserDTO;
use App\Models\User;

class UpdateUser
{
    public function execute(User $user, UserDTO $data): User
    {
        $user->name = $data->name;
        $user->email = $data->email;
        $user->empresa_id = $data->empresa_id;
        if ($data->password) {
            $user->password = $data->password;
        }
        $user->save();
        return $user;
    }
}
