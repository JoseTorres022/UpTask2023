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
            //BLOQUE DE RACTORIZACION DEL CODIGO
            //ESPERO QUE FUNCIONES XD :3 JAJAJJA
            $alertas = $usuario->validarNuevaCuenta();
            if(empty($alertas)){
                $existeUsuario=Usuario::where('email',$usuario->email);

            if($existeUsuario){
                Usuario::setAlerta('error','El Usuario ya esta registrado');
                $alertas=Usuario::getAlertas();
            }else{
                //Hashear el password
                $usuario->hashPassword();

                //Eliminar password2
                unset($usuario->password2);

                //Generar el token para el usuario
                $usuario->crearToken();


                //Crear un nuevo usuario
                $resultado =$usuario->guardar();

                //Enviar email al usuario
                $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                $email->enviarConfirmacion();
                

                //Redirigir al usaurio a la vista de confirmacion de su registro
                if($resultado){
                    header('Location: /mensaje');
                }
            }
            }




            //BLOQUE DE VALIDACION DEL USUARIO
            //FUNCIONA CORRECTAMENTE PERO, MARCA USUAIRO REGRISTADO PREVIAMENTE
            //Validar usaurios
            /*if(empty($alertas)){
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
            }*///if else primario
            

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
    $token =s($_GET['token']);
    
    //Mostrar token del usuario en la vista
    //debuguear($token);

    //Si no encuentra token, manda a la pagina principal.
    if(!$token) header('Location: /');

    //Encontrar al usuairo con este token:
    $usuario=Usuario::where('token',$token);

    if(empty($usuario)){
            //Si esta vacio este usuario/token
        Usuario::setAlerta('error','Token no valido :(');
    }else{
        //Confirmar la cuenta
        $usuario->confirmado=1;
        $usuario->token=null;
        unset($usuario->password2);

        //Guardar en la BD
        $usuario->guardar();

        Usuario::setAlerta('exito','Cuenta confirmada correntamente :3');

    }

    $alertas=Usuario::getAlertas();

        //Muestra la vista
        $router->render('auth/confirmar',[
        'titulo'=>'Confirma tu cuenta UpTask',
        'alertas'=>$alertas
        ]);        
    }
}