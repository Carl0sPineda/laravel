<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;
    protected $table = 'empleado';

    protected $fillable = [
        'id',
        'nombre',
        'apellidos',
        'email',
        'telefono'

    ];

    //un empleado puede tener varias orden de compra
    public function ordencompra(){
        return $this->hasMany('App\Models\OrdenCompra');
    }
}
