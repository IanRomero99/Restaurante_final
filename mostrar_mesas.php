<?php
session_start();
include_once("./inc/conexion.php");

// Comprobar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header('Location: ./formulario.php'); // Redirige a la página de inicio de sesión
    exit();
}
// Asigna un valor a la variable area, si ya tiene uno tendrá ese valor recogido por get y sino será null
$area = isset($_GET['area']) ? $_GET['area'] : null;
// $table = isset($_GET['table']) ? $_GET['table'] : null;
?>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    $(document).ready(function() {
        $(".comedor_opciones, .sala_opciones, .terraza_opciones").hide();

        <?php
        // Enseña y oculta diferentes elementos
        if ($area == 'terraza') {
            echo '$(".terraza_opciones").show(); $(".comedor_opciones, .sala_opciones").hide();';
        } elseif ($area == 'comedor') {
            echo '$(".comedor_opciones").show(); $(".terraza_opciones, .sala_opciones").hide();';
        } elseif ($area == 'sala') {
            echo '$(".sala_opciones").show(); $(".terraza_opciones, .comedor_opciones").hide();';
        }
        ?>
    });
</script>

<header>
    <div>
        <a href="./mostrar_mesas.php"><img id="logo" src="./src/LOGO3.png" alt="logo"></a>
    </div>
    <div class="responsive-header">
        <form method="get" action="mostrar_mesas.php">
            <input type='hidden' name='area' value='terraza'>
            <input type='submit' name='table' value="Terrazas" class="secciones-secund">
        </form>
        <form method="get" action="mostrar_mesas.php">
            <input type='hidden' name='area' value='comedor'>
            <input type='submit' name='table' value="Comedores" class="secciones-secund">
        </form>
        <form method="get" action="mostrar_mesas.php">
            <input type='hidden' name='area' value='sala'>
            <input type='submit' name='table' value="Salas Privadas" class="secciones-secund">
        </form>
        <a href="./estadisticas.php" class="secciones">
            <p class="extras">Estadísticas</p>
        </a>
        <a href="./cerrar_sesion.php" class="secciones">
            <p type="submit" name="cerrar_sesion" class="extras">Cerrar sesión</p>
        </a>
    </div>
    <hr class="hr-header">
    <!-- TERRAZAS -->
    <div class="flex terraza_opciones salas ">
        <form method="post" action="mostrar_mesas.php">
            <input type='hidden' name='area' value='terraza'>
            <input type='submit' name='terraza_1' value="Terraza 1" class="secciones-secund">
        </form>

        <form method="post" action="mostrar_mesas.php">
            <input type='hidden' name='area' value='terraza'>
            <input type='submit' name='terraza_2' value="Terraza 2" class="secciones-secund">
        </form>

        <form method="post" action="mostrar_mesas.php">
            <input type='hidden' name='area' value='terraza'>
            <input type='submit' name='terraza_3' value="Terraza 3" class="secciones-secund">
        </form>

        <form method="post" action="mostrar_mesas.php">
            <input type='hidden' name='area' value='terraza'>
            <input type='submit' name='terraza_4' value="Terraza 4  " class="secciones-secund">
        </form>
    </div>
    <!-- COMEDOR -->
    <div class=' flex comedor_opciones salas '>
        <form method="post" action="mostrar_mesas.php">
            <input type='hidden' name='area' value='sala'>
            <input type='submit' name='comedor_1' value="Comedor 1" class="secciones-secund">
        </form>
        <form method="post" action="mostrar_mesas.php">
            <input type='hidden' name='area' value='sala'>
            <input type='submit' name='comedor_2' value="Comedor 2" class="secciones-secund">
        </form>
        <form method="post" action="mostrar_mesas.php">
            <input type='hidden' name='area' value='sala'>
            <input type='submit' name='comedor_3' value="Comedor 3" class="secciones-secund">
        </form>
    </div>
    <!-- SALA -->
    <div class=' flex sala_opciones salas '>
        <form method="post" action="mostrar_mesas.php">
            <input type='hidden' name='area' value='sala'>
            <input type='submit' name='sala_privada_1' value="Sala Privada 1" class="secciones-secund">
        </form>
        <form method="post" action="mostrar_mesas.php">
            <input type='hidden' name='area' value='sala'>
            <input type='submit' name='sala_privada_2' value="Sala Privada 2" class="secciones-secund">
        </form>
        <form method="post" action="mostrar_mesas.php">
            <input type='hidden' name='area' value='sala'>
            <input type='submit' name='sala_privada_3' value="Sala Privada 3" class="secciones-secund">
        </form>
        <form method="post" action="mostrar_mesas.php">
            <input type='hidden' name='area' value='sala'>
            <input type='submit' name='sala_privada_4' value="Sala Privada 4" class="secciones-secund">
        </form>

    </div>
