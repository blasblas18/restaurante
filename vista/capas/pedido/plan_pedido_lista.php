<script>
	function pedido_lista()
	{	$.ajax(
		{	url : 'controlador/control_pedido.php',
			data : {accion:'Listado'},
			success : function(respuesta)
			{	$('#capa1').html(respuesta);	}
		});
	}

	function pedido_nuevo_formulario(idMesa)
	{	$.ajax(
		{	url : 'controlador/control_pedido.php',
			data : "accion=Nuevo&idMesa="+idMesa,
			success : function(respuesta)
			{	$('#capa1').html(respuesta);	}
		});
	}

	function detalles_pedido(idPedido)
	{	$.ajax(
		{	url : 'controlador/control_detalle.php',
			data : "accion=Listado&idPedido="+idPedido,
			success : function(respuesta)
			{	$('#capa1').html(respuesta);	}
		});
	}
    
    

	function editar_pedido(idPedido, idMesa)
	{	$.ajax(
		{	url : "controlador/control_pedido.php",
			data : "accion=Edicion&idPedido=" + idPedido + "&idMesa=" + idMesa,
			success : function(respuesta)
			{	$("#capa1").html(respuesta);	}
		});
	}

	function eliminar_pedido(idPedido, obj)
	{	if(confirm("\u00BFSeguro de Eliminar?"))
		{	$("#loading_pedido").show();
			$.ajax(
			{	url : "controlador/control_pedido.php",
				data : "accion=Eliminar&idPedido=" + idPedido,
				success : function(respuesta)
				{	alert("Registro Eliminado");
					var tr = obj.parentNode.parentNode.parentNode.parentNode;
					var table = tr.parentNode;
					table.removeChild(tr);
					$("#loading_pedido").hide();
				}
			});
		}
	}

</script>
<br />
<!-- Default datatable -->
<div class="widget row-fluid">
<div class="span12">
	<div class="navbar">
		<div class="navbar-inner">
			<h6><strong>Listado de Pedidos</strong> </h6>
            <span class="label label-success"><?php echo $datosM->getNombre(); ?></span>
			<div class="nav pull-right">
				<a class="dropdown-toggle navbar-icon" data-toggle="dropdown" href="javascript:void(0)">
					<i class="icon-cog"></i></a>
				<ul class="dropdown-menu pull-right">
					<li><a onclick="pedido_nuevo_formulario(<?php echo $idMesa;?>)" href="javascript:void(0)">
							<i class="icon-plus"></i>Nuevo Pedido</a></li>
				</ul>
			</div>
			<div id="loading_pedido" class="loading pull-right hide">
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
					<th>Estado</th>
					<th>Personas</th>
					<th>Fecha</th>
					<th>Observacion</th>
					<th>IdUsuario</th>
					<th class="no-sort tam80">Acci&oacute;n</th>
				</tr>
			</thead>
			<tbody>
				<?php	$i = 0;
				  foreach($datosP as $item)	{	$i++;
					if($item->getIdPedido()!=0)	{?>
					  <tr class="gradeX">
						<td><?php echo $item->getEstadoNombre();?></td>
						<td><?php echo $item->getPersonas();?></td>
						<td><?php echo $item->getFecha();?></td>
						<td><?php echo $item->getObservacion();?></td>
						<td><?php echo $item->getUsuario()->getNombre();?></td>
						<td class="align-center">
							<ul class="table-controls">
								<li><a onclick="detalles_pedido(<?php echo $item->getIdPedido();?>, <?php echo $item->getIdMesa();?>)" title="Detalles del Pedido" class="tip" href="javascript:void(0)">
										<i class="icon-shopping-cart"></i></a> </li>
								<li><a onclick="editar_pedido(<?php echo $item->getIdPedido();?>, <?php echo $item->getIdMesa();?>)" title="Editar Pedido" class="tip" href="javascript:void(0)">
										<i class="icon-edit"></i></a> </li>
								<li><a onclick="eliminar_pedido(<?php echo $item->getIdPedido();?>, this)" title="Eliminar Pedido" class="tip" href="javascript:void(0)">
										<i class="icon-remove"></i></a> </li>
							</ul>
						</td>
					</tr>
				<?php } 	}?>
			</tbody>
		</table><br /><br />
		</div>
	</div>
</div>
</div>
<!-- /default datatable -->

<script>
	$("#data-table").dataTable({
		"bJQueryUI": false,
		"bAutoWidth": false,
		"sPaginationType": "full_numbers",
		//"sDom": '<"datatable-header"fl>t<"datatable-footer"ip>',
		"oLanguage": {
			"sSearch": "<span>Buscar registro:</span> _INPUT_",
			"sLengthMenu": "<span>Mostrar:</span> _MENU_",
			"oPaginate": { "sFirst": "Primero", "sLast": "Último", "sNext": ">", "sPrevious": "<" }
		},
		"aoColumnDefs" : [{ "bSortable" : false, "aTargets" : [ "no-sort" ] }]
		//, "aaSorting": [[ 2, "desc" ]]
	});
</script>