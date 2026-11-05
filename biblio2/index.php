<?php 
    require_once "funciones.php";
    $pdo = conectar();
    $generos = ['Novela', 'Teatro', 'Ensayo', 'Poesía', 'Cómic'];
    $genero = $_GET['genero'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>index</title>
</head>
<body>
    <p>Libros disponibles: <?=contarDisponibles($pdo)?></p>
    <form method="GET" action="index.php">
        <select name="genero" id="genero"> 
            <option value="">Todos</option>
            <?php foreach( $generos as $gen ):?>
                <option value="<?=$gen?>"><?=$gen?></option>
            <?php endforeach;?>
        </select>
        <button type="submit">ok</button>
    </form>
    
    <?php if($genero != null && !in_array($genero, $generos)): ?>
        <?php dump($genero) ?>
        <h2>No se encontraron libros de ese género</h2>
    <?php else: ?>
        <?php $librosFilt = listarLibros($pdo, $genero) ?>
        <?php dump($genero) ?>
        <table>
            <th>titulo</th><th>isbn</th><th>Autor</th><th>Año</th><th>Genero</th><th>Estado</th><th>Acción</th>
            <?php foreach($librosFilt as $lib): ?>
                <tr>
                    <td><a href="ficha.php/?id=<?=$lib['id']?>"><?=$lib['titulo']?></a></td>
                    <td><?=$lib['isbn']?></td>
                    <td><?=$lib['autor']?></td>
                    <td><?=$lib['anio']?></td>
                    <td><?=$lib['genero']?></td>
                    <td><?=$lib['disponible']>0?'disponible':'prestado'?></td>
                    <td><form method="POST" action="accion.php">
                        <input type="hidden" name="id" value="<?=$lib['id']?>">
                        <input type="hidden" name="disponible" value="<?=$lib['disponible']?>">
                        <input type="hidden" name="accion" value="mod">
                        <button type="submit"><?=$lib['disponible']>0?'prestar':'solicitar'?></button>
                    </form>
                    <form method="POST" action="accion.php">
                        <input type="hidden" name="id" value="<?=$lib['id']?>">
                        <input type="hidden" name="disponible" value="<?=$lib['disponible']?>">
                        <input type="hidden" name="accion" value="del">
                        <button type="submit">borrar</button>
                    </form></form>
                    </td>
                <tr>
            <?php endforeach; ?>
        <table>
    <?php endif;?>

    <a href="nuevo.php">nuevo libro</a>
</body>
</html>