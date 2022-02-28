<?php
include_once '../modelo/conexionDB/conexionMySql/consulta.php';
include_once '../modelo/clases/funciones.php';

include_once '../modelo/clases/pedido.php';
include_once '../modelo/clases/comida.php';

class Detalle
{	private $idPedido = 0; 
	private $idComida; 
	private $cantidad; 
	private $observacion; 
    
    private $pedido; 
    private $comida; 

	public function __construct($con = NULL)
	{	$this->conex = $con;
		$this->conexion = new Consulta($con);
        
		$this->pedido = new Pedido($con);
		$this->comida = new Comida($con);
	}

	public function getConexion()
	{	return $this->conexion;	}
	public function getFuncion()
	{	return new Funciones();	}

	//SETS
	public function setPedido($pedido)             	{	$this->pedido = $pedido;	} 
	public function setComida($comida)             	{	$this->comida = $comida;	} 
	
    public function setIdPedido($idPedido)
	{	$this->idPedido = $idPedido;	} 
	public function setIdComida($idComida)
	{	$this->idComida = $idComida;	} 
	public function setCantidad($cantidad)
	{	$this->cantidad = $cantidad;	} 
	public function setObservacion($observacion)
	{	$this->observacion = $observacion;	} 

	//GETS
	public function getPedido()             	{	return $this->pedido;	} 
	public function getComida()             	{	return $this->comida;	} 
	
    public function getIdPedido()
	{	return $this->idPedido;	} 
	public function getIdComida()
	{	return $this->idComida;	} 
	public function getCantidad()
	{	return $this->cantidad;	} 
	public function getObservacion()
	{	return $this->observacion;	} 

	//METODOS GUARDAR
	public function guardar($tipo)
	{	($tipo == 0)?$this->agregar():$this->modificar();	}

	private function agregar()
	{	$consulta = "INSERT INTO detalle (idPedido, idComida, cantidad, observacion)
			VALUES('".$this->idPedido."', '".$this->idComida."', '".$this->cantidad."', '".$this->observacion."')";
        echo $consulta;
		$this->conexion->ejecutar($consulta);
	}

	private function modificar()
	{	$consulta = "UPDATE detalle 
			SET cantidad = '".$this->cantidad."', 
				observacion = '".$this->observacion."'
			WHERE idPedido = '".$this->idPedido."' AND  idComida = '".$this->idComida."'";
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
			$consulta = "SELECT ".$campo." FROM detalle ".$condicion.$orden.$limite;
			$datos = $this->conexion->datosTabla($consulta);
			foreach ($datos as $row)
			{	$objeto = new Detalle($this->conex);
				$objeto->setIdPedido($row['idPedido']);
				$objeto->setIdComida($row['idComida']);
				$objeto->setCantidad($row['cantidad']);
				$objeto->setObservacion($row['observacion']);
                
				$objeto->setPedido($this->pedido->get($row['idPedido']));
				$objeto->setComida($this->comida->get($row['idComida']));
				$tabla[] = $objeto;
			}
			return $tabla;
		}
		else
		{	$consulta = "SELECT * FROM detalle WHERE idPedido='".$id."' LIMIT 1";

			$datos = $this->conexion->datosTabla($consulta);
			$objeto = new Detalle($this->conex);
			if(count($datos)>0)
			{	$objeto->setIdPedido($datos[0]['idPedido']);
				$objeto->setIdComida($datos[0]['idComida']);
				$objeto->setCantidad($datos[0]['cantidad']);
				$objeto->setObservacion($datos[0]['observacion']);
                
				$objeto->setPedido($this->pedido->get($datos[0]['idPedido']));
				$objeto->setComida($this->comida->get($datos[0]['idComida']));
			}
			return $objeto;
		}
	}

	//ELIMINAR
	public function eliminar()
	{	$consulta = "DELETE FROM detalle WHERE idPedido='".$this->idPedido."' AND idComida='".$this->idComida."' LIMIT 1;";
		$this->conexion->ejecutar($consulta);
	}
}