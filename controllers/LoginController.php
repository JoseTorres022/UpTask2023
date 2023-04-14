<?php
namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;
class LoginController{
    public static function login(Router $router){


        if($_SERVER['REQUEST_METHOD']==='POST'){
            
        }
        //render a la vista
        $router->render('auth/login',[
            'titulo' => 'Iniciar Sesión'
        ]);
       }
    public static function logout(){
        echo "Desde Login";

    }

    public static function crear(Router $router){
        $alertas=[];
        $usuario=new Usuario;
        
        if($_SERVER['REQUEST_METHOD']==='POST'){
            //Todo lo que se escriba en el formulario se quede (excepto el password)
            $usuario->sincronizar($_POST);

            //Debuguear el usaurio
            //debuguear($usuario);

            //Alerta (tipo JSON), para la validacion de los datos del usuario
            $alertas = $usuario->validarNuevaCuenta();

            //Validar usaurios
            if(empty($alertas)){
                Usuario::setAlerta('error', 'El usuario ya esta registrado');
                $alertas=Usuario::getAlertas();
            }else{
                //Hash del usaurio
                $usuario->hashPassword();

                //Eliminar password2
                unset($usuario->password2);

                //Generar el token para el usuario
                $usuario->crearToken();
                
                //Confirmar usuario
                //$usuario->confirmado=0;
                //Debugueando el usuario en el navegador (tipo JSON)
                //debuguear($usuario);

                //Crear un nuevo usuario
                $resultado =$usuario->guardar();

                //Enviar email al usuario
                $email = new Email($usuario->email,$usuario->nombre,$usuario->token);

                debuguear($email);
                if($resultado){
                    header('Location: /mensaje');
                }              
            }
            

            //Se muestra en vista del navegador (tipo JSON)
            //debuguear($alertas);
        }
        //render a la vista
        $router->render('auth/crear',[
            'titulo' => 'Crea tu cuenta en UpTask',
            'usuario'=>$usuario,
            'alertas'=>$alertas
        ]);
    }

    public static function olvide(Router $router){
        if($_SERVER['REQUEST_METHOD']==='POST'){

        }
        //Muestra la vista
        $router->render('auth/olvide',[
            'titulo'=> 'Olvide mi Password'
        ]);
    }
    public static function reestablecer(Router $router){
        if($_SERVER['REQUEST_METHOD']==='POST'){

        }

        //Muestra la vista
        $router->render('auth/reestablecer',[
            'titulo'=>'Reestablecer Password'
        ]);
    }
    public static function mensaje(Router $router){
    
        //Muestra la vista
        $router->render('auth/mensaje',[
            'titulo'=>'Mensaje de Conformacion'
        ]);
    }
    public static function confirmar(Router $router){
        //Muestra la vista
        $router->render('auth/confirmar',[
        'titulo'=>'Cuenta Confirmada'
        ]);        
    }
}