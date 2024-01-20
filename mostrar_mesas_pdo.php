<?php
function mostrarMesas($nombreSala, $pdo) {

            
try {
    // Iniciar transacción y deshabilitar el modo de autocommit
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);

    // Consulta SQL para obtener el número de mesas en una sala específica
    $sqlSala = "SELECT COUNT(id_mesa) AS num_mesas FROM tbl_mesa WHERE id_sala = (SELECT id_sala FROM tbl_sala WHERE nombre = '$nombreSala')";

    // Ejecutar la consulta
    $stmtSala = $pdo->query($sqlSala);

    if ($stmtSala) {
        // Variable para almacenar la clase del formulario
        $claseFormulario = '';

        // Obtener el número de mesas
        $numMesas = $stmtSala->fetchColumn();

        // Determinar la clase del formulario según el número de mesas
        if ($numMesas == 2) {
            $claseFormulario = 'dos-mesas';
        } elseif ($numMesas == 4) {
            $claseFormulario = 'cuatro-mesas';
        } elseif ($numMesas == 6) {
            $claseFormulario = 'seis-mesas';
        }

        echo "<h2 class='migadepan'>Mesas de $nombreSala</h2>";
        echo "<form method='post' action='cambiar_estado_mesa.php' class='sala-distribucion $claseFormulario'>";

        // Consulta SQL para obtener las mesas de la sala
        $sqlMesas = "SELECT id_mesa, capacidad, ocupada FROM tbl_mesa WHERE id_sala = (SELECT id_sala FROM tbl_sala WHERE nombre = '$nombreSala')";
        $stmtMesas = $pdo->query($sqlMesas);

        // Recorrer los resultados y generar los botones de las mesas
        while ($row = $stmtMesas->fetch(PDO::FETCH_ASSOC)) {
            echo "<button type='submit' name='mesa_id' value='" . $row['id_mesa'] . "' ";

            // Concatenar clases para capacidad
            echo "class='mesa-" . $row['capacidad'];

            // Concatenar clases adicionales para ocupación
            if ($row['ocupada']) {
                echo "-ocupada";
            }

            echo " mesa-fondo";
            echo "'>Mesa " . $row['id_mesa'] . " - Capacidad: " . $row['capacidad'] . "</button>";
        }

        echo "</form>";
    } else {
        echo "Error en la consulta: " . $pdo->errorInfo()[2];
    }

    // Confirmar la transacción
    $pdo->commit();
}

} catch (Exception $e) {
    // Deshacer la transacción en caso de error
    $pdo->rollBack();
    echo "Error: " . $e->getMessage();
}





// verifica que botón ha sido pulsado
if (isset($_POST['terraza_1'])) {
mostrarMesas('terraza_1', $pdo);
} elseif (isset($_POST['terraza_2'])) {
mostrarMesas('terraza_2', $pdo);
} elseif (isset($_POST['terraza_3'])) {
mostrarMesas('terraza_3', $pdo);
} elseif (isset($_POST['terraza_4'])) {
mostrarMesas('terraza_4', $pdo);
} elseif (isset($_POST['comedor_1'])) {
mostrarMesas('comedor_1', $pdo);
} elseif (isset($_POST['comedor_2'])) {
mostrarMesas('comedor_2', $pdo);
} elseif (isset($_POST['comedor_3'])) {
mostrarMesas('comedor_3', $pdo);
} elseif (isset($_POST['sala_privada_1'])) {
mostrarMesas('sala_privada_1', $pdo);
} elseif (isset($_POST['sala_privada_2'])) {
mostrarMesas('sala_privada_2', $pdo);
} elseif (isset($_POST['sala_privada_3'])) {
mostrarMesas('sala_privada_3', $pdo);
} elseif (isset($_POST['sala_privada_4'])) {
mostrarMesas('sala_privada_4', $pdo);
} else {
// Redirigir o manejar de alguna manera si se accede a esta página de manera incorrecta
// header("Location: ./home.php");
// header("Location: mostrar_mesas.php");

// exit();
}

// Establecer valores predeterminados para los filtros si no están configurados
if (!isset($_SESSION['capacidadFiltro'])) {
$_SESSION['capacidadFiltro'] = null;
}

if (!isset($_SESSION['fechaFiltro'])) {
$_SESSION['fechaFiltro'] = null;
}
?>

