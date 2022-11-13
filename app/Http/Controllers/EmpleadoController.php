<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Empleado;
use Illuminate\Support\Facades\DB;

class EmpleadoController extends Controller
{

    public function __construct() {
        
    }

    public function __invoke() {}

    public function index(){
        $data = DB::select('EXECUTE pa_all_empleado'); 
        $response=array(
            'status'=>'success',
            'code'=>200,
            'data'=>$data
        );
        return response()->json($response,200);
    }

    public function show($id){
        $categoria=DB::select('EXECUTE pa_empleado_id ?', array($id));
            $response=array(
                'status'=>'success',
                'code'=>200,
                'data'=>$empleado
            );
        // }else{
        //     $response=array(
        //         'status'=>'error',
        //         'code'=>404,
        //         'message'=>'Empleado no encontrado'
        //     );
        // }
        return response()->json($response,$response['code']);
    }

    public function store(Request $request){
        $json=$request->input('json',null);
        $data=json_decode($json,true);
        $data=array_map('trim',$data);
        $rules=[
            'id'=>'',
            'nombre'=>'required',
            'apellidos'=>'required',
            'email'=>'required|email|unique:empleado',
            'telefono'=>'required'
            
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
                'EXECUTE pa_create_empleado ?,?,?,?,?',
            array(
                $data['id'],
                $data['nombre'],
                $data['apellidos'],
                $data['email'],
                $data['telefono']
            ));
    
            // $empleado=new Empleado();
            // $empleado->id=$data['id'];
            // $empleado->nombre=$data['nombre'];
            // $empleado->apellidos=$data['apellidos'];
            // $empleado->email=$data['email'];
            // $empleado->telefono=$data['telefono'];
            // $empleado->save();
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
            'nombre'=>'',
            'apellidos'=>'',
            'email'=>'',
            'telefono'=>''
            
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
            
            $updated = DB::UPDATE(
                'exec pa_update_empleado  ?,?,?,?,?',
                array(
                    $id,
                    $data['nombre'],
                    $data['apellidos'],
                    $data['email'],
                    $data['telefono']
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
                    'message'=>'No se pudo actualizar el empleado, puede ser que no exista'
                );
            }
        }
   
        return response()->json($response,$response['code']);
    }

    public function destroy($id){
        if(isset($id)){
            $deleted = DB::delete('EXECUTE  pa_delete_empleado ?',array($id));
            if($deleted){
                $response=array(
                    'status'=>'success',
                    'code'=>200,
                    'message'=>'Empleado eliminado correctamente'
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
