<?php
namespace App\Models;
use App\Models\Usuarios\Usuario;
class UsuarioModel extends Model{
    // Nombre de la tabla que se realizarán las consultas
    private $table = 'usuario';
    private $className=Usuario::class;
    public function __construct()
    {
        $dotenv = \Dotenv\Dotenv::createImmutable('../'); 
        $dotenv->load();
        $dbHost = $_ENV['DB_HOST'] ?? '';
        $dbName = $_ENV['DB_NAME'] ?? '';
        $user = $_ENV['DB_USER'] ?? '';
        $pass = $_ENV['DB_PASS'] ?? '';
        parent::__construct($dbHost, $dbName, $user, $pass);
        parent::setTable($this->table);
        parent::setClassName($this->className);
    }

    /**
     * comprueba si existe la tabla usuario y si no está creada la crea llamaando al método c
     * reateTable de Model pasándole como parámetro el string con el nombre de la tabla a crear ('usuario')
     * @return bool  verdadero si la tabla no está creada y se crea , false si no se crea la tabla o ésta ya estaba creada
     */
    public function createTableUsuario():bool
    {
        if (!$this->tableExists('usuario')){
            $campos="id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
             nombre VARCHAR(50) NOT NULL, apellidos VARCHAR(100) NOT NULL, 
             usuario VARCHAR(100) NOT NULL UNIQUE, 
             email VARCHAR(100) NOT NULL UNIQUE, 
             fecha_nac DATE , 
             contrasenia VARCHAR(72) NOT NULL, 
             puntos INT(11), 
             fecha_alta DATE, 
             rol VARCHAR(10) NOT NULL DEFAULT 'usuario'";
            parent::createTable($campos);
            return $this->tableExists('usuario');
        }
        return false;
    }

}