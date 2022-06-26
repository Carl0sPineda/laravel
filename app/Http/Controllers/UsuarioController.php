<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Usuario;
use App\Helpers\JwtAuth;

class UsuarioController extends Controller
{
    public function __construct() {
        $this->middleware('api.auth',['except'=>['index','store','login','show','getImage','uploadImage']]);
    }

    public function __invoke() {}

    public function index(){
        $data=Usuario::all();
        $response=array(
            'status'=>'success',
            'code'=>200,
            'data'=>$data
        );
        return response()->json($response,200);
    }

    public function show($id){
        $usuario=Usuario::find($id);
        if(is_object($usuario)){
            $response=array(
                'status'=>'success',
                'code'=>200,
                'data'=>$usuario
            );
        }else{
            $response=array(
                'status'=>'error',
                'code'=>404,
                'message'=>'Usuario no encontrado'
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
            'nombre'=>'required',
            'apellidos'=>'required',
            'role'=>'required',
            'email'=>'required|email|unique:usuario',
            'contrasenia'=>'required',
            'image'=>''
            
            
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
                $user=$jwtAuth->checkToken($token,true);

            $usuario=new Usuario();
            $usuario->id=$data['id'];
            $usuario->nombre=$data['nombre'];
            $usuario->apellidos=$data['apellidos'];
            $usuario->role=$data['role'];
            $usuario->email=$data['email'];
            $usuario->contrasenia=hash('sha256',$data['contrasenia']);
            $usuario->image=$data['image'];
            $usuario->save();
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
            'nombre'=>'required',
            'apellidos'=>'required',
            'role'=>'required',
            'email'=>'required|email',
            'contrasenia'=>'required',
            'image'=>''
            
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
            
            $updated=Usuario::where('id',$id)->update($data);
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
                    'message'=>'No se pudo actualizar el usuario, puede ser que no exista'
                );
            }
        }
   
        return response()->json($response,$response['code']);
    }

    public function destroy($id){
        if(isset($id)){
            $deleted = Usuario::where('id',$id)->delete();
            if($deleted){
                $response=array(
                    'status'=>'success',
                    'code'=>200,
                    'message'=>'Usuario eliminado correctamente'
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

    
    public function login(Request $request){
        $jwtAuth=new JwtAuth();
        $json=$request->input('json',null);
        $data=json_decode($json,true);
        $data=array_map('trim',$data);
        $rules=['email'=>'required|email','contrasenia'=>'required'];
        $valid=\validator($data,$rules);
        if($valid->fails()){
            $response=array(
                'status'=>'error',
                'code'=>406,
                'message'=>'Los datos enviados son incorrectos',
                'errors'=>$valid->errors()
            );
            return response()->json($response,406);
        }else{
            $response=$jwtAuth->getToken($data['email'],$data['contrasenia']);
            return response()->json($response);
        }

    }

    
    public function getIdentity(Request $request){
       $jwtAuth=new JwtAuth();
        $token=$request->header('token');
        if(isset($token)){

            $response=$jwtAuth->checkToken($token,true);

        }else{
            $response=array(
                'status'=>'error',
                'code'=>406,
                'message'=>'Token no enviado'
            );
        }
        return response()->json($response);

       

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
            \Storage::disk('usuarios')->put($filename,\File::get($image));
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
        $exist=\Storage::disk('usuarios')->exists($filename);
        if($exist){
            $file=\Storage::disk('usuarios')->get($filename);
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
