<?php
require_once "funciones.php";

$pdo = conectar();

$totalDisponibles = contarDisponibles($pdo);

$genero = $_GET['genero'] ?? null;

$libros = listarLibros($pdo, $genero);

$generos = ['Novela', 'Teatro', 'Ensayo', 'Poesía', 'Cómic'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Biblioteca del instituto</title>
</head>
<body>

    <h1>Biblioteca del instituto</h1>

    <p>Libros disponibles: <?= $totalDisponibles ?></p>

    <form method="GET" action="index.php">

        <label for="genero">Filtrar por género:</label>

        <select name="genero" id="genero">

            <option value="">Todos</option>

            <?php foreach ($generos as $g): ?>
                <option value="<?= htmlspecialchars($g) ?>"
                    <?= $genero === $g ? 'selected' : '' ?>>
                    <?= htmlspecialchars($g) ?>
                </option>
            <?php endforeach; ?>

        </select>

        <button type="submit">Filtrar</button>

    </form>

    <h2>Listado de libros</h2>

    <?php if (count($libros) === 0): ?>

        <p>No se han encontrado libros.</p>

    <?php else: ?>

        <table border="1">

            <tr>
                <th>Título</th>
                <th>Autor</th>
                <th>Año</th>
                <th>Género</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>

            <?php foreach ($libros as $libro): ?>

                <tr>
                    <td>
                        <a href="ficha.php?id=<?= (int) $libro['id'] ?>">
                            <?= htmlspecialchars($libro['titulo']) ?>
                        </a>
                    </td>

                    <td><?= htmlspecialchars($libro['autor']) ?></td>
                    <td><?= htmlspecialchars($libro['anio']) ?></td>
                    <td><?= htmlspecialchars($libro['genero']) ?></td>

                    <td>
                        <?= $libro['disponible'] ? 'Disponible' : 'Prestado' ?>
                    </td>

                    <td>
                        <?php if ($libro['disponible']): ?>

                            <form method="POST" action="accion.php">
                                <input type="hidden" name="id"
                                    value="<?= (int) $libro['id'] ?>">

                                <input type="hidden" name="accion" value="prestar">

                                <button type="submit">Prestar</button>
                            </form>

                        <?php else: ?>

                            <form method="POST" action="accion.php">
                                <input type="hidden" name="id"
                                    value="<?= (int) $libro['id'] ?>">

                                <input type="hidden" name="accion" value="devolver">

                                <button type="submit">Devolver</button>
                            </form>

                        <?php endif; ?>

                        <form action="accion.php" method="POST">
                            <input type="hidden" name="id"
                                value="<?= htmlspecialchars($libro['id']) ?>">

                            <input type="hidden" name="accion" value="borrar">

                            <button type="submit">Borrar</button>
                        </form>
                    </td>
                </tr>

            <?php endforeach; ?>

        </table>

    <?php endif; ?>

    <p><a href="nuevo.php">Añadir libro</a></p>

</body>
</html>

