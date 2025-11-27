<?php
// /views/edit.php

require_once '../php/controllers/BodegaController.php';

$controller = new BodegaController();

// Asegurar que el ID esté presente
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php?status=error&msg=ID faltante");
    exit;
}

$id_bodega = (int)$_GET['id'];
$bodega = $controller->obtenerBodegaParaEditar($id_bodega);
$encargados_disponibles = $controller->listarDatos()['encargados']; 

if (empty($bodega)) {
    header("Location: index.php?status=error&msg=Bodega no encontrada");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Editar Bodega #<?php echo $id_bodega; ?></title>
    <link rel="stylesheet" href="../css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>

    <header>
        <h1>Editar Bodega: <?php echo htmlspecialchars($bodega['nombre']); ?></h1>
    </header>

    <main>

        <button><a href="index.php">← Volver al Listado</a></button>
        <hr>
        
        <h2>Formulario de Edición</h2>
        
        <form method="POST" action="index.php" id="form-edicion">
            <input type="hidden" name="bodega_id" value="<?php echo htmlspecialchars($bodega['bodega_id']); ?>">
            
            <label for="codigo_identificador">Código Identificador (Máx 5):</label>
            <input type="text" name="codigo_identificador" maxlength="5" required 
                   value="<?php echo htmlspecialchars($bodega['codigo_identificador']); ?>"><br><br>

            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" required 
                   value="<?php echo htmlspecialchars($bodega['nombre']); ?>"><br><br>

            <label for="direccion">Dirección:</label>
            <input type="text" name="direccion" required 
                   value="<?php echo htmlspecialchars($bodega['direccion']); ?>"><br><br>

            <label for="dotacion">Dotación Máxima:</label>
            <input type="number" name="dotacion" required 
                   value="<?php echo htmlspecialchars($bodega['dotacion']); ?>"><br><br>
                   
            <label for="estado">Estado:</label>
            <select name="estado" required>
                <option value="Activada" <?php if ($bodega['estado'] === 'Activada') echo 'selected'; ?>>Activada</option>
                <option value="Desactivada" <?php if ($bodega['estado'] === 'Desactivada') echo 'selected'; ?>>Desactivada</option>
            </select><br><br>

            <label for="rut_encargado">Encargado(s):</label>
                <select name="rut_encargado[]" multiple required id="select-encargados-editar">
                    <option value="" disabled>Seleccione Encargado(s) (Busque aquí)</option>
                    <?php foreach ($encargados_disponibles as $encargado): 
                        $run_encargado = $encargado['run'];
                        // Verifica si este encargado está en la lista de asignados de la bodega
                        $seleccionado = in_array($run_encargado, $bodega['encargados_asignados']) ? 'selected' : '';
                    ?>
                        <option value="<?php echo htmlspecialchars($run_encargado); ?>" <?php echo $seleccionado; ?>>
                            <?php echo htmlspecialchars($encargado['nombre_completo']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <br><br>
            <button type="submit">Guardar Cambios</button>
        </form>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="../js/scripts.js"></script>
</body>
</html>