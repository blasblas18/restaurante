<?php
include_once '../modelo/conexionDB/conexionMySql/consulta.php';
include_once '../modelo/clases/funciones.php';
class Usuario
{	private $idUsuario = 0; 
	private $dni; 
	private $paterno; 
	private $materno; 
	private $nombre; 
	private $clave; 
	private $correo; 
	private $celular; 
	private $idTipo; 
	private $idNivel; 
	private $idAcceso; 
	private $idPermiso; 
	private $cod_2000; 
	private $cargo; 
	private $estado; 

	public function __construct($con = NULL)
	{	$this->conex = $con;
		$this->conexion = new Consulta($con);
	}

	public function getConexion()      	{	return $this->conexion;	}
	public function getFuncion()       	{	return new Funciones();	}

	//SETS    
	public function setIdUsuario($idUsuario)       	{	$this->idUsuario = $idUsuario;	} 
	public function setDni($dni)                   	{	$this->dni = $dni;	} 
	public function setPaterno($paterno)           	{	$this->paterno = $paterno;	} 
	public function setMaterno($materno)           	{	$this->materno = $materno;	} 
	public function setNombre($nombre)             	{	$this->nombre = $nombre;	} 
	public function setClave($clave)               	{	$this->clave = $clave;	} 
	public function setCorreo($correo)             	{	$this->correo = $correo;	} 
	public function setCelular($celular)           	{	$this->celular = $celular;	} 
	public function setIdTipo($idTipo)             	{	$this->idTipo = $idTipo;	    } 
	public function setIdNivel($idNivel)           	{	$this->idNivel = $idNivel;	    } 
	public function setIdAcceso($idAcceso)         	{	$this->idAcceso = $idAcceso;	} 
	public function setIdPermiso($idPermiso)       	{	$this->idPermiso = $idPermiso;	} 
	public function setCod_2000($cod_2000)         	{	$this->cod_2000 = $cod_2000;	} 
	public function setCargo($cargo)         	    {	$this->cargo = $cargo;	        } 
	public function setEstado($estado)         	    {	$this->estado = $estado;	    } 

	//GETS
	public function getIdUsuario()             	    {	return $this->idUsuario;	} 
	public function getDni()                   	    {	return $this->dni;	} 
	public function getPaterno()                   	{	return $this->paterno;	} 
	public function getMaterno()                   	{	return $this->materno;	} 
	public function getNombre()                    	{	return $this->nombre;	} 
    public function getNombreCompleto()             {	return $this->paterno." ".$this->materno.", ".$this->nombre;	} 
    public function getClave()                     	{	return $this->clave;	} 
	public function getCorreo()                    	{	return $this->correo;	} 
	public function getCelular()                   	{	return $this->celular;	} 
	public function getIdTipo()                    	{	return $this->idTipo;	}
	public function getTipoNombre()                 {	$data = array("1"=>"Salud","2"=>"Municipio"); return $data[$this->idTipo];	}     
	public function getIdNivel()                   	{	return $this->idNivel;	} 
	public function getNivelNombre2()                {	
	   $data = array("1"=>"D-R","2"=>"R-P","3"=>"M-D","4"=>"ES"); return $data[$this->idNivel];	}
	public function getNivelNombre()                {	
	   $data = array("1"=>"DIRESA-Regi&oacute;n","2"=>"Red-Provincia","3"=>"MicroRed-Distrito","4"=>"EESS"); return $data[$this->idNivel];	}
	public function getIdAcceso()                  	{	return $this->idAcceso;	} 
	public function getAccesoNombre()               {	$data = array("1"=>"SuperAdmin","2"=>"Admin","3"=>"Usuarios"); return $data[$this->idAcceso];	}
	public function getIdPermiso()                 	{	return $this->idPermiso;	} 
	public function getPermisoNombre()              {	$data = array("1"=>"Escritura","2"=>"Lectura"); return $data[$this->idPermiso];	}
	public function getCod_2000()                  	{	return $this->cod_2000;	} 
	public function getCargo()         	            {	return $this->cargo;	        } 
	public function getEstado()         	        {	return $this->estado;	    } 

	//METODOS GUARDAR
	public function guardar()
	{	($this->idUsuario == 0)?$this->agregar():$this->modificar();	}

	private function agregar()
	{	$consulta = "INSERT INTO salud_sismore.usuario (dni, paterno, materno, nombre, clave, correo, celular, idTipo, idNivel, idAcceso, idPermiso, cod_2000, cargo, estado)
			VALUES('".$this->dni."', '".$this->paterno."', '".$this->materno."', '".$this->nombre."', '".$this->clave."', '".$this->correo."', '".$this->celular."', '".$this->idTipo."', '".$this->idNivel."', '".$this->idAcceso."', '".$this->idPermiso."', '".$this->cod_2000."', '".$this->cargo."', '".$this->estado."')";
		$this->conexion->ejecutar($consulta);
	}

