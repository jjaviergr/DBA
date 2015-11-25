<!DOCTYPE html>
<html>
<head>
  <meta  charset="UTF-8">
  <title>Inserta Usuarios </title>
  <!--<link href=<link rel="stylesheet" type="text/css" href=".//css//estilos.css">--> 
</head>

<body>
<?php





?>
<form method="post" action="" name="">
    Introduce nombre <input type="text" name="nombre"><br>
    Introduce apellidos <input type="text" name="apellidos"><br>
    Introduce e-mail <input type="text" name="email"><br>
    Introduce Login <input type="text" name="login"><br>       
    Introduce password <input type="password" name="password">
    <br>
    Selecciona Rol de usuario
    <select name="rol">
      <option value="Administrador" >Administrador</option>
      <option value="Usuario" selected="selected">Usuario</option>
      <option value="Profesor">Profesor</option>  
    </select>
    <br>
    <input type="submit">

</form>

<?php

include_once 'util.php';
if (isset($_POST['nombre'])&&(isset($_POST['apellidos'])&&(isset($_POST['email']))))
{
    
    $db_noticias=  conecta_bd("localhost", "noticias", "root", "");
    //lanzar consulta insercion.
    $insercion_usuarios="INSERT INTO noticias.usuarios
        (login,password,nombre,apellidos,email,Rol)
        VALUES (?,?,?,?,?,?)";
//      (:login, :password, :nombre, :apellidos, :e-mail,  :Rol);";
    
    print_r($_POST);
    
    $login=$_POST['login'];
    $password=$_POST['password'];
    $nombre=$_POST['nombre'];
    $apellidos=$_POST['apellidos'];
    $email=$_POST['email'];
    $rol=$_POST['rol'];
    

    
    $sentencia = $db_noticias->prepare($insercion_usuarios);
    $sentencia->bindParam(1, $login);
    $sentencia->bindParam(2, $password);
    $sentencia->bindParam(3, $nombre);
    $sentencia->bindParam(4, $apellidos);
    $sentencia->bindParam(5, $email);
    $sentencia->bindParam(6, $rol);
    
    try
    {
        if ($sentencia->execute()==TRUE)
        {
            print "<br>Usuario añadido con exito!!";
  
        }

       else 
       {

            print "<p>No se han podido realizar los cambios.</p>";    
       }
    }
    catch(Exception $e)
    {
        print "Ha sucedido una excepcion con la BD ".$e;
    }
    
    cierra_db($db_noticias);
}
else
    print "<p>Falta algún parametro del formulario</p>"
?>
</body>
</html>