<?php   session_start();
include_once '../modelo/conexionDB/conexionMySql/conexion.php';
$Conexion = new Conexion();     $conex = $Conexion->conectar();
$dirClase = '../modelo/clases/usuario.php';
$dirClaseUsuarioRol = '../modelo/clases/usuario_rol.php';
//echo var_dump($_SESSION["sismore_salud_perm"]);
function veri_veri($idPermiso){   $idPer = explode(",", $idPermiso);    $texto = FALSE;
    foreach($idPer AS $id)    {   if(isset($_SESSION["sismore_salud_perm"][$id]))   if($_SESSION["sismore_salud_perm"][$id])  $texto = TRUE;    }
    return $texto;        } 
function veri_permiso($idPermiso){   if(!veri_veri($idPermiso))    {   echo "SinPermiso";  exit(0);     }    }

switch($_REQUEST["accion"])
{	case "Template":    veri_permiso("UsuarioMostrar");
		$html = file_get_contents("../vista/capas/principal.html");        
        $html = str_replace("{CAPA1}", "", $html); $html = str_replace("{CAPA2}", "", $html);
		$html = str_replace("{CAPA3}", "", $html); $html = str_replace("{CAPA4}", "", $html);
        
        echo $html."<script> $.ajax(
                    {   url : 'controlador/control_usuario.php',    data : 'accion=Listado',
                        success : function(respuesta){   $('#capa1').html(respuesta);  }
                    });  </script>";
	  break;
	case "Listado":       veri_permiso("UsuarioMostrar");
		include $dirClase;  $con = new Usuario($conex); $conC = $con->getConexion(); $conF = $con->getFuncion();
		$datos = $con->get();

		include ("../vista/capas/usuario/plantilla_usuario_lista.php");
        
        $idUsuario = isset($_REQUEST["idUsuario"])?$_REQUEST["idUsuario"]:0;
        //echo $idUsuario;
        if($idUsuario!=0)
            echo "<script> $.ajax(
                    {   url : 'controlador/control_usuario.php',
                        data : 'accion=UsuarioRolAsignadoLista&idUsuario=".$idUsuario."',
                        success : function(listado){   $('#espacioUsuarioRol').html(listado);  }
                    });  </script>";
	  break;
	case "Nuevo":  veri_permiso("UsuarioAgregar");
		include $dirClase;  $con = new Usuario($conex);  $conC = $con->getConexion(); $conF = $con->getFuncion();
		$html = file_get_contents("../vista/capas/usuario/plantilla_usuario_formulario.html");
        $optionsT = $con->getOptionTipo("1");           $optionsN = $con->getOptionNivel("1");
        $optionsA = $con->getOptionAcceso("1");         $optionsP = $con->getOptionPermiso("1");
        $optionsE = $con->getEstablecimiento()->getOption("34","000005577");       
        
		$variable = array("{TITULO_FORMULARIO}", "{INPUT_ID}", "{VALUE_DNI}", "{VALUE_PATERNO}", "{VALUE_MATERNO}"
            , "{VALUE_NOMBRE}", "{VALUE_CLAVE}", "{VALUE_CORREO}", "{VALUE_CELULAR}", "{OPTIONS_TIPO}"
            , "{OPTIONS_NIVEL}", "{OPTIONS_ACCESO}", "{OPTIONS_PERMISO}", "{OPTIONS_EESS}", "{VALUE_CARGO}", "{CHECK_ESTADO}");
		$valores = array(" Nueva - Formulario", "", "", "", "", "", "", "", "", $optionsT, $optionsN, $optionsA, $optionsP, $optionsE, "", " checked ");
		$html = $con->getFuncion()->replace_form($html, $variable, $valores);

		echo $html;
	  break;
	case "Edicion":    veri_permiso("UsuarioEditar");
		include $dirClase;  $con = new Usuario($conex); $conC = $con->getConexion(); $conF = $con->getFuncion();
		$idUsuario = $_REQUEST["idUsuario"];
		$datosU = $con->get($idUsuario);
        //echo var_dump($datosU);
		$html = file_get_contents("../vista/capas/usuario/plantilla_usuario_formulario.html");
        
		$variable = array("{TITULO_FORMULARIO}", "{INPUT_ID}", "{VALUE_DNI}", "{VALUE_PATERNO}", "{VALUE_MATERNO}"
            , "{VALUE_NOMBRE}", "{VALUE_CLAVE}", "{VALUE_CORREO}", "{VALUE_CELULAR}", "{OPTIONS_TIPO}"
            , "{OPTIONS_NIVEL}", "{OPTIONS_ACCESO}", "{OPTIONS_PERMISO}", "{OPTIONS_EESS}", "{VALUE_CARGO}", "{CHECK_ESTADO}");
		$valores = array(" - Editar"
			,"<input type='hidden' id='idUsuario' name='idUsuario' value='".$idUsuario."' />"
			, $datosU->getDni()	, $datosU->getPaterno()	, $datosU->getMaterno()	, $datosU->getNombre()
			, $datosU->getClave()	, $datosU->getCorreo()		, $datosU->getCelular()
			, $con->getOptionTipo($datosU->getIdTipo())
			, $con->getOptionNivel($datosU->getIdNivel())
			, $con->getOptionAcceso($datosU->getIdAcceso())
			, $con->getOptionPermiso($datosU->getIdPermiso())
			, $con->getEstablecimiento()->getOption("34", $datosU->getCod_2000())
			, $datosU->getCargo()
            , ($datosU->getEstado()=="1")?" checked ":""             );
		$html = $con->getFuncion()->replace_form($html, $variable, $valores);

		echo $html;
	  break;
	case "Guardar":    veri_permiso("UsuarioAgregar,UsuarioEditar");
		include $dirClase; $con = new Usuario($conex); $conC = $con->getConexion(); $conF = $con->getFuncion();

		if(isset($_REQUEST["idUsuario"]))
			$con->setIdUsuario($_REQUEST["idUsuario"]);

		$con->setDni($_REQUEST["dni"]);           		$con->setPaterno($_REQUEST["paterno"]);
		$con->setMaterno($_REQUEST["materno"]);   		$con->setNombre($_REQUEST["nombre"]);
		$con->setClave($_REQUEST["clave"]);       		$con->setCorreo($_REQUEST["correo"]);
		$con->setCelular($_REQUEST["celular"]);   		$con->setIdTipo($_REQUEST["idTipo"]);
		$con->setIdNivel($_REQUEST["idNivel"]);   		$con->setIdAcceso($_REQUEST["idAcceso"]);
		$con->setIdPermiso($_REQUEST["idPermiso"]);		$con->setCod_2000($_REQUEST["cod_2000"]);
		$con->setCargo($_REQUEST["cargo"]);             $con->setEstado($_REQUEST["estado"]);
		
		$con->guardar();
	  break;
	case "Eliminar":   veri_permiso("UsuarioEliminar");
		include $dirClase;  $con = new Usuario($conex);
		$con->setIdUsuario($_REQUEST["idUsuario"]);

		$con->eliminar();
	  break;
    case "Historial":
        include $dirClase; $con = new Usuario($conex); $conC = $con->getConexion(); $conF = $con->getFuncion();
        $anio = (isset($_REQUEST["anio"]))?$_REQUEST["anio"]:date("Y", time());
        $mes = (isset($_REQUEST["mes"]))?$_REQUEST["mes"]:date("m", time());
        
        $optionsA = $conF->getOption($conF->getDataAnio(2016), $anio);
        $optionsM = $conF->getOption($conF->getDataMes(), $mes);
        
        $query = "SELECT t1.idUsuario, paterno, materno, nombre, cantD, cantC, DATE_FORMAT(fecha, '%d-%m-%Y') AS fecha
            FROM ( SELECT idUsuario, COUNT(distinct(DAY(fecha))) AS cantD, COUNT(*) AS cantC, MAX(fecha) AS fecha 
            	FROM salud_sismore.acceso WHERE YEAR(fecha)='".$anio."' AND MONTH(fecha)='".$mes."'
            	GROUP BY idUsuario) AS t1
            INNER JOIN (SELECT * FROM salud_sismore.usuario) AS us ON us.idUsuario=t1.idUsuario
            ORDER BY cantD DESC";
        $datosU = $conC->datosTabla($query);
        
        $query = "SELECT MONTH(fecha) AS mes, COUNT(distinct(idUsuario)) AS cantU, COUNT(*) AS cantC
            FROM salud_sismore.acceso WHERE YEAR(fecha)='".$anio."' GROUP BY MONTH(fecha)";  
        $datos = $conC->datosTabla($query);     $dataGraf = "";   $dataGraf2 = "";   $mayor = 20;    $mayor2 = 20; 
            foreach($datos AS $item)
            {   
                $aux = round($item["cantC"]/100); if ($aux>=1 and (20*$aux)>$mayor )    $mayor = 20 * $aux;   
                $aux = round($item["cantU"]/100); if ($aux>=1 and (20*$aux)>$mayor2 )    $mayor2 = 20 * $aux;     
                $dataGraf .= ", [".$item["mes"].", ".$item["cantU"]."]";
                $dataGraf2 .= ", [".$item["mes"].", ".$item["cantC"]."]";  }
            $dataGraf = substr($dataGraf, 2, strlen($dataGraf));  
            $dataGraf2 = substr($dataGraf2, 2, strlen($dataGraf2));     
        
        include ("../vista/capas/usuario/plan_historial_acceso.php");
      break;
    case "UsuarioRolAsignadoLista":
        include $dirClaseUsuarioRol;  $conUR = new UsuarioRol($conex);
        $datosU = $conUR->getUsuario()->get($_REQUEST["idUsuario"]);
        $datos = $conUR->get(NULL, NULL, "idUsuario='".$_REQUEST["idUsuario"]."'");
        
        include ("../vista/capas/usuario/plan_usuario_rol_lista_asignada.php");
      break;
}
$Conexion->desconectar();

?>