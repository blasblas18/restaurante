<script>
	function usuario_lista()
	{	loading(0,0);
        $.ajax({	url : 'controlador/control_usuario.php', data : {accion:'Listado'},
			success : function(respuesta) {	$('#capa1').html(respuesta);   unloading();	}
		});
	}

	function usuario_nuevo_formulario()
	{	loading(0,0);
	    $.ajax({	url : 'controlador/control_usuario.php', data : "accion=Nuevo",
			success : function(respuesta) {	$('#capa1').html(respuesta);   unloading();    	}
		});
	}

	function editar_usuario(idUsuario)
	{	loading(0,0);
	    $.ajax({	url : "controlador/control_usuario.php", data : "accion=Edicion&idUsuario=" + idUsuario,
			success : function(respuesta) {	$("#capa1").html(respuesta);   unloading();    	}
		});
	}

	function eliminar_usuario(idUsuario, obj)
	{	if(confirm("\u00BFSeguro de Eliminar?"))
		{	$("#loading_usuario").show();
			$.ajax(
			{	url : "controlador/control_usuario.php",
				data : "accion=Eliminar&idUsuario=" + idUsuario,
				success : function(respuesta)
				{	alert("Registro Eliminado");
					var tr = obj.parentNode.parentNode.parentNode.parentNode;
					var table = tr.parentNode;
					table.removeChild(tr);
					$("#loading_usuario").hide();
				}
			});
		}
	}
    
    function ver_historial_acceso()
    {   loading(0,0);
	    $.ajax({	url : "controlador/control_usuario.php", data : "accion=Historial",
			success : function(respuesta) {	$("#capa1").html(respuesta);   unloading();    	}
		});
    }   
        
    function ver_usuario_rol_asignado(idUsuario)
	{	cargando('#espacioUsuarioRol',1);
        $.ajax(
		{	url : "controlador/control_usuario.php",
			data : "accion=UsuarioRolAsignadoLista&idUsuario=" + idUsuario,
			success : function(respuesta) {	$("#espacioUsuarioRol").html(respuesta);	}
		});
	}

</script>
<br />
<!-- Default datatable -->
<div class="widget row-fluid">
<div class="span8">
	<div class="navbar">
		<div class="navbar-inner">
			<h6><strong>Usuarios con Acceso a Nativa</strong></h6>
			<div class="nav pull-right">
				<a class="dropdown-toggle navbar-icon" data-toggle="dropdown" href="javascript:void(0)">
					<i class="icon-cog"></i></a>
            <?php   if(($_SESSION["sismore_salud_idAcceso"]=="1" OR $_SESSION["sismore_salud_idAcceso"]=="2") )  {    ?>
				<ul class="dropdown-menu pull-right">
					<li><a onclick="usuario_nuevo_formulario()" href="javascript:void(0)">
							<i class="icon-plus"></i>Nuevo Usuario</a></li>
				</ul>
            <?php   }    ?>
			</div>
            <div class="nav pull-right tip" title="Ver Accesos" data-placement='bottom'>
				<a onclick="ver_historial_acceso()" class="navbar-icon" href="javascript:void(0)">
					<i class="icon-bar-chart"></i></a>
            </div>
			<div id="loading_usuario" class="loading pull-right hide">
				<span>Cargando</span>
				<img src="img/elements/loaders/6s.gif" alt="" />
			</div>
		</div>
	</div>
	<div class="well body">
		<div class="table-overflow">
		<table class="table table-striped table-bordered" id="data-table">
			<thead>
				<tr>
					<th>Dni</th> <th>Nombre</th> <th>Celular</th> <th>Cargo</th>
					<th>Nivel</th> <th>Acc.</th> <th><span class="tip" title="Permiso">(P)</span></th> <th><span class="tip" title="Establecimiento">EE.SS.</span></th> 
                    <th class="no-sort tam70">Acci&oacute;n</th>
				</tr>
			</thead>
			<tbody>
				<?php	$i = 0;
				  foreach($datos as $item)	{	$i++;
                    if($item->getIdAcceso()==1)
                        if($_SESSION["sismore_salud_idAcceso"]>1)
                            continue;
					if($item->getIdAcceso()==2)
                        if($_SESSION["sismore_salud_idAcceso"]>2)
                            continue;
					if($item->getIdUsuario()!=0)	{?>
					  <tr class="gradeX">
						<td <?php echo ($item->getEstado()==1)?"":" class='text-error' style='text-decoration: line-through; font-weight: bold;' ";  ?> ><?php echo $item->getDni();?></td>
						<td><?php echo $item->getNombreCompleto();?></td>
						<td><?php echo $item->getCelular();?></td>
						<td><?php echo $item->getCargo();?></td>
						<td><span class="tip" title="<?php echo $item->getNivelNombre();?>"><?php echo $item->getNivelNombre2();?></span></td>
						<td><?php echo $item->getAccesoNombre();?></td>
						<td><span class="tip" title='<?php echo $item->getPermisoNombre(); ?>' data-placement='left'>
                            <?php echo substr($item->getPermisoNombre(),0,3);?></span></td>
						<td class="align-center"><span class="tip" title="<?php echo $item->getEstablecimiento()->getDescripcion();?>"><?php echo ($item->getCod_2000())*1;?></span></td>
						<td class="align-center">
							<ul class="table-controls">
                            <?php   if($_SESSION["sismore_salud_idAcceso"]<=2 )   { ?>
								<li><a onclick="editar_usuario(<?php echo $item->getIdUsuario();?>)" title="Editar Usuario" class="tip" href="javascript:void(0)">
										<i class="icon-edit"></i></a> </li>
							<?php    }
                                if($_SESSION["sismore_salud_idAcceso"]==1) {        ?>
                                <li><a onclick="eliminar_usuario(<?php echo $item->getIdUsuario();?>, this)" title="Eliminar Usuario" class="tip" href="javascript:void(0)">
										<i class="icon-remove"></i></a> </li>
                            <?php } ?>
                                <li><a onclick="ver_usuario_rol_asignado('<?php echo $item->getIdUsuario();?>')" title="Ver Roles Asignados" class="tip" href="javascript:void(0)">
                                            <i class="icon-key"></i></a> </li>
							</ul>
                        </td>
					</tr>
				<?php } 	}?>
			</tbody>
		</table><br /><br />
		</div>
	</div>