</div>
<div id="filtro">
<div class="filtros-separaciones">
<div class="margen-1">
    <h2 class="filtro-margin-top">Mesas Disponibles</h2>
    <form action="mostrar_mesas.php" method="post">
        <select name="capacidadFiltro" class="select-personas">
            <option disabled selected>Selecciona opción</option>
            <option value="2">2 personas</option>
            <option value="3">3 personas</option>
            <option value="4">4 personas</option>
            <option value="6">6 personas</option>
            <option value="8">8 personas</option>
            <option value="10">10 personas</option>
            <option value="15">15 personas</option>
        </select>
        <input class="aceptar-select-personas" type="submit" value="Enviar">
    </form>
    <div class="margen-2-primera">
        <div class="visible" id="capacidadFilter">
            <?php

            if (isset($_SESSION['capacidadFiltro'])) {
                //$capacidadFiltro = $_POST['capacidadFiltro'];

                echo "<div id='capacidadFilter' class='visible'>";

                filtrarMesasPorCapacidad($pdo, $_SESSION['capacidadFiltro']);
                echo "</div>";
            }
            ?>
        </div>
    </div>
</div>
<button class="botones-ocultar" onclick="toggleFilter('capacidadFilter')">Mostrar/Ocultar Filtro de Capacidad</button>
</div>

<div class="filtros-separaciones">
<div class="margen-1">
    <h2 class="filtro-margin-top">Camareros</h2>
    <h4>(Ordenados por la cantidad de mesas ocupadas)</h4>
    <br>
    <div class="margen-2-segunda">
        <?php

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Solo asignar las variables de sesión si el formulario ha sido enviado
            if (isset($_POST['capacidadFiltro'])) {
                $_SESSION['capacidadFiltro'] = $_POST['capacidadFiltro'];
            }
            if (isset($_POST['fechaFiltro'])) {
                $_SESSION['fechaFiltro'] = $_POST['fechaFiltro'];
            }
        }

        echo "<div id='camareroFilter' class='visible'>";
        mostrarCamarerosOrdenadosPorMesas($pdo);
        echo "</div>";
        ?>
    </div>
</div>
<button class="botones-ocultar" onclick="toggleFilter('camareroFilter')">Mostrar/Ocultar Filtro de Camareros</button>
</div>
</div>

<div id="historial">
<div class="filtros-separaciones">
<div class="margen-1">
    <div class="historial">
        <h2 class="filtro-margin-top">Historial</h2>
        <div class="margen-2-tercera">
            <?php
            try {
                $sqlHistorial = "SELECT
m.id_mesa,
s.nombre AS nombre_sala,
o.fecha_inicio,
o.fecha_fin
FROM
tbl_ocupacion o
JOIN tbl_mesa m ON o.id_mesa = m.id_mesa
JOIN tbl_sala s ON m.id_sala = s.id_sala
WHERE
o.fecha_fin IS NOT NULL
ORDER BY
o.fecha_inicio";

                // Ejecutar la consulta
                $resultHistorial = $pdo->query($sqlHistorial);
                // Verificar si se obtuvieron resultados
                if ($resultHistorial->num_rows > 0) {
                    // Mostrar los resultados
                    // while ($row = $resultHistorial->fetch_assoc()) {
                        while ($row = $resultHistorial->fetch(PDO::FETCH_ASSOC)) {
                        echo "<b>ID Mesa:</b> " . $row["id_mesa"] . "<br>";
                        echo "<b>Sala:</b> " . $row["nombre_sala"] . "<br>";
                        echo "<b>Fecha Inicio:</b> " . $row["fecha_inicio"] . "<br>";
                        echo "<b>Fecha Fin:</b> " . $row["fecha_fin"] . "<br>";
                        echo "<br>";
                    }
                } else {
                    echo "No se encontraron resultados";
                }
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }

            ?>
        </div>
    </div>
</div>
</div>
<div class="filtros-separaciones">
<div class="margen-1">
    <h2 class="filtro-margin-top">Historial por fecha</h2>
    <script src="./fecha.js"></script>
    <form action="mostrar_mesas.php" method="post" onsubmit="return validar_fecha()">
        <input class="select-fecha" type="date" id="fecha" name="fechaFiltro">
        <input class="aceptar-select-fecha" type="submit" value="Filtrar">
        <span id="error_fecha"></span>
    </form>
    <div class="margen-2-cuarta">
        <?php

        if (isset($_SESSION['fechaFiltro'])) {
            //$fechaFiltro = $_POST['fechaFiltro'];

            echo "<div id='fechaFilter' class='visible'>";
            filtrarMesasPorFecha($pdo, $_SESSION['fechaFiltro']);
            echo "</div>";
        }
        // mysqli_close($pdo);
        $pdo -> close();
    
        ?>
        </div>
    </div>
    <button class="botones-ocultar" onclick="toggleFilter('fechaFilter')">Mostrar/Ocultar Filtro por Fecha</button><br>
</div>
</div>
</div>

</body>

</html>