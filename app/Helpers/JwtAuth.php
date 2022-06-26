<?php
namespace App\Helpers;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\Usuario;
use Illuminate\Http\Request;


class JwtAuth{
    private $key;
    function __construct()
    {
        $this->key='12Sawsfe2saWaaasawSQ';
    }
    /**
     * Este metodo devuelve un Token en caso de que el usuario y contraseña sean correctas
     * o devuelve un arreglo con un mensaje en caso de que la autenticación sea incorrecta
     */
    public function getToken($email,$contrasenia){
        $usuario=Usuario::where(['email'=>$email,'contrasenia'=>hash('sha256',$contrasenia)])->first();
        if(is_object($usuario)){
            $token=array(
                'sub'=>$usuario->id,
                'nombre'=>$usuario->nombre,
                'apellidos'=>$usuario->apellidos,
                'role'=>$usuario->role,
                'email'=>$usuario->email,
                'image'=>$usuario->image,
                'iat'=>time(),
                'exp'=>time()+(1800)
            );

            $data=JWT::encode($token,$this->key,'HS256');
        }else{
            $data=array(
                'status'=>'error',
                'code'=>401,
                'message'=>'Datos de autenticación incorrectos'
            );
        }
        return $data;
    }
    /**Se debe crear un arreglo de tipo Key de JWT para enviarlo al decode */
    
    public function checkToken($jwt,$getIdentity=false){
        $auth=false;
    
        if(isset($jwt)){
            try{
                $decoded=JWT::decode($jwt,new Key($this->key,'HS256'));
                
            }catch(\DomainException $ex){
                $auth=false;
            }catch(\UnexpectedValueException $ex){
                 $auth=false;
            }catch(\ExpiredException $ex){
                $auth=false;
            }
            
            if(!empty($decoded)&&is_object($decoded)&&isset($decoded->sub)){
                $auth=true;
            }

            if($getIdentity&&$auth){

                return $decoded;
            }
        }

        return $auth;
      
      
    }

}
