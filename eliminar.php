<?php
// Inicializamos la sesión
session_start();

// Verificamos si existe la variable de sesión usuario
if (!isset($_SESSION['usuario'])) {
    header('Location: ./index.php'); // Si no existe, redirige al index
    exit();
}

// Obtenemos el usuario que ha iniciado sesión
$usuario = $_SESSION['usuario'];

require_once("./inc/conexion.php"); // Conexión a la base de datos

if (isset($_POST["id_user"]) && $_POST["id_user"]) {
    // Guardamos el valor en una variable
    $id_user = $_POST["id_user"];

    // Eliminar asociación del usuario con el rol
    $sql_eliminar = "UPDATE tbl_user SET id_rol = WHERE id_user = :id_user";
    $consulta_eliminar = $pdo->prepare($sql_eliminar);
    $consulta_eliminar->bindParam(":id_user", $id_user);
    $consulta_eliminar->execute();

    // Eliminar el usuario
    $consulta_eliminar_usuario = $pdo->prepare("DELETE FROM tbl_user WHERE id_user = :id_user");
    $consulta_eliminar_usuario->bindParam(":id_user", $id_user);
    $consulta_eliminar_usuario->execute();

    // Verificar si se eliminó con éxito
    if ($consulta_eliminar_usuario->rowCount() > 0) {
        echo "ok"; // Respondemos con 'ok' en lugar de un mensaje de texto
    } else {
        echo "No se pudo eliminar el usuario.";
    }
} else {
    echo "ID de usuario no proporcionado.";
}
?>
