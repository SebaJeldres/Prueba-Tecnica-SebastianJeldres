<?php
class Database {
    // Configuración de la conexión a PostgreSQL
    private $host = 'localhost';
    private $dbname = 'bd_bodega'; 
    private $user = 'app_conector';      
    private $password = 'ClaveSegura2025'; 
    private $port = '5432';
    private $conn;

    /**
     * Obtiene la instancia de conexión PDO.
     * @return PDO 
     */
    public function connect() {
        $this->conn = null;
        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}";
            $this->conn = new PDO($dsn, $this->user, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            

        } catch (PDOException $e) {
           
            error_log("Error de conexión a la base de datos: " . $e->getMessage());
            die("Error de conexión a la base de datos. Por favor, contacte a soporte.");
        }
        return $this->conn;
    }
}
?>