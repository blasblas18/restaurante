<?php
include_once '../modelo/conexionDB/conexionMySql/consulta.php';
include_once '../modelo/clases/funciones.php';
class Comida
{	private $idComida = 0; 
	private $nombre; 
	private $tipo; 
	private $precio; 

	public function __construct($con = NULL)
	{	$this->conex = $con;
		$this->conexion = new Consulta($con);
	}

	public function getConexion()
	{	return $this->conexion;	}
	public function getFuncion()
	{	return new Funciones();	}

	//SETS
	public function setIdComida($idComida)
	{	$this->idComida = $idComida;	} 
	public function setNombre($nombre)
	{	$this->nombre = $nombre;	} 
	public function setTipo($tipo)
	{	$this->tipo = $tipo;	} 
	public function setPrecio($precio)
	{	$this->precio = $precio;	} 

	//GETS
	public function getIdComida()
	{	return $this->idComida;	} 
	public function getNombre()
	{	return $this->nombre;	} 
	public function getTipo()
	{	return $this->tipo;	} 
	public function getPrecio()
	{	return $this->precio;	} 

	//METODOS GUARDAR
	public function guardar()
	{	($this->idComida == 0)?$this->agregar():$this->modificar();	}

	private function agregar()
	{	$consulta = "INSERT INTO comida (nombre, tipo, precio)
			VALUES('".$this->nombre."', '".$this->tipo."', '".$this->precio."')";
		$this->conexion->ejecutar($consulta);
	}

	private function modificar()
	{	$consulta = "UPDATE comida 
			SET nombre = '".$this->nombre."', 
				tipo = '".$this->tipo."', 
				precio = '".$this->precio."'
			WHERE idComida = '".$this->idComida."'";
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
			$consulta = "SELECT ".$campo." FROM comida ".$condicion.$orden.$limite;
			$datos = $this->conexion->datosTabla($consulta);
			foreach ($datos as $row)
			{	$objeto = new Comida($this->conex);
				$objeto->setIdComida($row['idComida']);
				$objeto->setNombre($row['nombre']);
				$objeto->setTipo($row['tipo']);
				$objeto->setPrecio($row['precio']);
				$tabla[] = $objeto;
			}
			return $tabla;
		}
		else
		{	$consulta = "SELECT * FROM comida WHERE idComida='".$id."' LIMIT 1";

			$datos = $this->conexion->datosTabla($consulta);
			$objeto = new Comida($this->conex);
			if(count($datos)>0)
			{	$objeto->setIdComida($datos[0]['idComida']);
				$objeto->setNombre($datos[0]['nombre']);
				$objeto->setTipo($datos[0]['tipo']);
				$objeto->setPrecio($datos[0]['precio']);
			}
			return $objeto;
		}
	}

	//ELIMINAR
	public function eliminar()
	{	$consulta = "DELETE FROM comida WHERE idComida='".$this->idComida."' LIMIT 1;";
		$this->conexion->ejecutar($consulta);
	}
}