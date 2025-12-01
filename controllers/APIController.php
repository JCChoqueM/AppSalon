<?php

namespace Controllers;

use Model\Cita;
use Model\Servicio;
use Model\CitaServicio;

class APIController
{
  public static function index()
  {
    $servicios = Servicio::all();

    echo json_encode($servicios);
  }
  public static function guardar()
  {

    //Almacenar la Cita y devolvemos el ID
    $cita = new Cita($_POST);
    $resultado = $cita->guardar();
    $id = $resultado['id'];

    //Almacena los Servicios con el ID de la Cita
    $idServicios = explode(",", $_POST['servicios']);
    foreach ($idServicios as $idServicio) {
      $args = [
        'citas_id' => $id,
        'servicios_id' => $idServicio
      ];
      $citaServicio = new CitaServicio($args);
      $citaServicio->guardar();
    }
    //Retornamos una respuesta

    echo json_encode(['resultado' => $resultado]);
  }

  public static function eliminar()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $id = $_POST['id'];
      $cita = Cita::find($id);
      $cita->eliminar();
      header('Location:' . $_SERVER['HTTP_REFERER']);
    }
  }
}
