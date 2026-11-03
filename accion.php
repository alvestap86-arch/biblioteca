<?php
require_once "funciones.php";

$pdo = conectar();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$accion = $_POST['accion'] ?? '';

$mensaje = '';

if ($id === false || $id === null || $id <= 0) {

    $mensaje = "Identificador de libro no válido";

} else {

    if ($accion === 'prestar') {

        $filas = cambiarDisponible($pdo, $id, 0);

        if ($filas === 0) {
            $mensaje = "No se ha podido cambiar el estado";
        } else {
            $mensaje = "Libro prestado correctamente";
        }

    } elseif ($accion === 'devolver') {

        $filas = cambiarDisponible($pdo, $id, 1);

        if ($filas === 0) {
            $mensaje = "No se ha podido cambiar el estado";
        } else {
            $mensaje = "Libro devuelto correctamente";
        }

    } elseif ($accion === 'borrar') {
        try {
            $resultado = borrarLibro($pdo, $id);

            if ($resultado > 0) {
                $mensaje = "Libro eliminado";
            } else {
                $mensaje = "Ese libro no existe";
            }

        } catch (PDOException $e) {

            if ((int) $e->errorInfo[1] === 1451) {
                $mensaje = "No se puede borrar: el libro tiene préstamos registrados";
            } else {
                $mensaje = "Ha ocurrido un error al borrar el libro";
            }
        }
    } else {
        $mensaje = "Acción no válida";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado de la operación</title>
</head>
<body>

    <h1>Resultado</h1>

    <p><?= htmlspecialchars($mensaje) ?></p>

    <a href="index.php">Volver a la biblioteca</a>

</body>
</html>