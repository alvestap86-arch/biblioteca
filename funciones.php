<?php
function dump($var){
    echo '<pre>'.print_r($var,1).'</pre>';
}

function conectar(): ?PDO {
    $host = 'localhost';
    $db   = 'biblioteca';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lanza excepciones en caso de error
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Devuelve arrays asociativos por defecto
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Usa sentencias preparadas reales
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
        return $pdo;
    } catch (PDOException $e) {
        throw new Exception("Lo sentimos, hubo un problema al conectar con el servidor.");
    }
}

// function contarDisponibles(PDO $pdo): int {
//     $pdo = conectar();
//     if (!pdo){
//         return 0;
//     }
//     $sql = "SELECT COUNT(*) FROM libros WHERE disponible = 1";
//     try {
//         $stmt = $pdo->prepare($sql);
//         $stmt->execute([]);
//         $totalDisponibles = (int) $stmt->fetchColumn();
//         return $totalDisponibles;
        
//     } catch (PDOException $e) {
//         throw new Exception("Error al contar las filas en la base de datos.");
//     }
// }
    function contarDisponibles(PDO $pdo): int {
        $sql = "SELECT COUNT(*) FROM libros WHERE disponible = 1";

        $stmt = $pdo->query($sql);

        return (int) $stmt->fetchColumn();
    }

    function obtenerLibro(PDO $pdo, int $id): array|false {
        $sql = "SELECT * FROM libros WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch();
    }

    function validarGenero(string $genero){
        $generos = ['Novela', 'Teatro', 'Ensayo', 'Poesía', 'Cómic'];
        if (in_array($genero, $generos)){
            return true;
        }else{
            return false;
        }
    }

    function listarLibros(PDO $pdo, ?string $genero = null): array {
        if ($genero === null || !validarGenero($genero)) {
            $sql = "SELECT * FROM libros";
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll();
        }
        else{
            $sql = "SELECT * FROM libros WHERE genero = :genero";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['genero' => $genero]);
            return $stmt->fetchAll();
        }
    }


?>