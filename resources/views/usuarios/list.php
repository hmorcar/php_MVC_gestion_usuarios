<?php
if (is_array($data)) {
    $usuarios = $data['usuarios'];
    $paginaActual = $data['paginaActual'];
    $totalPaginas = $data['totalPaginas'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="stylesheet" href="/css/style_list.css">
    <title>Lista de usuarios</title>
</head>

<body>

    <div class="ppal_container">
        <header>
            <h1>Tarea 4: MVC</h1>
            <nav>
                <a href="/logout"><button>Cerrar sesión</button></a>
            </nav>
        </header>
        <!--
    <a href="/usuario/nuevo">Nuevo usuario</a>
    -->
        <main>
            <section>
                <div class="buscador_container">
                    <h1>Buscar usuarios</h1>
                    <form action="/list" method="get" class="buscador">
                        <div class="form_row">
                            <div>
                                <label for="id">Id</label>
                                <input type="text" name="id" id="id" placeholder="Id" value="<?= htmlspecialchars($_GET['id'] ?? '') ?>">
                            </div>
                            <div>
                                <label for="nombre">Nombre</label>
                                <input type="text" name="nombre" id="nombre" placeholder="Nombre real" value="<?= htmlspecialchars($_GET['nombre'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="form_row">
                            <div>
                                <label for="apellidos">Apellidos</label>
                                <input type="text" name="apellidos" id="apellidos" placeholder="Apellidos" value="<?= htmlspecialchars($_GET['apellidos'] ?? '') ?>">
                            </div>
                            <div>
                                <label for="usuario">Usuario</label>
                                <input type="text" name="usuario" placeholder="Usuario" id="usuario" value="<?= htmlspecialchars($_GET['usuario'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="form_row">
                            <div>
                                <label for="email">Email</label>
                                <input type="email" name="email" placeholder="Email" id="email" value="<?= htmlspecialchars($_GET['email'] ?? '') ?>">
                            </div>
                            <div>
                                <label for="rol">Rol</label>
                                <input type="text" name="rol" placeholder="Rol" id="rol" value="<?= htmlspecialchars($_GET['rol'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="form_row">
                            <div>
                                <label for="puntos_min">Puntos mín.</label>
                                <input type="number" name="puntos_min" placeholder="Puntos mín." id="puntos_min" value="<?= htmlspecialchars($_GET['puntos_min'] ?? '') ?>">
                            </div>
                            <div>
                                <label for="puntos_max">Puntos máx.</label>
                                <input type="number" name="puntos_max" placeholder="Puntos máx." id="puntos_max" value="<?= htmlspecialchars($_GET['puntos_max'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="form_row">
                            <label for="fecha_nac">Fecha nacimiento</label>
                            <input type="date" name="fecha_nac" placeholder="Fecha nac." id="fecha_nac" value="<?= htmlspecialchars($_GET['fecha_nac'] ?? '') ?>">
                        </div>
                        <div class="form_row">
                            <label for ="fecha_alta">Fecha alta</label>
                            <input type="date" name="fecha_alta" placeholder="Fecha alta" id="fecha_alta" value="<?= htmlspecialchars($_GET['fecha_alta'] ?? '') ?>">    
                        </div>
                        <div class="button_row">
                            <button type="submit">Buscar</button>
                        </div>
                    </form>
                </div>
            </section>
            <section>
                <div class="listado_usuarios_container">
                    <h1>Listado usuarios</h1>
                    <table class="tabla_usuarios">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Nombre real</th>
                                <th>Apellidos</th>
                                <th>Usuario</th>
                                <th>Email</th>
                                <th>Fecha nac.</th>
                                <th>Rol</th>
                                <th>Puntos</th>
                                <th>Fecha alta</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($usuarios)): ?>
                                <?php foreach ($usuarios as $u): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($u->getId()) ?></td>
                                        <td><?= htmlspecialchars($u->getNombre()) ?></td>
                                        <td><?= htmlspecialchars($u->getApellidos()) ?></td>
                                        <td><?= htmlspecialchars($u->getUsuario()) ?></td>
                                        <td><?= htmlspecialchars($u->getEmail()) ?></td>
                                        <td><?= htmlspecialchars($u->getFecha_nac_string_usuario()) ?></td>
                                        <td><?= htmlspecialchars($u->getRol()) ?></td>
                                        <td><?= htmlspecialchars($u->getPuntos()) ?></td>
                                        <td><?= htmlspecialchars($u->getFecha_alta_string_usuario()) ?></td>
                                        <td>
                                            <a href="/usuario/<?= urlencode($u->getUsuario()) ?>"><button class="btn_accion editar">Editar</button></a>
                                            <form action="/usuario/<?= urlencode($u->getId()) ?>/borrar_usuario" method="post" style="display:inline;">
                                                <input type="hidden" name="csrf_token_borrar" value="<?php echo htmlspecialchars($_SESSION['csrf_token_borrar'])?>">
                                                <button type="submit" name='submit' onclick="return confirm('¿Seguro que quieres borrar este usuario?');" class="btn_accion eliminar">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="10">No hay usuarios que mostrar.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
            <?php
            /*
                // $data está definido en Controller.php y pasado en UsuarioController.php
                foreach ($data as $fila) {
                    echo "<tr>";
                    foreach ($fila as $celda) {
                        echo "<td>" . htmlspecialchars($celda) . "</td>";
                    }
                    echo "</tr>";
                }
                ?>
                 */
            ?>
            <?php
            // Construyo el query string con filtros para paginación
            $queryFiltros = $_GET;
            // Quito la página para agregarla dinámica
            unset($queryFiltros['p']); 
            $filtrosQuery = http_build_query($queryFiltros);
            ?>
            <?php if (isset($totalPaginas) && $totalPaginas > 1): ?>
                <div class="paginacion">
                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                        <?php if ($i == $paginaActual): ?>
                            <span class="pagina_actual"><?= $i ?></span>
                        <?php else: ?>
                            <a href="/list?p=<?= $i ?><?= !empty($filtrosQuery) ? '&' . $filtrosQuery : '' ?>"><?= $i ?></a>
                        <?php endif; ?>
                        <?= ($i < $totalPaginas) ? '  ' : '' ?>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </main>
        <?php
        require_once __DIR__ . '/../footer.php';
        ?>
    </div>
</body>
</html>