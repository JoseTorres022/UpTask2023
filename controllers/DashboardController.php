<?php

namespace Controllers;

use MVC\Router;
use Model\Proyecto;
use Model\Usuario;

class DashboardController
{
    public static function index(Router $router)
    {


        //mantiene la session
        session_start();

        //Pretegiendo el dashboard
        isAuth();

        $id = $_SESSION['id'];

        $proyectos = Proyecto::belongsTo('propietarioid', $id);

        //Renderizar la vista Index
        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
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

    public static function proyecto(Router $router)
    {
        session_start();
        isAuth();

        $token = $_GET['id'];
        if (!$token) header('Location: /dashboard');

        //Revisar si la persona es quien lo creo
        $proyecto = Proyecto::where('url', $token);
        if ($proyecto->propietarioid !== $_SESSION['id']) {
            header('Location: /dashboard');
        }

        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto
        ]);
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
