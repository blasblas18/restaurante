<?php
include_once '../modelo/conexionDB/conexionMySql/consulta.php';
include_once '../modelo/clases/funciones.php';
class Mesas
{	private $idMesa = 0; 
	private $nombre; 
	private $capacidad; 

	public function __construct($con = NULL)
	{	$this->conex = $con;
		$this->conexion = new Consulta($con);
	}

	public function getConexion()
	{	return $this->conexion;	}
	public function getFuncion()
	{	return new Funciones();	}

	//SETS
	public function setIdMesa($idMesa)
	{	$this->idMesa = $idMesa;	} 
	public function setNombre($nombre)
	{	$this->nombre = $nombre;	} 
	public function setCapacidad($capacidad)
	{	$this->capacidad = $capacidad;	} 

	//GETS
	public function getIdMesa()
	{	return $this->idMesa;	} 
	public function getNombre()
	{	return $this->nombre;	} 
	public function getCapacidad()
	{	return $this->capacidad;	} 

	//METODOS GUARDAR
	public function guardar()
	{	($this->idMesa == 0)?$this->agregar():$this->modificar();	}

	private function agregar()
	{	$consulta = "INSERT INTO restaurante.mesas (nombre, capacidad)
			VALUES('".$this->nombre."', '".$this->capacidad."')";
		$this->conexion->ejecutar($consulta);
	}

	private function modificar()
	{	$consulta = "UPDATE restaurante.mesas 
			SET nombre = '".$this->nombre."', 
				capacidad = '".$this->capacidad."'
			WHERE idMesa = '".$this->idMesa."'";
		$this->conexion->ejecutar($consulta);
	}

	//METODOS GET
	public function get($id = NULL, $campo = NULL, $condicion = NULL, $limite = NULL, $orden = NULL)
	{	if ($id == NULL)
	{	$tabla = array();
			$campo = ($campo == NULL)?"*":$campo;
			if($condicion == NULL)
				$condicion = "";
			else
			{	$matriz = explode(";;", $condicion);
				$condicion = " WHERE ";
				foreach($matriz AS $item)
					$condicion .= " ".$item." AND";
				$condicion = substr($condicion, 0, strlen($condicion)-4);
			}
			$limite = ($limite == NULL)?"":(" LIMIT ".$limite.";");
			$orden = ($orden == NULL)?"":(" ORDER BY ".$orden." ");
			$consulta = "SELECT ".$campo." FROM restaurante.mesas ".$condicion.$orden.$limite;
			$datos = $this->conexion->datosTabla($consulta);
			foreach ($datos as $row)
			{	$objeto = new Mesas($this->conex);
				$objeto->setIdMesa($row['idMesa']);
				$objeto->setNombre($row['nombre']);
				$objeto->setCapacidad($row['capacidad']);
				$tabla[] = $objeto;
			}
			return $tabla;
		}
		else
		{	$consulta = "SELECT * FROM restaurante.mesas WHERE idMesa='".$id."' LIMIT 1";

			$datos = $this->conexion->datosTabla($consulta);
			$objeto = new Mesas($this->conex);
			if(count($datos)>0)
			{	$objeto->setIdMesa($datos[0]['idMesa']);
				$objeto->setNombre($datos[0]['nombre']);
				$objeto->setCapacidad($datos[0]['capacidad']);
			}
			return $objeto;
		}
	}

	//ELIMINAR
	public function eliminar()
	{	$consulta = "DELETE FROM restaurante.mesas WHERE idMesa='".$this->idMesa."' LIMIT 1;";
		$this->conexion->ejecutar($consulta);
	}
}