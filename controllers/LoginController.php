<?php
namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;
class LoginController{
    public static function login(Router $router){
        $alertas=[];

        if($_SERVER['REQUEST_METHOD']==='POST'){
            $usuario = new Usuario($_POST);

            $alertas=$usuario->validarLogin();

            if(empty($alertas)){
                //Verificar que el usuario exista
                $usuario=Usuario::where('email', $usuario->email);

                if(!$usuario || !$usuario->confirmado){
                    Usuario::setAlerta('error','El usuario no existe o no esta confirmado');
                }else{
                    //El usuario existe
                    if(password_verify($_POST['password'], $usuario->password)){
                        //Iniciar la sesion del usuario
                        session_start();
                        $_SESSION['id']=$usuario->id;
                        $_SESSION['nombre']=$usuario->nombre;
                        $_SESSION['email']=$usuario->email;
                        $_SESSION['login']=true;

                        //Redireccionar
                        header('location: /dashboard');
                    }else{
                        Usuario::setAlerta('error','Password incorrecto');
                    }
                }
            }
        }
        $alertas=Usuario::getAlertas();
        //render a la vista
        $router->render('auth/login',[
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas
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

        $alertas=[];

        if($_SERVER['REQUEST_METHOD']==='POST'){
            $usuario=new Usuario($_POST);
            $alertas=$usuario->validarEmail();

            if(empty($alertas)){
                //Buscar el usuario
                $usuario=Usuario::where('email',$usuario->email);

                if($usuario && $usuario->confirmado){
                    //Generar un nuevo token
                    $usuario->crearToken();
                    unset($usuario->password2);

                    //Actualizar el usuario
                    $usuario->guardar();

                    //Enviar el email
                    $email=new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    //Imprimir la alerta
                    Usuario::setAlerta('exito','Hemos enviado las instrucciones a tu email');
                }else{
                    Usuario::setAlerta('error','EL Usuario no existe o no esta confirmado');
                }
            }
        }

        $alertas=Usuario::getAlertas();

        //Muestra la vista
        $router->render('auth/olvide',[
            'titulo'=> 'Olvide mi Password',
            'alertas'=>$alertas
        ]);
    }

    public static function reestablecer(Router $router){
        $token=s($_GET['token']);
        $mostrar=true;

        //Si no hay token, se reenvia a la pagina principal
        if(!$token) header('Localtion: /');

        //Identificar usuairo con este token
        $usuario=Usuario::where('token', $token);

        //Alerta al usuario
        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token No Valido');
            $mostrar=false;
        }
        
        if($_SERVER['REQUEST_METHOD']==='POST'){
            //Añadir el nuevo password
            $usuario->sincronizar($_POST);

            //Validar el password
            $alertas=$usuario->validarPassword();

            if(empty($alertas)){
                //Hashear el nuevo usuario
                $usuario->hashPassword();

                //Eliminar el token
                $usuario->token=null;
                
                //Guardar el usuario en la BD
                $resultado=$usuario->guardar();

                //Redireccionar
                if($resultado){
                    header('Location: /');
                }
            }
            //debuguear($usuario);
        }

        $alertas=Usuario::getAlertas();
        //Muestra la vista
        $router->render('auth/reestablecer',[
            'titulo'=>'Reestablecer Password',
            'alertas'=>$alertas,
            'mostrar'=>$mostrar
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