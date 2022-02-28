<?php
include_once '../modelo/conexionDB/conexionMySql/conexion.php';
$Conexion = new Conexion();     $conex = $Conexion->conectar();
$dirClase = '../modelo/clases/pedido.php';

switch($_REQUEST["accion"])
{	case "Template":
		$html = file_get_contents("../vista/capas/principal.html");

		include $dirClase;
		$con = new Pedido($conex);
		$datos = $con->get();

		ob_start();
		include ("../vista/capas/pedido/plan_pedido_lista.php");
		$lista_pedido = ob_get_clean();

		$html = str_replace("{CAPA1}", $lista_pedido, $html);
		$html = str_replace("{CAPA2}", "", $html);
		$html = str_replace("{CAPA3}", "", $html);
		$html = str_replace("{CAPA4}", "", $html);

		echo $html;
	  break;
	case "Listado":
		include $dirClase;  $con = new Pedido($conex);     $conC = $con->getConexion();   $conF = $con->getFuncion();
        $idMesa = $_REQUEST["idMesa"];
        $datosM = $con->getMesas()->get($idMesa);
		$datosP = $con->get(NULL, NULL, "idMesa='".$idMesa."'");

		include ("../vista/capas/pedido/plan_pedido_lista.php");

	  break;
	case "Nuevo":
		include $dirClase;  $con = new Pedido($conex);    $conC = $con->getConexion();   $conF = $con->getFuncion();
        $idMesa = $_REQUEST["idMesa"];
		$html = file_get_contents("../vista/capas/pedido/plan_pedido_formulario.html");

		$variable = array("{TITULO_FORMULARIO}", "{INPUT_ID}", "{VALUE_IDMESA}", "{VALUE_PERSONAS}", "{VALUE_ESTADO}", "{VALUE_OBSERVACION}", "{VALUE_IDUSUARIO}");
		$valores = array(" Nuevo", "", $idMesa, "", "", "", "");
		$html = $con->getFuncion()->replace_form($html, $variable, $valores);

		echo $html;
	  break;
	case "Edicion":
		include $dirClase;  $con = new Pedido($conex);
		$idPedido = $_REQUEST["idPedido"];
		$idMesa = $_REQUEST["idMesa"];
		$datosPedido = $con->get($idPedido);
		$html = file_get_contents("../vista/capas/pedido/plan_pedido_formulario.html");

		$variable = array("{TITULO_FORMULARIO}", "{INPUT_ID}", "{VALUE_IDMESA}", "{VALUE_PERSONAS}", "{VALUE_ESTADO}", "{VALUE_OBSERVACION}", "{VALUE_IDUSUARIO}");
		$valores = array(" - Editar"
			,"<input type='hidden' id='idPedido' name='idPedido' value='".$idPedido."' />"
			, $datosPedido->getIdMesa()
			, $datosPedido->getPersonas()
			, $datosPedido->getEstado()
			, $datosPedido->getObservacion()
			, $datosPedido->getIdUsuario());
		$html = $con->getFuncion()->replace_form($html, $variable, $valores);

		echo $html;
	  break;
	case "Guardar":
		include $dirClase;
		$con = new Pedido($conex);

		if(isset($_REQUEST["idPedido"]))
			$con->setIdPedido($_REQUEST["idPedido"]);

		$con->setIdMesa($_REQUEST["idMesa"]);
		$con->setPersonas($_REQUEST["personas"]);
		$con->setFecha($_REQUEST["fecha"]);
		$con->setEstado($_REQUEST["estado"]);
		$con->setObservacion($_REQUEST["observacion"]);
		$con->setIdUsuario($_REQUEST["idUsuario"]);
		
		$con->guardar();
	  break;
	case "Eliminar":
		include $dirClase;  $con = new Pedido($conex);
		$con->setIdPedido($_REQUEST["idPedido"]);

		$con->eliminar();
	  break;
}
$Conexion->desconectar();

?>