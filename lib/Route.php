<?php

namespace Lib;

/**
 * Esta clase se encargará de almacenar las rutas válidas
 * de la aplicación. Para registrarlas se llamará a la función
 * get o a la función post. Además de la dirección se guardará
 * un método con cada registro.
 */

class Route
{
    // Todas las URLs válidas de la aplicación
    private static $routes =  [];

    // Para peticiones GET se guardará en $routes la dirección ($uri) y la clase con un método (array $class)
    static public function get($uri, $class)
    {
        $uri = trim($uri, '/'); // Eliminamos los / de inicio y final de la ruta
        self::$routes['GET'][$uri] = $class;
    }

    // Para peticiones POST igual que GET
    static public function post($uri, $class)
    {
        $uri = trim($uri, '/');
        self::$routes['POST'][$uri] = $class;
    }

    // Recorre todos los $routes y si el usuario visita uno que existe
    // ejecuta la función guardada en el array
    public static function dispatch()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $uri = trim($uri, '/');

        // si nos llega algo por ? lo borramos, lo podemos recuperar en $_GET en el modelo
        if (strpos($uri, '?')) {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }

        $method = $_SERVER['REQUEST_METHOD'];  // get o post

        // Por cada $routes[get o post] recogemos al ruta ($route) y la clase y método (array $class)
        foreach (self::$routes[$method] as $route => $class) {
            // Nos pueden llegar rutas con : o sin, por lo que se deberá modificar para
            // tener en cuenta ambos casos.

            // Si tiene : . Por ejemplo, en el caso de curso, no se guardará /curso/:variable, se guardará 
            // una cadena del tipo /curso/[a-zA-Z0-9]+  , para que al evaluarse con preg_match coincida 
            // con cualquier cadena de la expresión regular.
            if (strpos($route, ':') != false) {
                // Se crea la $url cambiando el valor de parámetro con la cadena '[a-zA-Z0-9]+'
                $route = preg_replace('#:[a-zA-Z0-9]+#', '([a-zA-Z0-9]+)', $route);
                
                
            }

            // Caso normal. La cadena debe empezar y acabar igual: inicio ^ y fin $
            // Habría coincidencia por ejemplo en /curso/aaabbbccc, que que la segunda parte evalúa con [a-zA-Z0-9]+
            
            if (preg_match("#^$route$#", $uri, $matches)) {
                // La primera coincidencia será la ruta normal, así que la quitamos  
                $params = array_slice($matches, 1);

                // Se instancia la clase y llamada al método definido en la ruta 
                // HomeController.php ($controller) e index() ($class[1])
                $controller = new $class[0];
                $response = $controller->{$class[1]}(...$params);

                // La respuesta del controlador, que a su vez usa el modelo y las vistas
                echo $response;

                return;
            }
        }

        //echo 'Error 404 not found';
        http_response_code(404);
        include __DIR__ . '/../resources/views/404.php';
        exit;
    }
}
