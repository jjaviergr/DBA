<!DOCTYPE html>
<html>

<head lang="es">
    <title>Iniciar Sesion</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href=".//css//estilos.css">
</head>

<body>
    <div class="sesion effect2" id="color">
        <h2>I.E.S. Aguadulce</h2>
        <form class="formulario" role="form" action=".//php//login.php" method='post'>
            <div class="usuario">
                <input type="text" placeholder="E-mail / usuario"  name="login">
            </div>
            <div class="contraseña">
                <input type="password" class="form-control" placeholder="Contraseña" name="pass">
            </div>
            <div class="enviar">
                <button type="submit" class="enviar"><span>Enviar</span>
                </button>
            </div>
        </form>
    </div>
    
 
    
</body>

</html>
