<?php 
	session_start();
	require 'controlador/control_principal.php';
	$mvc = new control_principal();
    ini_set("memory_limit","256M");
    ini_set('max_execution_time', 600);  
    
    //echo var_dump($_SESSION["dnisismore$"]);  
    
    //if(!isset($_SESSION["dnisismore$"])) 
//	{
//		header('Location: ../sismore/public/login');
//	}
//	else
//	{	if(!isset($_SESSION["sismore_salud_idUsuario"])) 
//    	{
//    		$mvc->login_2();	
//            header("Refresh: 0");
//    	}
//    	else
//    	{	$mvc->principal();
//    	}
//	}	
	
    
    # VERSION ORIGINAL INICIAL    
  if(!isset($_SESSION["restaurante_idUsuario"])) 
	{
		$mvc->login();	
	}
	else
	{	$mvc->principal();
	}	
    
    
    
    
    
    
	
# VERSION ORIGINAL INICIAL    
//if(!isset($_SESSION["sismore_salud_idUsuario"])) 
//{
//    if(isset($_GET["accion"]) AND $_GET["accion"]=='login') 
//    {
//   	    $mvc->login();	
//    }
//    else
//    {	
//        $mvc->login_3();
//    }		
//}
//else
//{	  
//    $mvc->principal();
//}	
//	
//    
//    
    
    
    
    
    
    
    
    
    
    
?>