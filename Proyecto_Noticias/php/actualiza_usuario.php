<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>Actualiza usuario</title>
        <link rel="stylesheet" href="../jquery/jquery-ui-themes-1.11.4/themes/smoothness/jquery-ui.css">
      
        <script src="../jquery/jquery-1.11.3.min.js"></script>
        <script src="../jquery/jquery-ui-1.11.4/jquery-ui.js"></script>
        <link rel="stylesheet" href="../jquery/jquery-ui-themes-1.11.4/themes/black-tie/theme.css">

        <style>
/*          #feedback { font-size: 1.4em; }
          #selectable .ui-selecting { background: #FECA40; }
          #selectable .ui-selected { background: #F39814; color: white; }
          #selectable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
          #selectable li { margin: 3px; padding: 0.4em; font-size: 1.4em; height: 18px; }*/
        </style>
        <script>        
//          $(function() {
//            $( "#selectable" ).selectable({
//              stop: function() {
//                var result = $( "#select-result" ).empty();
//                $( ".ui-selected", this ).each(function() {
//                  var index = $( "#selectable li" ).index( this );
//                  result.append( " #" + ( index + 1 ) );
//                });
//              }
//            });
//          });
 

            window.edReg = function (sobj)
            {
                str = sobj.innerHTML || sobj
                tobj = document.getElementById('login')
                tobj.value = str.split(",")[0]
                tobj = document.getElementById('nombre')
                tobj.value = str.split(",")[1]
                tobj = document.getElementById('apellidos')
                tobj.value = str.split(",")[2]
                tobj = document.getElementById('email')
                tobj.value = str.split(",")[3]
                tobj = document.getElementById('rol')
                tobj.value = str.split(",")[4]

            }
        </script>

    </head>
    <body>
        <?php
        //imprime_usuarios();
        include_once 'util.php';

        $db_noticias = conecta_bd("localhost", "noticias", "root", "");

        $consulta_usuarios = "SELECT `usuarios`.`login`,
            `usuarios`.`login`,
            `usuarios`.`nombre`,
            `usuarios`.`apellidos`,
            `usuarios`.`email`,
            `usuarios`.`Rol`
        FROM `noticias`.`usuarios`;";



        $resultado = $db_noticias->query($consulta_usuarios);

        if ($resultado) {
            $row = $resultado->fetch();
            ?>
            <p>Escoge un usuario para actualizar</p><br>
                <select multiple onchange="edReg(this.options[this.selectedIndex].text)" id="selectable">
    <?php
    while ($row != null) {
        ?>
                        <option class="ui-widget-content">
                        <?php
                        print "${row['login']},${row['nombre']},${row['apellidos']},${row['email']},${row['Rol']}<br>";
                        ?>
                        </option>
                            <?php
                            $row = $resultado->fetch();
                        }
                        cierra_db($db_noticias);
                        ?>
                </select>

            <form id="fusuario_alterado" action="<?php echo $_SERVER['SCRIPT_NAME'];?>" method="post">            
            <fieldset>
                 <legend>Actualiza usuario</legend>
               <input type="text" id="login" name="login">            
                <input type="text" id="nombre" name="nombre">
                <input type="text" id="apellidos" name="apellidos">
                <input type="text" id="email" name="email">
                <input type="text" id="rol" name="rol">
                <input type="submit"  >

           
            </fieldset>
            </form>
    <?php
   
    if (isset($_POST['login']))
    { 
         $db_noticias = conecta_bd("localhost", "noticias", "root", "");
//        print_r($_POST);
        $consulta_act = "UPDATE usuarios
                   SET
                    login = ?,       
                    nombre = ?,
                    apellidos = ?,
                    email = ?,
                    Rol = ?
                    WHERE login like ?";

        $sentencia = $db_noticias->prepare($consulta_act);
        
        $login=$_POST['login'];
//        $password=$_POST['password'];
        $nombre=$_POST['nombre'];
        $apellidos=$_POST['apellidos'];
        $email=$_POST['email'];
        $rol=$_POST['rol'];
    
        
        
        $sentencia->bindParam(1, $login);
    //        $sentencia->bindParam(2, $password);
        $sentencia->bindParam(2, $nombre);
        $sentencia->bindParam(3, $apellidos);
        $sentencia->bindParam(4, $email);
        $sentencia->bindParam(5, $rol);
        $sentencia->bindParam(6, $login);

        try
        {
            
         $sentencia->execute();
         echo $sentencia->rowCount() . " records UPDATED successfully";
         cierra_db($db_noticias);
        } 
        catch (Exception $e)
        {
            print "<p>No se han podido realizar los cambios. $e</p>";
            cierra_db($db_noticias);
        }
    
    }   
}
?>
            
            
<!--<ol id="selectable">
  <li class="ui-widget-content">Item 1</li>
  <li class="ui-widget-content">Item 2</li>
  <li class="ui-widget-content">Item 3</li>
  <li class="ui-widget-content">Item 4</li>
  <li class="ui-widget-content">Item 5</li>
  <li class="ui-widget-content">Item 6</li>
</ol>-->
 
    </body>


</html>
