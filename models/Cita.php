<?php

namespace Model;

class Cita extends ActiveRecord {
//Base de datos

    protected static $tabla = 'citas';
    protected static $columnasDB = ['id', 'fecha', 'hora', 'usuarios_id'];

    public $id;
    public $fecha;
    public $hora;
    public $usuarios_id;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->fecha = $args['fecha'] ?? '';
        $this->hora = $args['hora'] ?? '';
        $this->usuarios_id = $args['usuarios_id'] ?? '';
    }

}
