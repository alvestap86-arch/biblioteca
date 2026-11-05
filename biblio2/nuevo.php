<?php
    
    include_once "funciones.php";
    $pdo = conectar();
    $errores = [];
    $mensaje = '';

    $isbn = '';
    $titulo = '';
    $autor = '';
    $anio = '';
    $genero = '';

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

            } catch (PDOException $e) {

                if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062) {
                    $errores[] = "Ya existe un libro con ese ISBN";
                } else {
                    $errores[] = "No se ha podido añadir el libro";
                }
            }
        }
        else{
            dump($errores);
        }
    }
    if ($mensaje !== ''){
        echo($mensaje);
    };
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>nuevo</title>
</head>
<body>
    <form method="POST" name="datos" id="datos">
        <label>ISBN</label>
        <input type="text" name="isbn" id="isbn"></input>
        <br>
        <label>titulo</label>
        <input type="text" name="titulo" id="titulo"></input>
        <br>
        <label>autor</label>
        <input type="text" name="autor" id="autor"></input>
        <br>
        <label>año</label>
        <input type="number" name="anio" id="anio"></input>
        <br>
        <label>genero</label>
        <input type="text" name="genero" id="genero"></input>
        <br>

        <button type="submit">enviar</button>
    </form>
    <a href="index.php">volver</a>
</body>
</html>