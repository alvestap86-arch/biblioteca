<?php 
    function dump($var){
        echo('<pre>'.var_dump($var).'</pre>');
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

    function contarDisponibles(PDO $pdo): int {
        $sql = "SELECT COUNT(*) FROM libros WHERE disponible = 1";
        $stmt = $pdo->query($sql);
        return $stmt->fetchColumn();
    }

    function obtenerLibro(PDO $pdo, int $id): array|false {
        $sql = "SELECT * FROM libros WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }


?>