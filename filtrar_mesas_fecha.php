<?php
function filtrarMesasPorFecha($pdo, $fechaFiltro)
{
    try {
        // Consulta SQL para filtrar mesas por fecha
        $sqlFiltroFecha = "SELECT m.id_mesa, s.nombre AS nombre_sala, o.fecha_inicio, o.fecha_fin
            FROM tbl_ocupacion o
            JOIN tbl_mesa m ON o.id_mesa = m.id_mesa
            JOIN tbl_sala s ON m.id_sala = s.id_sala
            WHERE  o.fecha_fin IS NOT NULL AND DATE (o.fecha_inicio) = ?
            ORDER BY o.fecha_inicio";

        // $stmtFiltroFecha = mysqli_prepare($pdo, $sqlFiltroFecha);
        $stmtFiltroFecha = $pdo -> prepare($sqlFiltroFecha);
        // mysqli_stmt_bind_param($stmtFiltroFecha, "s", $fechaFiltro);
        $stmtFiltroFecha->bindParam(1, $fechaFiltro, PDO::PARAM_STR);
        // mysqli_stmt_execute($stmtFiltroFecha);
        $stmtFiltroFecha -> execute($stmtFiltroFecha);
        // $resultFiltroFecha = mysqli_stmt_get_result($stmtFiltroFecha);
        $resultFiltroFecha = $stmtFiltroFecha ->fetch(PDO::FETCH_ASSOC);
        echo "<br>";
        echo "<h4>$fechaFiltro</h4>";
        if ($resultFiltroFecha) {
            // if (mysqli_num_rows($resultFiltroFecha) > 0) {
                if ($resultFiltroFecha->rowCount() > 0) {
                // while ($row = mysqli_fetch_assoc($resultFiltroFecha)) {
                    while ($row = $stmtFiltroFecha->fetch(PDO::FETCH_ASSOC)) {
                    echo "<br>";
                    echo "<b>ID Mesa: " . $row["id_mesa"] . "</b><br>";
                    echo "<b>Sala:</b> " . $row["nombre_sala"] . "<br>";
                    echo "<b>Fecha Inicio:</b> " . $row["fecha_inicio"] . "<br>";
                    echo "<b>Fecha Fin:</b> " . $row["fecha_fin"] . "<br>";
                }
            } else {
                echo "<p>No hay ocupaciones de mesas en la fecha seleccionada.</p>";
            }
        } else {
            throw new Exception("Error en la consulta de filtrado por fecha: " . mysqli_error($pdo));
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>


<!-- <!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="prePDOect" href="https://fonts.googleapis.com">
    <link rel="prePDOect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/home.css">
    <link rel="shortcut icon" href="./src/LOGO3.png" type="image/x-icon">
    <script>
        function toggleFilter(filterId) {
            var filter = document.getElementById(filterId);
            filter.classList.toggle("hidden");
            filter.classList.toggle("visible");
        }
    </script>
</head>

<body>
    <div class="row flex" id="">
        <div id="restaurante"> -->
