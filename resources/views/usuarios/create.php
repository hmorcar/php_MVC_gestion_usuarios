<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo usuario</title>
</head>
<body>
    <p>Formulario de inserción de nuevo usuario</p>

    <!-- La ruta en este caso será a POST -->
    <form action="/usuario" method="post">
       <input type="text" name="nombre">
       <button type="submit">Enviar</button> 
    </form>
</body>
</html>