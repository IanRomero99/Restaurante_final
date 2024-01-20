<?php
// Inicializamos la sesión
session_start();

// // Verificamos si existe la variable de sesión usuario
// if ($_SESSION['usuario']) {

// Obtenemos el usuario que ha iniciado sesión
$usuario = $_SESSION['usuario'];

require_once("./inc/conexion.php"); // Conexión a la base de datos

// Verificamos si el campo de búsqueda no está vacío
if (!empty($_POST["busqueda"])) {
    // Guardamos el valor en una variable
    $data = $_POST["busqueda"];

    // Consulta para obtener el usuario que se busca
    $consulta = $pdo->prepare("SELECT u.id_user, u.nombre, u.id_rol, r.nombre_rol
    FROM tbl_user u
    JOIN tbl_rol r ON u.id_rol = r.id_rol
    WHERE u.nombre != :nombre AND (nombre LIKE'%".$data."%')");
    $consulta->bindParam(":nombre", $usuario);
    $consulta->execute();
} else {
    // Si está vacío, aparecerán todos los usuarios menos el que ha iniciado sesión
    $consulta = $pdo->prepare("SELECT u.id_user, u.nombre, u.id_rol,  r.nombre_rol
    FROM tbl_user u
    JOIN tbl_rol r ON u.id_rol = r.id_rol
    WHERE u.nombre != :nombre");
    $consulta->bindParam(":nombre", $usuario);
    $consulta->execute();
}

$resultado = $consulta->fetchAll(PDO::FETCH_ASSOC); // Obtenemos los resultados

echo json_encode($resultado); // Enviamos el resultado mediante JSON


?>
