function validar() {

  // Validar usuario

  // Recogemos los id del input del usuario y luego del span que hemos creado
  // Validar usuario
  var input_usuario = document.getElementById("usuario");
  var error_usuario = document.getElementById("error_usuario");
  // cogemos la variable input_usuario  y miramos si esta vacio  o contiene solo espacios
  if (input_usuario.value.trim() === "" || /^\s+$/.test(input_usuario.value)) {
    error_usuario.textContent = "El usuario está vacío";
    // Le aplicamos unos estilos para que la letra tenga color rojo, y que el input los bordes que se vean de color rojo
    error_usuario.style.color = "red";
    input_usuario.style.border = "1px solid red";
    return false;
  } else {
    // Si esta bien dejamos el input y el span vacíos
    input_usuario.style.border = "";
    error_usuario.textContent = "";
  }

  // Validar que el usuario no tenga espacios 
  var palabras = input_usuario.value.split(" ");
  // Creamos una variable de palabras válidas
  var palabrasValidas = 0;

  // Recorrera toda la palabra hasta que detecte un espacio
  for (var i = 0; i < palabras.length; i++) {
    if (palabras[i].length >= 1) {
      palabrasValidas++;
    }
  }

  // Si el usuario tiene espacios saltara el error
  if (palabrasValidas !== 1) {
    // Utilizamos el text content para añadir el texto en el span
    error_usuario.textContent = "El usuario solo debe tener una palabra";

    // Le aplicamos unos estilos para que la letra tenga color rojo, y que el input los bordes que se vean de color rojo
    error_usuario.style.color = "red";
    input_usuario.style.border = "1px solid red";
    return false;
  } else {
    // Si esta bien dejamos el input y el span vacíos
  }

  // Validar contraseña

  // Recogemos los id del input del error y luego del span que hemos creado
  var input_pwd = document.getElementById("pwd");
  var error_pwd = document.getElementById("error_pwd");


  // Cogemos la variable input_usuario  y miramos si esta vacio  o contiene solo espacios
  if (input_pwd.value.trim() === "" || /^\s+$/.test(input_pwd.value)) {
    // Utilizamos el text content para añadir el texto en el span
    error_pwd.textContent = "La contraseña está vacía";
    // Le aplicamos unos estilos para que la letra tenga color rojo, y que el input los bordes que se vean de color rojo
    error_pwd.style.color = "red";
    input_pwd.style.border = "1px solid red";
    return false;
  } else {
    // Si esta bien dejamos el input y el span vacíos
    input_pwd.style.border = "";
    error_pwd.textContent = "";
  }

  // Validar que la contraseña tiene al menos 9 caracteres
  if (input_pwd.value.length < 9) {
    // Utilizamos el text content para añadir el texto en el span
    error_pwd.textContent = "La contraseña no cumple los requisitos";
    // Le aplicamos unos estilos para que la letra tenga color rojo, y que el input los bordes que se vean de color rojo
    error_pwd.style.color = "red";
    input_pwd.style.border = "1px solid red";
    return false;
  } else {

    return true;
  }
}