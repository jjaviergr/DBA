<?php

// EJEMPLO DE USO DE LAS FUNCIONES ANTERIORES
$login = recoge("login");
if ($login == "") {
    print "<p>No ha escrito ningún login</p>\n";
} else 
    {
         $password=recoge("password");
         if ($password == "") 
         {         
            print "<p>No ha escrito ningún password</p>\n";
         } 
         else
           { 
               $db=  conectaDb(); 
               
               $query="select count(*) from Login;";
               
               // ******inicio consulta preparada
               $result = $db->prepare($query);
               // ***** ejecucion preparada               
               $result->execute();
               
               // ***** comprueba que result tiene algo
               if (!$result) {
                  print "<p>Error en la consulta.</p>\n";
               }
               else {
                        print("Voy por aqui");
                        foreach ($result as $valor) {
                            print "<p>$valor[login] $valor[password]</p>\n";
                    }
               
               }
               $db=null;
           }    
     }

     function recoge($var) 
    {
        $tmp = (isset($_REQUEST[$var])) 
            ? strip_tags(trim(htmlspecialchars($_REQUEST[$var], ENT_QUOTES, "ISO-8859-1"))) 
            : "";
        if (get_magic_quotes_gpc()) {
            $tmp = stripslashes($tmp);
        }
        //$tmp = recorta($var, $tmp);
    return $tmp;
    }

// FUNCIÓN DE CONEXIÓN CON LA BASE DE DATOS MYSQL
    function conectaDb()
    {
        try {
            $db = new PDO("mysql:host=localhost", "root", "");
            $db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
            return($db);
        } catch (PDOException $e) 
        {
                //cabecera("Error grave");
            print "<p>Error: No puede conectarse con la base de datos.</p>\n";
    //      print "<p>Error: " . $e->getMessage() . "</p>\n";
            //pie();
            exit();
        }
    }
?>