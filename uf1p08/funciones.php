<?php
//// General
/// TODO Hacer comentarios extendidos
/// TODO Cambiar cuando hacemos referencia index.php a una variable
/// TODO Hacer funcion comprobar connexion con exit()
/// TODO Hacer una funcion check user con exit()
/// TODO Usar encriptación para las contraseña de los usuarios
/// TODO isset a todos los valores del formulario, pro si editan los requisitos

// Conectarse a la base de datos
function connexion() {
    // Obviando el error
    return @mysqli_connect("localhost", "root", "", "lingua");

}
function redireccionIndex() {
    header( "Refresh: 3; url = index.php");
}
function bonton_return() {
    print '<table><tr><td>';
    print '<a href="index.php"><button>Return</button></a>';
    print '</td></tr></table>';
}



//// Query's
/// TODO Poner seguridad para evitar SQL injection, Prepared Statements

// Consulta que el email y la contraseña estén en la base de datos
function query_lingua_usu(): string{
    return "SELECT email, nom, idioma_aprendre
            FROM usuaris
            WHERE (email = '".$_POST["correo"]."') and (password = '".md5($_POST["contra"])."');";
}
// Añade a la base de datos un nuevo usuario
function query_insert_usu(): string{
    return "INSERT INTO usuaris 
            VALUES ('".$_POST["correo"]."', '".md5($_POST["contra"])."', '".$_POST["nombre"]."', '".$_POST["idioma_natiu"]."', '".$_POST["idioma_aprendre"]."')";
}
// Consultamos los usuarios con el mismo idioma a aprender
function query_mostrar_aprender(): string{
    return "SELECT u.email, u.nom, ina.nom AS i_natiu , iap.nom AS i_aprendre
            FROM (usuaris AS u INNER JOIN idiomes AS ina ON u.idioma_natiu = ina.id) INNER JOIN idiomes AS iap ON u.idioma_aprendre = iap.id
            WHERE (iap.id = ".$_SESSION["idioma_aprendre"].") and (u.email != '".$_SESSION["correo"]."');";
}
// Consulta para eliminar el usuario
function query_delete_usu(): string{
    return "DELETE FROM usuaris where email = '".$_SESSION["correo"]."'";
}
// Consulta idiomes
function query_idiomas(): string {
    return "SELECT id, nom FROM idiomes";
}



//// Formulario
/// TODO Mejorar esta parte para que no sean funciones independientes form_index() form_registre()

// Header formulario index
function form_index () {
    print ('<form enctype="multipart/form-data" action="index.php?accion=sesion" method="POST">');
}
// Header formulario registro
function form_registre () {
    print ('<form enctype="multipart/form-data" action="index.php?accion=registro" method="POST">');
}
// Parte superior del formulario
function form_top(){
    print '<table>';
    print '<tr>';
    print '<td>';
    print '<label for="mail">Compte de mail:</label>';
    print '</td>';
    print '<td>';
    print ' <input type="email" name="correo" id="mail" required>';
    print '</td>';
    print '</tr>';
    print ' <tr>';
    print '<td>';
    print '<label for="pwd">Password:</label>';
    print '</td>';
    print '<td>';
    print '<input type="password" name="contra" id="pwd" required>';
    print '</td>';
    print '</tr>';
}
// Parte del centro del formulario de login
function form_mid_login() {
    print '<tr>';
    print '<td colspan="2">';
    print '<a href="index.php?accion=crear">Encara no estas registrat</a>';
    print '</td>';
    print '</tr>';
}
// Parte del centro del formulario de registro
function form_mid_create() {
    print '<tr>';
    print '<td>';
    print '<label for="nom">Nom:</label>';
    print '</td>';
    print '<td>';
    print '<input type="text" name="nombre" id="nom" required>';
    print '</td>';
    print '</tr>';
    print '<tr>';
    print '<td>';
    print '<label for="i_natiu">Idioma natiu:</label>';
    print '</td>';
    print '<td>';
    print '<select name="idioma_natiu" id="i_natiu" required>';
    form_mid_create_options();
    print '</select>';
    print '</td>';
    print '</tr>';
    print '<tr>';
    print '<td>';
    print '<label for="i_aprendre">Idioma que vols practicar: </label>';
    print '</td>';
    print '<td>';
    print '<select name="idioma_aprendre" id="i_aprendre" required>';
    form_mid_create_options();
    print '</select>';
    print '</td>';
    print '</tr>';


}
// Opciones idiomas formulario registro
function form_mid_create_options() {
    $connexion = connexion();
    $sql = query_idiomas();
    // Nos comemos el error
    $consulta = @mysqli_query($connexion, $sql);
    if ($consulta) {
        $numlinies = mysqli_num_rows($consulta);
        for ($i = 0; $i < $numlinies; $i++) {
            $linia = mysqli_fetch_assoc($consulta);
            print '<option value="'.$linia["id"].'">'.$linia["nom"].'</option>';
        }
    }
//    else {
//        print '<option value="1">Español</option>';
//    }

}
// Footer formulario
function form_bottom() {
    print '<tr>';
    print '<td colspan="2">';
    print '<input type="submit" name="envia" value="Enviar">';
    print '&nbsp';
    print '<input class="boton" type="reset" name="borrar" value="Natejar"/>';
    print '</td>';
    print '</tr>';
    print '</table>';
    print '</form>';
}



//// Validar

