<?php

require_once '../php/controllers/BodegaController.php';

// Inicializar el controlador y obtener todos los datos de la BD
$controller = new BodegaController();
$datos = $controller->listarDatos();

// Extraer los datos 
$bodegas = $datos['bodegas'];
$encargados = $datos['encargados']; 

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mantenedor de Bodegas</title>
</head>
<body>

    <header>
        <h1>Gestión de Bodegas y Almacenes</h1>
    </header>

    <main>
        <h2>Listado de Bodegas</h2>
        
        <?php if (empty($bodegas)): ?>
            <p>No hay bodegas registradas.</p>
        <?php else: ?>
            <table border="1" style="width:100%; text-align:left;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Bodega</th>
                        <th>Dirección</th>
                        <th>Encargado Asignado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bodegas as $bodega): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($bodega['id']); ?></td>
                            <td><?php echo htmlspecialchars($bodega['nombre_bodega']); ?></td>
                            <td><?php echo htmlspecialchars($bodega['direccion']); ?></td>
                            <td><?php echo htmlspecialchars($bodega['nombre_encargado']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
</body>
</html>