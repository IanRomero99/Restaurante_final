<?php
// Iniciamos sesión
session_start();

// Verificamos si el usuario ha iniciado sesión
if ($_SESSION['usuario']) {
    // Llamamos con un require_once a la conexión
    require_once("./inc/conexion.php");
    $usuario = $_SESSION['usuario'];

    // Hacemos una consulta que selecciona todas las salas excepto la del usuario que ha iniciado sesión
    $sql = "SELECT id_sala, nombre, tipo_sala, capacidad FROM tbl_sala WHERE nombre != :usuario";

    // Hacemos un try que ejecutará un statement
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":usuario", $usuario);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($resultado); // Enviamos el resultado mediante JSON
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage(); // Si hay algún error
    }
} else {
    // Redirigimos al index.php en caso de que no haya iniciado sesión
    header('Location: ../index.php');
    exit();
}
?>
