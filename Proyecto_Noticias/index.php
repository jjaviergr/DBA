<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <html>
    <head>
        <title>Pagina de Login</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
    </head>
    <body>
        <form action="login.php" method="post">
            <fieldset>
                <legend>Formulario de login </legend>
                <label>Inserta el login
                    <input type="text" name="login" id="login"/>
                </label>
                <label>Inserta el password 
                    <input type="password" name="password" id="password"/>
                </label>
            <input id="Boton_Envio" type="submit" value="Enviar"/>
            </fieldset>
        </form>
    </body>

        <?php
       include_once "utils.php";
       
       
//       Utils::conectaDb();
//       Utils::imprime("imagenes");
//       Utils::imprime("noticias");
//       Utils::imprime("rol");
//       Utils::imprime("usuarios");
       
        ?>
    </body>
</html>
