<?php
// /views/index.php

require_once '../php/controllers/BodegaController.php';

// Inicializar el controlador
$controller = new BodegaController();

// Llama al método que gestiona POST (crear) y GET (listar)
$datos = $controller->gestionarPeticion();

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

        <h2>Crear Nueva Bodega</h2>
        <form method="POST" action="index.php">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" required><br><br>

            <label for="codigo_identificador">Código Identificador:</label>
            <input type="text" name="codigo_identificador" required><br><br>

            <label for="direccion">Dirección:</label>
            <input type="text" name="direccion" required><br><br>
            
            <label for="dotacion">Dotación:</label>
            <input type="number" name="dotacion" value="0" required><br><br>

            <label for="rut_encargado">Encargado(s):</label>
            <select name="rut_encargado[]" multiple required> 
                <option value="" disabled selected>Seleccione Encargado(s) (Ctrl + Click)</option>
                <?php foreach ($encargados as $encargado): ?>
                    <option value="<?php echo htmlspecialchars($encargado['run']); ?>">
                        <?php echo htmlspecialchars($encargado['nombre_completo']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <br><br>
            <button type="submit">Guardar Bodega</button>
        </form>

        <hr>

        <h3>🔍 Filtrar Bodegas</h3>
        <form method="GET" action="index.php">
            <label for="filtro_estado">Estado:</label>
            <select name="filtro_estado" onchange="this.form.submit()">
                <option value="" <?php if (!isset($_GET['filtro_estado']) || $_GET['filtro_estado'] == '') echo 'selected'; ?>>Todas las Bodegas</option>
                
                <option value="Activada" <?php if (isset($_GET['filtro_estado']) && $_GET['filtro_estado'] == 'Activada') echo 'selected'; ?>>Activada</option>
                <option value="Desactivada" <?php if (isset($_GET['filtro_estado']) && $_GET['filtro_estado'] == 'Desactivada') echo 'selected'; ?>>Desactivada</option>
            </select>
            
            <?php if (isset($_GET['status'])): ?>
                <input type="hidden" name="status" value="<?php echo htmlspecialchars($_GET['status']); ?>">
            <?php endif; ?>
            
        </form>

        <hr>

        <h2>Listado de Bodegas</h2>
        
        <?php if (empty($bodegas)): ?>
            <p>No hay bodegas registradas.</p>
        <?php else: ?>
            <table border="1" style="width:100%; text-align:left;">
                <thead>
                    <tr>
                        <th>Codigo</th>
                        <th>Nombre Bodega</th>
                        <th>Dirección</th>
                        <th>Dotacion</th>
                        <th>Encargado Asignado</th>
                        <th>fecha Creacion</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                </thead>
                <tbody>
                    <?php foreach ($bodegas as $bodega): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($bodega['codigo_identificador']); ?></td>
                            <td><?php echo htmlspecialchars($bodega['nombre_bodega']); ?></td>
                            <td><?php echo htmlspecialchars($bodega['direccion']); ?></td>
                            <td><?php echo htmlspecialchars($bodega['dotacion']); ?></td>
                            <td><?php echo htmlspecialchars($bodega['nombre_encargado']); ?></td>
                            <td><?php echo htmlspecialchars($bodega['fecha_creacion']); ?></td>
                            <td><?php echo htmlspecialchars($bodega['estado']); ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $bodega['bodega_id']; ?>">Editar</a> |
                                <a href="index.php?accion=eliminar&id=<?php echo $bodega['bodega_id']; ?>" onclick="return confirm('¿Confirma la eliminación de la Bodega: <?php echo htmlspecialchars($bodega['nombre_bodega']); ?>?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
</body>
</html>