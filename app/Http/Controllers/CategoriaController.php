<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    public function __construct() {
        //$this->middleware('api.auth',['except'=>['store','login','show','getImage']]);
}


public function __invoke() {}

public function index(){
    $data=Categoria::all();
    $response=array(
        'status'=>'success',
        'code'=>200,
        'data'=>$data
    );
    return response()->json($response,200);
}

public function show($id){
    $categoria=Categoria::find($id);
    if(is_object($categoria)){
        $response=array(
            'status'=>'success',
            'code'=>200,
            'data'=>$categoria
        );
    }else{
        $response=array(
            'status'=>'error',
            'code'=>404,
            'message'=>'Categoria no encontrada'
        );
    }
    return response()->json($response,$response['code']);
}

public function store(Request $request){
    $json=$request->input('json',null);
    $data=json_decode($json,true);
    $data=array_map('trim',$data);
    $rules=[
        'id'=>'',
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
        /*$jwtAuth=new JwtAuth();
        $token=$request->header('token',null);
        $usuario=$jwtAuth->checkToken($token,true);*/
        
        $categoria=new Categoria();
        $categoria->id=$data['id'];
        $categoria->descripcion=$data['descripcion'];
        $categoria->save();

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
        $id=$data['id'];
        
        $updated=Categoria::where('id',$id)->update($data);
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
                'message'=>'No se pudo actualizar la direccion del usuario, puede ser que no exista'
            );
        }
    }

    return response()->json($response,$response['code']);
}

public function destroy($id){
    if(isset($id)){
        $deleted = Categoria::where('id',$id)->delete();
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
