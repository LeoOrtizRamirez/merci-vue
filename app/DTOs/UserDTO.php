<?php

namespace App\DTOs;

use Spatie\DataTransferObject\DataTransferObject;

class UserDTO extends DataTransferObject
{
    public string $name;
    public string $email;
    public ?string $password;
    public int $estado_id;
    public int $empresa_id;
}
