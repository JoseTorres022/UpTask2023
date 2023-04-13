<?php
namespace Controllers;

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

        $usuario=new Usuario;
        
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $usuario->sincronizar($_POST);
        }
        //render a la vista
        $router->render('auth/crear',[
            'titulo' => 'Crea tu cuenta en UpTask',
            'usuario'=>$usuario
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