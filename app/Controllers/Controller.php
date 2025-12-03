<?php

namespace App\Controllers;

class Controller 
{
    public function view($route, $data = null) {
        $route = str_replace('.', '/', $route); // Las rutas se indicarán con puntos en vez de /

        // IMPORTANTE: este código se está ejecuntado desde index.php, por lo que
        // la ruta deberá de especificarse desde allí
        if (file_exists("../resources/views/{$route}.php")) {
            ob_start();
            include "../resources/views/{$route}.php";
            $content = ob_get_clean();

            return $content;
        }
        else {
            echo "La vista no existe";
        }
    }

    public function redirect($route) {
        header("Location: {$route}");
    }
    public function error404(){
        http_response_code(404); 
        include "../resources/views/404.php";
        exit;
    }
    public function error403(){
        http_response_code(403); 
        include "../resources/views/404.php";
        exit;
    }


}