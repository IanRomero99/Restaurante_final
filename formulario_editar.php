<!DOCTYPE html>
<html>

<head>
    <title>Formulario de Datos Personales</title>
    <link rel="stylesheet" type="text/css" href="./css/formulario.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <link rel="shortcut icon" href="./src/LOGO3.png" type="image/x-icon">
    <!-- ESTILOS -->
    <style>
        .error-container {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .icon {
            margin-bottom: 20px;
            margin-top: 20px;
        }

        img {
            max-width: 300px;
            height: auto;
            margin-top: 25px;
            margin: 0 auto;
            /* Cambié float a margin para centrar la imagen */
            padding-right: 125px;
            margin-left: 57px;
        }
    </style>


</head>

<body>
    <!-- Llamar al validaciones.js -->
    <script src="./validaciones.js"></script>
    <!-- Creación del formulario que cuando se envie llamara a la funcion validar que se encuentra al principio de validaciones. -->
    <form action="./proc_formulario.php" method="post" onsubmit="return validar()">


        <div class="main_div">
            <div class="title">Editar</div>
            <!-- <img src='./src/LOGO3.png' alt="Imagen de Github"> -->
            <!-- Creamos el label para el usuario con su input de texto -->
            <div class="input_box">
                <label for="usuario">Usuario:</label>
                <input type="text" id="usuario" name="usuario" placeholder="Nombre de usuario">
                <div class="icon"><i class="fas fa-user"></i></div>
                <br>
                <!-- El span servirá para que salten los errores de javascript -->
                <span id="error_usuario"></span>
            </div>
        
            <br>


            <br>
            <!-- Creamos el label para la contraseña con su input de texto -->
            <div class="input_box">
                <label for="text">Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos" placeholder="Apellidos">
                <div class="icon"><i class="fas fa-lock"></i></div>
                <br>
                <!-- El span servirá para que salten los errores de javascript -->
                <span id="error_apellidos"></span>
            </div>


    <div class="input_box">
    <label for="rol">Rol:</label>
    <select id="rol" name="rol">
        <option value="admin">Admin</option>
        <option value="camarero">Camarero</option>
        <option value="mantenimiento">Mantenimiento</option>
    </select>
    <div class="icon"><i class="fas fa-lock"></i></div>
    <br>
    <!-- El span servirá para que salten los errores de javascript -->
    <span id="error_rol"></span>
</div>


            <br>
            <br>
            <br>
<!-- Creamos una clase que lo que hará que cuando salten las validaciones de php se muestre la letra en color rojo -->
            <div class="error-container">
                <?php if (isset($_GET['nombreNotExist'])) {
                    echo "El usuario o la contraseña esta incorrecto";
                } ?>
                <?php if (isset($_GET['passwdIncorrect'])) {
                    echo "El usuario o la contraseña esta incorrecto";
                } ?>
                <br>
            </div>
            <!-- Por último el boton de enviar, recordemos que cuando cliquemos se ira a proc_formulario -->
            <div class="input_box button">
                <input type="submit" name="enviar" value="Enviar">
            </div>

        </div>
        </div>
    </form>

</body>

</html>

</html>