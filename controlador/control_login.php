<?php session_start();
$bd_sis = "restaurante"; 
include '../modelo/conexionDB/conexionMySql/conexion.php';
$Conexion = new Conexion();
$conex = $Conexion->conectar();
include_once '../modelo/conexionDB/conexionMySql/consulta.php';
$conexion = new Consulta($conex);
      
$usuario = $_REQUEST["usuario"];
$clave = $_REQUEST["clave"];

//haciendo la consulta sobre el usuario y pasword
$q = "SELECT idUsuario FROM ".$bd_sis.".usuario WHERE dni='".$usuario."' AND clave ='".$clave."' AND estado='1'";
//$CARGAR = mysql_query($q, $dbh);
$CARGAR = $conexion->consultar($q);
$row = mysqli_fetch_row($CARGAR);
if($row[0]!=null)
{
	$q = "SELECT idUsuario, dni, nombre, idTipo  FROM ".$bd_sis.".usuario WHERE idUsuario='".$row[0]."'";
    $CARGAR = $conexion->consultar($q);
	$row = mysqli_fetch_row($CARGAR);
    //echo var_dump($row);
	if($row[0]!=null)
	{   $_SESSION['restaurante_idUsuario'] = $row[0];        
        $_SESSION['restaurante_dni'] = $row[1];
        $_SESSION['restaurante_nombre'] = $row[2];
        $_SESSION['restaurante_idTipo'] = $row[3];  // 1: SuperAdmin, 2: Admin, 3: Cajero, 4: Mozo
        
		echo "1";
        
        $query = "INSERT INTO ".$bd_sis.".acceso (idUsuario, motivo) VALUES ('".$row[0]."', 'LOGIN');";
        $conexion->consultar($query);
	}	
}
else
{
	echo "0";
}


?>