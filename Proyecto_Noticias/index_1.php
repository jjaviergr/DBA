<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
       include_once "utils.php";
       
       Utils::conectaDb();
       Utils::imprime("imagenes");
       Utils::imprime("noticias");
       Utils::imprime("rol");
       Utils::imprime("usuarios");
       
        ?>
    </body>
</html>