// Validamos la sesión con una consulta y if
function validar_sesion() {
    $connexion = connexion();
    $sql = query_lingua_usu();
    $consulta = mysqli_query($connexion, $sql);
    if (!$consulta) {
        mysqli_error($connexion);
        print ("Error en la consulta");
        redireccionIndex();
    }
    else {
        $numlinies = mysqli_num_rows($consulta);
        if ($numlinies != 1) {
            print ("Usuario incorrecto y contraseña");
            session_destroy();
            redireccionIndex();
        }
        else {
            // Formulario login enviado
            $linia_usu = mysqli_fetch_assoc($consulta);
            $_SESSION["nombre"] = $linia_usu['nom'];
            $_SESSION["idioma_aprendre"] = $linia_usu['idioma_aprendre'];
            $_SESSION["correo"] = $linia_usu["email"];
            //header( "Refresh: 5; url = index.php?accion=link");
            header("Location: index.php?accion=link");
        }

    }
}
// Validamos el registro de usuario con consulta y if
function validar_registro() {
    $connexion = connexion();
    // Formulario registro enviado
    $sql = query_insert_usu();
    $consulta = mysqli_query($connexion, $sql);
    if (!$consulta) {
        mysqli_error($connexion);
        print "Error al registrar, usuario ya registrado";
    } else {
        print "Usuario registrado";
    }
    session_destroy();
    redireccionIndex();
}



//// Mostrar

// Muestra por pantalla que no esta registrado mas un redirect
function mostrar_no_validado() {
    print "Usuario no logueado";
    redireccionIndex();
}
// Muestra el header de html
function mostrar_header() {
    print "<!doctype html>";
    print "<html lang='en'>";
    print "<head>";
    print "<title>Portal lingua</title>";
    print "<meta charset='UTF-8'>";
    print "<meta name='viewport' content='width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0'>";
    print "<meta http-equiv='X-UA-Compatible' content='ie=edge'>";
    print "<link rel='stylesheet' type='text/css' href='style.css'>";
    print "</head>";
    print "<body>";
}
// Muestra el formulario de login
function mostrar_login() {
    // Imprimir formulario login
    form_index();
    form_top();
    form_mid_login();
    form_bottom();
}
// Muestra el formulario registro
function mostrar_registre() {
    // Imprimir formulario registro
    form_registre();
    form_top();
    form_mid_create();
    form_bottom();
    bonton_return();
}
// Muestra los links
function mostrar_links() {
    if (!isset($_SESSION["correo"])) {
        mostrar_no_validado();
    }
    else {
        print "<p><a href='index.php?accion=tabla'>Veure persones que em poden ajudar</a></p>";
        print "<p><a href='index.php?accion=baixa'>Donar-me de baixa</a></p>";
        print "<p><a href='index.php?accion=sortir'>Sortir</a></p>";
    }
}
// Muestra la tabla idiomas
function mostrar_tabla() {
    $connexion = connexion();
    if (!isset($_SESSION["correo"])) {
        mostrar_no_validado();
    }
    else {
        $sql = query_mostrar_aprender();
        $consulta = mysqli_query($connexion, $sql);
        if (!$consulta) {
            mysqli_error($connexion);
            print ("Error en consulta");
        } else {
            $numlinies = mysqli_num_rows($consulta);
            if ($numlinies == 0) {
                print "<p>Hola " . $_SESSION["nombre"] . " no hay personas que te puedan ayudar</p>";
            } else {
                print "<p>Hola " . $_SESSION["nombre"] . " aqui et mostrem lers persones que et poden ajudar</p>";
                print "<table class='bordes'>";
                print "<tr class='bordes'>";
                print "<th class='bordes'>Nom</th>";
                print "<th class='bordes'>Email</th>";
                print "<th class='bordes'>Idioma natiu</th>";
                print "<th class='bordes'>Idioma que vol aprendre</th>";
                print "</tr>";
                for ($i = 0; $i < $numlinies; $i++) {
                    $linia = mysqli_fetch_assoc($consulta);
                    print "<tr class='bordes'>";
                    print "<td class='bordes'>" . $linia["nom"] . "</td>";
                    print "<td class='bordes'>" . $linia["email"] . "</td>";
                    print "<td class='bordes'>" . $linia["i_natiu"] . "</td>";
                    print "<td class='bordes'>" . $linia["i_aprendre"] . "</td>";
                    print "</tr>";
                }
                print "</table>";
            }
            print "<p><a href='index.php?accion=link'>Tornar al menu</a></p>";
        }
    }
}
// Muestra la confirmación de baixa de la base de datos
function mostrar_baixa() {
    $connexion = connexion();
    if (!isset($_SESSION["correo"])) {
        mostrar_no_validado();
    }
    else {
        $sql = query_delete_usu();
        $consulta = mysqli_query($connexion, $sql);
        if (!$consulta) {
            mysqli_error($connexion);
            print ("El usuario no existe en la base de datos");
        } else {
            print "Usuario dado de baja";
        }
        redireccionIndex();
    }
}
// Muestra la confirmación de cerrado de sesión
function mostrar_sortir() {
    if (!isset($_SESSION["correo"])) {
        mostrar_no_validado();
    }
    else {
        print "<p>Cerrando session</p>";
        session_destroy();
        redireccionIndex();
    }
}
// Muestra el footer de html
function mostrar_footer() {
    print "</body>";
    print "</html>";
}



//// Switch