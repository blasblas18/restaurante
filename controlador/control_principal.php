<?php   

//include ("controlador/funciones.php");

class control_principal	{	
	function cargar_template($titulo='RESTAURANTE :: SISTEMAS'){
		$html = $this->cargar_pagina("vista/template.html");
				
    	$html = $this->remplaza_contenido("{titulo_pagina}", $titulo, $html);
        
        $footer_contact = "$('a.contacto_admin').click(function(e) {    e.preventDefault();
        		var box = bootbox.alert('<strong>Sistema de Sistemas - Pucallpa</strong><br /><br />' 
                                    +  'Soporte : <strong>Sistemas Peru</strong><br />'
                                    +  'Correo: <strong>info@sistemasperu.com</strong>'
                                    +  '<br /><br /><strong>Sistemas de Sistemas S.R.L.</strong>' );
        		setTimeout(function() {
        			box.modal('hide');
        		}, 4000);
        	});";
        $html = $this->remplaza_contenido("{FOOTER_CONTACT}", $footer_contact, $html);
        
		return $html;
	}
	
	function principal()
   	{
		$html = $this->cargar_template('RESTAURANTE :: SISTEMAS');	
		
        $menu = $this->cargar_pagina("vista/menu/datos_menu.html");
		$html = $this->remplaza_contenido("{DATOS_MENU_PRINCIPAL}", $menu, $html);
        $html = $this->remplaza_contenido("{ADMIN_CARGAR_DATOS}", (($_SESSION["restaurante_idTipo"]=="1")?"":(($_SESSION["restaurante_idTipo"]=="2")?"":(($_SESSION["restaurante_idTipo"]=="2")?"":"hide"))), $html);
        $html = $this->remplaza_contenido("{USUARIO_CONECTADO_0}", (isset($_SESSION["restaurante_idUsuario"])?"hide":""), $html);
        $html = $this->remplaza_contenido("{USUARIO_CONECTADO_1}", (isset($_SESSION["restaurante_idUsuario"])?"":"hide"), $html);
        $html = $this->remplaza_contenido("{ADMIN_ORM}", (($_SESSION["restaurante_idTipo"]=="1")?"":"hide"), $html);
        $html = $this->remplaza_contenido("{USER_NICK}", $_SESSION["restaurante_dni"], $html);
        $html = $this->remplaza_contenido("{USER_PRIMERNOMBRE}", $_SESSION["restaurante_nombre"], $html);
					
		$this->mostrar_pagina($html);
   	}
	
	function login()
   	{
		$html = $this->cargar_pagina('vista/login.html');				
		$this->mostrar_pagina($html);
   	}
	
	private function cargar_pagina($page)
	{
		return file_get_contents($page);
	}
	
	private function remplaza_contenido($lugar, $salida, $pagina)
	{
		 return str_replace($lugar, $salida, $pagina);	 	
	}

	private function mostrar_pagina($html)
	{
		echo $html;
	}
}

?>