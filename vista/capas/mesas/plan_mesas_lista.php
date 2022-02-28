<script>
	

	function accion_mesas(id)
	{	$.ajax(
		{	url : 'controlador/control_pedido.php',
			data : "accion=Listado&idMesa="+id,
			success : function(respuesta)
			{	$('#capa1').html(respuesta);	}
		});
	}

</script>
<br />
<!-- Default datatable -->
<div class="widget row-fluid">
<div class="span12">
	<div class="navbar">
		<div class="navbar-inner">
			<h6><strong>Listado de Mesas</strong></h6>
			
			<div id="loading_mesas" class="loading pull-right hide">
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
					<th>Detalle</th>
					<th>Capacidad</th>
					<th>Personas</th>
					<th>Platos</th>
					<th>Estado</th>
					<th class="no-sort tam50">Acci&oacute;n</th>
				</tr>
			</thead>
			<tbody>
				<?php	$i = 0;
				  foreach($datos as $item)	{	$i++;
					if($item->getIdMesa()!=0)	{
					   
                        $query = "SELECT * FROM restaurante.pedido
                                    WHERE idMesa='".$item->getIdMesa()."' AND estado!='3' AND estado!='2' LIMIT 1;  "; 
                        $datosP = $conC->datosTabla($query);     
                        $estado = (count($datosP)>0)?$datosP[0]["estado"]:'0';  
                        $personas = (count($datosP)>0)?$datosP[0]["personas"]:'-'; 
                       
                ?>
					  <tr class="gradeX">
						<td><?php echo $item->getNombre();?></td>
						<td class="align-center"><?php echo $item->getCapacidad();?></td>
						<td class="align-center"><?php echo $personas;?></td>
						<td><?php echo $item->getCapacidad();?></td>
						<td class="align-center"><span class="label label-<?php echo ($estado==1)?"important":"success"; ?>"><?php echo ($estado==1)?"O":"L"; ?></span></td>
						<td class="align-center">
							<ul class="table-controls">
								<li><a onclick="accion_mesas(<?php echo $item->getIdMesa();?>)" title="Acci&oacute;n Mesas" class="tip" href="javascript:void(0)">
										<i class="icon-arrow-right"></i></a> </li>
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