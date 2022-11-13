<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
// use App\Models\Categoria;
use Illuminate\Support\Facades\DB;

class CategoriaController extends Controller
{
    public function __construct() {
        //$this->middleware('api.auth',['except'=>['store','login','show','getImage']]);
}


public function __invoke() {}


public function index(){
    $data = DB::select('EXECUTE pa_all_categoria'); 
    $response=array(
        'status'=>'success',
        'code'=>200,
        'data'=>$data
    );
    return response()->json($response,200);
}

public function show($id){
    $categoria=DB::select('EXECUTE pa_categoria_id ?', array($id));
        $response=array(
            'status'=>'success',
            'code'=>200,
            'data'=>$categoria
        );
    return response()->json($response,$response['code']);
}

public function store(Request $request){
    $json=$request->input('json',null);
    $data=json_decode($json,true);
    $data=array_map('trim',$data);
    $rules=[
       // 'id'=>'',
        'descripcion'=>'required',
    ];
    $valid=\validator($data,$rules);
    if($valid->fails()){
        $response=array(
            'status'=>'error',
            'code'=>406,
            'message'=>'Datos enviados no cumplen con las reglas establecidas',
            'errors'=>$valid->errors()
        );
    
    }else{
        $response = DB::INSERT(
            'EXECUTE pa_create_categoria ?',
        array(
           // $data['id'],
            $data['descripcion']));

        $response=array(
            'status'=>'success',
            'code'=>200,
            'message'=>'Datos almacenados satisfactoriamente'
        );
    }
    return response()->json($response,$response['code']);
}

public function update(Request $request){
    $json=$request->input('json',null);
    $data=json_decode($json,true);

    $data=array_map('trim',$data);
    $rules=[
        'id'=>'required',
        'descripcion'=>'required',
        
        
    ];
    $valid=\validator($data,$rules);
    if($valid->fails()){
        $response=array(
            'status'=>'error',
            'code'=>406,
            'message'=>'Datos enviados no cumplen con las reglas establecidas',
            'errors'=>$valid->errors()
        );
    }else{
        $id = $data['id'];

        $updated = DB::UPDATE(
            'exec pa_update_categoria ?, ?',
            array(
                $id,
                $data['descripcion'],
            )
        );
        if($updated>0){
            $response=array(
                'status'=>'success',
                'code'=>200,
                'message'=>'Datos actualizados satisfactoriamente'
            );
        }else{
            $response=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'No se pudo actualizar la categoria, puede ser que no exista'
            );
        }
    }

    return response()->json($response,$response['code']);
}

public function destroy($id){
    if(isset($id)){
        $deleted = DB::delete('EXECUTE pa_delete_categoria ?',array($id));
        if($deleted){
            $response=array(
                'status'=>'success',
                'code'=>200,
                'message'=>'Categoria eliminada correctamente'
            );
        }else{
            $response=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'No se pudo eliminar la categoria'
            );
        }
    }else{
        $response=array(
            'status'=>'error',
            'code'=>400,
            'message'=>'Falta el identificador del recurso a eliminar'
        );
    }
    return response()->json($response,$response['code']);
}
}
