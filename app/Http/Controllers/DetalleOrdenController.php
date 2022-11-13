<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\DetalleOrden;
use App\Models\Productos;
use App\Helpers\JwtAuth;
use Illuminate\Support\Facades\DB;

class DetalleOrdenController extends Controller
{
    public function __construct() {}

    public function index(){

        $data = DB::select('EXECUTE pa_all_detalleOrden'); 
        $response=array(
            'status' => 'success',
            'code' => '200',
            'data'=> $data
        );

        return response()->json($response,200);
    }
    
    public function show($id){
        $data=DB::select('EXECUTE pa_detalleOrden_id ?', array($id));
            $response=array(
                'status'=>'success',
                'code'=>200,
                'data'=>$data
            );
        // }else{
        //     $response=array(
        //         'status'=>'error',
        //         'code'=>404,
        //         'message'=>'Detalle de orden no encontrado'
        //     );
        // }
        return response()->json($response,$response['code']);
    }

    public function showByOrden($id){
        $data=DB::select('EXECUTE pa_detalleOrden_idOrden ?', array($id));
            $response=array(
                'status'=>'success',
                'code'=>200,
                'data'=>$data
            );
        // }else{
        //     $response=array(
        //         'status'=>'error',
        //         'code'=>404,
        //         'message'=>'Detalle de orden no encontrado'
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
            'idOrdenCompra'=>'required',
            'idProducto'=>'required',
            'cantidad'=>'required',
            'costoUnidad'=>'required',
            'descuento'=>'required',
            'subtotal'=>'required'
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
            $response = DB::INSERT(
                'EXECUTE pa_create_detalleOrden ?,?,?,?,?,?',
            array(
                //$data['id'],
                $data['idOrdenCompra'],
                $data['idProducto'],
                $data['cantidad'],
                $data['costoUnidad'],
                $data['descuento'],
                $data['subtotal']
            ));
            // $detalleorden=new DetalleOrden();
            //$detalleorden->id=$data['id'];
            // $detalleorden->idOrdenCompra=$data['idOrdenCompra'];
            // $detalleorden->idProducto=$data['idProducto'];
            // $detalleorden->cantidad=$data['cantidad'];
            // $detalleorden->costoUnidad=$data['costoUnidad'];
            // $detalleorden->descuento=$data['descuento'];
            // $detalleorden->subtotal=$data['subtotal'];
            // $detalleorden->save();
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

            $updated = DB::UPDATE(
                'exec pa_update_detalleOrden ?,?,?,?,?,?,?',
                array(
                    $id,
                    $data['idOrdenCompra'],
                    $data['idProducto'],
                    $data['cantidad'],
                    $data['costoUnidad'],
                    $data['descuento'],
                    $data['subtotal']
                )
            );            if($updated>0){
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
            $deleted = DB::delete('EXECUTE pa_delete_detalleOrden ?',array($id));
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