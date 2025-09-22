<?php
define('TEMPLATES_URL', __DIR__ . '/templates');
define('FUNCIONES_URL', __DIR__ . '/funciones.php');
define('CARPETA_IMAGENES', $_SERVER["DOCUMENT_ROOT"] . '/imagenes/');
function incluirTemplate(string $nombre, bool $inicio = false)
{
    /* echo (TEMPLATES_URL . "/$nombre.php");  */
    include TEMPLATES_URL . "/$nombre.php";
    // define('TEMPLATES_URL','/templates');   
    // include "includes/temaplates/$nombre.php";
}
function estaAutenticado()
{
    session_start();

    if (!$_SESSION['login']) {
        header('Location: /');
    }
}
/* function estaAutenticado(): bool
{
     session_start();
    $auth = $_SESSION['login'] ?? false;
    if ($auth) {
        return true;
    }
    return false;
} */
function debuguear($variable): string
{
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

//Escapa /sanitizar el HTML
function s($html): string
{
    $s = htmlspecialchars($html);
    return $s;
}

//validar tipo de contenido
function validarTipoContenido($tipo)
{
    $tipos = ['vendedor', 'propiedad'];
    return in_array($tipo, $tipos);
}

//Muestra los mensajes
function mostrarNotificacion($codigo)
{
    switch ($codigo) {
        case 1:
            return [
                'mensaje' => 'Creado Correctamente',
                'valor' => ''
            ];
        case 2:
            return [
                'mensaje' => 'Actualizado Correctamente',
                'valor' => 'amarillo'
            ];
        case 3:
            return [
                'mensaje' => 'Eliminado Correctamente',
                'valor' => 'rojo'
            ];
        case 4:
            return [
                'mensaje' => 'Mensaje Enviado correctamente',
                'valor' => 'verde'
            ];
        case 5:
            return [
                'mensaje' => 'Error al enviar el mensaje',
                'valor' => 'rojo'
            ];
        default:
            return false;
    }
}

function validarORedireccionar(string $url)
{
    $id = $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);
    if (!$id) {
        header("Location: $url");
    }
    return $id;
}
