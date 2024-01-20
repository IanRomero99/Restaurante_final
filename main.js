document.addEventListener('DOMContentLoaded', function() {
    var buscar = document.getElementById("buscar");

    if (buscar) {
        buscar.addEventListener("keyup", function() {
            var valor = buscar.value;
            if (valor === "") {
                listarUsuarios('');
            } else {
                listarUsuarios(valor);
            }
        });
    } else {
        console.error('Elemento con ID "buscar" no encontrado');
    }
});


listarUsuarios(''); //listamos usuariios
// Listar usuarios
function listarUsuarios(valor) {
    var resultado = document.getElementById('resultado'); // Obtenemos el elemento con el id 'resultado' y lo guardamos en la variable resultado
    
    // Creamos una nueva instancia de FormData y agregamos la clave 'busqueda' con el valor proporcionado
    var formdata = new FormData();
    formdata.append('busqueda', valor);
    
    // Creamos un objeto XMLHttpRequest
    var ajax = new XMLHttpRequest();
    
    // Indicamos el método de envío y la URL del archivo PHP
    ajax.open('POST', './listar_usuarios.php');

    // Definimos la función que se ejecutará cuando la solicitud Ajax haya sido completada
    ajax.onload = function() {
        // Variable para construir la cadena de HTML
        var str = "";

        // Verificamos si la solicitud HTTP fue exitosa (código 200)
        if (ajax.status === 200) {
            // Parseamos la respuesta JSON del servidor
            var json = JSON.parse(ajax.responseText);

            // Variable para construir las filas de la tabla HTML
            var tabla = "";

            // Iteramos sobre cada elemento del array JSON
            json.forEach(function(item) {
                // Construimos una fila de la tabla con los datos del elemento actual
                str = "<tr><td>" + item.id_user + "</td>";
                str += "<td>" + item.nombre + "</td>";
                str += "<td>" + item.nombre_rol + "</td>"; 
                // Agregamos esta línea para mostrar el tipo de rol
                str += "<td><button type='button' id='editar' onclick='BotonEditar(" + item.id_user + ")'>Editar</button>";
                str += "</td>";
                str += "<td><button type='button' id='eliminar' onclick='BotonEliminar(" + item.id_user + ")'>Eliminar</button>";
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



// ELIMINAR USUARIO
function BotonEliminar(id_user) {
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
            //creamos nueva instancia de formdata
            var formdata = new FormData();
            //agregamos nueva clave y valor al objeto
            formdata.append('id_user', id_user);
            //creamos objeto ajax
            var ajax = new XMLHttpRequest();
            //definimos metodo, url y que sea asincrono
            ajax.open('POST', './eliminar.php', true);

            ajax.onload = function() {
                if (ajax.status === 200) { //si es exitoso obtenemos la respuesta del servidor
                    if (ajax.responseText === "ok") { //si la respuesta es "ok"
                        Swal.fire({
                            icon: 'success',
                            title: 'Usuario eliminado',
                            showCancelButton: false,
                            timer: 1500
                        });
                        listarUsuarios(valor);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error al eliminar el usuario',
                            showCancelButton: false,
                            timer: 1500
                        });
                    }
                }
            }
            ajax.send(formdata); // Envía la solicitud HTTP al servidor con los datos en 'formdata'

        }
    })
}
 // Actualizar tabla de listar usuarios cada 5 segundos (5000 milisegundos)
setInterval(function() {
    // Obtener el valor actual del campo de búsqueda
    var valor = buscar.value;

    // Llamar a la función para listar usuarios con el valor actual
    listarUsuarios(valor);
}, 1000); 




// var form_editar = document.getElementById("form_editar");

// function BotonEditar() {
//     // Verifica si el estilo actual es "block"
//     if (form_editar.style.display === "block") {
//         // Si es "block", cambia a "none" para ocultar el formulario
//         form_editar.style.display = "none";
//     } else {
//         // Si no es "block", cambia a "block" para mostrar el formulario
//         form_editar.style.display = "block";
//     }
// }

// // Agrega un listener para ocultar el formulario cuando se hace clic en él
// form_editar.addEventListener("click", function() {
//     form_editar.style.display = "none";
// });

