<?php
session_start();

if (isset($_SESSION['usuario'])) {
    $usuario = $_POST['usuario_crear'];
    $pwd = $_POST['contra_crear'];
    $rol = $_POST['rol_crear'];

    // Incluye aquí la conexión a la base de datos
    require_once("./inc/conexion.php"); // Conexión a la base de datos

    // Verificamos si el usuario ya existe en la base de datos
    $sql_check = "SELECT COUNT(*) FROM tbl_user WHERE nombre = :nombre";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->bindParam(':nombre', $usuario);
    $stmt_check->execute();
    $user_count = $stmt_check->fetchColumn();
    $stmt_check->closeCursor();



    if ($user_count > 0) {
        // El usuario ya existe, muestra un mensaje de error
        echo "existe";
    } else {
        // El usuario no existe, procedemos con la inserción
        $pwdEncriptada = hash("sha256", $pwd);

        // Insertamos el nuevo usuario en la base de datos
        $sql = "INSERT INTO tbl_user (`nombre`, `contra`, `id_rol`) VALUES (:nombre, :pwdEncriptada, :id_rol)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $usuario);
        $stmt->bindParam(':pwdEncriptada', $pwdEncriptada);
        // Recogemos el id del rol
        $stmt->bindParam(':id_rol', $rol);
        $stmt->execute();

    
        // Verifica si la inserción fue exitosa
        if ($stmt->rowCount() > 0) {
            echo "ok";
            exit();
        } 
    }
} else {
    echo "Usuario no autenticado.";
}
?>
