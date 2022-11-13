<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\OrdenCompra;
use App\Helpers\JwtAuth;
use Illuminate\Support\Facades\DB;

class OrdenCompraController extends Controller
{
    public function __construct() {}

    public function index(){

        $data=DB::select('EXECUTE pa_all_ordenCompra'); 
        $response=array(
            'status' => 'success',
            'code' => '200',
            'data'=> $data
        );

        return response()->json($response,200);
    }

   /* public function show($id){
        $data=DB::select('EXECUTE pa_ordenCompra_id ?', array($id));
            $response=array(
                'status'=>'success',
                'code'=>200,
                'data'=>$data
            );
        // }else{
        //     $response=array(
        //         'status'=>'error',
        //         'code'=>404,
        //         'message'=>'Ingreso de vehiculo no encontrada'
        //     );
        // }
        return response()->json($response,$response['code']);
    }*/

    
        public function showById($id){
        $data=DB::select('EXECUTE pa_ordenCompra_id ?', array($id));
            $response=array(
                'status'=>'success',
                'code'=>200,
                'data'=>$data
            );
        // }else{
        //     $response=array(
        //         'status'=>'error',
        //         'code'=>404,
        //         'message'=>'Ingreso de vehiculo no encontrada'
        //     );
        // }
        return response()->json($response,$response['code']);
    }

    public function show($id){
        $data=DB::select('EXECUTE pa_ordenCompra_Usuario ?', array($id));
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
           // 'id'=>'',
            'idUsuario'=>'required',
            'idEmpleado'=>'required',
            'subtotal'=>'required',
            'descuento'=>'required',
            'total'=>'required',
            'fechaEnvio'=>'required'
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
            $jwtAuth=new JwtAuth();
            $token=$request->header('token',null);
            $usuario=$jwtAuth->checkToken($token,true);


            $response = DB::INSERT(
                'EXECUTE pa_create_ordenCompra ?,?,?,?,?,?',
            array(
               // $data['id'],
                $data['idUsuario'],
                $data['idEmpleado'],
                $data['subtotal'],
                $data['descuento'],
                $data['total'],
                $data['fechaEnvio']));

            // $ordenCompra = new OrdenCompra();
            //$ordenCompra->id=$data['id'];
            // $ordenCompra->idUsuario=$usuario->sub;
            // $ordenCompra->idUsuario=$data['idUsuario'];
            // $ordenCompra->idEmpleado=$data['idEmpleado'];
            // $ordenCompra->save();

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
            'subtotal',
            'descuento',
            'total',
            'fechaEnvio'
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
                'exec pa_update_ordenCompra ?,?,?,?,?',
                array(
                    $data['id'],
                    $data['subtotal'],
                    $data['descuento'],
                    $data['total'],
                    $data['fechaEnvio']
                )
            );
                if($updated>1){
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
            $deleted = DB::delete('EXECUTE pa_delete_ordenCompra ?',array($id));
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
