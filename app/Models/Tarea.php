<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    use HasFactory;

    public function actividad(){
        return $this->belongsTo('App\Models\Actividade');
    }

    public function estado(){
        return $this->belongsTo('App\Models\Estado');
    }
}
