<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Telefonos extends Model
{
    use HasFactory;
    protected $table = 'telefonos';

    protected $fillable = [
        'id',
        'idUsuario',
        'numTelefono',

    ];

    //Relacion un usuario puede tener uno o varios telefonos
    public function usuario(){
        return $this->belongsTo('App\Models\Usuario','idUsuario');
    }
}
