<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    /*Un prestamo tiene muchos pagos*/
    public function payments(){
        return $this->hasMany('App\Models\Payment');
    }

    public function status(){
        return $this->belongsTo('App\Models\Status');
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function customer(){
        return $this->belongsTo('App\Models\Customer');
    }
}
