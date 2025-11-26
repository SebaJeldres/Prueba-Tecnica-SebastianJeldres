<?php

class BodegaModel {
    private $db; 

    public function __construct($db_connection) {
        $this->db = $db_connection;
    }

    public function obtenerTodasBodegas() {
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
                encargado e ON be.encargado_id = e.run
            GROUP BY
                b.bodega_id, b.nombre, b.direccion, b.codigo_identificador, b.fecha_creacion, b.dotacion, b.estado
            ORDER BY 
                b.nombre ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        // retorna un array
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
}
?>