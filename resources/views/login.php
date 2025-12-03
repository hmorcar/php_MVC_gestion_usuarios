<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <div class="ppal_container">
        <header>
            <h1>Tarea 4: MVC</h1>
            <nav>
                <a href="/crear_bd"><button>Crear base de datos</button></a>
            </nav>
        </header>
        <main>
            <div>
                <h1>Inicia sesión</h1>
            </div>
            <div clas="form_container">
                <form action="/login" method="post">
                    <div class="form_item">
                        <label for="usuario">Nombre de usuario</label>
                        <input type="text" name="usuario" id="usuario">
                    </div>
                    <div class="form_item">
                        <label for="contrasenia">Contraseña</label>
                        <input type="password" name="contrasenia" id="contrasenia">
                    </div>
                    <div class="button_container">
                        <button type="submit" name="submit">Enviar</button>
                    </div>
                    <div>
                        <a href="/registro">Registro de nuevo usuario</a>
                    </div>
                </form>
            </div>
            <div>
            <?php 
            /*Verifica si los datos se envian como un array asociativo, 
            si recibe directamente el array de errores (array indexado) o si el array está vacío*/
            $errores = $data['errores'] ?? $data ?? [];
            if($errores){?>
                <ul>
            <?php
                foreach ($errores as $error){?>
                    <li><?php echo $error ?></li>
                <?php   
                 }?>
                </ul>
            <?php
            }
            ?>
            </div>
        </main>
        <?php
        include_once('footer.php');
        ?>
    </div>
</body>
</html>