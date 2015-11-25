<!DOCTYPE html>
<html>
<head>
  <meta  charset="UTF-8">
  <title>Login </title>
  <!--<link href=<link rel="stylesheet" type="text/css" href=".//css//estilos.css">--> 
</head>

<body>
<?php

   include_once 'util.php';
   
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
           if( autentica($bd_url,$esquema,$bd_user,$bd_pass,$usuario,$password))
           {
               print "<br>Es un usuario con credenciales en buen estado";
               graba_session($bd_url, $esquema, $bd_user, $bd_pass, $usuario, $password);               
              
               header("Location: http://www.google.es");                    
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

       // No hay cookies guardadas
       global $host,$uri,$pagina_destino;
       
       if (isset($_POST['login']) && isset($_POST['pass'])) 
       {
          $usuario = $_POST['login'];
          $password = md5($_POST['pass']);
//          print "<br>$usuario|$password";
          
          if (autentica($bd_url, $esquema, $bd_user, $bd_pass, $usuario, $password)==1)
          { 
             $longevidad=graba_session($bd_url, $esquema, $bd_user, $bd_pass,$usuario,$password);
             if($grabar_cookies===1)
             {
                $longevidad=  determina_longevidad(determina_rol($bd_url, $esquema, $bd_user, $bd_pass, $usuario, $password));
                graba_cookies_credenciales($usuario,$password,$longevidad);
             }
             header("Location: http://www.google.es"); 
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
    
   
       
?>


</body>
</html>