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


    }

    public static function crear(Router $router){
        if($_SERVER['REQUEST_METHOD']==='POST'){

        }
        //render a la vista
        $router->render('auth/crear',[
            'titulo' => 'Crea tu cuenta en UpTask'
        ]);
    }

    public static function olvide(){

        
        if($_SERVER['REQUEST_METHOD']==='POST'){

        }
    }
    public static function restablecer(){

        
        if($_SERVER['REQUEST_METHOD']==='POST'){

        }
    }
    public static function mensaje(){

        
    }
    public static function confirmar(){

        
    }
}