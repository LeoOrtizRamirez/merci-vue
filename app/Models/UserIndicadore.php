<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserIndicadore extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function indicador()
    {
        return $this->belongsTo(Indicadore::class);
    }

    public function datos()
    {
        return $this->hasMany(UsersIndicadoresDato::class);
    }
}
