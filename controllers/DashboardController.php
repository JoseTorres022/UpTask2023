<?php
namespace Controllers;

use MVC\Router;

class DashboardController{
    public static function index(Router $router){

        //mantiene la session
        session_start();

        //Pretegiendo el dashboard
        isAuth();

        //Renderizar la vista Index
        $router->render('dashboard/index',[
            'titulo'=>'Proyectos'
        ]);
    }

    public static function crear_proyecto(Router $router){

        //mantiene la session
        session_start();

        //Pretegiendo el dashboard
        isAuth();

        //Alertas
        $alertas=[];

        //Renderizar la vista Index
        $router->render('dashboard/crear-proyecto',[
            'alertas'=>$alertas,
            'titulo'=>'Crear Proyecto'
        ]);
    }

    public static function perfil(Router $router){

        //mantiene la session
        session_start();

        //Pretegiendo el dashboard
        //isAuth();

        //Renderizar la vista Index
        $router->render('dashboard/perfil',[
            'titulo'=>'Perfil'
        ]);
    }
}