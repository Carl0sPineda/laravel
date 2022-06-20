<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\SeguimientoCompra;

class SeguimientoCompraController extends Controller
{
    public function __construct() {
        
    }

    public function __invoke() {}

    public function index(){
        $data=SeguimientoCompra::all()->load('ordencompra');
        $response=array(
            'status'=>'success',
            'code'=>200,
            'data'=>$data
        );
        return response()->json($response,200);
    }

    public function show($id){
        $data=SeguimientoCompra::find($id);
        if(is_object($data)){
            $data=$data->load('ordencompra');
            $response=array(
                'status'=>'success',
                'code'=>200,
                'data'=>$data
            );
        }else{
            $response=array(
                'status'=>'error',
                'code'=>404,
                'message'=>'Seguimiento de Compra no encontrado'
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
            'idOrdenCompra'=>'required',
            'fechaEnvio'=>'required',
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

            $seguimientoCompra=new SeguimientoCompra();
            $seguimientoCompra->id=$data['id'];
            $seguimientoCompra->idOrdenCompra=$data['idOrdenCompra'];
            $seguimientoCompra->fechaEnvio=$data['fechaEnvio'];
            $seguimientoCompra->fechaEntrega=$data['fechaEntrega'];
            $seguimientoCompra->numeroGuia=$data['numeroGuia'];
            $seguimientoCompra->estado=$data['estado'];
            $seguimientoCompra->save();

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
            'idOrdenCompra'=>'required',
            'fechaEnvio'=>'required',
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
            
            $updated=SeguimientoCompra::where('id',$id)->update($data);
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
            $deleted = SeguimientoCompra::where('id',$id)->delete();
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
