<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;
    protected $table = 'categoria';

    protected $fillable = [
        'id',
        'nombre',
        'descripcion'
    ];

    //una categoria va a tener muchos productos
    public function producto(){
        return $this->hasMany('App\Models\Productos');
    }
}