function BotonEditar(id_user) {
    // Creamos una nueva instancia de FormData
    var formdata = new FormData(form_editar);
    // Agregamos una nueva clave y valor al objeto FormData
    formdata.append('id_user', id_user);

    // Creamos un objeto XMLHttpRequest
    var ajax = new XMLHttpRequest();
    // Definimos el método, la URL y establecemos que sea asíncrono
    ajax.open('POST', './consulta_editar.php', true);

    // Definimos la función que se ejecutará cuando la solicitud AJAX esté completa
    ajax.onload = function () {

        // Verificamos si la solicitud fue exitosa (código de estado 200)
        if (ajax.status === 200) {
            try {
                var json = JSON.parse(ajax.responseText);
                
                // Verificamos si hay datos en la respuesta JSON
                if (json.data) {
                    var json = JSON.parse(ajax.responseText);
                    document.getElementById("nombre").value = json.data.nombre;
                    document.getElementById("nombre").value = json.data.nombre;
                    document.getElementById("contra").value = json.data.contra;
                    document.getElementById("rol").value = json.data.rol;
                    document.getElementById("enviar").value = "Actualizar";
                } else {
                    // Manejar el caso en que no hay datos
                    console.log("No se encontraron datos para el usuario con ID: " + id_user);
                }
            } catch (e) {
                console.log(ajax.responseText);
                console.error("Error al parsear la respuesta JSON.");
            }
        } else {
            editar.innerHTML = "Error en la solicitud AJAX";
        }
    }

    // Enviamos la solicitud HTTP al servidor con los datos en 'formdata'
    ajax.send(formdata);
}



