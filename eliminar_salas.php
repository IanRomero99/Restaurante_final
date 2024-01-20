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

try {
    // Verificamos si se proporcionó el ID de la sala
    if (isset($_POST["id_sala"]) && $_POST["id_sala"]) {
        // Guardamos el valor en una variable
        $id_sala = $_POST["id_sala"];

        // Eliminar la sala
        $consulta_eliminar_sala = $pdo->prepare("DELETE FROM tbl_sala WHERE id_sala = :id_sala");
        $consulta_eliminar_sala->bindParam(":id_sala", $id_sala);
        $consulta_eliminar_sala->execute();

        // Verificar si se eliminó con éxito
        if ($consulta_eliminar_sala->rowCount() > 0) {
            echo "ok"; // Respondemos con 'ok' en lugar de un mensaje de texto
        } else {
            echo "No se pudo eliminar la sala.";
        }
    } else {
        echo "ID de sala no proporcionado.";
    }
} catch (PDOException $e) {
    // Manejo de excepciones para posibles errores de la base de datos
    echo "Error en la base de datos: " . $e->getMessage();
}
?>
