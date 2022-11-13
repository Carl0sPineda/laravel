<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenCompra extends Model
{
    use HasFactory;
    protected $table = 'ordencompra';

    protected $fillable = [
        'id',
        'idUsuario',
        'idEmpleado',
        'subtotal',
        'descuento',
        'total',
        'fechaEnvio'
        
    ];

    //orden compra tiene un usuario
    public function usuario(){
        return $this->belongsTo('App\Models\Usuario','idUsuario');
    }

    //orden compra tiene un empleado
    public function empleado(){
        return $this->belongsTo('App\Models\Empleado','idEmpleado');
    }

    //orden compra puede tener muchos detallesOrden
    public function detalleorden(){
        return $this->hasMany('App\Models\DetalleOrden');
    }

    //orden compra puede tener muchos seguimientosCompra

    public function seguimientocompra(){
        return $this->hasMany('App\Models\SeguimientoCompra');
    }


    
}
