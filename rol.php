<?php
session_start();

if (isset($_SESSION['usuario'])) {

    // Incluye aquí la conexión a la base de datos
    require_once("./inc/conexion.php"); // Conexión a la base de datos

    // Obtén el ID del rol utilizando el tipo de rol
    $sql_get_role = "SELECT * FROM tbl_rol";
    $stmt_get_role = $pdo->prepare($sql_get_role);
    $stmt_get_role->execute();
    $resultado = $stmt_get_role->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($resultado); // Enviamos el resultado mediante JSON
}
