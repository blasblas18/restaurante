<script>
    function cargar_filtro()
    {   var anio = $("#anio").val(), mes = $("#mes").val(); loading(0,0);
        $.ajax({	url : "controlador/control_usuario.php", data : "accion=Historial&anio=" + anio + "&mes=" + mes,
			success : function(respuesta) {	$("#capa1").html(respuesta);   unloading();    	}
		});
    }
</script>

<br /> 
<div class="widget row-fluid" >
    <div class="span5 sepH_c" id="capaEstableLista">    
        <div class="navbar">
            <div class="navbar-inner">
                <h6><i class="icon-key"></i>Historial de Accesos del <strong>Mes.</strong></h6>
                          
            </div>
        </div>
        <div class="well body">
            
            <div class="table-overflow">
                <div class="row-fluid">
                    <div class="pull-right sepH_a">
                        <div class="pull-right" style="margin-top: -7px;">
                            <select onchange="cargar_filtro()"  id="mes" name="mes" data-placeholder="Seleccione Mes" >
                                <option value=""></option> <?php echo $optionsM; ?>
                            </select>
                        </div>
                    </div>   
                    <div class="pull-right sepH_a sepV_b">
                        <div class="pull-right" style="margin-top: -7px;">
                            <select onchange="cargar_filtro()"  id="anio" name="anio" data-placeholder="Seleccione Anio" >
                                <option value=""></option> <?php echo $optionsA; ?>
                            </select>
                        </div>
                    </div>           
                </div>
                <table class="table table-striped table-bordered" id="dataU">
                    <thead>
                        <tr>
                            <th colspan="2" style="text-align: center;" ><span class="label label-warning">Datos</span></th>                    
                            <th class="tam50" colspan="3" style="text-align: center;">
                                <span class="tip label label-success" title="Atenciones">&nbsp;Conexi&oacute;n&nbsp;</span></th>
                        </tr>
                        <tr>
                            <th class="tam20">#</th>
                            <th >Usuario</th> 
                            <th class="tam50" style="text-align: center;"><span class="tip" title="D&iacute;as Conectados">D&iacute;as</span></th>
                            <th class="tam50" style="text-align: center;"><span class="tip" title="Total de Conexiones">Accesos</span></th>   
                            <th class="tam80" style="text-align: center;"><span class="tip" title="Ultima Conexi&oacute;n">Fecha U.</span></th>   
                        </tr>
                    </thead>
                    <tbody>
                        <?php   $i = 0; 
                          foreach($datosU as $item)	{   $i++;      
                            $nombre = explode(" ", $item["nombre"]);                                                            
                        ?>
                    		  <tr class="gradeX">
                                <td><?php echo $i; ?></td>
                                <td><?php echo ucwords(strtolower($nombre[0]." ".$item["paterno"])); ?></td>
                                <td class="align-center"><span class="hide"><?php echo str_pad($item["cantD"],2,"0", STR_PAD_LEFT);?></span>
                                    <?php echo $item["cantD"];?>&nbsp;</td>
                                <td class="align-center"><?php echo $item["cantC"];?>&nbsp;</td>
                                <td class="align-center"><?php echo $item["fecha"];?>&nbsp;</td>
                              </tr>
                  		<?php }     ?>
                    </tbody>
                </table><br /><br />
            </div>
        </div>
    </div>
    <div class="span7" id="capaEstableGrafico">    
        <div class="navbar">
            <div class="navbar-inner">
                <h6><i class="icon-bar-chart"></i>
                    Gr&aacute;fica de Accesos al SISMORE </h6>
                <span class="label label-important"><?php echo $anio; ?></span>
                <!-- <div class="nav pull-right">
                    <a onclick="imprSelec('capa1')" title="Imprimir" data-placement='left' href="javascript:void(0)" class="navbar-icon tip">
                        <i class="icon-print" ></i></a>                
                </div> -->                    
            </div>
        </div>
        <div class="well body">
            <div class="graph-standard" id="grafica" style="height: 200px;">
            </div>
            <div class="graph-standard" id="grafica2" style="height: 200px;">
            </div>
        </div>
    </div>
