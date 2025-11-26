<?php
// /php/controllers/BodegaController.php

require_once __DIR__ . '/../db.php'; 
require_once __DIR__ . '/../models/BodegaModel.php';

class BodegaController {
    private $model;

    public function __construct() {
        $database = new Database();
        $db_conn = $database->connect();
        $this->model = new BodegaModel($db_conn);
    }

    public function listarDatos() {

        // Obtener la lista de bodegas
        $bodegas = $this->model->obtenerTodasBodegas();
        
        // Obtener la lista de encargados 
        $encargados = $this->model->obtenerEncargadosParaSeleccion();

        // Retornar todos los datos a la vista que se va a renderizar
        return [
            'bodegas' => $bodegas,
            'encargados' => $encargados
        ];
    }
    
}
?>