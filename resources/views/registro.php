<?php
$mensaje = '';
$errores = [];

if (is_array($data)) {
    $errores = $data;
} elseif (is_string($data)) {
    $mensaje = $data;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta nuevo usuario</title>
    <link rel="stylesheet" href="/css/styles.css">
</head>

<body>
    <div class="ppal_container">
        <?php
        include_once('header.php')
        ?>
        <main>
            <div>
                <h1>Date de alta</h1>
            </div>
            <div class="form_container">
                <form action="/registro" method="post">
                    <div class="form_item">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" id="nombre">
                    </div>
                    <div class="form_item">
                        <label for="apellidos">Apellidos</label>
                        <input type="text" name="apellidos" id="apellidos">
                    </div>
                    <div class="form_item">
                        <label for="usuario">Nombre de usuario</label>
                        <input type="text" name="usuario" id="usuario">
                    </div>
                    <div class="form_item">
                        <label for="email">E-mail</label>
                        <input type="text" name="email" id="email">
                    </div>
                    <div class="form_item">
                        <label for="fecha_nac">Fecha de nacimiento</label>
                        <input type="date" name="fecha_nac" id="fecha_nac">
                    </div>
                    <div class="form_item">
                        <label for="contrasenia1">Contraseña</label>
                        <input type="password" name="contrasenia1" id="constrasenia1">
                    </div>
                    <div class="form_item">
                        <label for="contrasenia2">Repita la contraseña</label>
                        <input type="password" name="contrasenia2" id="contrasenia2">
                    </div>
                    <!-- TENGO QUE PONER EL ROL???
                    <div class="form_item">
                        <label for="rol">Rol</label>
                        <div class="form_item_radio">
                            <div>
                                <label for="usuario">Usuario</label>
                                <input type="radio" name="rol" id="rol_usuario" value="usuario">
                            </div>
                            <div>
                                <label for="rol_admin">Administrador</label>
                                <input type="radio" name="rol" id="rol_admin" value="admin">
                            </div>
                        </div>
                    </div>
                     -->
                    <div class="button_container">
                        <button type="submit" name="submit">Enviar</button>
                    </div>
                </form>
                <div>
                    <a href="/login">Volver a login</a>
                </div>
                <div>
                <?php if (!empty($mensaje)): ?>
                    <p class="ok"><?= htmlspecialchars($mensaje) ?></p>
                <?php endif; ?>

                <?php if (!empty($errores)): ?>
                    <ul>
                        <?php foreach ($errores as $e): ?>
                            <li><?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                </div>
            </div>
        </main>
        <?php
        include_once('footer.php');
        ?>
    </div>
</body>

</html>