<?php
namespace App\Models;
use PDO;
use PDOException;

/**
 * Gestiona la conexión de la base de datos e incluye un esquema para
 * un Query Builder. Los return son ejemplo en caso de consultar la tabla
 * usuarios.
 */

class Model{
    private PDO $connection;
    private $query; // Consulta a ejecutar
    private string $select = '*';
    private string $where='';
    private array $values = [];
    private string $orderBy='';
    private string $table=''; // Definido en la clase hijo
    private string $className;

    public function __construct($dbHost,$dbName, $user, $pass)  // Se puede modificar según montéis la conexión
    {
        $this->connection($dbHost,$dbName, $user, $pass);
    }

    public function connection($dbHost,$dbName, $user, $pass):void
    {

        try { 
            $this->connection = new PDO("mysql:host=$dbHost;dbname=$dbName",$user, $pass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e){ 
            die('Error : ' . $e->GetMessage());  
        }
    }

    // QUERY BUILDER
    // Consultas: 

    // Recibe la cadena de consulta y la ejecuta
    public function query($sql, $data = []):self{
        // Si hay $data se lanzará una consulta preparada, en otro caso una normal
        // Está configurado para mysqli, cambiar para usar PDO
        if ($data) {
            // Sentencia preparada, pasando array como parámetros
            // Cambiar a PDO
            $smtp = $this->connection->prepare($sql);
            $smtp->execute($data);
            $this->query = $smtp;
        } else {
            $this->query = $this->connection->query($sql);
        }
        //permite encadenar métodos
        return $this;
    }

    public function select(string ...$columns):self{
        // Separamos el array en una cadena con ,
        $this->select = implode(', ', $columns);
        return $this;
    }

    // Devuelve todos los registros de una tabla
    public function all():array
    {
       // La consulta sería
        $sql = "SELECT * FROM {$this->table}";
        // Y se llama a la sentencia
        return $this->query($sql)->get();
    }

    // Consulta base a la que se irán añadiendo partes
    /**
     * @param string $className (opcional) 
     * @return array 
     * devuelve un array con objetos de la clase estándar genérica de 
     * PHP si no se le pasa por parámtro nombre de clase, si se indica el nombre de la clase
     * devolverá un array de objetos de dicha clase
     *
     */
    public function get():array{
        if (empty($this->query)) {
            $sql = "SELECT {$this->select} FROM {$this->table}";
            // Se comprueban si están definidos para añadirlos a la cadena $sql
            if ($this->where) {
                $sql .= " WHERE {$this->where}";
            }
            if ($this->orderBy) {
                $sql .= " ORDER BY {$this->orderBy}";
            }
            $this->query($sql, $this->values);
        }
        return $this->query->fetchAll(PDO::FETCH_CLASS, $this->className);
       
    }

    public function find($id):object{
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $this->query($sql, [$id]);
        $result = $this->query->fetchObject($this->className);
        return $result ?: null;
    }

    // Se añade where a la sentencia con operador específico
    public function where($column, $operator, $value = null, $chainType = 'AND'):self
    {
        if ($value == null) { // Si no se pasa operador, por defecto =
            $value = $operator;
            $operator = '=';
        }

        // Si ya había algo de antes 
        if ($this->where) {
            $this->where .= " {$chainType} {$column} {$operator} ?";
        } else {
            $this->where = "{$column} {$operator} ?";
        }

        $this->values[] = $value;

        return $this;
    }

    // Se añade orderBy a la sentencia
    public function orderBy($column, $order = 'ASC'):self
    {
        if ($this->orderBy) {
            $this->orderBy .= ", {$column} {$order}";
        } else {
            $this->orderBy = "{$column} {$order}";
        }
        return $this;
    }

    // Insertar, recibimos un $_GET o $_POST
    public function create($data):self
    {

        $columns = array_keys($data); // array de claves del array
        $columns = implode(', ', $columns); // y creamos una cadena separada por ,

        $values = array_values($data); // array de los valores

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES (?" . str_repeat(',?', count($values) - 1) . ")";

        $this->query($sql, $values);

        return $this;
    }


    public function update($id, $data):self
    {
        $fields = [];

        foreach ($data as $key => $value) {
            $fields[] = "{$key} = ?";
        }

        $fields = implode(', ', $fields);

        $sql = "UPDATE {$this->table} SET {$fields} WHERE id = ?";

        $values = array_values($data);
        $values[] = $id;

        $this->query($sql, $values);
        return $this;
    }

    public function delete($id):void
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $this->query($sql, [$id], 'i');
    }
    
    public function createTable($data){
    
        $sql="CREATE TABLE  {$this->table} ($data)";
        $this->query($sql);
        return $this;
    }

    public function setTable($table):void
    {
        $this->table = $table;

    }
    //DEBUG iría aqui?
    public function tableExists(string $table): bool{
        $sql = "SELECT COUNT(*)
            FROM INFORMATION_SCHEMA.TABLES
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = ?";
    
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$table]);
        return $stmt->fetchColumn() > 0; 
}

    public function setClassName($className):void
    {
        $this->className = $className;
    }
    
    public function getConnection():PDO
    {
        return $this->connection;
    }
}
