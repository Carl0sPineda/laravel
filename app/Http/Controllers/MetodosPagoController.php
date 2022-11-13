<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\MetodosPago;
use Illuminate\Support\Facades\DB;

class MetodosPagoController extends Controller
{
    public function __construct() {
        
    }

    public function __invoke() {}

    public function index(){
        $data = DB::select('EXECUTE pa_all_metodosPago'); 
        $response=array(
            'status'=>'success',
            'code'=>200,
            'data'=>$data
        );
        return response()->json($response,200);
    }

    public function show($id){
        $data=DB::select('EXECUTE pa_metodosPago_id ?', array($id));
            $response=array(
                'status'=>'success',
                'code'=>200,
                'data'=>$data
            );
        // }else{
        //     $response=array(
        //         'status'=>'error',
        //         'code'=>404,
        //         'message'=>'Metodo de pago no encontrado'
        //     );
        // }
        return response()->json($response,$response['code']);
    }

    public function store(Request $request){
        $json=$request->input('json',null);
        $data=json_decode($json,true);
        $data=array_map('trim',$data);
        $rules=[
            //'id'=>'',
            'idUsuario'=>'required',
            'tipoTarjeta'=>'required',
            'numTarjeta'=>'required',
            'saldoFijo'=>'required'
            
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
                'EXECUTE pa_create_metodosPago ?,?,?,?',
            array(
                //$data['id'],
                $data['idUsuario'],
                $data['tipoTarjeta'],
                $data['numTarjeta'],
                $data['saldoFijo']
            ));
    
            // $metodospago=new MetodosPago();
            //  $metodospago->id=$data['id'];
            // $metodospago->idUsuario=$data['idUsuario'];
            // $metodospago->tipoTarjeta=$data['tipoTarjeta'];
            // $metodospago->numTarjeta=$data['numTarjeta'];
            // $metodospago->saldoFijo=$data['saldoFijo'];
            // $metodospago->save();

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
            //'idUsuario'=>'required',
            'tipoTarjeta'=>'required',
            'numTarjeta'=>'required' 
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
                'exec pa_update_metodosPago ?,?,?,?',
                array(
                    $id,
                    $data['tipoTarjeta'],
                    $data['numTarjeta'],
                    $data['saldoFijo']
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
                    'message'=>'No se pudo actualizar el metodo de pago del usuario, puede ser que no exista'
                );
            }
        }
   
        return response()->json($response,$response['code']);
    }

    public function destroy($id){
        if(isset($id)){
            $deleted = DB::delete('EXECUTE pa_delete_metodosPago ?',array($id));
            if($deleted){
                $response=array(
                    'status'=>'success',
                    'code'=>200,
                    'message'=>'Metodo de pago eliminado correctamente'
                );
            }else{
                $response=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'No se pudo eliminar el metodo de pago'
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
