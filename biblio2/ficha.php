<?php 
    require_once "funciones.php";

    $pdo = conectar();

    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($id === false || $id === null || $id < 0){
        echo('No se encontró el libro');
    } else{
        $libro = obtenerLibro($pdo, $id);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ficha</title>
</head>
<body>
    <h1><?= htmlspecialchars($libro['titulo'])?></h1>
    <li>
        <ul><?= htmlspecialchars($libro['isbn'])?></ul>
        <ul><?= htmlspecialchars($libro['autor'])?></ul>
        <ul><?= htmlspecialchars($libro['anio'])?></ul>
        <ul><?= htmlspecialchars($libro['genero'])?></ul>
    </li>
</body>
</html>