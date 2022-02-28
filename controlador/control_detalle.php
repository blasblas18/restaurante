<?php
include_once '../modelo/conexionDB/conexionMySql/conexion.php';
$Conexion = new Conexion();     $conex = $Conexion->conectar();
$dirClase = '../modelo/clases/detalle.php';

switch($_REQUEST["accion"])
{	case "Template":
		$html = file_get_contents("../vista/capas/principal.html");

		include $dirClase;
		$con = new Detalle($conex);
		$datos = $con->get();

		ob_start();
		include ("../vista/capas/detalle/plan_detalle_lista.php");
		$lista_detalle = ob_get_clean();

		$html = str_replace("{CAPA1}", $lista_detalle, $html);
		$html = str_replace("{CAPA2}", "", $html);
		$html = str_replace("{CAPA3}", "", $html);
		$html = str_replace("{CAPA4}", "", $html);

		echo $html;
	  break;
	case "Listado":
		include $dirClase;  $con = new Detalle($conex);     $conC = $con->getConexion();   $conF = $con->getFuncion();
        $idPedido = $_REQUEST["idPedido"];
        $datosP = $con->getPedido()->get($idPedido);
        $datosM = $datosP->getMesas();
        
		$datosD = $con->get(NULL, NULL, "idPedido='".$idPedido."'");

		
        include ("../vista/capas/detalle/plan_detalle_lista.php");

	  break;
	case "Nuevo":
		include $dirClase;  $con = new Detalle($conex); $conC = $con->getConexion();   $conF = $con->getFuncion();
        $idPedido = $_REQUEST["idPedido"];
		$tipoGuardar = $_REQUEST["tipoGuardar"];
		$html = file_get_contents("../vista/capas/detalle/plan_detalle_formulario.html");

		$variable = array("{TITULO_FORMULARIO}", "{INPUT_ID}", "{VALUE_IDPEDIDO}", "{VALUE_IDCOMIDA}", "{VALUE_CANTIDAD}", "{VALUE_OBSERVACION}", "{TIPO_GUARDAR}");
		$valores = array(" Nueva - Formulario", "<input type='hidden' id='idPedido' name='idPedido' value='".$idPedido."' />", $idPedido, "", "", "", 0);
		$html = $con->getFuncion()->replace_form($html, $variable, $valores);

		echo $html;
	  break;
	case "Edicion":
		include $dirClase;  $con = new Detalle($conex);$conC = $con->getConexion();   $conF = $con->getFuncion();
        $idPedido = $_REQUEST["idPedido"];
		$idComida = $_REQUEST["idComida"];
		$tipoGuardar = $_REQUEST["tipoGuardar"];
		$datosDetalle = $con->get(NULL, NULL, "idPedido='".$idPedido."' AND idComida='".$idComida."'", 1);
		$html = file_get_contents("../vista/capas/detalle/plan_detalle_formulario.html");

		$variable = array("{TITULO_FORMULARIO}", "{INPUT_ID}", "{VALUE_IDPEDIDO}", "{VALUE_IDCOMIDA}", "{VALUE_CANTIDAD}", "{VALUE_OBSERVACION}", "{TIPO_GUARDAR}");
		$valores = array(" - Editar"
			,"<input type='hidden' id='idPedido' name='idPedido' value='".$idPedido."' />"
			, $idPedido
			, $datosDetalle[0]->getIdComida()
			, $datosDetalle[0]->getCantidad()
			, $datosDetalle[0]->getObservacion()
            , 1);
		$html = $con->getFuncion()->replace_form($html, $variable, $valores);

		echo $html;
	  break;
	case "Guardar":
		include $dirClase;
		$con = new Detalle($conex);
        
        $tipoGuardar = $_REQUEST["tipoGuardar"]; 

		if(isset($_REQUEST["idPedido"]))
			$con->setIdPedido($_REQUEST["idPedido"]);

		$con->setIdComida($_REQUEST["idComida"]);
		$con->setCantidad($_REQUEST["cantidad"]);
		$con->setObservacion($_REQUEST["observacion"]);
		
		$con->guardar($tipoGuardar);
	  break;
	case "Eliminar":
		include $dirClase;  $con = new Detalle($conex);
		$con->setIdPedido($_REQUEST["idPedido"]);
		$con->setIdComida($_REQUEST["idComida"]);

		$con->eliminar();
	  break;
}
$Conexion->desconectar();

?>