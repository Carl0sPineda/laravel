<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Usuario;
use App\Helpers\JwtAuth;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    public function __construct() {
        //$this->middleware('api.auth',['except'=>['index','store','login', 'update','show','getImage','uploadImage']]);
    }

    public function __invoke() {}

    public function index(){
        $data = DB::select('EXECUTE pa_all_usuario'); 
        $response=array(
            'status'=>'success',
            'code'=>200,
            'data'=>$data
        );
        return response()->json($response,200);
    }

    public function show($id){
        $usuario=DB::select('EXECUTE pa_usuario_id ?', array($id));
        
            $response=array(
                'status'=>'success',
                'code'=>200,
                'data'=>$usuario
            );
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
           // 'email'=>'required',
            'contrasenia'=>'required',
            'image'
            
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
            $password = hash('sha256',$data['contrasenia']);
            $response = DB::INSERT(
                'EXECUTE pa_create_usuario ?,?,?,?,?,?,?',
            array(
                $data['id'],
                $data['nombre'],
                $data['apellidos'],
                $data['role'],
                $data['email'],
               // $data['contrasenia'],
               $password,
                $data['image']));

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
            // 'role'=>'required',
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
            
           /* $jwtAuth=new JwtAuth();
            $token=$request->header('token',null);
            $usuario=$jwtAuth->checkToken($token,true);*/
        
            $id=$data['id'];

            $updated = DB::UPDATE(
                'EXECUTE pa_update_usuario ?,?,?,?,?,?',
                array(
                    $data['id'],
                    $data['nombre'],
                    $data['apellidos'],
                    $data['email'],
                    $data['contrasenia'],
                    $data['image']

                )
            );
            if($updated<1){
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
            $deleted = DB::delete('EXECUTE pa_delete_usuario ?',array($id));
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

    public function upload(Request $request){
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
            $image_name=time().$image->getClientOriginalName();
            \Storage::disk('usuarios')->put($image_name,\File::get($image));
            $response=array(
                'status'=>'success',
                'code'=>200,
                'message'=>'Imagen guardada correctamente',
                'image'=>$image_name
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
