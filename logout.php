<?php
session_start();
//guardar el nombre de la sessión para luego borrar las cookies
$session_name = session_name();
// Destruye todas las variables de la sesión
if(!isset($_SESSION["restaurante_idUsuario"]))
{	header("Location:index.php"); } 
else
{
//session_unset();
$_SESSION = array();
unset($_SESSION["restaurante_idUsuario"]);
unset($_SESSION["restaurante_dni"]);
unset($_SESSION["restaurante_nombre"]);
unset($_SESSION["restaurante_idTipo"]);
unset($_COOKIE["restaurante_idUsuario"]);
unset($_COOKIE["restaurante_dni"]);
unset($_COOKIE["restaurante_nombre"]);
unset($_COOKIE["restaurante_idTipo"]);
session_destroy();
// Para borrar las cookies asociadas a la sesión
// Es necesario hacer una petición http para que el navegador las elimine
if ( isset( $_COOKIE[$session_name] ) ) 
	{
    if ( setcookie(session_name(), '', time()-3600, '/') ) 
		{
        header("Location:index.php");
        exit();   
    	} 
	}
header("Location:index.php");
}
?>