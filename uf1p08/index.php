<?php

    include_once("funciones.php");

    // debug =t rue ... outputs debugging information
    $debug = true;

    // Header
    mostrar_header();
    print ("<h1>Portal Lingua</h1>");

    // Conectarse a la base de datos
    $connexion = connexion();

    if ($connexion) { // Comprobar connexion // TODO cambiar y usar function exit()
        session_start();
        if (!isset($_GET["accion"])) {
            // Imprimir formulario login
            mostrar_login();
        }
        else {
            switch ($_GET["accion"]) { // TODO poner en una funcion
                case "sesion":
                    validar_sesion($connexion);
                    break;
                case "crear":
                    // Imprimir formulario registro
                    mostrar_registre();
                    break;
                case "registro":
                    // Validar que el usuario este ne la base de datos
                    validar_registro($connexion);
                    break;
                case "link":
                    // Muestra los links
                    mostrar_links();
                    break;
                    // Muestra en una tabla los usuarios con el mismo idioma a aprender
                case "tabla":
                    mostrar_tabla($connexion);
                    break;
                    // Dar de baja el usuario logged
                case "baixa":
                    mostrar_baixa($connexion);
                    break;
                    // Cerrar sesión
                case "sortir":
                    mostrar_sortir();
                    break;
                default:
                    // Por defecto
                    print "Error 404, te estamos redireccion a index";
                    redireccionIndex();
                }
            }

    }
    else {
        print "<h1>No se podido conectar a la base de datos</h1>";
        exit(0);
    }

    // Footer
    mostrar_footer();