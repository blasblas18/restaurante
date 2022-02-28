<?php
include_once '../modelo/conexionDB/conexionMySql/conexion.php';
$Conexion = new Conexion();     $conex = $Conexion->conectar();
$dirClase = '../modelo/clases/comida.php';

switch($_REQUEST["accion"])
{	case "Template":
		$html = file_get_contents("../vista/capas/principal.html");

		include $dirClase;
		$con = new Comida($conex);
		$datos = $con->get();

		ob_start();
		include ("../vista/capas/comida/plan_comida_lista.php");
		$lista_comida = ob_get_clean();

		$html = str_replace("{CAPA1}", $lista_comida, $html);
		$html = str_replace("{CAPA2}", "", $html);
		$html = str_replace("{CAPA3}", "", $html);
		$html = str_replace("{CAPA4}", "", $html);

		echo $html;
	  break;
	case "Listado":
		include $dirClase;  $con = new Comida($conex);
		$datos = $con->get();

		include ("../vista/capas/comida/plan_comida_lista.php");

	  break;
	case "Nuevo":
		include $dirClase;  $con = new Comida($conex);
		$html = file_get_contents("../vista/capas/comida/plan_comida_formulario.html");

		$variable = array("{TITULO_FORMULARIO}", "{INPUT_ID}", "{VALUE_NOMBRE}", "{VALUE_TIPO}", "{VALUE_PRECIO}");
		$valores = array(" Nueva - Formulario", "", "", "", "");
		$html = $con->getFuncion()->replace_form($html, $variable, $valores);

		echo $html;
	  break;
	case "Edicion":
		include $dirClase;  $con = new Comida($conex);
		$idComida = $_REQUEST["idComida"];
		$datosComida = $con->get($idComida);
		$html = file_get_contents("../vista/capas/comida/plan_comida_formulario.html");

		$variable = array("{TITULO_FORMULARIO}", "{INPUT_ID}", "{VALUE_NOMBRE}", "{VALUE_TIPO}", "{VALUE_PRECIO}");
		$valores = array(" - Editar"
			,"<input type='hidden' id='idComida' name='idComida' value='".$idComida."' />"
			, $datosComida->getNombre()
			, $datosComida->getTipo()
			, $datosComida->getPrecio());
		$html = $con->getFuncion()->replace_form($html, $variable, $valores);

		echo $html;
	  break;
	case "Guardar":
		include $dirClase;
		$con = new Comida($conex);

		if(isset($_REQUEST["idComida"]))
			$con->setIdComida($_REQUEST["idComida"]);

		$con->setNombre($_REQUEST["nombre"]);
		$con->setTipo($_REQUEST["tipo"]);
		$con->setPrecio($_REQUEST["precio"]);
		
		$con->guardar();
	  break;
	case "Eliminar":
		include $dirClase;  $con = new Comida($conex);
		$con->setIdComida($_REQUEST["idComida"]);

		$con->eliminar();
	  break;
}
$Conexion->desconectar();

?>