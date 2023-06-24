<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleHasPermission extends Model
{
    use HasFactory;

    public function permission(){
        return $this->belongsTo('Spatie\Permission\Models\Permission');
    }

    public function role(){
        return $this->belongsTo('Spatie\Permission\Models\Role');
    }
}
