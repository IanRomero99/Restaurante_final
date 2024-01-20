<?php
function filtrarMesasPorCapacidad($pdo, $capacidadFiltro)
{
    try {
        // Consulta SQL para filtrar mesas por capacidad
        $sqlFiltro = "SELECT m.id_mesa, m.capacidad, s.nombre as sala_nombre 
        FROM tbl_mesa m 
        INNER JOIN tbl_sala s ON m.id_sala = s.id_sala 
        WHERE m.capacidad = ? AND m.ocupada = 0 
        ORDER BY m.capacidad";

        // $stmtFiltro = mysqli_prepare($pdo, $sqlFiltro);
        $stmtFiltro = $pdo -> prepare($sqlFiltro);
        // mysqli_stmt_bind_param($stmtFiltro, "i", $capacidadFiltro);
        $stmtFiltro->bindParam(1, $capacidadFiltro, PDO::PARAM_INT);
        // mysqli_stmt_execute($stmtFiltro);
        $stmtFiltro -> execute();
        // $resultFiltro = mysqli_stmt_get_result($stmtFiltro);
        $resultFiltro = $stmtFiltro -> fetch(PDO::FETCH_ASSOC);
        echo "<h3>Filtradas por capacidad: $capacidadFiltro personas</h3><br>";
        if ($resultFiltro) {
            // if (mysqli_num_rows($resultFiltro) > 0) {
                if ($resultFiltro->rowCount() > 0) {

                    while ($row = $stmtFiltro->fetch(PDO::FETCH_ASSOC)) {
                    echo "<p>Mesa: " . $row['id_mesa'] . " - Capacidad: " . $row['capacidad'] . " <br> Sala: " . $row['sala_nombre'] . "</p><br>";
                }
            } else {
                echo "<br>";
                echo "<p>No hay mesas disponibles con la capacidad seleccionada.</p>";
            }
        } else {
            throw new Exception("Error en la consulta de filtrado: " . $pdo -> error());
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
