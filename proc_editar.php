<?php
session_start();

if (isset($_SESSION['usuario'])) {
    $usuario = $_POST['usuario'];
    $pwd = $_POST['contra'];
    $rol = $_POST['rol'];

    // Incluye aquí la conexión a la base de datos
    require_once("./inc/conexion.php"); // Conexión a la base de datos

    // Verificamos si el usuario ya existe en la base de datos
    $sql_check = "SELECT id_user, nombre, contra, id_rol FROM tbl_user WHERE nombre = :nombre";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->bindParam(':nombre', $usuario);
    $stmt_check->execute();
    $user_count = $stmt_check->fetch(PDO::FETCH_ASSOC);
    $stmt_check->closeCursor();

    if ($user_count) {
        // El usuario ya existe, muestra un mensaje indicando que puede actualizar
        echo "existe";
    } else {
        // El usuario no existe, procedemos con la inserción
        $pwdEncriptada = hash("sha256", $pwd);

       // Actualizamos el usuario existente en la base de datos
        $sql_update = "UPDATE tbl_user SET nombre = :nombre, contra = :pwdEncriptada, id_rol = :id_rol WHERE id_user = :id_user";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->bindParam(':nombre', $usuario);
        $stmt_update->bindParam(':pwdEncriptada', $pwdEncriptada);
        $stmt_update->bindParam(':id_rol', $rol);
        $stmt_update->execute();


        // Verifica si la inserción fue exitosa
        if ($stmt_update->rowCount() > 0) {
            echo "ok";
            exit();
        } 
    }
} else {
    echo "Usuario no autenticado.";
}
?>
