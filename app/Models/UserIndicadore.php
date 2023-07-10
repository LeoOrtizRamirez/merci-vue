<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserIndicadore extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsToMany('App\User');
    }

    public function indicador()
    {
        return $this->belongsToMany('App\Indicadore');
    }
}
