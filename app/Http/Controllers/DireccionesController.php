<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Direcciones;
use Illuminate\Support\Facades\DB;

class DireccionesController extends Controller
{
    public function __construct() {
        
    }

    public function __invoke() {}

    public function index(){
        $data = DB::select('EXECUTE pa_all_direcciones'); 
        $response=array(
            'status'=>'success',
            'code'=>200,
            'data'=>$data
        );
        return response()->json($response,200);
    }

    public function show($id){
        $data=DB::select('EXECUTE pa_direcciones_id ?', array($id));
            $data=$data->load('usuario');
            $response=array(
                'status'=>'success',
                'code'=>200,
                'data'=>$data
            );
        // }else{
        //     $response=array(
        //         'status'=>'error',
        //         'code'=>404,
        //         'message'=>'Direccion no encontrada'
        //     );
        // }
        return response()->json($response,$response['code']);
    }

    public function store(Request $request){
        $json=$request->input('json',null);
        $data=json_decode($json,true);
        $data=array_map('trim',$data);
        $rules=[
           // 'id'=>'',
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
            $response = DB::INSERT(
                'EXECUTE pa_create_direccion ?,?,?,?,?',
            array(
               // $data['id'],
                $data['idUsuario'],
                $data['provincia'],
                $data['canton'],
                $data['distrito'],
                $data['otrasSenias']
            ));
            
            // $direcciones=new Direcciones();
           // $direcciones->id=$data['id'];
            // $direcciones->idUsuario=$data['idUsuario'];
            // $direcciones->provincia=$data['provincia'];
            // $direcciones->canton=$data['canton'];
            // $direcciones->distrito=$data['distrito'];
            // $direcciones->otrasSenias=$data['otrasSenias'];
            // $direcciones->save();

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
            'id'=>'',
            //'idUsuario'=>'required',
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
            
            $updated = DB::UPDATE(
                'exec pa_update_direccion  ?,?,?,?,?',
                array(
                    $id,
                    $data['provincia'],
                    $data['canton'],
                    $data['distrito'],
                    $data['otrasSenias'],
                )
            );
            // $updated=Direcciones::where('id',$id)->update($data);
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
            $deleted = DB::delete('EXECUTE pa_delete_direccion ?',array($id));
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
