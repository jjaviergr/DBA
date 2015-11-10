<?php

class Utils
{
    
    /**
     * Esta función aporta la seguridad minima a la conexión con la BD para 
     * evitar  ataques de inyección SQL
     */
     public static function recoge($var) 
    {
        $tmp = (isset($_REQUEST[$var])) 
            ? strip_tags(trim(htmlspecialchars($_REQUEST[$var], ENT_QUOTES, "utf-8"))) 
            : "";
        if (get_magic_quotes_gpc()) {
            $tmp = stripslashes($tmp);
        }
        //$tmp = recorta($var, $tmp);
    return $tmp;
    }

// FUNCIÓN DE CONEXIÓN CON LA BASE DE DATOS MYSQL
    /**
     * Función que se conecta a una BD mysql. Es necesario poner manualmente
     * el nombre de la BD.
     */
    public static function conectaDb()
    {
        
        try 
        {
            $db = new PDO("mysql:host=localhost;dbname=noticias", "root", "");
            $db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
            return($db);
        } 
        catch (PDOException $e) 
        {
            print "<p>Error: No puede conectarse con la base de datos.</p>\n";    
            $db=null;
            exit();
         }    
    }
    
    
    /**
     * Imprime los campos de una tabla y imprime la info de $registro
     */
    public static function imprime_uno($registro,$tabla)
    {
        $campos=self::averigua_campos_tabla($tabla);
        $lista_campos=explode("|",$campos);
        $n_lista_campos=count($lista_campos);
            
        print "<table border=1>";            
        print "<tr>";
        for($i=0;$i<$n_lista_campos-1;$i++)
        {
           $campo=$lista_campos[$i];
           print "<th>".$campo."</th>"  ; 
        }  
        print "</tr>";

        foreach($registro as $z)
        {
            print  "<tr>";
            for($i=0;$i<$n_lista_campos-1;$i++)
            {                          
                $campo=$lista_campos[$i];
                print "<td>".$z[$campo]."</td>"  ; 
            }                           
            print "</tr>";
        }
        print "</table>";
        $db = null;
    }
    
    /**
     * Función que devuelve una cadena con los campos de una tabla
     */
    public static function averigua_campos_tabla($tabla)
    {       
       try
       {
                $db=self::conectaDb(); 
                $consulta="describe ".$tabla.";";
                $result = $db->query($consulta);
                $cad="";
                foreach($result as $i)
                {
//                    print $i['Field'];
                    $cad=$cad.$i['Field'].'|';
                }                
                return($cad); 
                $db=null;
       }
       catch (Exception $e) 
       {
              echo 'Excepción capturada: ', $e->getMessage(), "\n";
              $db = null;
              return($e->getMessage());
       }
    }
    
    /**
     * función que imprime en pantalla toda la información de una tabla 
     */
    public static function imprime($tabla)
    {
        $consulta="select * from ".$tabla;
        try
        {
            $db=self::conectaDb();
            
            $campos=self::averigua_campos_tabla($tabla);
            $lista_campos=explode("|",$campos);
            $n_lista_campos=count($lista_campos);
            $result = $db->query($consulta);
            
            print "<table border=1>";            
            print "<tr>";
            for($i=0;$i<$n_lista_campos-1;$i++)
            {
               $campo=$lista_campos[$i];
               print "<th>".$campo."</th>"  ; 
            }  
            print "</tr>";
            
            foreach($result as $z)
            {
                print  "<tr>";
                for($i=0;$i<$n_lista_campos-1;$i++)
                {                          
                    $campo=$lista_campos[$i];
                    print "<td>".$z[$campo]."</td>"  ; 
                }                           
                print "</tr>";
            }
            print "</table>";
            $db = null;
         }
         catch (Exception $e) 
         {
              echo 'Excepción capturada: ', $e->getMessage(), "\n";
              $db = null;
              return($e->getMessage());
         }
    }    
}

?>