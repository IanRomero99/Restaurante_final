<?php
session_start();

if (!isset($_POST['inicio'])) {
    header("location: ../index.html");
    exit;
} else if (isset($_GET['logout'])) {
    session_destroy();
    header("location: ../index.html");
    exit;
} else { // comprobamos que la solicitud se envíe con POST
    $nombre = $_POST["nombre"];
    $contra = $_POST["contra"];

    // Validación del nombre y la contra
    if (empty($nombre) || empty($contra)) {
        header("Location: ../login.php?emptyUsr");
        exit;
    } else if (empty($nombre)) {
        header("Location: ../login.php?emptyNombre");
        exit;
    } else if (empty($contra)) {
        header("Location: ../login.php?emptyContra");
        exit;
    }

    // Incluye el archivo que contiene la conexión a la base de datos.
    include_once("./conexion.php");

    // Resto del código para validar el inicio de sesión...

    // Se prepara una consulta SQL para seleccionar datos de la tabla "tbl_camarero" donde el campo "nombre" sea igual al valor de la variable $nombre.
    $query = "SELECT id_user, contra FROM tbl_user WHERE nombre = :nombre";
    $stmt = mysqli_prepare($pdo, $query); // Se prepara la consulta SQL en la conexión $pdo.

    // Se vincula el valor de $nombre como un parámetro en la consulta SQL (tipo string).
    mysqli_stmt_bind_param($stmt, "s", $nombre);
    // Se ejecuta la consulta con el valor $nombre.
    mysqli_stmt_execute($stmt);
    // Se almacena el resultado de la consulta.
    mysqli_stmt_store_result($stmt);

    // Si la consulta devuelve al menos una fila (es decir, el nombre existe en la base de datos).
    if (mysqli_stmt_num_rows($stmt) > 0) {
        // Se vinculan las columnas de la consulta con variables PHP.
        mysqli_stmt_bind_result($stmt, $id_camarero, $hashedContra);
        // Se obtienen los valores de las columnas en las variables correspondientes.
        mysqli_stmt_fetch($stmt);
        // Se verifica si la contraseña proporcionada ($contra) coincide con la contraseña almacenada en la base de datos ($hashedContra).
        if (password_verify($contra, $hashedContra)) {
            // Inicio de sesión exitoso
            session_start();
            // Se almacena el ID del camarero en la sesión.
            $_SESSION["id_camarero"] = $id_camarero;
            // Redirige al usuario a una página de contenido (la línea comentada no está activa).
            header("Location: ../alumnos.php");
        } else {
            // Si la contraseña no coincide, se redirige de vuelta a la página de inicio de sesión con un mensaje de error.
            header("Location: ../login.php?errorContra");
        }
    } else {
        // Si el nombre no existe en la base de datos, se redirige de vuelta a la página de inicio de sesión con un mensaje de error.
        header("Location: ../login.php?errorNombre");
    }

    // Se cierra el statement de MySQL.
    mysqli_stmt_close($stmt);
    // Se cierra la conexión a la base de datos.
    mysqli_close($pdo);
}
?>