</div>

<div class="span4">
	<div class="navbar">
		<div class="navbar-inner">
			<h6><i class="icon-key"></i><strong>Lista Roles Asinados</strong></h6>
			<div class="nav pull-right">
				<a class="dropdown-toggle navbar-icon" data-toggle="dropdown" href="javascript:void(0)">
					<i class="icon-cog"></i></a>
				<ul class="dropdown-menu pull-right">
					<li><a id="boton_asignar_rol" href="javascript:void(0)">
							<i class="icon-hand-right"></i>Asignar Roles</a></li>
                    <li><a onclick="eliminar_usuario_rol_todos()" href="javascript:void(0)">
							<i class="icon-trash"></i>Quitar los roles</a></li>
                    <li></li><li></li>
                    <li><a onclick="rol_lista()" href="javascript:void(0)">
							<i class="icon-key"></i>Todos los Roles</a></li>
                    <li><a onclick="permiso_lista()" href="javascript:void(0)">
							<i class="icon-unlock"></i>Todos los Permisos</a></li>       
				</ul>
			</div>
			<div id="loading_usuario_rol" class="loading pull-right hide">
				<span>Cargando</span>
				<img src="img/elements/loaders/6s.gif" alt="" />
			</div>
		</div>
	</div>
	<div class="well body" id="espacioUsuarioRol">
        <div class="alert alert-block">            
	        <h6>USUARIO "Selecione un Usuario"</h6>
        </div>	
        <div class="table-overflow">
		<table class="table table-striped table-bordered" id="data-table-usuario-rol">
			<thead>
				<tr>
					<th class="tam40">ID</th>
                    <th>Permiso de Acesso</th>
					<th class="no-sort tam80">Acci&oacute;n</th>
				</tr>
			</thead>
			<tbody>
                <tr>    <td colspan="3">No hay datos disponibles</td>   </tr>
				
			</tbody>
		</table><br /><br />
		</div>
	</div>
</div>

</div>
<!-- /default datatable -->

<script>
    $(".tip").tooltip();
	$("#data-table").dataTable({
		"bJQueryUI": false,
		"bAutoWidth": false,
		"sPaginationType": "full_numbers",
		//"sDom": '<"datatable-header"fl>t<"datatable-footer"ip>',
		"oLanguage": {
			"sSearch": "<span>Buscar registro:</span> _INPUT_",
			"sLengthMenu": "<span>Mostrar:</span> _MENU_",
			"oPaginate": { "sFirst": "Primero", "sLast": "&Uacute;ltimo", "sNext": ">", "sPrevious": "<" }
		},
		"aoColumnDefs" : [{ "bSortable" : false, "aTargets" : [ "no-sort" ] }]
		//, "aaSorting": [[ 2, "desc" ]]
	});
</script>