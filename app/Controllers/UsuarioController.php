<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use DateTime;

class UsuarioController extends Controller
{

    public function showList()
    {

        if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol'])) {
            return $this->redirect('/login');
        }

        $usuario_sesion_model=new UsuarioModel();
        $usuario_sesion=$usuario_sesion_model->find($_SESSION['id']);
        if ($usuario_sesion  && $usuario_sesion->getRol()!=='admin') {
            return $this->error403();
        }

        $paginaActual = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
        $porPagina = 10;
        //offset se refiere a la posición desde la que se deben empezar a mostrar los resultados en una consulta paginada 
        $offset = ($paginaActual - 1) * $porPagina;
        $usuarioModel = new UsuarioModel();
        $where=[];
        $totalUsuarios=0;
        $usuarioMod=new UsuarioModel();

        //Campos que se buscan con LIKE
        $camposLike = ['nombre', 'apellidos', 'usuario', 'email', 'rol'];
        foreach ($camposLike as $campo) {
            if (!empty($_GET[$campo])) {
                $where[]=['columna'=>$campo,'operador'=>'LIKE','valor'=>'%' . $_GET[$campo] . '%'];

            }
        }
        //campos que se buscan con =
        $camposIgual=['id','fecha_nac','fecha_alta'];
        foreach ($camposIgual as $campo) {
            if (!empty($_GET[$campo])) {
                $where[]=['columna'=>$campo,'operador'=>'=','valor'=>$_GET[$campo]];
            }
        }
        //Rango para puntos
        if (!empty($_GET['puntos_min'])) {
            $where[]=['columna'=>'puntos','operador'=>'>=','valor'=>(int)$_GET['puntos_min']];
        }
        if (!empty($_GET['puntos_max'])) {
            $where[]=['columna'=>'puntos','operador'=>'<=','valor'=>(int)$_GET['puntos_max']];
        }
        foreach($where as $w ){
            $usuarioModel->where($w['columna'],$w['operador'],$w['valor']);
            $usuarioMod->where($w['columna'],$w['operador'],$w['valor']);
        }
         // Obtenemos los usuarios resultado de la consulta paginada
        $usuarios_pag_actual=$usuarioModel->select('*')->limit($porPagina)->offset($offset)->get();
       
        $resultado_count=$usuarioMod->select('COUNT(*) AS total')->get()[0];
        $totalUsuarios = $resultado_count->total ?? 0;
        
        var_dump($totalUsuarios);
        

        
        
        
        
        /* 
        $condiciones = [];

        $parametros = [];
        //Campos que se buscan con LIKE
        $camposLike = ['nombre', 'apellidos', 'usuario', 'email', 'rol'];
        foreach ($camposLike as $campo) {
            if (!empty($_GET[$campo])) {
                $condiciones[] = "$campo LIKE :$campo";
                $parametros[":$campo"] = '%' . $_GET[$campo] . '%';
            }
        }
        //Campos que se buscan con =
        if (!empty($_GET['id'])) {
            $condiciones[] = "id = :id";
            $parametros[':id'] = (int)$_GET['id'];
        }
        if (!empty($_GET['fecha_nac'])) {
            $condiciones[] = "fecha_nac = :fecha_nac";
            $parametros[':fecha_nac'] = $_GET['fecha_nac'];
        }
        if (!empty($_GET['fecha_alta'])) {
            $condiciones[] = "fecha_alta = :fecha_alta";
            $parametros[':fecha_alta'] = $_GET['fecha_alta'];
        }

        //Rango para puntos
        if (!empty($_GET['puntos_min'])) {
            $condiciones[] = "puntos >= :puntos_min";
            $parametros[':puntos_min'] = (int)$_GET['puntos_min'];
        }
        if (!empty($_GET['puntos_max'])) {
            $condiciones[] = "puntos <= :puntos_max";
            $parametros[':puntos_max'] = (int)$_GET['puntos_max'];
        }
        //Si el array condiciones no está vacío construye el where uniendo todos los elementos del array $condiciones en una sola cadena , separanado cada elemento con la palabra
        // ' AND ' , si está vacío el where es una cadena vacía
        //$where = !empty($condiciones) ? 'WHERE ' . implode(' AND ', $condiciones) : '';
        if(!empty($condiciones)){
            foreach($condiciones as $condicion){

            }
        }
        //obtenemos el total de usuarios que resultan de la consulta
        $sqlTotal = "SELECT COUNT(*) as total FROM usuario $where";
        $resultado = $usuarioModel->query($sqlTotal, $parametros)->get();
        $totalUsuarios = $resultado[0]->total ?? 0;

        // Obtenemos los usuarios resultado de la consulta paginada
        $sql = "SELECT * FROM usuario $where LIMIT {$porPagina} OFFSET {$offset}";
 
        $usuarios_pag_actual = $usuarioModel->query($sql, $parametros)->get();
        */
        $totalPaginas = max(1, ceil($totalUsuarios / $porPagina));
        
