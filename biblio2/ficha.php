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
    <?php if(!$libro):?>
        <h1>No existe dicho libro</h1>
    <?php else: ?>
        <h1><?= htmlspecialchars($libro['titulo'])?></h1>
        <ul>
            <li><?= htmlspecialchars($libro['isbn'])?></li>
            <li><?= htmlspecialchars($libro['autor'])?></li>
            <li><?= htmlspecialchars($libro['anio'])?></li>
            <li><?= htmlspecialchars($libro['genero'])?></li>
            <li><?= $libro['disponible']>0? 'disponible':'prestado'?></li>
    </ul>
    <?php endif;?>
    <a href="../index.php">volver</a>
</body>
</html>