</div>
<script>
    $(".tip").tooltip();
    
    $(document).ready(function(){        
        var data1 = [ <?php echo $dataGraf;?> ];     
        var data2 = [ <?php echo $dataGraf2;?> ]; 
        
        var ds1 = new Array();
        ds1.push({ label:"Conexiones", color: 'blue', data:data2, lines: { show: true }, points: { show: true, order: 2 }    });    
         
        var ds2 = new Array();
        ds2.push({ label:"Usuarios", color: 'green', data:data1, lines: { show: true }, points: { show: true, order: 1 }    });
         
        var options = {
            xaxis: {  min: 0, max: 13, axisLabelUseCanvas: true, axisLabelFontSizePixels: 13,
                ticks: [[1,'Ene'],[2,'Feb'],[3,'Mar'],[4,'Abr'],[5,'May'],[6,'Jun'],[7,'Jul'],[8,'Ago'],[9,'Sep'],[10,'Oct'],[11,'Nov'],[12,'Dic']],
                mode: null, tickSize: 1, axisLabelFontFamily: "Verdana, Arial, Helvetica, Tahoma, sans-serif", axisLabelPadding: 7
            },
            yaxis: { min: 0, tickDecimals: 0, tickSize: <?php echo $mayor;  ?>  },
            legend: {	labelBoxBorderColor: "#000000", position: "ne",	margin: [5, 10], backgroundColor: "#CCCCCC"   },
            series:{     }, bars:{  barWidth:0.7    }, grid:{  hoverable: true, backgroundColor: { colors: ["#efefef", "#dedede"] }  }
        };        
        
        var options2 = {
            xaxis: {  min: 0, max: 13, axisLabelUseCanvas: true, axisLabelFontSizePixels: 13,
                ticks: [[1,'Ene'],[2,'Feb'],[3,'Mar'],[4,'Abr'],[5,'May'],[6,'Jun'],[7,'Jul'],[8,'Ago'],[9,'Sep'],[10,'Oct'],[11,'Nov'],[12,'Dic']],
                mode: null, tickSize: 1, axisLabelFontFamily: "Verdana, Arial, Helvetica, Tahoma, sans-serif", axisLabelPadding: 7
            },
            yaxis: { min: 0, tickDecimals: 0, tickSize: <?php echo $mayor2;  ?>  },
            legend: {	labelBoxBorderColor: "#000000", position: "ne",	margin: [5, 10], backgroundColor: "#CCCCCC"   },
            series:{     }, bars:{  barWidth:0.7    }, grid:{  hoverable: true, backgroundColor: { colors: ["#efefef", "#dedede"] }  }
        };
        $.plot($("#grafica"), ds1, options);   
        
        $.plot($("#grafica2"), ds2, options2);   
        
        
        //tooltip function
        function showTooltip(x, y, contents, areAbsoluteXY) {       var rootElt = 'body';	
            $('<div id="tooltip5" class="chart-tooltip">' + contents + '</div>').css( { position: 'absolute', display: 'none', top: y - 42, left: x - 18,'z-index': '9999', 'color': '#fff',	'font-size': '11px', opacity: 0.9
            }).prependTo(rootElt).show();
        }
        
        //add tooltip event
        $("#grafica, #grafica2").bind("plothover", function (event, pos, item) {
            if (item) { 
                if (previousPoint != item.datapoint) {      previousPoint = item.datapoint;     
                    $('.chart-tooltip').remove();     
                    var x = item.datapoint[1];         
                    //All the bars concerning a same x value must display a tooltip with this value and not the shifted value
                    if(item.series.bars.order){
                        for(var i=0; i < item.series.data.length; i++){
                            if(item.series.data[i][3] == item.datapoint[0])
                                x = item.series.data[i][0];
                        }
                    }     
                    var y = item.datapoint[1];          showTooltip(item.pageX+5, item.pageY+5, x + "");     
                }
            }else {  $('.chart-tooltip').remove();
                previousPoint = null;  
            }     
        }); 
 
    
    
        //Fecha mes y año para grafica
        $('#textoFecha').datepicker({
             changeMonth: true, changeYear: true, showButtonPanel: true, dateFormat: 'MM yy',
             onClose: function() {
                var iMonth = $("#ui-datepicker-div .ui-datepicker-month :selected").val(), iYear = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                $(this).datepicker('setDate', new Date(iYear, iMonth, 1));
                $('.ui-datepicker-calendar').css("display", "none");
                
                //alert(parseInt(iMonth) + 1);    alert(iYear);
                var mes = parseInt(iMonth) + 1;   if(mes<10) mes = "0" + mes;
                var fechaReporte = "01-" + mes + "-" + iYear;   var idUsuario = $("#idUsuario").val();
                cargando('#capa2',1);
                $.ajax({  url : 'controlador/control_reporte.php',
                    data : "accion=GraficoControlDiario&fechaReporte=" + fechaReporte + "&idUsuario="+ idUsuario,			
                    success : function(resultado) {  $("#capa2").html(resultado);  },					
                });           
             },
             beforeShow: function() {
                if ((selDate = $(this).val()).length > 0)
                {   iYear = selDate.substring(selDate.length - 4, selDate.length);
                    iMonth = jQuery.inArray(selDate.substring(0, selDate.length - 5),
                    $(this).datepicker('option', 'monthNames'));
                    $(this).datepicker('option', 'defaultDate', new Date(iYear, iMonth, 1));
                    $(this).datepicker('setDate', new Date(iYear, iMonth, 1));
                }            
            }      
        });
    });  
    
    
    $("#anio, #mes").select2();
    $('#dataU').dataTable({"bFilter": true, "bLengthChange": false,
		"bJQueryUI": false, "bAutoWidth": false, "sPaginationType": "full_numbers",
		//"sDom": '<"datatable-header"fl>t<"datatable-footer"ip>',
		"oLanguage": {
			"sSearch": "<span><strong>Buscar Usuario:</strong></span> _INPUT_",
			"sLengthMenu": "<span>Mostrar:</span> _MENU_",
			"oPaginate": { "sFirst": "<<", "sLast": ">>", "sNext": ">", "sPrevious": "<" }
		},
        "aoColumnDefs" : [{ "bSortable" : false, "aTargets" : [ "no-sort" ] }]
        , "aaSorting": [[ 2, "desc" ]]
    });
</script>