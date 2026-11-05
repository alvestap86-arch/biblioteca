<?php
    include_once "funciones.php";
    $pdo = conectar();
    $id = $_POST['id']??'';
    $disponible = $_POST['disponible']??'';
    $accion = $_POST['accion']??'';
    if ($accion === 'mod' && $id !== '' && $disponible !== ''){
        $resultado = cambiarDisponible($pdo,$id,$disponible);
        $mensaje = 'EXITO EN LA MODIFICACIÓN';
    }
    if ($accion === 'del' && $id !== ''){
        try{
            $resultado = borrarLibro($pdo,$id);
            $mensaje = 'EXITO EN EL BORRADO';
        }catch(Exception $e){
        echo 'No se puede borrar este libro, ya que pertenece a la tabla prestamos', "\n";}
    }
    if (isset($resultado) && $resultado> 0){
        echo($mensaje);
    }else{
        echo('Ha ocurrido un problema');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>accion</title>
</head>
<body>
    <a href="index.php">Volver</a>
</body>
</html>