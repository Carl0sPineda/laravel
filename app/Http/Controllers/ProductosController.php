<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Productos;
use App\Models\Categoria;
use App\Helpers\JwtAuth;
use Illuminate\Support\Facades\DB;

class ProductosController extends Controller
{
    public function __construct() {
     // $this->middleware('api.auth',['except'=>['index','store','login','show','getImage']]);
    }

    public function __invoke() {}

    public function index(){
        $data = DB::select('EXECUTE pa_all_producto'); 
        $response=array(
            'status'=>'success',
            'code'=>200,
            'data'=>$data
        );
        return response()->json($response,200);
    }

    public function show($id){
        $data=DB::select('EXECUTE pa_producto_id ?', array($id));
        //$categoria=$data->load('categoria');
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
            'idCatgoria'=>'required',
            'descripcion'=>'required',
            'stock'=>'required',
            'precio'=>'required',
            'imagen'=>'required'
            
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
               /* $jwtAuth=new JwtAuth();
                $token=$request->header('token',null);
                $usuario=$jwtAuth->checkToken($token,true);*/

                $response = DB::INSERT(
                    'EXECUTE pa_create_producto ?,?,?,?,?',
                array(
                    //$data['id'],
                    $data['idCatgoria'],
                    $data['descripcion'],
                    $data['stock'],
                    $data['precio'],
                    $data['imagen'],
                ));
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
            'idCatgoria'=>'required',
            'descripcion'=>'required',
            'stock'=>'required',
            'precio'=>'required',
            'imagen'=>''
           
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
                'exec pa_update_categoria ?,?,?,?,?,?',
                array(
                    $id,
                    $data['idCatgoria'],
                    $data['descripcion'],
                    $data['stock'],
                    $data['precio'],
                    $data['imagen']
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
                    'message'=>'No se pudo actualizar product, puede ser que no exista'
                );
            }
        }
   
        return response()->json($response,$response['code']);
    }

    public function destroy($id){
        if(isset($id)){
            $deleted = DB::delete('EXECUTE  pa_delete_producto ?',array($id));
            if($deleted){
                $response=array(
                    'status'=>'success',
                    'code'=>200,
                    'message'=>'Producto eliminado correctamente'
                );
            }else{
                $response=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'No se pudo eliminar el Producto'
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

    public function upload(Request $request){
        $imagen=$request->file('file0');
        $valid=\Validator::make($request->all(),[
            'file0'=>'required|image|mimes:jpg,png'
            
        ]);
        if(!$imagen||$valid->fails()){
            $response=array(
                'status'=>'error',
                'code'=>406,
                'message'=>'Error al subir el archivo img',
                'errors'=>$valid->errors()
            );
        }else{
            $imagen_name=time().$imagen->getClientOriginalName();
            \Storage::disk('productos')->put($imagen_name,\File::get($imagen));
            $response=array(
                'status'=>'success',
                'code'=>200,
                'message'=>'Imagen guardada correctamente',
                'imagen'=>$imagen_name
            );
        }
        return response()->json($response,$response['code']);
    }
    public function getImage($filename){
        $exist=\Storage::disk('productos')->exists($filename);
        if($exist){
            $file=\Storage::disk('productos')->get($filename);
            return new Response($file,200);
        }else{
            $response=array(
                'status'=>'error',
                'code'=>404,
                'message'=>'Imagen no existe'
                
                
            );
            return response()->json($response,404);
        }
    }
}
