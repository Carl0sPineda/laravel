<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direcciones extends Model
{
    use HasFactory;
    protected $table = 'direcciones';

    protected $fillable = [
        'id',
        'idUsuario',
        'provincia',
        'canton',
        'distrito',
        'otrasSenias'

    ];

    //Relacion un usuario tiene una o varias direcciones
    public function usuario(){
        return $this->belongsTo('App\Models\Usuario','idUsuario');
    }
}
