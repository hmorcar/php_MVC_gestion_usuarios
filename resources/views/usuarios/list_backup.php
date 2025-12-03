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
    <link rel="stylesheet" href="/css/style_table.css">
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
                                    <form action="/usuario/<?= urlencode($u->getId()) ?>/borrar" method="post" style="display:inline;">
                                        <button type="submit" onclick="return confirm('¿Seguro que quieres borrar este usuario?');" class="btn_accion eliminar">Eliminar</button>
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
            <?php if (isset($totalPaginas) && $totalPaginas > 1): ?>
                <div class="paginacion">
                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                        <?php if ($i == $paginaActual): ?>
                            <span class="pagina_actual"><?= $i ?></span>
                        <?php else: ?>
                            <a href="/list?p=<?= $i ?>"><?= $i ?></a>
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