<!DOCTYPE html>
<html>
<head>
  <meta  charset="UTF-8">
  <title>Login </title>
  <!--<link href=<link rel="stylesheet" type="text/css" href=".//css//estilos.css">--> 
</head>

<body>
<?php
   $bd_url="localhost";
   $esquema="noticias";
   $bd_user="root";
   $bd_pass="";
   
   $host  = $_SERVER['HTTP_HOST'];
   $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
//   $uri=  $_SERVER['PHP_SELF'];
//    print "<br>HOST :$host";
//    print "<br>URI :$uri";
   $pagina_destino="index.php";
    // 1. Comprobamos si ya tenemos credenciales guardadas  
//       print "<br>cookiesss ";print_r($_COOKIE);
//        print "<br>". $_COOKIE['login'];
//       print "<br>1 ".isset($_COOKIE["login"])."  2 ".isset($_COOKIE["pass"]);
   
   $intentado=0;
    if (isset($_COOKIE['login']) && isset($_COOKIE['pass']))
    {
        print "<br>Hay cookies";
        $usuario=$_COOKIE['login'];
        $password=$_COOKIE['pass'];
        if (isset($_COOKIE['intentado']))
        {
           $intentado=$_COOKIE['intentado'];        
        }
            
        if (!$intentado) // probamos con las credenciales guardadas.
        {
            //Accedio con exito la ultima vez
            print "<br>Es la 1º vez que accede con este navegador";
           if( autentica($bd_url,$esquema,$bd_user,$bd_pass,$usuario,$password))
           {
               print "<br>Es un usuario con credenciales en buen estado";
               graba_session($bd_url, $esquema, $bd_user, $bd_pass, $usuario, $password);               
               print "<br>Location: http://$host/proyecto_noticias/index.php";
//               header("Location: http://$host/proyecto_noticias/index.php");                    
           }                           
           else 
           {
                print "<br>Fallo de autenticación con cookies";
//                print "<br>Es un usuario con cookies que cambio sus credenciales";
                setcookie("intentado","true",60, "/"); //grabo el intento
                usa_formulario(0,$bd_url, $esquema, $bd_user, $bd_pass);
           }
                        
        }
    }
    else //no tiene credenciales guardadas en cookies. 
    {    
       print "<br>No tienes cookies , es tu 1º vez";
       usa_formulario(1,$bd_url, $esquema, $bd_user, $bd_pass);           
    }
        
    function usa_formulario($grabar_cookies,$bd_url, $esquema, $bd_user, $bd_pass)
    {   
//         print_r($_POST);
       // No hay cookies guardadas
       global $host,$uri,$pagina_destino;
       
       if (isset($_POST['login']) && isset($_POST['pass'])) 
       {
          $usuario = $_POST['login'];
          $password = md5($_POST['pass']);
//          print "<br>$usuario|$password";
          
//         echo("<br>usa_formulario_llamando a autentica  :".autentica($bd_url, $esquema, $bd_user, $bd_pass, $usuario, $password)."<br>");
          if (autentica($bd_url, $esquema, $bd_user, $bd_pass, $usuario, $password)==1)
          { 
             //guardo cookies       
//             print "<br>Usando formulario";
             $longevidad=graba_session($bd_url, $esquema, $bd_user, $bd_pass,$usuario,$password);
             if($grabar_cookies===1)
             {
//                print "<br>longevidad :$longevidad";
                $longevidad=  determina_longevidad(determina_rol($bd_url, $esquema, $bd_user, $bd_pass, $usuario, $password));
                graba_cookies_credenciales($usuario,$password,$longevidad);
                print "<br>Grabando cookies de credenciales";
             }
             print "<br>Location: http://$host/proyecto_noticias/index.php";
//           header("Location: http://$host/proyecto_noticias/index.php"); 
          }
          else
          {
              print "<br>No autentica por motivos desconocidos. Contacte con su adm.";
          }
       }
       else
       {         
          print "Error de credenciales. Prueba otra vez.";
          print_r($_POST);
    }
        
    }
       
    function graba_cookies_credenciales($usuario,$password,$longevidad)
    {
        print "<br>Longividad cookie : $longevidad";
        
        $exito_login=setcookie("login",$usuario,time()+$longevidad, "/");
        $exito_pass=setcookie("pass",$password,time()+$longevidad, "/");
        print "<br>cookies de credenciales guardadas ";
    }
    
    function determina_longevidad($rol_recuperado)
    {
        switch ($rol_recuperado) // Esto es por si quiero distintos tiempos de cookie
        {                        
            case "1":$duracion=30*24*60*60;break;
            default:$duracion=365*24*60*60;
        }
        return($duracion);
    }
    
    function graba_session($bd_url, $bd_esquema, $bd_user, $bd_pass,$usuario,$password)
    {
//        print "<br>Graba_cookie_rol_recuperado $bd_url, $bd_esquema, $bd_user, $bd_pass,$login,$password"   ;
        
        $rol_recuperado=  determina_rol($bd_url, $bd_esquema, $bd_user, $bd_pass,$usuario,$password);
        $duracion=  determina_longevidad($rol_recuperado);
        //inicio sesión y grabo
        session_start();
        $_SESSION['usuario']=$usuario;
        $_SESSION['usuario']=$rol_recuperado;
        print "<br>Sesión grabada";
    }
    
    function determina_rol($bd_url,$bd_esquema,$bd_user,$bd_pass,$login,$password)
    {
//        print "<br>determina_rol $bd_url,$bd_esquema,$bd_user,$bd_pass,$login,$password";
        $dwes=conecta_bd($bd_url, $bd_esquema, $bd_user, $bd_pass);
        
//        print "<br> usuarioRol : $login - Rolpass : $password <br>";
        //select Rol_id from usuarios where Login like 'fulano' AND Password like 'e10adc3949ba59abbe56e057f20f883e';
        $resultado = $dwes->prepare('select Rol_id from usuarios where Login like :login AND Password like :passwd');       
        $resultado->execute(Array(':login' => $login,':passwd'=>$password));
       
        $numfilas=$resultado->rowCount();
        
//        print "<br>numfilasrol :$numfilas";
        
        if($numfilas>0) 
        {
            $fila = $resultado->fetch();
            return($fila['Rol_id']);
            print "<br>Usuario autenticado";
        }
        else 
        {
            // Si las credenciales no son válidas, se vuelven a pedir
            $error = "<br>Fallo al recuperar el Rol";
            print "$error";
            return(false);
        }
        
        unset($resultado);
        
        cierra_db($dwes);
    }
    
    function cierra_db($bd)
    {
        try
        {
            unset($bd);
        }
        catch (Exception $e)
        {
            die("Error cerrando: " . $e->getMessage());
        }
    }
    
    function conecta_bd($bd_url,$bd_esquema,$bd_user,$bd_pass)
    {
//        print "<br>Conecta :$bd_url|$bd_esquema|$bd_user|$bd_pass";
        try 
        {
            $opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
            $dsn = "mysql:host=$bd_url;dbname=$bd_esquema";
            $dwes = new PDO($dsn, $bd_user, $bd_pass, $opc);
            if (!$dwes) {
            print "<br>Fallo.Revisa conexión con BD";
        }
    }
        catch (PDOException $e) {
            print "<br>Excepcion con la BD : $e";
        }
        return $dwes;
    }
    
    function autentica($url,$esquema,$bd_user,$bd_pass,$login,$password)
    {
        $encontrado=FALSE;
        
//        print "<br>Autentica :$url|$esquema|$bd_user|$bd_pass|$login|$password";
        
        $dwes=conecta_bd($url,$esquema,$bd_user,$bd_pass);
        $resultado = $dwes->prepare('select * from usuarios where Login like :login AND Password like :passwd');
      
  
        $resultado->execute(Array(':login' => $login,':passwd'=>$password));
       
        
        $numero_filas = $resultado->rowCount() ; 
        
//        print "<br>numero filas :$numero_filas";
        if ( $numero_filas > 0 )
        {
           $encontrado=TRUE;        
        }
       
        
        unset($resultado);
        cierra_db($dwes);
        
        
        return($encontrado);
    }
?>


</body>
</html>