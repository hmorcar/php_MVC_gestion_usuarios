<?php 
    if (is_array($data)) {
        $usuario = $data['usuario'];
        $mensaje_actualizar = $data['mensaje_actualizar'] ?? '';
        $errores_actualizar = $data['errores_actualizar'] ?? [];
        $mensaje_enviar_puntos = $data['mensaje_enviar_puntos'] ?? '';
        $errores_enviar_puntos = $data['errores_enviar_puntos'] ?? [];
    } else {
        $usuario = $data;
        $mensaje_actualizar = '';
        $errores_actualizar = [];
        $mensaje_enviar_puntos = '';
        $errores_enviar_puntos = [];
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/styles.css">
    <title>Document</title>
</head>

<body>
    <div class="ppal_container">
        <header>
            <h1>Tarea 4: MVC</h1>
            <nav>
                <a href="/logout"><button>Cerrar sesión</button></a>
            <?php if ($_SESSION['rol']==='admin'): ?>
                <a href="/list"><button>Lista de usuarios</button></a>
            <?php endif;?>
            </nav>
        </header>
        <main>
            <section>
                <h1><?php echo 'Bienvenid@ ' . $_SESSION['usuario'] ?></h1>
            </section>
            <section>
                <div>
                    <h2><?php echo 'Datos de '.$usuario->getUsuario()?></h2>
                </div>
                <div>
                    <ul class="usuario_data">
                        <?php if ($usuario):  
                        ?>
                            <li><?php echo "<strong>Nombre:</strong>" . " " . $usuario->getNombre() ?></li>
                            <li><?php echo "<strong>Apellidos:</strong>" . " " . $usuario->getApellidos() ?></li>
                            <li><?php echo "<strong>Nombre de usuario:</strong>" . " " . $usuario->getUsuario() ?></li>
                            <li><?php echo "<strong>Email:</strong>" . " " . $usuario->getEmail() ?></li>
                            <li><?php echo "<strong>Fecha de nacimiento:</strong>" . " " . $usuario->getFecha_nac_string_usuario() ?></li>
                            <li><?php echo "<strong>Rol:</strong>" . " " . $usuario->getRol() ?></li>
                            <li><?php echo "<strong>Puntos:</strong>" . " " . $usuario->getPuntos() ?></li>
                            <li><?php echo "<strong>Fecha de alta:</strong>" . " " . $usuario->getFecha_alta_string_usuario() ?></li>
                        <?php
                        endif;
                        ?>
                    </ul>
                </div>
            </section>
            <section>
                <div>
                    <h2>Modifica tus datos</h2>
                </div>
                <div class="form_container">
                    <form action="/usuario/<?= $usuario->getUsuario() ?>/actualizar" method="post">
                        <div class="form_item">
                            <label for="nombre">Nombre</label>
                            <input type="text" name="nombre" id="nombre" value="<?php echo $usuario->getNombre() ?>">
                        </div>
                        <div class="form_item">
                            <label for="apellidos">Apellidos</label>
                            <input type="text" name="apellidos" id="apellidos" value="<?php echo $usuario->getApellidos() ?>">
                        </div>
                        <div class="form_item">
                            <label for="usuario">Nombre de usuario</label>
                            <input type="text" name="usuario" id="usuario" value="<?php echo $usuario->getUsuario() ?>">
                        </div>
                        <div class="form_item">
                            <label for="email">E-mail</label>
                            <input type="text" name="email" id="email" value="<?php echo $usuario->getEmail() ?>">
                        </div>
                        <div class="form_item">
                            <label for="fecha_nac">Fecha de nacimiento</label>
                            <input type="date" name="fecha_nac" id="fecha_nac" value="<?php echo $usuario->getFecha_nac_string() ?>">
                        </div>
                        <div class="form_item">
                            <label for="contrasenia1">Contraseña</label>
                            <input type="password" name="contrasenia1" id="constrasenia1">
                        </div>
                        <div class="form_item">
                            <label for="contrasenia2">Repita la contraseña</label>
                            <input type="password" name="contrasenia2" id="contrasenia2">
                        </div>
                        <?php
                        if ($_SESSION['rol'] === 'admin'): ?>
                            <div class="form_item">
                                <label for="rol">Rol</label>
                                <div class="form_item_radio">
                                    <div>
                                        <label for="usuario">Usuario</label>
                                        <input type="radio" name="rol" id="rol_usuario" value="usuario" <?php echo $usuario->getRol() === 'usuario' ? 'checked' : '' ?>>
                                    </div>
                                    <div>
                                        <label for="rol_admin">Administrador</label>
                                        <input type="radio" name="rol" id="rol_admin" value="admin" <?php echo $usuario->getRol() === 'admin' ? 'checked' : '' ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="form_item">
                                <label for="puntos">Puntos</label>
                                <input type="text" name="puntos" id="puntos" value="<?php echo $usuario->getPuntos() ?>">
                            </div>
                            <div class="form_item">
                                <label for="fecha_alta">Fecha de alta</label>
                                <input type="date" name="fecha_alta" id="fecha_alta" value="<?php echo $usuario->getFecha_alta_string() ?>">
                            </div>
                        <?php
                        endif;
                        ?>
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'])?>">
                        <div class="button_container">
                            <button type="submit" name="submit">Enviar</button>
                        </div>
                    </form>
                </div>
                <div>
                <?php 
                if(!empty($errores_actualizar)):?>
                    <ul>
                <?php 
                    foreach($errores_actualizar as $e):?>
                            <li><?php  echo $e ?></li>
                <?php 
                    endforeach;
                ?>
                    </ul>
                <?php
                endif;
                ?>
                </div>
                <div>
                <?php 
                if($mensaje_actualizar):?>
                    <p><?php echo $mensaje_actualizar ?></p>
                <?php
                endif;
                ?>
                </div>
            </section>
            <section>
                <div>
                    <h2>Envía puntos</h2>
                </div>
                <div class="form_container">
                    <form action="/usuario/<?= $usuario->getUsuario() ?>/enviar_puntos" method="post">
                        <div class="form_item">
                            <label for="usuario_destino">Usuario destino</label>
                            <input type="text" name="usuario_destino" id="usuario_destino">
                        </div>
                        <div class="form_item">
                            <label for="puntos_enviar">Puntos a enviar</label>
                            <input type="text" name="puntos_enviar" id="puntos_enviar">
                        </div>
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'])?>">
                        <div class="button_container">
                            <button type="submit" name="submit">Enviar</button>
                        </div>
                    </form>
                </div>
                <div>
                <?php
                if(!empty($errores_enviar_puntos)):?>
                    <ul>
                <?php 
                    foreach($errores_enviar_puntos as $e):?>
                            <li><?php  echo $e ?></li>
                <?php 
                    endforeach;
                ?>
                    </ul>
                <?php
                endif;
                ?>
                </div>
                <div>
                <?php 
                if($mensaje_enviar_puntos):?>
                    <p><?php echo $mensaje_enviar_puntos ?></p>
                <?php
                endif;
                ?>
                </div>
            </section>
        </main>
        <?php require_once  __DIR__ . '/../footer.php'; ?>
    </div>
</body>
</html>