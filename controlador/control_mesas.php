<?php
include_once '../modelo/conexionDB/conexionMySql/conexion.php';
$Conexion = new Conexion();     $conex = $Conexion->conectar();
$dirClase = '../modelo/clases/mesas.php';

switch($_REQUEST["accion"])
{	case "Template":
		$html = file_get_contents("../vista/capas/principal.html");

		include $dirClase;
		$con = new Mesas($conex);
		$datos = $con->get();

		ob_start();
		include ("../vista/capas/mesas/plan_mesas_lista.php");
		$lista_mesas = ob_get_clean();

		$html = str_replace("{CAPA1}", $lista_mesas, $html);
		$html = str_replace("{CAPA2}", "", $html);
		$html = str_replace("{CAPA3}", "", $html);
		$html = str_replace("{CAPA4}", "", $html);

		echo $html;
	  break;
	case "Listado":
		include $dirClase;  $con = new Mesas($conex);  $conC = $con->getConexion();   $conF = $con->getFuncion();
		$datos = $con->get();
        $html = file_get_contents("../vista/capas/principal.html");
        
        ob_start();
		include ("../vista/capas/mesas/plan_mesas_lista.php");
		$lista_mesas = ob_get_clean();

		$html = str_replace("{CAPA1}", $lista_mesas, $html);
		$html = str_replace("{CAPA2}", "", $html);
		$html = str_replace("{CAPA3}", "", $html);
		$html = str_replace("{CAPA4}", "", $html);
        
        echo $html;

	  break;
	case "Nuevo":
		include $dirClase;  $con = new Mesas($conex);
		$html = file_get_contents("../vista/capas/mesas/plan_mesas_formulario.html");

		$variable = array("{TITULO_FORMULARIO}", "{INPUT_ID}", "{VALUE_NOMBRE}", "{VALUE_CAPACIDAD}");
		$valores = array(" Nueva - Formulario", "", "", "");
		$html = $con->getFuncion()->replace_form($html, $variable, $valores);

		echo $html;
	  break;
	case "Edicion":
		include $dirClase;  $con = new Mesas($conex);
		$idMesa = $_REQUEST["idMesa"];
		$datosMesas = $con->get($idMesa);
		$html = file_get_contents("../vista/capas/mesas/plan_mesas_formulario.html");

		$variable = array("{TITULO_FORMULARIO}", "{INPUT_ID}", "{VALUE_NOMBRE}", "{VALUE_CAPACIDAD}");
		$valores = array(" - Editar"
			,"<input type='hidden' id='idMesa' name='idMesa' value='".$idMesa."' />"
			, $datosMesas->getNombre()
			, $datosMesas->getCapacidad());
		$html = $con->getFuncion()->replace_form($html, $variable, $valores);

		echo $html;
	  break;
	case "Guardar":
		include $dirClase;
		$con = new Mesas($conex);

		if(isset($_REQUEST["idMesa"]))
			$con->setIdMesa($_REQUEST["idMesa"]);

		$con->setNombre($_REQUEST["nombre"]);
		$con->setCapacidad($_REQUEST["capacidad"]);
		
		$con->guardar();
	  break;
	case "Eliminar":
		include $dirClase;  $con = new Mesas($conex);
		$con->setIdMesa($_REQUEST["idMesa"]);

		$con->eliminar();
	  break;
}
$Conexion->desconectar();

?>