<?php
// /test_db.php (Borrar después de verificar)

// Asegúrate de ajustar la ruta si es diferente
require_once 'php/bd.php'; 

echo "Intentando conectar a PostgreSQL...<br>";

try {
    $database = new Database();
    $conn = $database->connect();
    
    // Si connect() no lanzó excepción y devolvió una conexión:
    if ($conn) {
        echo "<h2 style='color: green;'>✅ ¡Conexión Exitosa!</h2>";
        
        // Opcional: Probar una consulta simple (listar los encargados que ya insertaste)
        $stmt = $conn->query("SELECT run, nombre, apellido_paterno FROM encargado");
        $encargados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<h3>Datos de Prueba (Encargados) encontrados:</h3>";
        echo "<ul>";
        foreach ($encargados as $e) {
            echo "<li>{$e['nombre']} {$e['apellido_paterno']} ({$e['run']})</li>";
        }
        echo "</ul>";
        
    } else {
        echo "<h2 style='color: orange;'>⚠️ Conexión Fallida (Error interno).</h2>";
    }
    
} catch (Exception $e) {
    // Esto captura cualquier excepción que pueda haber escapado al die() en db.php
    echo "<h2 style='color: red;'>❌ Error Crítico: " . $e->getMessage() . "</h2>";
}

?>