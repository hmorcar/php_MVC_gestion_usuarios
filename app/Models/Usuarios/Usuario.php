<?php
namespace App\Models\Usuarios;
use DateTime;
class Usuario{
    private int $id;
    private string $nombre;
    private string $apellidos;
    private string $usuario;
    private string $email;
    private  $fecha_nac;
    private string $contrasenia;
    private int $puntos;
    private  $fecha_alta;
    private string $rol;
    public function __construct(?string $nombre='', ?string $apellidos='', ?string $usuario='', ?string $email='',$fecha_nac='',  ?string $contrasenia='', ?string $rol=''){
        if($nombre && $apellidos && $usuario && $email  && $fecha_nac && $contrasenia){
            $this->nombre=$nombre;
            $this->apellidos=$apellidos;
            $this->usuario=$usuario;
            $this->email=$email;
            $this->contrasenia=$contrasenia;
            $this->fecha_alta=new DateTime(); 
            $this->puntos=0;
             $this->rol=$rol??'usuario';  
        }
        $this->setFecha_nac($this->fecha_nac);
        $this->setFecha_alta($this->fecha_alta);
       
    } 
        
    public function getNombre()
    {
        return $this->nombre;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function getApellidos()
    {
        return $this->apellidos;
    }
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;

    }
    public function getFecha_nac()
    {
        return $this->fecha_nac;
    }
    public function getFecha_nac_string_usuario()
    {
        return $this->fecha_nac->format('d-m-Y');
    }
    public function getFecha_nac_string()
    {
        return $this->fecha_nac->format('Y-m-d');
    }

    public function setFecha_nac($fecha_nac){
    if (is_string($fecha_nac)) {
        $this->fecha_nac = new DateTime($fecha_nac);  
    } elseif ($fecha_nac instanceof DateTime) {
        $this->fecha_nac = $fecha_nac;
    }
    }
    public function setFecha_alta($fecha_alta){
        if (is_string($fecha_alta)) {
            $this->fecha_alta = new DateTime($fecha_alta);  
        } elseif ($fecha_alta instanceof DateTime) {
            $this->fecha_alta = $fecha_alta;
        }
    }

    public function getPuntos()
    {
        return $this->puntos;
    }
    public function setPuntos($puntos)
    {
        $this->puntos = $puntos;
    }
    public function getFecha_alta()
    {
        return $this->fecha_alta;
    }
    public function getFecha_alta_string_usuario()
    {
         return $this->fecha_alta->format('d-m-Y');
    }
    public function getFecha_alta_string()
    {
         return $this->fecha_alta->format('Y-m-d');
    }
    public function getRol()
    {
        return $this->rol;
    }
    public function setRol($rol)
    {
        $this->rol = $rol;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

    }

    public function getContrasenia()
    {
        return $this->contrasenia;
    }


    public function setContrasenia($contrasenia)
    {
        $this->contrasenia = $contrasenia;

    }

    public function getId()
    {
        return $this->id;
    }

}

?>