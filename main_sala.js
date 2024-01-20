document.addEventListener('DOMContentLoaded', function() {
    var buscar = document.getElementById("buscar");

    if (buscar) {
        buscar.addEventListener("keyup", function() {
            var valor = buscar.value;
            if (valor === "") {
                listarsalas('');
            } else {
                listarsalas(valor);
            }
        });
    } else {
        console.error('Elemento con ID "buscar" no encontrado');
    }
});

listarsalas(''); // Listamos salas

// Listar salas
function listarsalas(valor) {
    var resultado = document.getElementById('resultado'); // Obtenemos el elemento con el id 'resultado' y lo guardamos en la variable resultado
    
    // Creamos una nueva instancia de FormData y agregamos la clave 'busqueda' con el valor proporcionado
    var formdata = new FormData();
    formdata.append('busqueda', valor);
    
    // Creamos un objeto XMLHttpRequest
    var ajax = new XMLHttpRequest();
    
    // Indicamos el método de envío y la URL del archivo PHP
    ajax.open('POST', './listar_salas.php');

    // Definimos la función que se ejecutará cuando la solicitud Ajax haya sido completada
    ajax.onload = function() {
        // Variable para construir las filas de la tabla HTML
         tabla = "";

        // Verificamos si la solicitud HTTP fue exitosa (código 200)
        if (ajax.status === 200) {
            // Parseamos la respuesta JSON del servidor
            var json = JSON.parse(ajax.responseText);

            // Iteramos sobre cada elemento del array JSON
            json.forEach(function(item) {
                // Construimos una fila de la tabla con los datos del elemento actual
                let str = "<tr><td>" + item.id_sala + "</td>";
                str += "<td>" + item.nombre + "</td>";
                str += "<td>" + item.tipo_sala + "</td>"; 
                str += "<td>" + item.capacidad + "</td>"; 
                // Agregamos esta línea para mostrar el tipo de rol
                str += "<td><button type='button' id='editar' onclick='BotonEditar(" + item.id_sala + ")'>Editar</button>";
                str += "</td>";
                str += "<td><button type='button' id='eliminar' onclick='BotonEliminar(" + item.id_sala + ")'>Eliminar</button>";
                str += "</td>";
                str += "</tr>";
                tabla += str;
                
            });

            // Insertamos la tabla construida en el elemento con id 'resultado'
            resultado.innerHTML = tabla;
        } else {
            // En caso de error, mostramos un mensaje de error en el elemento 'resultado'
            resultado.innerText = "Error en la solicitud Ajax";
        }
    };

    // Enviamos la solicitud HTTP al servidor con los datos en 'formdata'
    ajax.send(formdata);
}

// ELIMINAR SALA
function BotonEliminar(id_sala) {
    Swal.fire({
        title: "¿Estás seguro?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí",
        cancelButtonText: "No"
    }).then((result) => {
        if (result.isConfirmed) {
            // Creamos una nueva instancia de FormData
            var formdata = new FormData();
            // Agregamos una nueva clave y valor al objeto FormData
            formdata.append('id_sala', id_sala);
            // Creamos un objeto XMLHttpRequest
            var ajax = new XMLHttpRequest();
            // Definimos el método, la URL y establecemos que sea asíncrono
            ajax.open('POST', './eliminar_salas.php', true);

            ajax.onload = function() {
                if (ajax.status === 200) { // Si la solicitud es exitosa
                    if (ajax.responseText === "ok") { // Si la respuesta es "ok"
                        Swal.fire({
                            icon: 'success',
                            title: 'Sala eliminada',
                            showCancelButton: false,
                            timer: 1500
                        });
                        // Listar salas nuevamente después de eliminar
                        listarsalas('');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error al eliminar la sala',
                            showCancelButton: false,
                            timer: 1500
                        });
                    }
                }
            };
            // Enviamos la solicitud HTTP al servidor con los datos en 'formdata'
            ajax.send(formdata);
        }
    });
}

// Actualizar tabla de listar salas cada 5 segundos (5000 milisegundos)
setInterval(function() {
    // Obtener el valor actual del campo de búsqueda
    var valor = buscar.value;

    // Llamar a la función para listar salas con el valor actual
    listarsalas(valor);
}, 1000);  // Reducí el intervalo de actualización a 5 segundos (5000 milisegundos)


function crearSala(id_user) {
    // Obtenemos el elemento con el id "form_crear"
    var crear = document.getElementById("form__crear");

    // Creamos una nueva instancia de FormData
    var formdata = new FormData(crear);

    // Agregamos nueva clave y valor al objeto FormData
    formdata.append('id_user', id_user);

    // Creamos el objeto XMLHttpRequest
    var ajax = new XMLHttpRequest();

    // Definimos método, URL y que sea asíncrono
    ajax.open('POST', './crear_salas.php', true);

    // Definimos la función que se ejecutará cuando la solicitud AJAX sea completada
    ajax.onload = function() {
        // Variable para construir las filas de la tabla HTM

        // Verificamos si la solicitud fue exitosa (código de estado 200)
        if (ajax.status === 200) {
            if (ajax.responseText == "ok") {
                Swal.fire({
                    icon: 'success',
                    title: 'Sala creada',
                    showConfirmButton: false,
                    timer: 1500
                });
                // Resetear el formulario
                crear.reset();
                // Refrescar el listado de registros y eliminar filtros que haya activos
                listarsalas('');
                
            }else{
                console.log("ERROR");
            }
        }
    }

    };