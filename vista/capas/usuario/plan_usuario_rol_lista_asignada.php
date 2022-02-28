<script>
    $('#boton_asignar_rol').click(function()
    {   var idUsuario = $("#idUsuario").val();
        $.ajax(
		{	url : "controlador/control_usuario_rol.php",
			data : "accion=AsignarRolFormulario&idUsuario=" + idUsuario,
			success : function(respuesta)
			{	$("#capa1").html(respuesta);	}
		});
    });
    
    function eliminar_usuario_rol_uno(idUsuario, idRol)
    {   if(confirm("\u00BFSeguro de Quitar Rol?"))
        {   $.ajax(
    		{	url : "controlador/control_usuario_rol.php",
    			data : "accion=EliminarUno&idUsuario="+idUsuario+"&idRol=" + idRol,
    			success : function(respuesta)
    			{	cargando('#espacioUsuarioRol',1);
    			    $.ajax(
            		{	url : "controlador/control_usuario.php",
            			data : "accion=UsuarioRolAsignadoLista&idUsuario=" + idUsuario,
            			success : function(listado)
            			{	$("#espacioUsuarioRol").html(listado);	}
            		});
                }
    		}); 
        }
    }
    
    
</script> 
        <div class="alert alert-block">            
	        <input type="hidden" id="idUsuario" value="<?php echo $datosU->getIdUsuario(); ?>" />
            <h6>USUARIO "<?php echo $datosU->getNombre(); ?>"</h6>	        
        </div>
        <div class="table-overflow">
		<table class="table table-striped table-bordered" id="data-table-usuario-rol">
			<thead>
				<tr>
					<th class="tam40">ID</th>
                    <th>Roles de Acesso</th>
					<th class="no-sort tam40">Acci&oacute;n</th>
				</tr>
			</thead>
			<tbody>
				<?php	$i = 0;
				  foreach($datos as $item)	{	$i++;
					if($item->getIdUsuario()!=0)	{?>
					  <tr class="gradeX">						
                        <td><?php echo $item->getIdRol();?></td>
                        <td><?php echo $item->getRol()->getNombre();?></td>
						<td class="align-center">
							<ul class="table-controls">
                                <li><a onclick="eliminar_usuario_rol_uno('<?php echo $item->getIdUsuario();?>', '<?php echo $item->getIdRol();?>')" title="Eliminar Rol Asignado" class="tip" href="javascript:void(0)">
										<i class="icon-remove"></i></a></li>                                
							</ul>
						</td>
					</tr>
				<?php }                 	
                    }
                    if(!count($datos))
                        echo "<tr>    <td class='align-center' colspan=\"3\">No hay datos disponibles</td>   </tr>"
                ?>
			</tbody>
		</table><br /><br />
		</div>
        

<strong><script>
    $('.tip').tooltip();   
    
</script></strong>