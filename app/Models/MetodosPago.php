<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodosPago extends Model
{
    use HasFactory;
    protected $table = 'metodospago';

    protected $fillable =[
        'id',
        'idUsuario',
        'tipoTarjeta',
        'numTarjeta'

    ];

    //Relacion Un usuario puede tener una o varios metodoa de pago

    public function usuario(){
        return $this->belongsTo('App\Models\Usuario','idUsuario');
    }

}
