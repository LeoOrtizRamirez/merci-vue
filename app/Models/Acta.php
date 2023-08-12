<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acta extends Model
{
    use HasFactory;

    public function tareas(){
        return $this->hasMany('App\Models\Tarea');
    }

    public function empresa(){
        return $this->belongsTo('App\Models\Empresa');
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
