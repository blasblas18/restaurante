<?php
include_once '../modelo/conexionDB/conexionMySql/conexion.php';
$Conexion = new Conexion();     $conex = $Conexion->conectar();
$dirClaseNinoCred = '../modelo/clases/nino_cred.php';
$dirClaseNinoTamizaje = '../modelo/clases/nino_tamizaje.php';

switch($_REQUEST["accion"])
{	case "Template":
        include $dirClaseNinoCred;  $con = new NinoCred($conex);
        $conC = $con->getConexion();    $conF = $con->getFuncion();        
		$html = file_get_contents("../vista/capas/principal.html");
		$band = 0;
        
        $lista = file_get_contents("../vista/capas/actualizar/plan_actualizar_principal_lista.html");
        
        $pend = "";        
        include_once "../vista/capas/actualizar/plan_actualizar_verifica_formulario_bd.php";        
        
        if($band == 1)
            $pend = file_get_contents("../vista/capas/actualizar/plan_actualizar_pendiente_formulario.html");
                
		$html = str_replace("{CAPA1}", $pend, $html);
		$html = str_replace("{CAPA2}", $lista, $html);
		$html = str_replace("{CAPA3}", "", $html);
		$html = str_replace("{CAPA4}", "", $html);

		echo $html;
	  break;
    case "Actualizar":
        include_once $dirClaseNinoCred;  $con = new NinoCred($conex);
        include_once $dirClaseNinoTamizaje;  $conNT = new NinoTamizaje($conex);
        $conC = $con->getConexion();    $conF = $con->getFuncion();
        
        include_once "../vista/capas/actualizar/plan_actualizar_verifica_guardar_bd.php"; 
        
      break;
}
$Conexion->desconectar();

?>