<?php

class BodegaModel {
    private $db; 

    public function __construct($db_connection) {
        $this->db = $db_connection;
    }

    public function obtenerTodasBodegas(string $filtro_estado = null): array {
    $sql = "SELECT 
                b.bodega_id, 
                b.nombre AS nombre_bodega, 
                b.direccion, 
                b.codigo_identificador,
                b.fecha_creacion,
                b.dotacion,
                b.estado,
                STRING_AGG(e.nombre || ' ' || e.apellido_paterno || ' ' || e.apellido_materno, ', ') AS nombre_encargado
            FROM 
                bodega b
            LEFT JOIN 
                bodega_encargado be ON b.bodega_id = be.bodega_id
            LEFT JOIN 
                encargado e ON be.encargado_id = e.run";
            
    $params = [];
    
    if ($filtro_estado) {
        $sql .= " WHERE b.estado = :estado";
        $params[':estado'] = $filtro_estado;
    }
            
    $sql .= " GROUP BY
                b.bodega_id, b.nombre, b.direccion, b.codigo_identificador, b.fecha_creacion, b.dotacion, b.estado
            ORDER BY 
                b.nombre ASC";
    
    $stmt = $this->db->prepare($sql);
    $stmt->execute($params); 
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    

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
        // Iniciar la transacción
        $this->db->beginTransaction();

            $sql_bodega = "INSERT INTO bodega 
                (codigo_identificador, nombre, direccion, dotacion, estado, fecha_creacion) 
            VALUES 
                (:codigo_identificador, :nombre, :direccion, :dotacion, :estado, :fecha_creacion)
            RETURNING bodega_id";

            $params_bodega = [
                ':codigo_identificador' => $datos['codigo_identificador'],
                ':nombre'               => $datos['nombre'],
                ':direccion'            => $datos['direccion'],
                ':dotacion'             => $datos['dotacion'], 
                ':estado'               => $datos['estado'] ?? 'Activada', 
                ':fecha_creacion'       => date('Y-m-d H:i:s')
            ];

            $stmt_bodega = $this->db->prepare($sql_bodega);
            $stmt_bodega->execute($params_bodega);
            
            // Obtener el ID generado por la secuencia
            $nueva_bodega_id = $stmt_bodega->fetchColumn(); 
            
            // Aseguramos que el rut_encargado sea tratado como un array
            $run_a_asignar = is_array($datos['rut_encargado']) ? $datos['rut_encargado'] : [$datos['rut_encargado']];

            $sql_relacion = "INSERT INTO bodega_encargado (bodega_id, encargado_id) 
                             VALUES (:bodega_id, :encargado_id)";
            $stmt_relacion = $this->db->prepare($sql_relacion);
            
            foreach ($run_a_asignar as $run) {
                $stmt_relacion->execute([
                    ':bodega_id'   => $nueva_bodega_id, 
                    ':encargado_id' => $run 
                ]);
            }

            // Confirmar la transacción
            $this->db->commit();
            return true;

    }

    public function obtenerBodegaPorId(int $id_bodega): array {
        // 1. Obtener datos de la Bodega principal
        $sql_bodega = "SELECT * FROM bodega WHERE bodega_id = :id";
        $stmt_bodega = $this->db->prepare($sql_bodega);
        $stmt_bodega->execute([':id' => $id_bodega]);
        $bodega = $stmt_bodega->fetch(PDO::FETCH_ASSOC);

        if (!$bodega) {
            return [];
        }

        $sql_encargados = "SELECT encargado_id FROM bodega_encargado WHERE bodega_id = :id";
        $stmt_encargados = $this->db->prepare($sql_encargados);
        $stmt_encargados->execute([':id' => $id_bodega]);
        
        // Retorna solo los valores Run
        $encargados_asignados = $stmt_encargados->fetchAll(PDO::FETCH_COLUMN, 0); 

        $bodega['encargados_asignados'] = $encargados_asignados;

        return $bodega;
    }

    public function actualizarBodega(array $datos): bool {
    $this->db->beginTransaction();

        $sql_bodega = "UPDATE bodega SET 
                           codigo_identificador = :codigo_identificador,
                           nombre = :nombre,
                           direccion = :direccion,
                           dotacion = :dotacion,
                           estado = :estado
                       WHERE bodega_id = :bodega_id";
        
        $params_bodega = [
            ':codigo_identificador' => $datos['codigo_identificador'],
            ':nombre'               => $datos['nombre'],
            ':direccion'            => $datos['direccion'],
            ':dotacion'             => $datos['dotacion'],
            ':estado'               => $datos['estado'],
            ':bodega_id'            => $datos['bodega_id'] 
        ];

        $stmt_bodega = $this->db->prepare($sql_bodega);
        $stmt_bodega->execute($params_bodega);

        // Gestionar relaciones
        
        //Elimiar todas las asignaciones anteriores 
        $sql_delete_relaciones = "DELETE FROM bodega_encargado WHERE bodega_id = :bodega_id";
        $stmt_delete = $this->db->prepare($sql_delete_relaciones);
        $stmt_delete->execute([':bodega_id' => $datos['bodega_id']]);

        // Insertar las nuevas asignaciones
        $run_a_asignar = is_array($datos['rut_encargado']) ? $datos['rut_encargado'] : [$datos['rut_encargado']];
        
        // El formulario debe enviar al menos un encargado
        if (!empty($run_a_asignar) && !in_array("", $run_a_asignar)) {
            $sql_insert_relacion = "INSERT INTO bodega_encargado (bodega_id, encargado_id) 
                                    VALUES (:bodega_id, :encargado_id)";
            $stmt_insert = $this->db->prepare($sql_insert_relacion);
            
            foreach ($run_a_asignar as $run) {
                $stmt_insert->execute([
                    ':bodega_id'    => $datos['bodega_id'], 
                    ':encargado_id' => $run
                ]);
            }
        }
        
        $this->db->commit();
        return true;

    }

    public function eliminarBodega(int $id_bodega): bool {
    // Las restricciones ON DELETE CASCADE se encargarán de borrar las relaciones en bodega_encargado
    $sql = "DELETE FROM bodega WHERE bodega_id = :id";
    $stmt = $this->db->prepare($sql);
    
        return $stmt->execute([':id' => $id_bodega]);
}
}
?>