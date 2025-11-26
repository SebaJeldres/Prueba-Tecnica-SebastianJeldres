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

    public function crear(array $post_data): bool {
        // Validar que los campos esenciales no estén vacíos
        if (empty($post_data['nombre']) || empty($post_data['direccion']) || empty($post_data['rut_encargado'])) {
            return false;
        }

        // Llamar al Modelo para realizar la insercion
        return $this->model->crearBodega($post_data);
    }

    public function gestionarPeticion() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Intentar crear
            $resultado = $this->crear($_POST);
            
            // Redirigir despues de POST 
            $status = $resultado ? 'success' : 'error';
            header("Location: index.php?status={$status}");
            exit; // Detine el script después de la redireccion
        }
        
        // Si no es POST, ejecuta el listado normal (GET)
        return $this->listarDatos();
    }
    
}
?>