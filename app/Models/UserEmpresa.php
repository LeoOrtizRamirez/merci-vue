<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEmpresa extends Model
{
    use HasFactory;

    public function user() {
        return $this->hasMany(User::class);
    }

    public function empresa() {
        return $this->hasMany(Empresa::class);
    }
}
