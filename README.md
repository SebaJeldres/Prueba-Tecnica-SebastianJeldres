#  Mantenedor de Bodegas y Almacenes

Este proyecto es un sistema de gestión web basado en PHP y PostgreSQL, utilizando el patrón Modelo-Vista-Controlador (MVC).

---

##  Instalación y Ejecución

### 1.  Requisitos y Dependencias

Asegúrate de tener instalado y ejecutándose lo siguiente:

* **Servidor Web:** Apache (Recomendado **XAMPP** o WAMP).
* **Lenguaje:** PHP 7.4 o superior.
* **Base de Datos:** PostgreSQL 10 o superior.
* **Extensión PHP:** `pdo_pgsql` (Necesaria para la conexión a la base de datos).

### 2.  Configuración de la Base de Datos

Antes de levantar el servidor, debes configurar las credenciales de conexión y el esquema de la base de datos.

#### A. Ajustar Conexión PHP

Edita el archivo **`php/db.php`**

```php
// php/db/Conexion.php (Fragmento)

private $user = "postgres"; 
private $password = "tu_contraseña"; // <-- ¡Ajustar aquí!

##  Librerías y Dependencias

El proyecto se desarrolla con PHP nativo y utiliza las siguientes librerías y extensiones para el funcionamiento seguro del *backend* y el dinamismo del *frontend*.

### Frontend (JavaScript/CSS)

| Librería | Propósito |
| :--- | :--- |
| **jQuery** | Simplifica la manipulación del DOM y el manejo de eventos en el *frontend*. Es la dependencia base para la librería Select2. |
| **Select2** | Mejora la experiencia de usuario en la selección de encargados, convirtiendo los *dropdowns* (`<select multiple>`) en componentes interactivos con búsqueda y soporte para *tags* de selección. |

### Backend (Extensiones PHP)

| Extensión | Propósito |
| :--- | :--- |
| **PDO** | Capa de abstracción de base de datos de PHP. Se usa para establecer conexiones seguras y ejecutar consultas parametrizadas, **previniendo la inyección SQL**. |
| **`pdo_pgsql`** | El *driver* específico que permite a PDO comunicarse de manera efectiva con la base de datos **PostgreSQL**. |
