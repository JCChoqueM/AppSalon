<?php

namespace Controllers;

use MVC\Router;
use Model\Usuario;
use Classes\Email;


class LoginController
{
    public static function login(Router $router)
    {
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();

            if (empty($alertas)) {
                //comprobar que exista el usuario
                $usuario = Usuario::where('email', $auth->email);

                if ($usuario) {
                    //verificar el password
                    if ($usuario->comprobarPasswordAndVerificado($auth->password)) {
                        //autenticar el usuario
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        //Redireccionar al usuario

                        if ($usuario->admin === "1") {
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        } else {
                            header('Location: /cita');
                        }
                    }
                } else {
                    Usuario::setAlerta('error', 'El Usuario no Existe...');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/login', [
            'alertas' => $alertas,

        ]);
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
    public static function confirmar(Router $router)
    {
        $alertas = [];
        $token = s($_GET['token']);
        $usuario = Usuario::where('token', $token);


        if (empty($usuario)) {
            //Mostrar mensaje de error

            Usuario::setAlerta('error', 'Token no Valido');
        } else {
            //Modificar a usuario confirmado
            $usuario->confirmado = "1";
            $usuario->token = null;
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta Comprobada Correctamente');
        }

        //Obtener alertas
        $alertas = Usuario::getAlertas();

        //Render a la vista
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}
