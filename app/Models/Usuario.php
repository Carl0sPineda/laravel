<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;
    protected $table = 'usuario';

    protected $fillable = [
        'id',
        'nombre',
        'apellidos',
        'rol',
        'email',
        'contrasenia',
        'fechaRegistro'
    ];

    //un usuario puede tener varios telefenos 
    public function telefonos(){
        return $this->hasMany('App\Models\Telefonos');
    }
    //un usuario puede tener varias direcciones 
    public function direcciones(){
        return $this->hasMany('App\Models\Direcciones');
    }
    //un usuario puede tener varios metodos de pago 
    public function metodospago(){
        return $this->hasMany('App\Models\MetodosPago');
    }
    
    //un usuario puede tener varias ordenes de compra
    public function ordencompra(){
        return $this->hasMany('App\Models\OrdenCompra');

    }
}
