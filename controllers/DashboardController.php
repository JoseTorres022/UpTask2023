<?php
namespace Controllers;

use MVC\Router;

class DashboardController{
    public static function index(Router $router){


        //Renderizar la vista Index
        $router->render('dashboard/index',[

        ]);
    }
}