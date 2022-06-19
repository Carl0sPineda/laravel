<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Direcciones;


class DireccionesController extends Controller
{
    public function __construct() {
        
    }

    public function __invoke() {}

    public function index(){
        $data=Direcciones::all()->load('usuario');
        $response=array(
            'status'=>'success',
            'code'=>200,
            'data'=>$data
        );
        return response()->json($response,200);
    }

    public function show($id){
        $data=Direcciones::find($id);
        if(is_object($data)){
            $data=$data->load('usuario');
            $response=array(
                'status'=>'success',
                'code'=>200,
                'data'=>$data
            );
        }else{
            $response=array(
                'status'=>'error',
                'code'=>404,
                'message'=>'Direccion no encontrada'
            );
        }
        return response()->json($response,$response['code']);
    }

    public function store(Request $request){
        $json=$request->input('json',null);
        $data=json_decode($json,true);
        $data=array_map('trim',$data);
        $rules=[
            'id'=>'required',
            'idUsuario'=>'required',
            'provincia'=>'required',
            'canton'=>'required',
            'distrito'=>'required',
            'otrasSenias'=>'',
            
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
            
            $direcciones=new Direcciones();
            $direcciones->id=$data['id'];
            $direcciones->idUsuario=$data['idUsuario'];
            $direcciones->provincia=$data['provincia'];
            $direcciones->canton=$data['canton'];
            $direcciones->distrito=$data['distrito'];
            $direcciones->otrasSenias=$data['otrasSenias'];
            $direcciones->save();

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
            'idUsuario'=>'required',
            'provincia'=>'required',
            'canton'=>'required',
            'distrito'=>'required',
            'otrasSenias'
            
            
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
            
            $updated=Direcciones::where('id',$id)->update($data);
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
            $deleted = Direcciones::where('id',$id)->delete();
            if($deleted){
                $response=array(
                    'status'=>'success',
                    'code'=>200,
                    'message'=>'Direccion eliminado correctamente'
                );
            }else{
                $response=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'No se pudo eliminar la direccion'
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
