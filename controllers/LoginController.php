<?php

namespace Controllers;

use MVC\Router;

class LoginController
{
    public static function login(Router $router)
    {
        $router->render('auth/login');
    }
    public static function logout()
    {
        echo "Hola desde logout";
    }
    public static function olvide()
    {
        echo "Hola desde olvide";
    }
    public static function recuperar()
    {
        echo "Hola desde recuperar";
    }

    public static function crear(Router $router)
    {
        $router->render('auth/crear-cuenta');
    }
}
