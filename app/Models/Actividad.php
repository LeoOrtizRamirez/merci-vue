<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    use HasFactory;

    protected $table = "actividades";

    public function tareas(){
        return $this->hasMany('App\Models\Tarea');
    }

    public function categoria(){
        return $this->belongsTo('App\Models\Categoria');
    }
}
