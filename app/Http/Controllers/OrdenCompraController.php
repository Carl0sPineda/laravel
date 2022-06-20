<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\OrdenCompra;

class OrdenCompraController extends Controller
{
    public function __construct() {}

    public function index(){

        $data=OrdenCompra::all()->load("usuario","empleado");
        $response=array(
            'status' => 'success',
            'code' => '200',
            'data'=> $data
        );

        return response()->json($response,200);
    }

    public function show($id){
        $data=OrdenCompra::find($id);
        if(is_object($data)){
            $data=$data->load("usuario","empleado");
            $response=array(
                'status'=>'success',
                'code'=>200,
                'data'=>$data
            );
        }else{
            $response=array(
                'status'=>'error',
                'code'=>404,
                'message'=>'Ingreso de vehiculo no encontrada'
            );
        }
        return response()->json($response,$response['code']);
    }

    public function store(Request $request){
        $json=$request->input('json',null);
        $data=json_decode($json,true);
        $data=array_map('trim',$data);
        $rules=[
            'idUsuario'=>'required',
            'idEmpleado'=>'required',
            'fechaOrden'=>'required',
            'subTotal'=>'required',
            'impuesto'=>'required',
            'descuento'=>'required',
            'total'=>'required', 
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
            $ordenCompra = new OrdenCompra();
            $ordenCompra->idUsuario=$data['idUsuario'];
            $ordenCompra->idEmpleado=$data['idEmpleado'];
            $ordenCompra->fechaOrden=$data['fechaOrden'];
            $ordenCompra->subTotal=$data['subTotal'];
            $ordenCompra->impuesto=$data['impuesto'];
            $ordenCompra->descuento=$data['descuento'];
            $ordenCompra->total=$data['total'];
            $ordenCompra->save();
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
            $updated=OrdenCompra::where('id',$id)->update($data);
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
                    'message'=>'No se pudo actualizar la Orden de Compra'
                );
            }
        }
   
        return response()->json($response,$response['code']);
    }

    public function destroy($id){
        if(isset($id)){
            $deleted = OrdenCompra::where('id',$id)->delete();
            if($deleted){
                $response=array(
                    'status'=>'success',
                    'code'=>200,
                    'message'=>'Orden de compra eliminada correctamente'
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