</header>

<?php
// Función para mostrar las mesas ocupadas por los camareros que más mesas han ocupado
try {
    // Consulta SQL para mostrar los camareros ordenados por la cantidad de mesas que han ocupado
    $sqlCamareros = "
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
    $stmtCamareros->execute();

    // Obtén todos los resultados como un array asociativo
    $resultCamareros = $stmtCamareros->fetchAll(PDO::FETCH_ASSOC);

    if (!$resultCamareros) {
        die("Error en la consulta: " . $stmtCamareros->errorInfo()[2]);
    } else {
        if ($stmtCamareros->rowCount() > 0) {
            foreach ($resultCamareros as $row) {
                echo "<p>------------------------</p>";
                echo "<p><b>Camarero:</b> " . $row['nombre'] . " <br> <b>Mesas Ocupadas:</b> " . $row['num_mesas_ocupadas'] . "</p>";

                if ($row['num_mesas_ocupadas'] > 0) {
                    echo "<br><b><p>Mesas Ocupadas:</p></b>";

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
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


// verifica si se han enviado parametros por post
if ($_SERVER["REQUEST_METHOD"] == "POST") {
if (isset($_POST['capacidadFiltro'])) {
    $_SESSION['capacidadFiltro'] = $_POST['capacidadFiltro'];
}
if (isset($_POST['fechaFiltro'])) {
    $_SESSION['fechaFiltro'] = $_POST['fechaFiltro'];
}
}

    

// verifica si se han enviado parametros por post
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['capacidadFiltro'])) {
        $_SESSION['capacidadFiltro'] = $_POST['capacidadFiltro'];
    }
    if (isset($_POST['fechaFiltro'])) {
        $_SESSION['fechaFiltro'] = $_POST['fechaFiltro'];
    }
}

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


// verificamos que el formulario ha sido enviado por post y si contiene un campo llamado capacidadFiltro


    function filtrarMesasPorCapacidad($pdo, $capacidadFiltro)
    {
        try {
            // Consulta SQL para filtrar mesas por capacidad
            $sqlFiltro = "SELECT m.id_mesa, m.capacidad, s.nombre as sala_nombre 
                FROM tbl_mesa m 
                INNER JOIN tbl_sala s ON m.id_sala = s.id_sala 
                WHERE m.capacidad = ? AND m.ocupada = 0 
                ORDER BY m.capacidad";
    
            $stmtFiltro = $pdo->prepare($sqlFiltro);
            $stmtFiltro->bindParam(1, $capacidadFiltro, PDO::PARAM_INT);
            $stmtFiltro->execute();
    
            $resultFiltro = $stmtFiltro->fetchAll(PDO::FETCH_ASSOC);
            echo "<h3>Filtradas por capacidad: $capacidadFiltro personas</h3><br>";
    
            if ($resultFiltro) {
                foreach ($resultFiltro as $row) {
                    echo "<p>Mesa: " . $row['id_mesa'] . " - Capacidad: " . $row['capacidad'] . " <br> Sala: " . $row['sala_nombre'] . "</p><br>";
                }
            } else {
                echo "<br>";
                echo "<p>No hay mesas disponibles con la capacidad seleccionada.</p>";
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
?>


<!DOCTYPE html>
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
        <div id="restaurante">

            <?php
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