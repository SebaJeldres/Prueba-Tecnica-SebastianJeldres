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

        $filtro = $_GET['filtro_estado'] ?? null;

        // Obtener la lista de bodegas
        $bodegas = $this->model->obtenerTodasBodegas($filtro);
        
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
        $status = null;
        $id = $_REQUEST['id'] ?? null; // Obtener ID de GET o POST

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Manejar Creación o Actualización
            
            if (isset($_POST['bodega_id']) && !empty($_POST['bodega_id'])) {
                // Si existe un ID en POST, es una ACTUALIZACIÓN
                $resultado = $this->actualizar($_POST);
                $status = $resultado ? 'update_success' : 'update_error';
            } else {
                // Si no existe ID en POST, es una CREACIÓN
                $resultado = $this->crear($_POST);
                $status = $resultado ? 'success' : 'error';
            }
            
            // Redirigir después de POST
            header("Location: index.php?status={$status}");
            exit; 
            
        } elseif (isset($_GET['accion'])) {
            // Manejar acciones GET (Eliminar)
            
            if ($_GET['accion'] === 'eliminar' && !empty($id)) {
                $resultado = $this->eliminar((int)$id);
                $status = $resultado ? 'delete_success' : 'delete_error';
                
                header("Location: index.php?status={$status}");
                exit; 
            }
            // Si la acción es 'editar', la lógica de carga está en index.php
        }
        
        // Si no es POST y no es una acción especial, ejecuta el listado normal (GET)
        return $this->listarDatos();
    }

    public function obtenerBodegaParaEditar(int $id) {
        return $this->model->obtenerBodegaPorId($id);
    }
    
    public function actualizar(array $post_data): bool {
        // La validación básica la realiza el Modelo, aquí solo llamamos al método
        return $this->model->actualizarBodega($post_data);
    }
    
    public function eliminar(int $id): bool {
        return $this->model->eliminarBodega($id);
    }


    
}
?>