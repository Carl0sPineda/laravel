<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleOrden extends Model
{
    use HasFactory;
    protected $table = 'detalleorden';

    protected $fillable = [
        'id',
        'idOrdenCompra',
        'idProducto',
        'cantidad',
        'costoUnidad',
        'descuento',
        'subtotal',


    ];


    //relacion con orden compra
    public function ordencompra(){
        return $this->belongsTo('App\Models\OrdenCompra','idOrdenCompra');
    }

    //relacion con producto
    public function producto(){
        return $this->belongsTo('App\Models\Productos','idProducto');
    } 
}
