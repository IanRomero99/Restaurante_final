<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Asegúrate de incluir SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="stylesheet" href="./css/mostrar.css">
</head>

<body>
    <?php
    // Inicializamos la sesión
    // session_start();

    // // Si existe creada la variable de sesión "username" le permitirá pasar al listado
    // if ($_SESSION['usuario']) {

    ?>

    <header>
        <button id="cerrarSesionBtn" class="botonLila">Cerrar Sesión</button>
    </header>

    <form action="" method="post" id="frmbusqueda">
        <div class="form-group">
            <br>
            <label for="buscar">Buscar: </label>
            <input type="text" name="buscar" id="buscar" class="form-control">

            <button type='button' id='crear_boton' onclick='BotonCrear();'>Crear</button>

            <br>
        </div>
    </form>

    <div class="container" id="container">
        <div class="row">
            <div class="card-busqueda">
                <div>
                    <table class="table table-hover table-responsive">
                        <thead>
                            <tr>
                                <th>id_sala</th>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Capacidad</th>
                                <th>Editar</th>
                                <th>Eliminar</th>
                            </tr>
                        </thead>
                        <!-- Coloca tu script después del elemento 'resultado' -->
                        <tbody id="resultado">
                            <!-- Aquí irán los datos de la tabla -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <form id="form__crear" style="display block">
            <div style="padding: 10px;">
            <div class="main_div">
            <div class="title">Crear</div>
            <!-- <img src='./src/LOGO3.png' alt="Imagen de Github"> -->
            <!-- Creamos el label para el usuario con su input de texto -->
            <div class="input_box">
                <label for="usuario_crear">Usuario:</label>
                <input type="text" id="usuario_crear" name="usuario_crear" placeholder="Nombre de usuario">
                <div class="icon"><i class="fas fa-user"></i></div>
                <br>
                <!-- El span servirá para que salten los errores de javascript -->
                <span id="error_usuario"></span>
            </div>
        
            <br>


            <br>
            <!-- Creamos el label para la contraseña con su input de texto -->
            <div class="input_box">
                <label for="contra_crear">Contraseña</label>
                <input type="password" id="contra_crear" name="contra_crear" placeholder="Contraseña">
                <div class="icon"><i class="fas fa-lock"></i></div>
                <br>
                <!-- El span servirá para que salten los errores de javascript -->
                <span id="error_contraseña"></span>
            </div>


            <div class="input_box">
    <label for="rol_crear">Rol:</label>
    <select id="rol_crear" name="rol_crear">
        <!-- Las opciones se llenarán dinámicamente desde la base de datos -->
    </select>
    <div class="icon"><i class="fas fa-lock"></i></div>
    <br>
    <!-- El span servirá para que salten los errores de javascript -->
    <span id="error_rol"></span>
</div>

<!-- Cambiamos el tipo de botón a "button" y agregamos el evento onclick -->
<input type="button" id="enviar_crear" name="enviar" value="Enviar" onclick="crearSala()">

  

</div>

            <div>
                
    </form>
                </div>
            </div>

    <script src="./main_sala.js"></script>
</body>

</html>
