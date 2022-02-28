<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Clase que permite conectarnos al servidor de base de datos MySql
 * @author CTIC-UNAS <ctic@unas.edu.pe>
 */
class Consulta
{
    private $conexion;

    /**
     * Construye la conección al servidor de base de datos MySql
     */
    public function __construct($con = NULL)
    {   $this->conexion = $con;
    }    
    
    /**
     * Realiza la consulta a la base de datos
     * @param type $consulta
     * @return type $resultado
     */
    public function consultar($consulta)
    {   $this->resultado = mysqli_query($this->conexion,$consulta);
		return $this->resultado;
    }
    
    /**
     * Ejecuta comandos como instrucciones INSERT, DELETE, UPDATE
     * @param type $sql
     */
    public function ejecutar($sql)
    {	mysqli_query($this->conexion,$sql);        
    }


    /**
     * Obtiene el número de registros obtenidos de la consulta realizada
     * @param type $resultado
     * @return type $cantidad
     */
    public function numeroRegistros($consulta)
    {   return mysqli_num_rows($consulta);
    }
    
    /**
     * Retorna en un array los registros obtenidos de la consulta realizada
     * @param type $resultado
     * @return type $array
     */
    public function datosTabla($consulta)
    {   $array = array();
        $resultado = $this->consultar($consulta);
        //echo var_dump($resultado);
        if($resultado)
            while ($row = mysqli_fetch_array($resultado))
            {
                $array[] = $row;
            }
        return $array;
    }   
}
?>