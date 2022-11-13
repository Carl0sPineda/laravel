<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeguimientoCompra extends Model
{
    use HasFactory;
    protected $table = 'seguimientocompra';

    protected $fillable = [
        'id',
        'idOrdenCompra',
        'fechaEntrega',
        'numeroGuia',
        'estado'
    ];


    //relacion con orden compra
    public function ordencompra(){
        return $this->belongsTo('App\Models\OrdenCompra','idOrdenCompra');
    }


}
