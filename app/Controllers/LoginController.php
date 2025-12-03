<?php
namespace App\Controllers;
use App\Models\UsuarioModel;
use App\Models\Usuarios\Usuario;
class LoginController extends Controller{
    
    public function sanitizaDatos(string $datos):string{
        $datos=$datos = trim($datos);
        $datos = htmlspecialchars($datos);
        return $datos;
    }
    public function showLogin(){
        return $this->view('login');
    }
    public function login(){
        if (isset($_POST['submit']) && $_SERVER['REQUEST_METHOD']=='POST'){
            if(!empty($_POST['usuario']) && !empty($_POST['contrasenia'])){
                $errores=[];
                $usuario=$this->sanitizaDatos($_POST['usuario']);
                $contrasenia=$this->sanitizaDatos($_POST['contrasenia']);
                $usuarioModel=new UsuarioModel();
                $usuario_valido=null;
                $usuario_encontrado=$usuarioModel->select('*')->where('usuario', $usuario)->get();
                if($usuario_encontrado){
                    foreach($usuario_encontrado as $us){
                        if(password_verify($contrasenia,$us->getContrasenia())){
                            $usuario_valido=$us;
                            break;
                        }
                    }
                    if ($usuario_valido){
                        session_start();
                        /*guardo en $_SESSION['id'] el id del usuario para poder 
                        verificar el usuario posteriormente cuando se realicen cambios en su nombre o rol
                        */
                        $_SESSION['id']=$usuario_valido->getId();
                        $_SESSION['usuario']=$usuario_valido->getUsuario();
                        $_SESSION['rol']=$usuario_valido->getRol();
                    }else{
                        $errores[]="Contraseña incorrecta";
                    }
                }else{
                    $errores[]="No hay un usuario registrado con ese nombre";   
                }
            }else{
                $errores[]="Debe rellenar todos los campos";
            }
            if(empty($errores)){
                return $this->redirect('/usuario/'.$usuario_valido->getUsuario());  
            }else{
                return $this->view('login', ['errores'=>$errores]);
            }
        }
    }
    public function logout(){    
        $_SESSION = [];               
        session_destroy();            
        return $this->redirect('/login');   
    }   
}