        $_SESSION['csrf_token_borrar']=bin2hex(random_bytes(32));

        return $this->view('usuarios.list', [
        'usuarios' => $usuarios_pag_actual,
        'paginaActual' => $paginaActual,
        'totalPaginas' => $totalPaginas,
        // Pasar también los parámetros de búsqueda para el formulario
        'filtros' => $_GET,

    ]);

    }



    /**
     * 1.Comprueba que el usuario esté logueado y que su rol sea admin.De no ser así muestra página de error
     * 2. Calcula la página actual y el offset
     * 3.Calcula la cantidad dtotal de usuarios y las páginas recesarias para mostrar 10 usuarios por página
     * 4.Obtiene los 10 usuarios correspondientes a la página actual
     * 5.LLama a la vista mandándole los 10 usuarios a mostrar en la página, la página actual y la cantidad total de páginas
     * @return view la vista con los usuarios correspondientes a la página actual si el usuario está logueado
     *  y es admin, si no devuelve una página de error
     */

    /*
    DEBUG-->BACKUP DE showList()


    public function showList()
    {
        if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
            return $this->error403();
        }
        $paginaActual = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
        $porPagina = 10;
        //offset se refiere a la posición desde la que se deben empezar a mostrar los resultados en una consulta paginada 
        $offset = ($paginaActual - 1) * $porPagina;
        $usuarioModel = new UsuarioModel();
        $totalUsuarios = count($usuarioModel->all());
        $usuarioMod = new UsuarioModel();
        $usuarios_pag_actual = $usuarioMod
            ->select('*')
            ->query("SELECT * FROM usuario LIMIT {$porPagina} OFFSET {$offset}")
            ->get();
        $totalPaginas = max(1, ceil($totalUsuarios / $porPagina));
        return $this->view('usuarios.list', [
            'usuarios'      => $usuarios_pag_actual,
            'paginaActual'  => $paginaActual,
            'totalPaginas'  => $totalPaginas,
        ]);
    }

    */

    public function store()
    {
        // Volvemos a tener acceso al modelo
        $usuarioModel = new UsuarioModel();

        // Se llama a la función correpondiente, pasando como parámetro
        // $_POST
        var_dump($_POST);
        echo "Se ha enviado desde POST";

        // Podríamos redirigir a donde se desee después de insertar
        //return $this->redirect('/contacts');
    }

    public function show($username)
    {
        //Guardo en variables el mensaje y errores guardados en $_SESSION y elimino esas claves de sesión
        $mensaje_actualizar = $_SESSION['mensaje_actualizar'] ?? '';
        $errores_actualizar = $_SESSION['errores_actualizar'] ?? [];
        $mensaje_enviar_puntos = $_SESSION['mensaje_enviar_puntos'] ?? [];
        $errores_enviar_puntos = $_SESSION['errores_enviar_puntos'] ?? [];
        unset($_SESSION['mensaje_actualizar']);
        unset($_SESSION['errores_actualizar']);
        unset($_SESSION['mensaje_enviar_puntos']);
        unset($_SESSION['errores_enviar_puntos']);
        if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol'])) {
            return $this->redirect('/login');
        }
        if (!$this->validaUsuario($username)) {
            return $this->error403();
        }
        $usuario_sesion_model=new UsuarioModel();
        $usuario_sesion=$usuario_sesion_model->find($_SESSION['id']);
        if ($usuario_sesion && $usuario_sesion->getUsuario()!==$username && $usuario_sesion->getRol()!=='admin') {
            return $this->error403();
        }
        $usuarioModel = new UsuarioModel();
        $usuarios = $usuarioModel->select('*')->where('usuario', $username)->get();
        if (empty($usuarios)) {
            return $this->error404();
        } else {
            $usuario = $usuarios[0];
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            return $this->view('usuarios.show', [
                'usuario' => $usuario,
                'usuario_sesion'=>$usuario_sesion,
                'mensaje_actualizar' => $mensaje_actualizar,
                'errores_actualizar' => $errores_actualizar,
                'mensaje_enviar_puntos' => $mensaje_enviar_puntos,
                'errores_enviar_puntos' => $errores_enviar_puntos
            ]);
        }
    }

    public function validaUsuario(string $username): bool
    {
        if (!preg_match('/^[a-z0-9]{2,100}$/', $username)) {
            return false;
        } else {
            return true;
        }
    }
    public function update($username)
    {
        if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol'])) {
            return $this->redirect('/login');
        }
        if (!$this->validaUsuario($username)) {
            return $this->error403();
        }
        $usuario_sesion_model=new UsuarioModel();
        $usuario_sesion=$usuario_sesion_model->find($_SESSION['id']);
        if ($usuario_sesion && $usuario_sesion->getUsuario()!==$username && $usuario_sesion->getRol()!=='admin') {
            return $this->error403();
        }

        if (isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] === "POST") {
            $mensaje = '';
            $usuarioModel = new UsuarioModel();
            $usuarios_encontrados = $usuarioModel->select('*')->where('usuario', $username)->get();
            if (empty($usuarios_encontrados)) {
                return $this->error404();
            }
            $usuario_encontrado = $usuarios_encontrados[0];

            if (isset($_POST['csrf_token']) && isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
                $nombre = '';
                $apellidos = '';
                $usuario = '';
                $email = '';
                $fecha_nac = '';
                $contrasenia = '';
                $rol = '';
                $fecha_alta = '';
                $puntos = '';
                $errores = [];
                if (empty($_POST['nombre'])) {
                    $nombre = $usuario_encontrado->getNombre();
                } else {
                    if (!preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñÜü ]{2,50}$/u', $_POST['nombre'])) {
                        $errores[] = "El nombre debe tener entre 2 y 50 caracteres válidos ";
                    } else {
                        $nombre = $this->sanitizaDatos($_POST['nombre']);
                    }
                }
                if (empty($_POST['apellidos'])) {
                    $apellidos = $usuario_encontrado->getApellidos();
                } else {
                    if (!preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñÜü ]{2,100}$/u', $_POST['apellidos'])) {
                        $errores[] = "El campo apellidos debe contener entre 2 y 100 caracteres válidos ";
                    } else {
                        $apellidos = $this->sanitizaDatos($_POST['apellidos']);
                    }
                }
                if (empty($_POST['usuario'])) {
                    $usuario = $usuario_encontrado->getUsuario();
                } else {
                    if (!preg_match('/^[a-z0-9]{2,100}$/', $_POST['usuario'])) {
                        $errores[] = "El nombre de usuario debe contener entre 2 y 100 caracteres válidos ";
                    } else {
                        $usuarioMod = new UsuarioModel();
                        $usuario_introducido = $this->sanitizaDatos($_POST['usuario']);
                        $usuario_existe = $usuarioMod->select('*')->where('usuario', $usuario_introducido)->get()[0] ?? '';

                        if ($usuario_existe && $usuario_existe->getUsuario() !== $usuario_encontrado->getUsuario()) {

                            $errores[] = "Ya hay un usuario registrado con ese nombre de usuario";
                        } else {
                            $usuario = $usuario_introducido;
                        }
                    }
                }
                if (empty($_POST['email'])) {
                    $email = $usuario_encontrado->getEmail();
                } else {
                    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                        $errores[] = "El email tiene una formato incorrecto ";
                    } else {
                        $usuarioMod = new UsuarioModel();
                        $email_introducido = $this->sanitizaDatos($_POST['email']);
                        $email_existe = $usuarioMod->select('*')->where('email', $email_introducido)->get()[0] ?? '';

                        if ($email_existe && $email_existe->getEmail() !== $usuario_encontrado->getEmail()) {
                            $errores[] = "Ya existe un usuario registrado con el email introducido";
                        } else {
                            $email = $email_introducido;
                        }
                    }
                }
                if (empty($_POST['fecha_nac'])) {
                    $fecha_nac = $usuario_encontrado->getFecha_nac_string();
                } else {
                    $date = DateTime::createFromFormat('Y-m-d', $_POST['fecha_nac']);
                    if (!($date && $date->format('Y-m-d') === $_POST['fecha_nac'])) {
                        $errores[] = 'La fecha de nacimiento no es válida';
                    } else {
                        $fecha_nac = $date->format('Y-m-d');
                    }
                }
                if (empty($_POST['contrasenia1'])) {
                    $contrasenia = $usuario_encontrado->getContrasenia();
                } else {
                    if (empty($_POST['contrasenia2'])) {
                        $errores[] = "Debe repetir la contraseña";
                    } else {
                        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&._])[A-Za-zÁÉÍÓÚáéíóúÑñÜü\d@$!%*#?&._]{8,40}$/u', $_POST['contrasenia1'])) {
                            $errores[] = "La contraseña debe tener entre 8 y 40 caracteres y contener al menos 1 número, una mayúscula, una minúscula y un carácter especial (@$!%*#?&.)";
                        } else {
                            if ($_POST['contrasenia1'] !== $_POST['contrasenia2']) {
                                $errores[] = "Las contraseñas no coinciden";
                            } else {
                                $contrasenia = password_hash($_POST['contrasenia1'], PASSWORD_DEFAULT);
                            }
                        }
                    }
                }
                if ($usuario_sesion->getRol()==='admin') {
                    if (empty($_POST['rol'])) {
                        $rol = $usuario_encontrado->getRol();
                    } else {
                        if ($_POST['rol'] !== 'admin' && $_POST['rol'] !== 'usuario') {
                            $errores[] = "Opción de usuario no válida";
                        } else {
                            $rol = $_POST['rol'];
                        }
                    }

                    if (empty($_POST['puntos'])) {
                        $puntos = $usuario_encontrado->getPuntos();
                    } else {
                        if (!preg_match('/^[0-9]{1,11}$/', $_POST['puntos'])) {
                            $errores[] = "El campo puntos debe ser un número entero con una longitud entre 1 y 11 cifras";
                        } else {
                            $puntos = $_POST['puntos'];
                        }
                    }
                    if (empty($_POST['fecha_alta'])) {
                        $fecha_alta = $usuario_encontrado->getFecha_alta_string();
                    } else {
                        $date = DateTime::createFromFormat('Y-m-d', $_POST['fecha_alta']);
                        if (!($date && $date->format('Y-m-d') === $_POST['fecha_alta'])) {
                            $errores[] = 'La fecha de nacimiento no es válida';
                        } else {
                            $fecha_alta = $date->format('Y-m-d');
                        }
                    }
                    if (empty($errores)) {
                        $usuarioModel->update(
                            $usuario_encontrado->getId(),
                            [
                                'nombre' => $nombre,
                                'apellidos' => $apellidos,
                                'usuario' => $usuario,
                                'email' => $email,
                                'fecha_nac' => $fecha_nac,
                                'contrasenia' => $contrasenia,
                                'puntos' => $puntos,
                                'fecha_alta' => $fecha_alta,
                                'rol' => $rol
                            ]
                        );
                        $mensaje = "Modificación realizada con éxito";
                        $usuarioMod = new UsuarioModel();
                        $usuarios_modificados = $usuarioMod->select('*')->where('id', $usuario_encontrado->getId())->get();
                        $usuario_modificado = !empty($usuarios_modificados)
                            ? $usuarios_modificados[0]
                            : $usuario_encontrado;


                        //si es el propio usuario el que realiza los cambios guardo en $_SESSION 
                        //el nuevo nombre de usuario y rol para evitar que se le cierre la sesión,
                        //así evito tb que machaque la sesión del admin   
                        if ($usuario_encontrado->getId() === $_SESSION['id']) {
                            $_SESSION['rol'] = $usuario_modificado->getRol();
                            $_SESSION['usuario'] = $usuario_modificado->getUsuario();
                        }
                        //en vez de llamar a la vista, para que recargue la página y así el token csrf hago redirect e 
                        //incluyo los datos a mostrar en la vista en $_SESSION, que una vez utilizados borraré en show()
                        $_SESSION['mensaje_actualizar'] = $mensaje;
                        return $this->redirect('/usuario/' . $usuario_modificado->getUsuario());

                    } else {
                        $_SESSION['errores_actualizar'] = $errores;
                        return $this->redirect('/usuario/' . $usuario_encontrado->getUsuario());
                    }
                } else if ($usuario_sesion->getRol()==='usuario') {
                    if (empty($errores)) {
                        $usuarioModel->update(
                            $usuario_encontrado->getId(),
                            [
                                'nombre' => $nombre,
                                'apellidos' => $apellidos,
                                'usuario' => $usuario,
                                'email' => $email,
                                'fecha_nac' => $fecha_nac,
                                'contrasenia' => $contrasenia
                            ]
                        );
                        $mensaje = "Modificación realizada con éxito";
                        $usuarioMod = new UsuarioModel();
                        $usuarios_modificados = $usuarioMod->select('*')->where('id', $usuario_encontrado->getId())->get();
                        $usuario_modificado = !empty($usuarios_modificados)
                            ? $usuarios_modificados[0]
                            : $usuario_encontrado;
                        $_SESSION['mensaje_actualizar'] = $mensaje;
                        $_SESSION['usuario'] = $usuario_modificado;
                        return $this->redirect('/usuario/' . $usuario_modificado->getUsuario());

                    } else {
                        $_SESSION['errores_actualizar'] = $errores;
                        return $this->redirect('/usuario/' . $usuario_encontrado->getUsuario());
                    }
                }
            } else {
                $mensaje = "Solicitud inválida";
                $_SESSION['mensaje_actualizar'] = $mensaje;
                return $this->redirect('/usuario/' . $usuario_encontrado->getUsuario());
            }
        }
    }
    public function enviarPuntos($username)
    {
        if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol'])) {
            return $this->redirect('/login');
        }
        if (!$this->validaUsuario($username)) {
            return $this->error403();
        }
        $usuario_sesion_model=new UsuarioModel();
        $usuario_sesion=$usuario_sesion_model->find($_SESSION['id']);
        if ($usuario_sesion && $usuario_sesion->getUsuario()!==$username && $usuario_sesion->getRol()!=='admin') {
            return $this->error403();
        }
        if (isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] === "POST") {
            if (isset($_POST['csrf_token']) && isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
                $usuarioModel = new UsuarioModel();
                $errores = [];
                $mensaje = "";
                $usuario_origen = $usuarioModel->select('*')->where('usuario', $username)->get()[0];

                if (!$usuario_origen) {
                    return $this->redirect('/login');
                }


                if (empty($_POST['usuario_destino'])) {
                    $errores[] = "El campo usuario destino está vacío";
                } else if (!preg_match('/^[a-z0-9]{2,100}$/', $_POST['usuario_destino'])) {
                    $errores[] = "El nombre de usuario debe contener entre 2 y 100 caracteres válidos";
                } else {
                    $usuarioMod = new UsuarioModel();
                    $usuario_destino = $usuarioMod->select('*')
                        ->where('usuario', $this->sanitizaDatos($_POST['usuario_destino']))
                        ->get()[0] ?? null;

                    if (!$usuario_destino) {
                        $errores[] = "El usuario destino no existe";
                    } else {
                        if ($usuario_destino->getUsuario() === $usuario_origen->getUsuario()) {
                            $errores[] = "No te puedes enviar puntos a ti mismo";
                        }
                    }
                }

                if (empty($_POST['puntos_enviar'])) {
                    $errores[] = "El campo puntos a enviar está vacío";
                } else if (!preg_match('/^[0-9]{1,11}$/', $_POST['puntos_enviar'])) {
                    $errores[] = "El campo puntos debe ser un número entero válido";
                } else {
                    $puntos = (int)$_POST['puntos_enviar'];

                    if ($usuario_origen->getPuntos() < $puntos) {
                        $errores[] = "Puntos insuficientes";
                    }
                }

                if (!empty($errores)) {
                    $_SESSION['errores_enviar_puntos'] = $errores;
                    return $this->redirect('/usuario/' . $usuario_origen->getUsuario());
                }

                $usuarioMod1 = new UsuarioModel();
                $usuarioMod2 = new UsuarioModel();
                //$mensaje = $usuarioMod1->enviaPuntosConTransaccion($usuario_origen, $usuario_destino, $puntos);
                //con Método transaccion general
                $mensaje=$usuarioMod1->transaccion(function($usuario_origen,$usuario_destino,$puntos) use ($usuarioMod1){
                    $puntos_origen=$usuario_origen->getPuntos();
                    $puntos_destino=$usuario_destino->getPuntos();
                    $puntos_origen=$puntos_origen-$puntos;
                    $puntos_destino=$puntos_destino+$puntos;
                    $usuarioMod1->update($usuario_origen->getId(),['puntos'=>$puntos_origen]);
                     $usuarioMod1->update($usuario_destino->getId(),['puntos'=>$puntos_destino]); 
                }, $usuario_origen,$usuario_destino,$puntos);
                
                $_SESSION['mensaje_enviar_puntos'] = $mensaje;
                return $this->redirect('/usuario/' . $usuario_origen->getUsuario());
            } else {
                $mensaje = "Solicitud inválida";
                $_SESSION['mensaje_enviar_puntos'] = $mensaje;
                return $this->redirect('/usuario/' . $username);
            }
        }
    }

    public function destroy($id)
    {
        if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol'])) {
            return $this->redirect('/login');
        }
        if(!preg_match('/^[0-9]{1,11}$/',$id)){
            return $this->error404();
        }
        $usuario_sesion_model=new UsuarioModel();
        $usuario_sesion=$usuario_sesion_model->find($_SESSION['id']);
        if ($usuario_sesion  && $usuario_sesion->getRol()!=='admin') {
            return $this->error403();
        }
        if (isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] === "POST") {
            if (isset($_POST['csrf_token_borrar']) && isset($_SESSION['csrf_token_borrar']) && hash_equals($_SESSION['csrf_token_borrar'], $_POST['csrf_token_borrar'])) {
                $usuarioModel=new UsuarioModel();
                $usuario_existe=$usuarioModel->find($id);
                if($usuario_existe){
                    $usuarioModel->delete($usuario_existe->getId());
                     return $this->redirect('/list/');
                }
           
            }
        }   
    }

    public function crear_bd()
    {
        $cont = 0;
        $data = false;
        $usuarioModel = new UsuarioModel();

        if (!$usuarioModel->tableExists('usuario')) {
            $usuarioModel->createTableUsuario();
            $nombres = ['María', 'Marta', 'Luis', 'Juan', 'Antonio'];
            $apellidos1 = ['Jiménez', 'López', 'Sánchez', 'Martín', 'Váquez'];
            $apellidos2 = ['Lorenzo', 'Macías', 'Álvarez', 'Rodríguez', 'Fernández'];
            $usuarios = [];
            $user = true;
            foreach ($nombres as $nombre) {
                foreach ($apellidos1 as $apellido1) {
                    foreach ($apellidos2 as $apellido2) {
                        $username = strtolower($nombre . $apellido1 . $apellido2);
                        $username = str_replace(['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Ü', 'Ñ'], ['a', 'e', 'i', 'o', 'u', 'u', 'n', 'a', 'e', 'i', 'o', 'u', 'u', 'n'], $username);
                        $usuarios[] = [
                            'nombre' => $nombre,
                            'apellidos' => $apellido1 . " " . $apellido2,
                            'usuario' => $username,
                            'email' => $username . "@mail.com",
                            'fecha_nac' => '1998-10-11',
                            'contrasenia' => password_hash("00000" . $nombre . "_", PASSWORD_DEFAULT),
                            'puntos' => rand(1, 100),
                            'fecha_alta' => date_format(new DateTime(), 'Y-m-d'),
                            'rol' => ($cont <= 5) ? 'admin' : 'usuario'
                        ];
                        $cont++;
                    }
                }
            }
            $data = $usuarioModel->añadeUsuariosConTransaccion($usuarios);
        }
        return $this->view('crear_bd', $data);
    }
    public function sanitizaDatos(string $datos): string
    {
        $datos = trim($datos);
        $datos = htmlspecialchars($datos);
        return $datos;
    }

    /**
     * Comprueba que los datos de registro introducidos tienen el formato esperado y que no exista un 
     * usuario registrado con el  nombre de usuario o email introducido
     * Si los datos tienen el formato correcto y no existe un usuario en la base de datos con el mismo 
     * nombre de usuario y/o email muestra un mensaje de éxito en la página de registro.En caso contrario se
     *  muestran los errores correspondientes en la página de registro
     * 
     */
    public function alta()
    {

        if (isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = '';
            $apellidos = '';
            $usuario = '';
            $email = '';
            $fecha_nac = '';
            $contrasenia = '';
            $rol = 'usuario';
           
            $errores = [];
            if (empty($_POST['nombre'])) {
                $errores[] = "El campo nombre está vacío";
            } else {
                if (!preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñÜü ]{2,50}$/u', $_POST['nombre'])) {
                    $errores[] = "El nombre debe tener entre 2 y 50 caracteres válidos ";
                } else {
                    $nombre = $this->sanitizaDatos($_POST['nombre']);
                }
            }
            if (empty($_POST['apellidos'])) {
                $errores[] = "El campo apellidos está vacío";
            } else {
                if (!preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñÜü ]{2,100}$/u', $_POST['apellidos'])) {
                    $errores[] = "El campo apellidos debe contener entre 2 y 100 caracteres válidos ";
                } else {
                    $apellidos = $this->sanitizaDatos($_POST['apellidos']);
                }
            }
            if (empty($_POST['usuario'])) {
                $errores[] = "El campo nombre de usuario está vacío";
            } else {
                if (!preg_match('/^[a-z0-9]{2,100}$/', $_POST['usuario'])) {
                    $errores[] = "El nombre de usuario debe contener entre 2 y 100 caracteres válidos ";
                } else {
                    $usuario_introducido = $this->sanitizaDatos($_POST['usuario']);
                    $usuarioModel = new UsuarioModel();
                    $usuario_existe = $usuarioModel->select('usuario')->where('usuario', $usuario_introducido)->get();
                    if ($usuario_existe) {
                        $errores[] = "Ya hay un usuario registrado con ese nombre de usuario";
                    } else {
                        $usuario = $usuario_introducido;
                    }
                }
            }
            if (empty($_POST['email'])) {
                $errores[] = "El campo e-mail está vacío";
            } else {
                if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    $errores[] = "El email tiene una formato incorrecto ";
                } else {
                    $email_introducido = $this->sanitizaDatos($_POST['email']);
                    $usuarioModel = new UsuarioModel();
                    $email_existe = $usuarioModel->select('email')->where('email', $email_introducido)->get();
                    if ($email_existe) {
                        $errores[] = "Ya existe un usuario registrado con el email introducido";
                    } else {
                        $email = $email_introducido;
                    }
                }
            }
            if (empty($_POST['fecha_nac'])) {
                $errores[] = "El campo fecha de nacimiento está vacío";
            } else {
                $date = DateTime::createFromFormat('Y-m-d', $_POST['fecha_nac']);
                if (!($date && $date->format('Y-m-d') === $_POST['fecha_nac'])) {
                    $errores[] = 'La fecha de nacimiento no es válida';
                } else {
                    $fecha_nac = $date->format('Y-m-d');
                }
            }
            if (empty($_POST['contrasenia1'])) {
                $errores[] = "El campo contraseña está vacio";
            } else {
                if (empty($_POST['contrasenia2'])) {
                    $errores[] = "Debe repetir la contraseña";
                } else {
                    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&._])[A-Za-zÁÉÍÓÚáéíóúÑñÜü\d@$!%*#?&._]{8,40}$/u', $_POST['contrasenia1'])) {
                        $errores[] = "La contraseña debe tener entre 8 y 40 caracteres y contener al menos 1 número, una mayúscula, una minúscula y un carácter especial (@$!%*#?&.)";
                    } else {
                        if ($_POST['contrasenia1'] !== $_POST['contrasenia2']) {
                            $errores[] = "Las contraseñas no coinciden";
                        } else {
                            $contrasenia = $_POST['contrasenia1'];
                        }
                    }
                }
            }

            if (empty($errores)) {
                $usuarioModel = new UsuarioModel();
                $usuarioModel->create(
                    [
                        'nombre' => $nombre,
                        'apellidos' => $apellidos,
                        'usuario' => $usuario,
                        'email' => $email,
                        'fecha_nac' => $fecha_nac,
                        'contrasenia' => password_hash($contrasenia, PASSWORD_DEFAULT),
                        'puntos' => 0,
                        'fecha_alta' => date_format(new DateTime(), 'Y-m-d'),
                        'rol' => $rol
                    ]
                );
                $mensaje = "Usuario registrado con éxito";
                return $this->view('registro', $mensaje);
            } else {
                return $this->view('registro', $errores);
            }
        }
    }
}
