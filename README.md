# 🚀 Mantenedor de Bodegas y Almacenes

Este proyecto es un sistema de gestión web basado en PHP y PostgreSQL, utilizando el patrón Modelo-Vista-Controlador (MVC).

---

## 🛠️ Instalación y Ejecución

### 1. 📋 Requisitos y Dependencias

Asegúrate de tener instalado y ejecutándose lo siguiente:

* **Servidor Web:** Apache (Recomendado **XAMPP** o WAMP).
* **Lenguaje:** PHP 7.4 o superior.
* **Base de Datos:** PostgreSQL 10 o superior.
* **Extensión PHP:** `pdo_pgsql` (Necesaria para la conexión a la base de datos).

### 2. 💾 Configuración de la Base de Datos

Antes de levantar el servidor, debes configurar las credenciales de conexión y el esquema de la base de datos.

#### A. Ajustar Conexión PHP

Edita el archivo **`php/db/Conexion.php`** y reemplaza `"tu_contraseña"` con tu contraseña de PostgreSQL.

```php
// php/db/Conexion.php (Fragmento)

private $user = "postgres"; 
private $password = "tu_contraseña"; // <-- ¡Ajustar aquí!
