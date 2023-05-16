<?php

namespace Controllers;

use MVC\Router;
use Model\Proyecto;

class DashboardController
{
    public static function index(Router $router)
    {

        //mantiene la session
        session_start();

        //Pretegiendo el dashboard
        isAuth();

        //Renderizar la vista Index
        $router->render('dashboard/index', [
            'titulo' => 'Proyectos'
        ]);
    }

    public static function crear_proyecto(Router $router)
    {

        //mantiene la session
        session_start();

        //Pretegiendo el dashboard
        isAuth();

        //Alertas
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //debuguear('Submit');
            $proyecto = new Proyecto($_POST);

            //debuguear($proyecto);
            $alertas = $proyecto->validarProyecto();

            if (empty($alertas)) {
                //Generar una URL unica
                $hash = md5(uniqid());
                $proyecto->url = $hash;

                //Almacenar el creador del proyecto
                $proyecto->propietarioid = $_SESSION['id'];

                //Guardar proyecto
                $proyecto->guardar();

                //Redireccionar
                header('Location: /proyecto?id=' . $proyecto->url);
            }
        }
        //Renderizar la vista Index
        $router->render('dashboard/crear-proyecto', [
            'alertas' => $alertas,
            'titulo' => 'Crear Proyecto'
        ]);
    }

    public static function proyecto(Router $router){

        $router->render('dashboard/proyecto')
    }



    public static function perfil(Router $router)
    {

        //mantiene la session
        session_start();

        //Pretegiendo el dashboard
        //isAuth();

        //Renderizar la vista Index
        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil'
        ]);
    }
}
