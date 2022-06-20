<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Productos;

class ProductosController extends Controller
{
    public function __construct() {
        
    }

    public function __invoke() {}

    public function index(){
        $data=Productos::all()->load('categoria');
        $response=array(
            'status'=>'success',
            'code'=>200,
            'data'=>$data
        );
        return response()->json($response,200);
    }

    public function show($id){
        $data=Productos::find($id);
        if(is_object($data)){
            $data=$data->load('categoria');
            $response=array(
                'status'=>'success',
                'code'=>200,
                'data'=>$data
            );
        }else{
            $response=array(
                'status'=>'error',
                'code'=>404,
                'message'=>'Producto no encontrado'
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
            'idCatgoria'=>'required',
            'descripcion'=>'required',
            'stock'=>'required',
            'precio'=>'required'
            //imagen
            
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

            $productos=new Productos();
            $productos->id=$data['id'];
            $productos->idCatgoria=$data['idCatgoria'];
            $productos->descripcion=$data['descripcion'];
            $productos->stock=$data['stock'];
            $productos->precio=$data['precio'];
            $productos->save();

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
            'precio'=>'required'
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
            
            $updated=Productos::where('id',$id)->update($data);
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
            $deleted = Productos::where('id',$id)->delete();
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

    public function uploadImage(Request $request){
        $image=$request->file('file0');
        $valid=\Validator::make($request->all(),[
            'file0'=>'required|image|mimes:jpg,png'
        ]);
        if(!$image||$valid->fails()){
            $response=array(
                'status'=>'error',
                'code'=>406,
                'message'=>'Error al subir el archivo',
                'errors'=>$valid->errors()
            );
        }else{
            $filename=time().$image->getClientOriginalName();
            \Storage::disk('')->put($filename,\File::get($image));
            $response=array(
                'status'=>'success',
                'code'=>200,
                'message'=>'Imagen guardada correctamente',
                'image_name'=>$filename
            );
        }
        return response()->json($response,$response['code']);
    }
    public function getImage($filename){
        $exist=\Storage::disk('products')->exists($filename);
        if($exist){
            $file=\Storage::disk('products')->get($filename);
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
