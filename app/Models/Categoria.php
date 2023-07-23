<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    public function actividades()
    {
        return $this->hasMany('App\Models\Actividade');
    }

    public function hasRegistros($id)
    {
        $actividades = Actividade::where('categoria_id', $id)->get();
        if (sizeof($actividades) > 0) {
            return true;
        } else {
            return false;
        }
    }
}
