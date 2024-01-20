<?php
// Creamos la variable de errores que está vacía 
$errores = "";
// Hacemos un session_start
session_start();

// Verificar si el campo 'usuario' está presente en $_POST
if (isset($_POST['usuario'])) {
    // Recogemos los datos que ha introducido el usuario
    $usuario = $_POST['usuario'];

    // Incluir el archivo de funciones
    require_once('./funciones.php');
    // Incluir el archivo de conexión a la base de datos
    include_once("./inc/conexion.php");

    // Verificar si el usuario ya existe en la base de datos
    $sql_check = "SELECT nombre, contra FROM tbl_user WHERE nombre = :usuario";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->bindParam(':usuario', $usuario, PDO::PARAM_STR);
    $stmt_check->execute();

    // Obtener el resultado
    $resultado_check = $stmt_check->fetch(PDO::FETCH_ASSOC);

    // Verificamos si se encontró algún resultado
    if (!$resultado_check) {
        // El usuario no existe, agregar un mensaje de error a la variable $errores que se mostrará en la página anterior 
        $errores = '?nombreNotExist=true';
    } else {
        // El usuario existe, ahora verificamos la contraseña
        $pwd = $_POST['pwd']; // Recogemos la contraseña del formulario
        $pwdEncriptada = hash("sha256", $pwd);

        // Consulta para obtener la contraseña almacenada en la base de datos
        $sql_password = "SELECT contra FROM tbl_user WHERE nombre = :usuario";
        $stmt_password = $pdo->prepare($sql_password);
        $stmt_password->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $stmt_password->execute();

        // Obtener el resultado
        $stored_password = $stmt_password->fetchColumn();

    

        // Verificar si la contraseña ingresada coincide con la almacenada en la base de datos
        if (hash_equals($pwdEncriptada, $stored_password)) {
            // Contraseña coincide, redirigir a sessiones.php que la cogerá por la url
            $datosRecibidos = array(
                'usuario' => $usuario
            );
            $datosDevueltos = http_build_query($datosRecibidos);
            header("Location: ./sessiones.php?" . $datosDevueltos);
            exit();
        } else {
            // La contraseña no coincide, agregar un mensaje de error a la variable $errores que aparecerá en la página de index.php
            $errores = '?passwdIncorrect=true';
        }
    }
}

// Redirigir a index.php con mensajes de error 
if ($errores != "") {
    $datosRecibidos = array(
        'usuario' => $usuario,
    );
    $datosDevueltos = http_build_query($datosRecibidos);
    header("Location: ./index.php" . $errores . "&" . $datosDevueltos);
    exit();
}
?>
