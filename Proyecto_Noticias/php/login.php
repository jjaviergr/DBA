<!DOCTYPE html>
<html>
<head>
  <meta  charset="UTF-8">
  <title>Login </title>
  <link href=<link rel="stylesheet" type="text/css" href=".//css//estilos.css"> 
</head>

<body>
<?php
    // Comprobamos si ya se ha enviado el formulario
    if (isset($_POST['enviar'])) {
        $usuario = $_POST['login'];
        $password = $_POST['pass'];
       
        if (empty($usuario) || empty($password)) 
            $error = "Debes introducir un nombre de usuario y una contraseña";
        else {
            print "mmm";
            try 
            {
                $opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
                $dsn = "mysql:host=localhost;dbname=noticias";
                $dwes = new PDO($dsn, "root", "", $opc);
            }
            catch (PDOException $e) {
                die("Error: " . $e->getMessage());
            }

            // Ejecutamos la consulta para comprobar las credenciales
            $query_autenticacion = "select Login,Password,rol_id from usuarios where Login=? and Password=?";
            $resultado = $dwes->prepare($query_autenticacion);
            $resultado->bindParam(1, $login, PDO::PARAM_STR, 20);
            $resultado->bindParam(2, $password, PDO::PARAM_STR, 20);           
            $resultado->execute();
   /*         $sql = "SELECT usuario FROM usuarios " .
            "WHERE usuario='$usuario' " .
            "AND contrasena='" . md5($password) . "'"; */
            
            
            if($resultado = $dwes->query($sql)) 
            {
                $fila = $resultado->fetch();
                if ($fila != null) {
                    session_start();
                    $_SESSION['usuario']=$usuario;
                   
                    print "<br>Usuario autenticado";
                    /*recuperar rol usuario
                     * 
                     *grabo cookie con id rol.
                     * mando a pagina noticias.
                     */
//                   
                    $rol_recuperado=$fila['rol_id'];
                    switch ($rol_recuperado) // Esto es por si quiero distintos tiempos de cookie
                    {                        
                        case 2:$duracion=30*24*60*60;break;
                        default:$duracion=365*24*60*60;
                    }
                    $name="usuario";
                    $dato=$rol_recuperado;
                    
                    setcookie($name,$dato,$duracion);
                    
                    $host  = $_SERVER['HTTP_HOST'];
                    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'noticias.php';
                    header("Location: http://$host/$extra");
//                    header("Location: http://$host$uri/$extra");
//                    exit;
            }
            else 
            {
                // Si las credenciales no son válidas, se vuelven a pedir
                $error = "Usuario o contraseña no válidos!";
            }
                unset($resultado);
            }
            unset($dwes);
           
        }
   }
?>


</body>
</html>