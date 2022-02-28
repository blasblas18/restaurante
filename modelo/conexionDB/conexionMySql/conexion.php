<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Clase que permite conectarnos al servidor de base de datos MySql
 * @author CTIC-UNAS <ctic@unas.edu.pe>
 */
class Conexion
{
    private $link;
    private $servidor, $usuario, $contraseña, $db, $puerto;

    /**
     * Construye la conección al servidor de base de datos MySql
     */
    public function __construct()
    {
        $this->servidor = "localhost";
        $this->usuario = "root";
        $this->contraseña = '';
        $this->db = "restaurante";					
    }
    
 //   public function __construct()
//    {
//        $this->servidor = "localhost";
//        $this->usuario = "nativatec_salud";
//        $this->contraseña = '2015salud';
//        $this->db = "salud_sismore";					
//    }
//    
    /**
     * Realiza la conexión y seleciona la base de datos a utilizar
     */
    public function conectar()
    {   $this->link = mysqli_connect($this->servidor, $this->usuario, $this->contraseña, $this->db)
        or die("No se logró establecer la conexión con el servidor MySQL: ".  mysqli_error($this->link));
		return $this->link;
    }  
	
	public function desconectar()
    {
        mysqli_close($this->link);
    }    
   
    public function __sleep()
    {
        return array('server', 'username', 'password', 'db');
    }
    
    public function __wakeup()
    {
        $this->connect();
    }
}
?>