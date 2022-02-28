<?php
include_once '../modelo/conexionDB/conexionMySql/consulta.php';
include_once '../modelo/clases/funciones.php';

include_once '../modelo/clases/usuario.php';
include_once '../modelo/clases/mesas.php';
class Pedido
{	private $idPedido = 0; 
	private $idMesa; 
	private $personas; 
	private $fecha; 
	private $estado; 
	private $observacion; 
	private $idUsuario; 
    
    private $usuario; 
    private $mesas; 

	public function __construct($con = NULL)
	{	$this->conex = $con;
		$this->conexion = new Consulta($con);
        
        $this->usuario = new Usuario($con);
        $this->mesas = new Mesas($con);
	}

	public function getConexion()
	{	return $this->conexion;	}
	public function getFuncion()
	{	return new Funciones();	}

	//SETS	
	public function setUsuario($usuario)           	{	$this->usuario = $usuario;	} 
    public function setMesas($mesas)           	    {	$this->mesas = $mesas;	} 
    
    public function setIdPedido($idPedido)          {	$this->idPedido = $idPedido;	} 
	public function setIdMesa($idMesa)             	{	$this->idMesa = $idMesa;	} 
	public function setPersonas($personas)         	{	$this->personas = $personas;	} 
	public function setFecha($fecha)               	{	$this->fecha = $fecha;	} 
	public function setEstado($estado)             	{	$this->estado = $estado;	} 
	public function setObservacion($observacion)   	{	$this->observacion = $observacion;	} 
	public function setIdUsuario($idUsuario)       	{	$this->idUsuario = $idUsuario;	}
    
	 

	//GETS
	public function getUsuario()           	{	return $this->usuario;	}     
    public function getMesas()           	{	return $this->mesas;	} 
    
    public function getIdPedido()
	{	return $this->idPedido;	} 
	public function getIdMesa()            	{	return $this->idMesa;	} 
	public function getPersonas()          	{	return $this->personas;	} 
	public function getFecha()             	{	return $this->fecha;	} 
	public function getEstado()            	{	return $this->estado;	} 
	public function getObservacion()       	{	return $this->observacion;	} 
	public function getIdUsuario()         	{	return $this->idUsuario;	} 
    public function getEstadoNombre()        {	
	   $data = array("1"=>"Pendiente","2"=>"Atendido","3"=>"Pagado","4"=>"Cancelado"); 
       return $data[$this->estado];	}

	//METODOS GUARDAR
	public function guardar()
	{	($this->idPedido == 0)?$this->agregar():$this->modificar();	}

	private function agregar()
	{	$consulta = "INSERT INTO pedido (idMesa, personas, estado, observacion, idUsuario)
			VALUES('".$this->idMesa."', '".$this->personas."', '".$this->estado."', '".$this->observacion."', '".$this->idUsuario."')";
		$this->conexion->ejecutar($consulta);
	}

	private function modificar()
	{	$consulta = "UPDATE pedido 
			SET idMesa = '".$this->idMesa."', 
				personas = '".$this->personas."',
				estado = '".$this->estado."', 
				observacion = '".$this->observacion."', 
				idUsuario = '".$this->idUsuario."'
			WHERE idPedido = '".$this->idPedido."'";
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
			$consulta = "SELECT ".$campo." FROM pedido ".$condicion.$orden.$limite;
			$datos = $this->conexion->datosTabla($consulta);
			foreach ($datos as $row)
			{	$objeto = new Pedido($this->conex);
				$objeto->setIdPedido($row['idPedido']);
				$objeto->setIdMesa($row['idMesa']);
				$objeto->setPersonas($row['personas']);
				$objeto->setFecha($row['fecha']);
				$objeto->setEstado($row['estado']);
				$objeto->setObservacion($row['observacion']);
				$objeto->setIdUsuario($row['idUsuario']);
                
				$objeto->setUsuario($this->usuario->get($row['idUsuario']));
				$objeto->setMesas($this->mesas->get($row['idMesa']));
                
				$tabla[] = $objeto;
			}
			return $tabla;
		}
		else
		{	$consulta = "SELECT * FROM pedido WHERE idPedido='".$id."' LIMIT 1";

			$datos = $this->conexion->datosTabla($consulta);
			$objeto = new Pedido($this->conex);
			if(count($datos)>0)
			{	$objeto->setIdPedido($datos[0]['idPedido']);
				$objeto->setIdMesa($datos[0]['idMesa']);
				$objeto->setPersonas($datos[0]['personas']);
				$objeto->setFecha($datos[0]['fecha']);
				$objeto->setEstado($datos[0]['estado']);
				$objeto->setObservacion($datos[0]['observacion']);
				$objeto->setIdUsuario($datos[0]['idUsuario']);
                
				$objeto->setUsuario($this->usuario->get($datos[0]['idUsuario']));
				$objeto->setMesas($this->mesas->get($datos[0]['idMesa']));
			}
			return $objeto;
		}
	}

	//ELIMINAR
	public function eliminar()
	{	$consulta = "DELETE FROM pedido WHERE idPedido='".$this->idPedido."' LIMIT 1;";
		$this->conexion->ejecutar($consulta);
	}
}