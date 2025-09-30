<?php

namespace Controllers;

use MVC\Router;
use Model\Usuario;
use Classes\Email;


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
    public static function olvide(Router $router)
    {
        $router->render('auth/olvide-password');
    }
    public static function recuperar()
    {
        echo "Hola desde recuperar";
    }

    public static function crear(Router $router)
    {
        $usuario = new Usuario;
        //Alertas vacias
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario->sincronizar($_POST);

            $alertas = $usuario->validarNuevaCuenta();
            //Revisar que alertas este vacio
            if (empty($alertas)) {
                //verificar que el usuario no este registrado
                $resultado = $usuario->existeUsuario();
                if ($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else {
                    //Hashear el password
                    $usuario->hashPassword();
                    //generar token unico
                    $usuario->crearToken();
                    //Enviar el email
                   $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                   $email->enviarConfirmacion();

                   //Crear el usuario
                   $resultado = $usuario->guardar();
                   
                
                   if ($resultado) {
                  
                        header('Location: /mensaje'); 
                   }
                }
            }
        }
        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas

        ]);
        
    }
    public static function mensaje(Router $router)
    {
        $router->render('auth/mensaje');
    }
}
