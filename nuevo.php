<?php
    require_once "funciones.php";
    $pdo = conectar();

    $errores = [];
    $mensaje = '';

    $isbn = '';
    $titulo = '';
    $autor = '';
    $anio = '';
    $genero = '';

    $generos = ['Novela', 'Teatro', 'Ensayo', 'Poesía', 'Comic'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $isbn = trim($_POST['isbn'] ?? '');
    $titulo = trim($_POST['titulo'] ?? '');
    $autor = trim($_POST['autor'] ?? '');
    $anio = $_POST['anio'] ?? '';
    $genero = $_POST['genero'] ?? '';

    // Validar campos obligatorios
    if ($isbn === '') {
        $errores[] = "El ISBN es obligatorio";
    }

    if ($titulo === '') {
        $errores[] = "El título es obligatorio";
    }

    if ($autor === '') {
        $errores[] = "El autor es obligatorio";
    }

    // Validar año
    $anioValidado = filter_var($anio, FILTER_VALIDATE_INT);

    if ($anioValidado === false || $anioValidado < 1450 || $anioValidado > date('Y')) {
        $errores[] = "El año debe ser un entero entre 1450 y el año actual";
    }

    // Validar género
    if (!validarGenero($genero)) {
        $errores[] = "El género seleccionado no es válido";
    }

    // Insertar si no hay errores
    if (empty($errores)) {

        $datos = [
            'isbn' => $isbn,
            'titulo' => $titulo,
            'autor' => $autor,
            'anio' => $anioValidado,
            'genero' => $genero
        ];

        try {
            $id = insertarLibro($pdo, $datos);
            $mensaje = "Libro añadido con id " . $id;

            // Limpiar el formulario
            $isbn = '';
            $titulo = '';
            $autor = '';
            $anio = '';
            $genero = '';

        } catch (PDOException $e) {

            if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062) {
                $errores[] = "Ya existe un libro con ese ISBN";
            } else {
                $errores[] = "No se ha podido añadir el libro";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir libro</title>
</head>
<body>

    <h1>Añadir libro</h1>

    <?php if (!empty($errores)): ?>
        <ul>
            <?php foreach ($errores as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if ($mensaje !== ''): ?>
        <p><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <form method="POST" action="nuevo.php">

        <p>
            <label for="isbn">ISBN:</label>
            <input type="text" name="isbn" id="isbn"
                   value="<?= htmlspecialchars($isbn) ?>">
        </p>

        <p>
            <label for="titulo">Título:</label>
            <input type="text" name="titulo" id="titulo"
                   value="<?= htmlspecialchars($titulo) ?>">
        </p>

        <p>
            <label for="autor">Autor:</label>
            <input type="text" name="autor" id="autor"
                   value="<?= htmlspecialchars($autor) ?>">
        </p>

        <p>
            <label for="anio">Año:</label>
            <input type="number" name="anio" id="anio"
                   value="<?= htmlspecialchars((string) $anio) ?>">
        </p>

        <p>
            <label for="genero">Género:</label>
            <select name="genero" id="genero">

                <option value="">Selecciona un género</option>

                <?php foreach ($generos as $g): ?>
                    <option value="<?= htmlspecialchars($g) ?>"
                        <?= $genero === $g ? 'selected' : '' ?>>
                        <?= htmlspecialchars($g) ?>
                    </option>
                <?php endforeach; ?>

            </select>
        </p>

        <button type="submit">Añadir libro</button>

    </form>

    <p><a href="index.php">Volver a la biblioteca</a></p>

</body>
</html>

