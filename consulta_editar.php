<?php

// Incluye aquí la conexión a la base de datos
require_once("./inc/conexion.php"); // Conexión a la base de datos

$response = array(); // Array para almacenar la respuesta

if(isset($_POST['id_user'])) {
    $id_user = $_POST['id_user'];

    // Validación del ID (puedes ajustar esto según tus necesidades)
    if (!filter_var($id_user, FILTER_VALIDATE_INT, array("options" => array("min_range" => 1)))) {
        $response['error'] = "ID de usuario no válido";
    } else {
        $sql_check = "SELECT * FROM tbl_user WHERE id_user = :id_user";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt_check->execute();

        // Verifica si la consulta fue exitosa
        if ($stmt_check) {
            // Obtiene los resultados como un array asociativo
            $resultado = $stmt_check->fetch(PDO::FETCH_ASSOC);
            $response['data'] = $resultado;
        } else {
            $response['error'] = "Error en la consulta SQL";
        }
    }
} else {
    $response['error'] = "ID de usuario no proporcionado";
}


// Envía la respuesta como JSON
echo json_encode($response);

?>
