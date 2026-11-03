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