	private function modificar()
	{	$consulta = "UPDATE salud_sismore.usuario 
			SET dni = '".$this->dni."', paterno = '".$this->paterno."', materno = '".$this->materno."', nombre = '".$this->nombre."', 
				clave = '".$this->clave."', correo = '".$this->correo."', celular = '".$this->celular."', idTipo = '".$this->idTipo."', 
				idNivel = '".$this->idNivel."', idAcceso = '".$this->idAcceso."', idPermiso = '".$this->idPermiso."', 
				cod_2000 = '".$this->cod_2000."', cargo = '".$this->cargo."', estado = '".$this->estado."'
                 WHERE idUsuario = '".$this->idUsuario."'";
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
			$consulta = "SELECT ".$campo." FROM salud_sismore.usuario ".$condicion.$orden.$limite;
			$datos = $this->conexion->datosTabla($consulta);
			foreach ($datos as $row)
			{	$objeto = new Usuario($this->conex);
				$objeto->setIdUsuario($row['idUsuario']);
				$objeto->setDni($row['dni']);
				$objeto->setPaterno($row['paterno']);
				$objeto->setMaterno($row['materno']);
				$objeto->setNombre($row['nombre']);
				$objeto->setClave($row['clave']);
				$objeto->setCorreo($row['correo']);
				$objeto->setCelular($row['celular']);
				$objeto->setIdTipo($row['idTipo']);
				$objeto->setIdNivel($row['idNivel']);
				$objeto->setIdAcceso($row['idAcceso']);
				$objeto->setIdPermiso($row['idPermiso']);
				$objeto->setCod_2000($row['cod_2000']);
				$objeto->setCargo($row['cargo']);
				$objeto->setEstado($row['estado']);
                
				$tabla[] = $objeto;
			}
			return $tabla;
		}
		else
		{	$consulta = "SELECT * FROM salud_sismore.usuario WHERE idUsuario='".$id."' LIMIT 1";

			$datos = $this->conexion->datosTabla($consulta);
			$objeto = new Usuario($this->conex);
			if(count($datos)>0)
			{	$objeto->setIdUsuario($datos[0]['idUsuario']);
				$objeto->setDni($datos[0]['dni']);
				$objeto->setPaterno($datos[0]['paterno']);
				$objeto->setMaterno($datos[0]['materno']);
				$objeto->setNombre($datos[0]['nombre']);
				$objeto->setClave($datos[0]['clave']);
				$objeto->setCorreo($datos[0]['correo']);
				$objeto->setCelular($datos[0]['celular']);
				$objeto->setIdTipo($datos[0]['idTipo']);
				$objeto->setIdNivel($datos[0]['idNivel']);
				$objeto->setIdAcceso($datos[0]['idAcceso']);
				$objeto->setIdPermiso($datos[0]['idPermiso']);
				$objeto->setCod_2000($datos[0]['cod_2000']);
				$objeto->setCargo($datos[0]['cargo']);
				$objeto->setEstado($datos[0]['estado']);
			}
			return $objeto;
		}
	}
    
    function getOptionTipo($id = NULL) 
    {   $datos = array(array("1","Salud"), array("2","Alcaldes"));
        $opciones = $this->getFuncion()->getOption($datos, $id );
        return $opciones;
    }
    
    function getOptionNivel($id = NULL) 
    {   $datos = array(array("1","DIRESA-Region"), array("2","Red-Provincia"), array("3","MicroRed-Distrito"), array("4","EE.SS."));
        $opciones = $this->getFuncion()->getOption($datos, $id );
        return $opciones;
    }
    
    function getOptionAcceso($id = NULL) 
    {   $datos = array(array("1","SuperAdmin"), array("2","Admin"), array("3","Usuarios"));
        $opciones = $this->getFuncion()->getOption($datos, $id );
        return $opciones;
    }
    
    function getOptionPermiso($id = NULL) 
    {   $datos = array(array("1","Escritura"), array("2","Lectura"));
        $opciones = $this->getFuncion()->getOption($datos, $id );
        return $opciones;
    }
    
    

	//ELIMINAR
	public function eliminar()
	{	$consulta = "DELETE FROM salud_sismore.usuario WHERE idUsuario='".$this->idUsuario."' LIMIT 1;";
		$this->conexion->ejecutar($consulta);
	}
}
?>