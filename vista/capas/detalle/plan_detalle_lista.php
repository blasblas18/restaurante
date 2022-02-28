<script>
	function detalle_lista()
	{	$.ajax(
		{	url : 'controlador/control_detalle.php',
			data : {accion:'Listado'},
			success : function(respuesta)
			{	$('#capa1').html(respuesta);	}
		});
	}
    
    function volver_a_pedido(idMesa)
	{	$.ajax(
		{	url : 'controlador/control_pedido.php',
				data : "accion=Listado&idMesa="+idMesa,
			success : function(respuesta)
			{	$('#capa1').html(respuesta);	}
		});
	}
    
    

	function detalle_nuevo_formulario(idPedido)
	{	$.ajax(
		{	url : 'controlador/control_detalle.php',
			data : "accion=Nuevo&idPedido="+idPedido + "&tipoGuardar=0",
			success : function(respuesta)
			{	$('#capa1').html(respuesta);	}
		});
	}

	function editar_detalle(idPedido, idComida)
	{	$.ajax(
		{	url : "controlador/control_detalle.php",
			data : "accion=Edicion&idPedido=" + idPedido + "&idComida=" + idComida + "&tipoGuardar=1",
			success : function(respuesta)
			{	$("#capa1").html(respuesta);	}
		});
	}

	function eliminar_detalle(idPedido, idComida, obj)
	{	if(confirm("\u00BFSeguro de Eliminar?"))
		{	$("#loading_detalle").show();
			$.ajax(
			{	url : "controlador/control_detalle.php",
				data : "accion=Eliminar&idPedido=" + idPedido + "&idComida=" + idComida,
				success : function(respuesta)
				{	alert("Registro Eliminado");
					var tr = obj.parentNode.parentNode.parentNode.parentNode;
					var table = tr.parentNode;
					table.removeChild(tr);
					$("#loading_detalle").hide();
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
			<h6><strong>Listado de Detalle</strong></h6>
            <span class="label label-success"><?php echo $datosM->getNombre(); ?></span>
			<div class="nav pull-right">
				<a class="dropdown-toggle navbar-icon" data-toggle="dropdown" href="javascript:void(0)">
					<i class="icon-cog"></i></a>
				<ul class="dropdown-menu pull-right">
					<li><a onclick="detalle_nuevo_formulario('<?php echo $idPedido; ?>')" href="javascript:void(0)">
							<i class="icon-plus"></i>Nuevo Detalle</a></li>
				</ul>
			</div>
			<div class="nav pull-right">
				<a onclick="volver_a_pedido('<?php echo $datosM->getIdMesa(); ?>')" class="dropdown-toggle navbar-icon" href="javascript:void(0)">
					<i class="icon-arrow-left"></i></a>
			</div>
			<div id="loading_detalle" class="loading pull-right hide">
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
					<th>Comida</th>
					<th>Cantidad</th>
					<th>Observacion</th>
					<th class="no-sort tam50">Acción</th>
				</tr>
			</thead>
			<tbody>
				<?php	$i = 0;
				  foreach($datosD as $item)	{	$i++;
					if($item->getIdPedido()!=0)	{?>
					  <tr class="gradeX">
						<td><?php echo $item->getComida()->getNombre();?></td>
						<td><?php echo $item->getCantidad();?></td>
						<td><?php echo $item->getObservacion();?></td>
						<td class="align-center">
							<ul class="table-controls">
								<li><a onclick="editar_detalle(<?php echo $item->getIdPedido();?>, <?php echo $item->getIdComida();?>)" title="Editar Detalle" class="tip" href="javascript:void(0)">
										<i class="icon-edit"></i></a> </li>
								<li><a onclick="eliminar_detalle(<?php echo $item->getIdPedido();?>, <?php echo $item->getIdComida();?>, this)" title="Eliminar Detalle" class="tip" href="javascript:void(0)">
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