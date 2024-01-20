<?php
function mostrarCamarerosOrdenadosPorMesas($pdo)
{
    try {
        // Consulta SQL para mostrar los camareros ordenados por la cantidad de mesas que han ocupado
        $sqlCamareros = "
    SELECT
    SELECT
    u.nombre as nombre,
    COUNT(o.id_mesa) as num_mesas_ocupadas,
    GROUP_CONCAT(o.id_mesa ORDER BY o.id_mesa) as mesas_ocupadas_ids,
    GROUP_CONCAT(o.num_veces_ocupada ORDER BY o.id_mesa) as veces_ocupada,
    GROUP_CONCAT(o.id_ocupacion ORDER BY o.fecha_inicio) as ocupacion_ids,
    GROUP_CONCAT(DISTINCT o.fecha_inicio ORDER BY o.fecha_inicio) as fechas_inicio
FROM tbl_user u
LEFT JOIN (
    SELECT
        u.id_user,
        m.id_mesa,
        COUNT(*) as num_veces_ocupada,
        GROUP_CONCAT(o.id_ocupacion) as id_ocupacion,
        GROUP_CONCAT(o.fecha_inicio ORDER BY o.fecha_inicio) as fecha_inicio
    FROM tbl_ocupacion o
    INNER JOIN tbl_mesa m ON o.id_mesa = m.id_mesa
    GROUP BY u.id_user, m.id_mesa
) o ON u.id_user = o.id_user
GROUP BY u.id_user
ORDER BY num_mesas_ocupadas DESC;
";

        $stmtCamareros = $pdo->prepare($sqlCamareros);
        if (!$stmtCamareros) {
            throw new Exception("Error en la preparación de la consulta: " . $pdo->errorInfo()[2]);
        }

        if (!$stmtCamareros->execute()) {
            throw new Exception("Error al ejecutar la consulta: " . $stmtCamareros->errorInfo()[2]);
        }

        $resultCamareros = $stmtCamareros->fetchAll(PDO::FETCH_ASSOC);

        if ($resultCamareros) {
            foreach ($resultCamareros as $row) {
                echo "<p>------------------------</p>";
                echo "<p>Camarero: " . $row['nombre_camarero'] . " - Mesas Ocupadas: " . $row['num_mesas_ocupadas'] . "</p>";

                if ($row['num_mesas_ocupadas'] > 0) {
                    echo "<p>Mesas Ocupadas:</p>";

                    $mesasIds = explode(",", $row['mesas_ocupadas_ids']);
                    $vecesOcupada = explode(",", $row['veces_ocupada']);
                    $fechasInicio = explode(",", $row['fechas_inicio']);

                    $totalVecesOcupadas = 0;

                    for ($i = 0; $i < count($mesasIds); $i++) {
                        if ($mesasIds[$i] != null && $vecesOcupada[$i] != null) {
                            echo "Mesa " . $mesasIds[$i] . " - Veces Ocupada: " . $vecesOcupada[$i];
                            $totalVecesOcupadas += $vecesOcupada[$i];

                            // Si la mesa se ha ocupado más de una vez, mostrar las fechas correspondientes
                            if ($vecesOcupada[$i] > 1) {
                                echo "<ul>";
                                for ($j = 0; $j < $vecesOcupada[$i]; $j++) {
                                    echo "<li>Ocupación " . ($j + 1) . ": " . $fechasInicio[$i * $vecesOcupada[$i] + $j] . "</li>";
                                }
                                echo "</ul>";
                            } else {
                                if ($fechasInicio[$i] != null) {
                                    echo " - Fecha Inicio: " . $fechasInicio[$i];
                                    echo "<br>";
                                }
                            }

                            echo "<br>";
                            echo "<br>";
                        }
                    }
                    echo "Total ocupaciones: " . $totalVecesOcupadas;
                }
            }
        } else {
            echo "<p>No hay resultados.</p>";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['capacidadFiltro'])) {
        $_SESSION['capacidadFiltro'] = $_POST['capacidadFiltro'];
    }
    if (isset($_POST['fechaFiltro'])) {
        $_SESSION['fechaFiltro'] = $_POST['fechaFiltro'];
    }
}
?>
