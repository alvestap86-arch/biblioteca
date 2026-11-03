<?php
    require_once "funciones.php";

    $pdo = conectar();

    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($id === false || $id === null || $id <= 0) {
        $libro = false;
    } else {
        $libro = obtenerLibro($pdo, $id);
    }


?>

<?php if (!$libro): ?>

    <h1>Libro no encontrado</h1>

<?php else: ?>

    <h1><?= htmlspecialchars($libro['titulo']) ?></h1>

    <p>ISBN: <?= htmlspecialchars($libro['isbn']) ?></p>
    <p>Autor: <?= htmlspecialchars($libro['autor']) ?></p>
    <p>Año: <?= htmlspecialchars($libro['anio']) ?></p>
    <p>Género: <?= htmlspecialchars($libro['genero']) ?></p>

    <p>
        Estado:
        <?php if ($libro['disponible'] === 1){
            echo('Disponible');
        }else{
            echo('Prestado');
        }
        ?>
    </p>

<?php endif; ?>