<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productos extends Model
{
    use HasFactory;
    protected $table = 'producto';

    protected $fillable = [
        'id',
        'idCatgoria',
        'nombre',
        'descripcion',
        'stock',
        'precio',
        'image'

    ];

    //Relacion uno a muchos con categoria 
    public function categoria(){
        return $this->belongsTo('App\Models\Categoria','idCatgoria');
    }

    //un producto puede tener muchos detallesOrden

    public function detalleorden(){
        return $this->hasMany('App\Models\DetalleOrden');
    }
    

}