// EDITAR USUARIO 
function EditarUser(id_user) {
    var editar = document.getElementById("form_editar"); // Obtenemos el elemento con el id "editar"

    // Creamos una nueva instancia de FormData
    var formdata = new FormData(editar);
    // Agregamos una nueva clave y valor al objeto FormData
    formdata.append('id_user', id_user);

    // Creamos un objeto XMLHttpRequest
    var ajax = new XMLHttpRequest();
    // Definimos el método, la URL y establecemos que sea asíncrono
    ajax.open('POST', './proc_editar.php', true);

    // Definimos la función que se ejecutará cuando la solicitud AJAX esté completa
    ajax.onload = function () {

        // Verificamos si la solicitud fue exitosa (código de estado 200)
        if (ajax.status === 200) {
            var json = JSON.parse(ajax.responseText);
            try {
                if (ajax.responseText === "ok") {
                    // Si la respuesta es "ok", no es necesario parsearla
                    Swal.fire({
                        icon: 'success',
                        title: 'Usuario modificado',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    // Resetear el formulario
                    editar.reset();
                    // Refrescar el listado de registros y eliminar filtros que haya activos
                    listarUsuarios('');
                } else {
                    // Si la respuesta no es "ok", parseamos la respuesta JSON
                    var json = JSON.parse(ajax.responseText);
               
                }
            } catch (e) {
                editar.innerHTML = "Error al parsear la respuesta JSON.";
            }
        } else {
            editar.innerHTML = "Error en la solicitud AJAX";
        }
    }

    // Enviamos la solicitud HTTP al servidor con los datos en 'formdata'
    ajax.send(formdata);
}


// var form_crear = document.getElementById("form__crear");

// function BotonCrear() {
//     // Verifica si el estilo actual es "block"
//     if (form_crear.style.display === "block") {
//         // Si es "block", cambia a "none" solo si el clic no fue en un input
//         if (!event.target.tagName.toLowerCase() === 'input') {
//             form_crear.style.display = "none";
//         }
//     } else {
//         // Si no es "block", cambia a "block" para mostrar el formulario
//         form_crear.style.display = "block";
//     }
// }

// // Agrega un listener para ocultar el formulario solo si el clic no fue en un input
// form_crear.addEventListener("click", function(event) {
//     if (event.target.tagName.toLowerCase() !== 'input') {
//         form_crear.style.display = "none";
//     }
// });


function crearUser(id_user) {
    // Obtenemos el elemento con el id "form_crear"
    var crear = document.getElementById("form__crear");

    // Creamos una nueva instancia de FormData
    var formdata = new FormData(crear);

    // Agregamos nueva clave y valor al objeto FormData
    formdata.append('id_user', id_user);

    // Creamos el objeto XMLHttpRequest
    var ajax = new XMLHttpRequest();

    // Definimos método, URL y que sea asíncrono
    ajax.open('POST', './crear_usuarios.php', true);

    // Definimos la función que se ejecutará cuando la solicitud AJAX sea completada
    ajax.onload = function() {
        // Variable para construir las filas de la tabla HTM

        // Verificamos si la solicitud fue exitosa (código de estado 200)
        if (ajax.status === 200) {
            if (ajax.responseText == "ok") {
                Swal.fire({
                    icon: 'success',
                    title: 'Usuario creado',
                    showConfirmButton: false,
                    timer: 1500
                });
                // Resetear el formulario
                crear.reset();
                // Refrescar el listado de registros y eliminar filtros que haya activos
                listarUsuarios('');
                
            }else{
                console.log("ERROR");
            }
        }
                

    };

    // Enviamos la solicitud HTTP al servidor con los datos en 'formdata'
    ajax.send(formdata);
}


// Recoger los roles
// Creacion de la funcion 

function recogerRoles() {
    // Cogemos el id del provincia pais
    var rol = document.getElementById("rol_crear");

    // Creamos el una solicitud de Ajax para sacar los roles
    var ajax = new XMLHttpRequest();

    // Definimos la función que manejará la respuesta de la solicitud Ajax
    ajax.onreadystatechange = function() {
        // Si se ha completado la solicitud y la respuesta está lista
        if (ajax.status == 200 && ajax.readyState == 4) {
            // Parseamos la respuesta JSON
            var respuesta_rol = ajax.responseText;
            // console.log(ajax.responseText);
            
            try {
                // Convertimos la respuesta JSON a un array de objetos
                var rol_JSON = JSON.parse(respuesta_rol);
                
                // Limpiamos la lista de roles
                rol.innerHTML = "";
                
                var box = "";
                
                // Recorremos los roles y los añadimos a la lista
                for (var i = 0; i < rol_JSON.length; i++) {
                    box += "<option value='" + rol_JSON[i].id_rol + "'>" + rol_JSON[i].nombre_rol + "</option>";
                }
                
                rol.innerHTML = box;
            } catch (e) {
                // console.error("Error al parsear la respuesta JSON.");
            }
        }
        
    };

    // Configuramos la solicitud Ajax
    ajax.open("POST", "./rol.php", true);
    ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajax.send();
    // Enviamos la solicitud con el parámetro provincia
}
recogerRoles('');



function recogerRoles2() {
    // Cogemos el id del provincia pais
    var rol = document.getElementById("rol");

    // Creamos el una solicitud de Ajax para sacar los roles
    var ajax = new XMLHttpRequest();

    // Definimos la función que manejará la respuesta de la solicitud Ajax
    ajax.onreadystatechange = function() {
        // Si se ha completado la solicitud y la respuesta está lista
        if (ajax.status == 200 && ajax.readyState == 4) {
            // Parseamos la respuesta JSON
            var respuesta_rol = ajax.responseText;
            // console.log(ajax.responseText);
            
            try {
                // Convertimos la respuesta JSON a un array de objetos
                var rol_JSON = JSON.parse(respuesta_rol);
                
                // Limpiamos la lista de roles
                rol.innerHTML = "";
                
                var box = "";
                
                // Recorremos los roles y los añadimos a la lista
                for (var i = 0; i < rol_JSON.length; i++) {
                    box += "<option value='" + rol_JSON[i].id_rol + "'>" + rol_JSON[i].nombre_rol + "</option>";
                }
                
                rol.innerHTML = box;
            } catch (e) {
                // console.error("Error al parsear la respuesta JSON.");
            }
        }
        
    };

    // Configuramos la solicitud Ajax
    ajax.open("POST", "./rol.php", true);
    ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajax.send();
    // Enviamos la solicitud con el parámetro provincia
}
recogerRoles2('');




