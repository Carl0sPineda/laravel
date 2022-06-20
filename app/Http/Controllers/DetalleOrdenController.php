<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\DetalleOrden;

class DetalleOrdenController extends Controller
{
    public function __construct() {}

    public function index(){

        $data=DetalleOrden::all()->load("ordencompra","producto");
        $response=array(
            'status' => 'success',
            'code' => '200',
            'data'=> $data
        );

        return response()->json($response,200);
    }

    public function show($id){
        $data=DetalleOrden::find($id);
        if(is_object($data)){
            $data=$data->load("ordencompra","producto");
            $response=array(
                'status'=>'success',
                'code'=>200,
                'data'=>$data
            );
        }else{
            $response=array(
                'status'=>'error',
                'code'=>404,
                'message'=>'Detalle de orden no encontrado'
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
            'idProducto'=>'required',
            'cantidad'=>'required',
            'costoUnidad'=>'required',
            'descuento'=>'required',
            'subtotal'=>'required',
             
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
            $detalleorden=new DetalleOrden();
            $detalleorden->id=$data['id'];
            $detalleorden->idOrdenCompra=$data['idOrdenCompra'];
            $detalleorden->idProducto=$data['idProducto'];
            $detalleorden->cantidad=$data['cantidad'];
            $detalleorden->costoUnidad=$data['costoUnidad'];
            $detalleorden->descuento=$data['descuento'];
            $detalleorden->subtotal=$data['subtotal'];
            $detalleorden->save();
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
            'idProducto'=>'required',
            'cantidad'=>'required',
            'costoUnidad'=>'required',
            'descuento'=>'required',
            'subtotal'=>'required',

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
            $updated=DetalleOrden::where('id',$id)->update($data);
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
                    'message'=>'No se pudo actualizar el detalle de la orden'
                );
            }
        }
   
        return response()->json($response,$response['code']);
    }

    public function destroy($id){
        if(isset($id)){
            $deleted = DetalleOrden::where('id',$id)->delete();
            if($deleted){
                $response=array(
                    'status'=>'success',
                    'code'=>200,
                    'message'=>'Detalle de orden eliminado correctamente'
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