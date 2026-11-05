<?php 
    function dump($var){
        echo('<pre>'.var_dump($var).'</pre>');
    }

    function conectar(): ?PDO {
        $host = 'localhost';
        $db   = 'biblioteca';
        $user = 'root';
        $pass = 'root';
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

    function listarLibros(PDO $pdo, ?string $genero = null): array{
        $generos = ['Novela', 'Teatro', 'Ensayo', 'Poesía', 'Cómic'];
        if (!isset($genero) || $genero == null) {
            $sql = "SELECT * FROM libros";
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll();
        } else {
            $sql = "SELECT * FROM libros WHERE genero = :genero";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['genero' => $genero]);
            return $stmt->fetchAll();
        }
    }

    function validarGenero(string $genero){
         $generos = ['Novela', 'Teatro', 'Ensayo', 'Poesía', 'Cómic'];
         if (in_array($genero, $generos)){
            return true;
         } else {
            return false;
         }
    }

    function insertarLibro(PDO $pdo, array $datos): int{
        $titulo = $datos['titulo'];
        $isbn = $datos['isbn'];
        $autor = $datos['autor'];
        $anio = $datos['anio'];
        $genero = $datos['genero'];
        $disponible = 1;

        if (!is_string($datos['isbn']) ||
                !is_string($datos['titulo']) ||
                !is_string($datos['autor']) ||
                !is_string($datos['genero']) ||
                !is_int($datos['anio'])) {

                throw new InvalidArgumentException("Tipos de datos incorrectos");
        }

            // Comprobar los valores
            if (trim($datos['isbn']) === '' ||
                trim($datos['titulo']) === '' ||
                trim($datos['autor']) === '') {

                throw new InvalidArgumentException("Hay campos obligatorios vacíos");
            }

            if (!validarGenero($datos['genero'])) {
                throw new InvalidArgumentException("Género no válido");
            }

            if ($datos['anio'] < 1450 || $datos['anio'] > (int) date('Y')) {
                throw new InvalidArgumentException("Año no válido");
            }

            $sql = "INSERT INTO libros (isbn, titulo, autor, anio, genero, disponible) VALUES (:isbn, :titulo, :autor, :anio, :genero, :disponible)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'isbn' => $isbn,
                'titulo' => $titulo,
                'autor' => $autor,
                'anio' => $anio,
                'genero' => $genero,
                'disponible' => $disponible
            ]);
            return $pdo->lastInsertId();
    }

    function cambiarDisponible(PDO $pdo, int $id, int $disponible): int{
        if ($disponible === 0){
            $sql = "UPDATE libros SET disponible = 1 where id = :id AND disponible = 0";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'id' => $id
            ]);
            return $stmt->rowCount();
        }
        else{
            $sql="UPDATE libros SET disponible = 0 WHERE id = :id AND disponible = 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'id' => $id
            ]);
            return $stmt->rowCount();
        }
    }

    function  borrarLibro(PDO $pdo, int $id): int{
        $sql = "DELETE FROM libros WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id'=> $id
        ]);
        return $stmt->rowCount();
    }


?>