<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Usuario;

class UsuarioController extends Controller
{
    public function __construct() {
        
    }

    public function __invoke() {}

    public function index(){
        $data=Usuario::all();
        $response=array(
            'status'=>'success',
            'code'=>200,
            'data'=>$data
        );
        return response()->json($response,200);
    }

    public function show($id){
        $usuario=Usuario::find($id);
        if(is_object($usuario)){
            $response=array(
                'status'=>'success',
                'code'=>200,
                'data'=>$usuario
            );
        }else{
            $response=array(
                'status'=>'error',
                'code'=>404,
                'message'=>'Usuario no encontrado'
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
            'nombre'=>'required',
            'apellidos'=>'required',
            'rol'=>'required',
            'email'=>'required|email|unique:usuario',
            'contrasenia'=>'required',
            'fechaRegistro'=>'required'
            
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
            $usuario=new Usuario();
            $usuario->id=$data['id'];
            $usuario->nombre=$data['nombre'];
            $usuario->apellidos=$data['apellidos'];
            $usuario->rol=$data['rol'];
            $usuario->email=$data['email'];
            $usuario->contrasenia=hash('sha256',$data['contrasenia']);
            $usuario->fechaRegistro=$data['fechaRegistro'];
            $usuario->save();
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
            'nombre'=>'required',
            'apellidos'=>'required',
            'rol'=>'required',
            'email'=>'required|email',
            'contrasenia'=>'required',
            'fechaRegistro'=>'required'
            
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
            
            $updated=Usuario::where('id',$id)->update($data);
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
                    'message'=>'No se pudo actualizar el usuario, puede ser que no exista'
                );
            }
        }
   
        return response()->json($response,$response['code']);
    }

    public function destroy($id){
        if(isset($id)){
            $deleted = Usuario::where('id',$id)->delete();
            if($deleted){
                $response=array(
                    'status'=>'success',
                    'code'=>200,
                    'message'=>'Usuario eliminado correctamente'
                );
            }else{
                $response=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'No se pudo eliminar el recurso'
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
