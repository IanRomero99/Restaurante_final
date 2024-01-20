<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirección según Rol</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- Agrega aquí tus enlaces a hojas de estilo y otros recursos si es necesario -->
</head>
<body>

<?php
session_start();

if (!isset($_GET['usuario'])) {
    header('Location: ./index.php'); // Redirige a la página de inicio de sesión
    exit();
} else {
    $usuarioRecibido = $_GET['usuario'];
    $_SESSION['usuario'] = $usuarioRecibido;
    echo "Usuario recibido: " . $usuarioRecibido;
}
    

// Agrega la conexión a la base de datos
require_once('./inc/conexion.php');

$sql_rol = "SELECT r.nombre_rol
            FROM tbl_user u
            JOIN tbl_rol r ON u.id_rol = r.id_rol
            WHERE u.nombre = :usuario";

$stmt_rol = $pdo->prepare($sql_rol);
$stmt_rol->bindParam(':usuario', $usuarioRecibido, PDO::PARAM_STR);
$stmt_rol->execute();

// Obtener el resultado
$resultado = $stmt_rol->fetch(PDO::FETCH_ASSOC);

// Comparar roles
if ($resultado['nombre_rol'] === "Admin") {
    echo '<script>
            Swal.fire({
                title: "Aceptado",
                text: "Has entrado a la página principal del Administrador",
                icon: "success"
            });
            window.location.href = "./admin.php";
          </script>';
    exit();
} elseif ($resultado['nombre_rol'] === "Camarero") {
    echo '<script>
            Swal.fire({
                title: "Aceptado",
                text: "Has entrado a la página principal del Camarero",
                icon: "success"
            });
            window.location.href = "./admin.php";
          </script>';
    exit();
} elseif ($resultado['nombre_rol'] === "Mantenimiento") {
    echo '<script>
            Swal.fire({
                title: "Aceptado",
                text: "Has entrado a la página principal del Mantenimiento",
                icon: "success"
            });
            window.location.href = "./mantenimiento.php";
          </script>';
    exit();
}

// Si no se encontró un resultado válido, puedes redirigir a una página por defecto o mostrar un mensaje de error
echo '<script>
        Swal.fire({
            title: "Error",
            text: "No se encontró información de rol para el usuario",
            icon: "error"
        });
        window.location.href = "./index.php";
      </script>';
?>

</body>
</html>
