<!DOCTYPE html>
<html>

<head lang="es">
    <title>Iniciar Sesion</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href=".//css//estilos.css">
</head>

<body>
<?php

/**
 * funcion login.php
 */

    
    
print "<p>";print_r($_REQUEST);print "</p>";

    $query_autenticacion = "select Login,Password,rol_id from usuarios where Login=? and Password=?";
    $query_recup_rol="select rol.nombre from rol where rol.id=?";
    
    $login = recoge("login");
    $password = recoge("pass");
    $db = conectaDb("noticias","root","");    
    $result = $db->prepare($query_autenticacion);
    if ((strcmp($login,"")==0) || (strcmp($password,"")==0)) 
    {
        print "<br>".(strcmp($login,""));
        print "<br>".(strcmp($password,""));
        print "<p>Error de credenciales</p>\n";
    } else 
    {
        
    
        $result->bindParam(1, $login, PDO::PARAM_STR, 20);
        $result->bindParam(2, $password, PDO::PARAM_STR, 20);           
        $result->execute();

// ***** comprueba que result tiene algo
        if (!$result) 
        {
            print "<p>Error en la consulta.</p>\n";
        } 
        else 
        {
            if ($result->rowCount() <= 0) 
            {
                print "<br>No existe ningún usuario con ese nombre";
            } 
            else 
            {
                //chicha.
                print "Autenticado";
                session_start();
                // Comprobamos si la variable ya existe
                if (isset($_SESSION['visitas'])) {
                    $_SESSION['visitas'] ++;
                } else {
                    $_SESSION['visitas'] = 0;
                }

                //0.01 Recuperar rol de usuario
                $q_recupera_rol;
                  //0.1 Grabar cookie de usuario 'login' si es nuevo o hay cambios
                    //0.1.a Si 'login' es usuario grabar cookie tiempo infinito
                    //0.1.b Si 'login' es otro grabar cookie para 1 mes.
                //1. 
                // 1.a Si rol es usuario lanzar y visionar consultas usuario.
                // 1.b Si rol es profesor lanzar y visionar consultas profesor.
                
            }
        }
        $db = null; //hemos terminado. session_unset.???
    }

    /**
     * Función que graba cookies
     * @param type $nombre ; El nombre de la cookie
     * @param type $dato   ; Los datos que vas a guardar en la cookie
     * @param type $duracion ; Lo que deseas que dure.
     */
    function graba_cookie($nombre,$dato,$duracion)
    {
        setcookie($name,$dato,$duracion);
    }
    
    
        /**
         * Función que quita los caracteres especiales de la $var que recupere de ·$_REQUEST
         * @param Cadena $var ; el nombre de la var que queremos extraer de $_REQUEST.
         * @return Devuelve un texto sin blancos, ni slashes, ni caracteres especiales.
         */
        function recoge($var) {
            $tmp = (isset($_REQUEST[$var])) ? strip_tags(trim(htmlspecialchars($_REQUEST[$var], ENT_QUOTES, "utf-8"))) : "";
            if (get_magic_quotes_gpc()) {
                $tmp = stripslashes($tmp);
            }
            //$tmp = recorta($var, $tmp);
            return $tmp;
        }
//
        /**
         * FUNCIÓN DE CONEXIÓN CON LA BASE DE DATOS MYSQL
         * @param cadena $esquema - Esquema al que nos queremos conectar.
         * @return Un dataset a la BD a la que nos queremos conectar o una excepción.
         */
        function conectaDb($esquema, $bduser, $bdpass) {
            try {
                $db = new PDO("mysql:host=localhost;dbname=".$esquema, $bduser, $bdpass);
                $db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
                return($db);
            } catch (PDOException $e) 
            {
               print "<p>Error: No puede conectarse con la base de datos.</p>\n";
               exit();
            }
        }
?>
</body>