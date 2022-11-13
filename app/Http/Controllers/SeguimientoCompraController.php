<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\SeguimientoCompra;
use Illuminate\Support\Facades\DB;

class SeguimientoCompraController extends Controller
{
    public function __construct() {
        
    }

    public function __invoke() {}
    
    public function index(){
        $data = DB::select('EXECUTE pa_all_seguimientoCompra'); 
        $response=array(
            'status'=>'success',
            'code'=>200,
            'data'=>$data
        );
        return response()->json($response,200);
    }

    public function show($id){
        $data=DB::select('EXECUTE pa_seguimientoCompra_id ?', array($id));
            $response=array(
                'status'=>'success',
                'code'=>200,
                'data'=>$data
            );

        return response()->json($response,$response['code']);
    }


    public function store(Request $request){
        $json=$request->input('json',null);
        $data=json_decode($json,true);
        $data=array_map('trim',$data);
        $rules=[
            //'id'=>'',
            'idOrdenCompra'=>'required',
            'fechaEntrega'=>'required',
            'numeroGuia'=>'required',
            'estado'=>'required'
            
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
                'EXECUTE pa_create_seguimientoCompra ?,?,?,?',
            array(
                //$data['id'],
                $data['idOrdenCompra'],
                $data['fechaEntrega'],
                $data['numeroGuia'],
                $data['estado']));

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
            //'idOrdenCompra'=>'required',
            'fechaEntrega'=>'required',
            'numeroGuia'=>'required',
            'estado'=>'required'
            
            
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
                'exec pa_update_seguimientoCompra ?,?,?,?',
                array(
                    $id,
                    //$data['idOrdenCompra'],
                    $data['fechaEntrega'],
                    $data['numeroGuia'],
                    $data['estado']
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
                    'message'=>'No se pudo actualizar el seguimiento de compra, puede ser que no exista'
                );
            }
        }
   
        return response()->json($response,$response['code']);
    }

    public function destroy($id){
        if(isset($id)){
            $deleted = DB::delete('EXECUTE pa_delete_seguimientoCompra ?',array($id));
            if($deleted){
                $response=array(
                    'status'=>'success',
                    'code'=>200,
                    'message'=>'Seguimiento de compra eliminado correctamente'
                );
            }else{
                $response=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'No se pudo eliminar el Seguimiento de compra'
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
