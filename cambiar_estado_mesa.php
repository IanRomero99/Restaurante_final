<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<style>
    html {
        background-color: #3a5f68;
        color: #3a5f68;
    }
</style>

<?php
session_start();
$usuarioRecibido = isset($_POST['usuario']) ? $_POST['usuario'] : '';

include_once("./inc/conexion.php");

if (isset($_POST['mesa_id'])) {
    $mesa_id = filter_var($_POST['mesa_id'], FILTER_SANITIZE_NUMBER_INT);

    // Antes de la transacción
    echo "Antes de la transacción";

    try {
        // Inicia la transacción
        $pdo->beginTransaction();

        // Consulta SQL para obtener el estado actual de ocupación de la mesa
        $sqlEstadoActual = "SELECT ocupada FROM tbl_mesa WHERE id_mesa = ?";
        $stmtEstadoActual = $pdo->prepare($sqlEstadoActual);
        $stmtEstadoActual->execute([$mesa_id]);
        $resultEstadoActual = $stmtEstadoActual->fetch(PDO::FETCH_ASSOC);

        if ($resultEstadoActual) {
            $ocupada = $resultEstadoActual['ocupada'];

            // Invierte el estado de ocupación
            $nuevoEstado = !$ocupada;

            // Actualiza el estado de ocupación en la base de datos
            $sqlActualizarEstado = "UPDATE tbl_mesa SET ocupada = ? WHERE id_mesa = ?";
            $stmtActualizarEstado = $pdo->prepare($sqlActualizarEstado);
            $stmtActualizarEstado->execute([$nuevoEstado, $mesa_id]);

            if ($stmtActualizarEstado) {
                // Si la mesa está ocupada, inserta una nueva fila en tbl_ocupacion con la fecha de inicio
                if ($nuevoEstado == 1) {
                    $id_camarero = 1; // Reemplaza con el ID del camarero actual
                    $sqlInsertarOcupacion = "INSERT INTO tbl_ocupacion (id_mesa, id_camarero, fecha_inicio, fecha_fin) VALUES (?, ?, NOW(), NULL)";
                    $stmtInsertarOcupacion = $pdo->prepare($sqlInsertarOcupacion);
                    $stmtInsertarOcupacion->execute([$mesa_id, $id_camarero]);

                    if (!$stmtInsertarOcupacion) {
                        // Si hay un error en la inserción, realiza un rollback
                        $pdo->rollBack();
                        echo "Error al insertar la ocupación de la mesa: " . $pdo->errorInfo()[2];
                        exit();
                    }
                } else {
                    // Si la mesa está desocupada, actualiza la fecha_fin en tbl_ocupacion
                    $sqlActualizarOcupacion = "UPDATE tbl_ocupacion SET fecha_fin = NOW() WHERE id_mesa = ? AND fecha_fin IS NULL";
                    $stmtActualizarOcupacion = $pdo->prepare($sqlActualizarOcupacion);
                    $stmtActualizarOcupacion->execute([$mesa_id]);

                    if (!$stmtActualizarOcupacion) {
                        // Si hay un error en la actualización, realiza un rollback
                        $pdo->rollBack();
                        echo "Error al actualizar la ocupación de la mesa: " . $pdo->errorInfo()[2];
                        exit();
                    }
                }
            }
        }

        // Confirma la transacción
        $pdo->commit();

        // Cierra la conexión
        $pdo = null;

        // Agrega el script de SweetAlert para la notificación y redirección
        ?>
        <script>
            Swal.fire({
                title: 'Aceptado',
                text: 'Has entrado a la página principal',
                icon: 'success'
            }).then(() => {
                const usuario = "<?php echo $usuarioRecibido; ?>";
                window.location.href = './mostrar_mesas.php?usuario=' + usuario;
            });
        </script>
        <?php

        // Después de la transacción
        echo "Después de la transacción";
    } catch (PDOException $e) {
        // Manejo de excepciones
        echo "Excepción: " . $e->getMessage();
    }
} else {
    // Si se intenta acceder a este archivo de manera incorrecta, redirige a la página principal
    // header("Location: ./index.php");
    echo "cambio no realizado";
    exit();
}
?>
