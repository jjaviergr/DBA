<!DOCTYPE html>
<html>

<head lang="es">
    <title>Iniciar Sesion</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href=".//css//estilos.css">
</head>

<body>
    <?php
    include_once 'php/util.php';
   
   $bd_url="localhost";
   $esquema="noticias";
   $bd_user="root";
   $bd_pass="";
   
  
       //coger cookies
       //   si hay cookies autenticar
       //           si autentica ir al header
       //   si no autentican cookies o no hay cookies ir a login y autenticar       
       //           si autentica grabar cookies, grabar sesion y ir al header
       //           si no autentica mostrar mensaje de error ...
   if (isset($_COOKIE['login']) && isset($_COOKIE['pass']))
   {
       $usuario=$_COOKIE['login'];
       $password=$_COOKIE['pass'];
        if( autentica($bd_url,$esquema,$bd_user,$bd_pass,$usuario,$password))
           {               
               graba_session($bd_url, $esquema, $bd_user, $bd_pass, $usuario, $password);               
               header("Location: http://www.google.es");                    
           }
   }
   if ((!isset($_COOKIE['login']) && isset($_COOKIE['pass'])) || (!autentica($bd_url,$esquema,$bd_user,$bd_pass,$usuario,$password)))
   {
       muestra_formulario();
       if (autentica($bd_url,$esquema,$bd_user,$bd_pass,$usuario,$password))
       {
           graba_cookies_credenciales($usuario, $password, 365*24*60*60);
           graba_session($bd_url, $bd_esquema, $bd_user, $bd_pass, $usuario, $password);
           header("Location: http://www.google.es");                    
       }
       if (!autentica($bd_url,$esquema,$bd_user,$bd_pass,$usuario,$password))
       {
                print "Error de autenticacion";
                muestra_formulario();
       }
   }
      
   function graba_session($usuario,$password)
   {
       global $bd_url, $bd_esquema, $bd_user, $bd_pass;
       
        $rol_recuperado=  determina_rol($bd_url, $bd_esquema, $bd_user, $bd_pass,$usuario,$password);
        session_start();
        $_SESSION['usuario']=$usuario;
        $_SESSION['rol']=$rol_recuperado;
   }
    
   /**
     * Funcion que recupera el rol de la tabla de usuarios de la BD noticias
     * @param type $bd_url
     * @param type $bd_esquema
     * @param type $bd_user
     * @param type $bd_pass
     * @param type $login
     * @param type $password
     * @return type
     */
    function determina_rol($login,$password)
    {
        global $bd_url,$bd_esquema,$bd_user,$bd_pass;
        
        $dwes=conecta_bd($bd_url, $bd_esquema, $bd_user, $bd_pass);        
        $resultado = $dwes->prepare('select rol_nombre from usuarios where login like :login AND password like :passwd');       
        $resultado->execute(Array(':login' => $login,':passwd'=>$password));
        $numfilas=$resultado->rowCount();                
        if($numfilas>0) 
        {
            $fila = $resultado->fetch();
            return($fila['Rol_id']);
            print "<br>Usuario autenticado";
        }
        else 
        {      
            $error = "<br>Fallo al recuperar el Rol";
            print "$error";
            return(false);
        }        
        unset($resultado);        
        cierra_db($dwes);
    }
    
   function graba_cookies_credenciales($usuario,$password,$longevidad)
    {          
       setcookie("login",$usuario,time()+$longevidad, "/");
       setcookie("pass",$password,time()+$longevidad, "/");     
    }
    
    function usuario_es_administrador($login,$pass)
    {
        $dwes=conecta_bd($bd_url, $bd_esquema, $bd_user, $bd_pass);
        $consulta="SELECT login".
                "FROM noticias.usuarios ".
                "where rol_nombre like :rol_nombre".
                "and login like :login";
        $resultado = $dwes->prepare($consulta);       
        $resultado->bindParam(1, $rol_nombre);
        $resultado->bindParam(2, $login);
        $resultado->execute();
        
//        if $resultado->rowCount()
        cierra_db($dwes);
    }
    
    function muestra_formulario()
    {
        ?>
    
    <div class="sesion effect2" id="color">
        <h2>I.E.S. Aguadulce</h2>
       <form class="formulario" role="form" action=".//php//login.php" method='post'>
            <!--<form class="formulario" role="form" action=".//php//pon_md5.php" method='post'>-->
            <div class="usuario">
                <input type="text" placeholder="E-mail / usuario"  name="login">
            </div>
            <div class="contraseña">
                <input type="password" class="form-control" placeholder="Contraseña" name="pass">
            </div>
            <div class="enviar">
                <button type="submit" class="enviar" name='enviar'><span>Enviar</span>
                </button>
            </div>
        </form>
    </div>
    <?php
    }
 ?>
    
</body>

</html>
