<?php

class BodegaModel {
    private $db; 

    public function __construct($db_connection) {
        $this->db = $db_connection;
    }

    // Obtener Bodegas
    public function obtenerTodasBodegas() {
        $sql = "SELECT 
                    b.bodega_id, 
                    b.nombre AS nombre_bodega, 
                    b.direccion, 
                    b.codigo_identificador,
                    b.fecha_creacion,
                    b.dotacion,
                    b.estado,
                    e.nombre || ' ' || e.apellido_paterno || ' ' || e.apellido_materno AS nombre_encargado
                FROM bodega b
                LEFT JOIN encargado e ON b.rut_encargado = e.run
                ORDER BY b.nombre ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        // retorna un array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Obtener Encargados 
    public function obtenerEncargadosParaSeleccion() {
        $sql = "SELECT 
                    run, 
                    nombre || ' ' || apellido_paterno || ' ' || apellido_materno AS nombre_completo 
                FROM encargado 
                ORDER BY nombre_completo ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        // Retorna como un array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crearBodega(array $datos): bool {
    // Insrrcion de datos en sql
        $sql = "INSERT INTO bodega 
                    (codigo_identificador, nombre, direccion, dotacion, estado, fecha_creacion, rut_encargado) 
                VALUES 
                    (:codigo_identificador, :nombre, :direccion, :dotacion, :estado, :fecha_creacion, :rut_encargado)";
        
        // Preparamos los parámetros
        $params = [
            ':codigo_identificador' => $datos['codigo_identificador'],
            ':nombre'         => $datos['nombre'],
            ':direccion'      => $datos['direccion'],
            ':dotacion'       => $datos['dotacion'], 
            ':estado'         => $datos['estado'] ?? 'Activo', // Valor por defecto
            ':fecha_creacion' => date('Y-m-d H:i:s'),
            ':rut_encargado'  => $datos['rut_encargado']  
        ];

        
        $stmt = $this->db->prepare($sql);

        // execute() devuelve true o false
        return $stmt->execute($params); 
        
    }
}
?>