<?php //plantilla
include_once '../modelo/conexionDB/conexionMySql/conexion.php';
$Conexion = new Conexion();
$conex = $Conexion->conectar();
$ti = "'";   
switch($_REQUEST["accion"])
{   case "ORM_Template":
        $html = file_get_contents("../vista/orm/plantilla_orm_principal.html");
        
        echo $html;
        break;      
    case "ORM_clase": 
        $tabla = $_REQUEST["tabla"];  $clase = $_REQUEST["clase"]; 
        echo "SELECT * FROM ".$tabla;
        $result = mysqli_query($conex, "SELECT * FROM ".$tabla);
        $fields_cnt = mysqli_num_fields($result);
        $row = mysqli_fetch_assoc($result);        
        $fields = mysqli_fetch_fields($result);     
        $campos_tabla = ""; $i = 0; $set_tabla = ""; $get_tabla = "";
        $agregar_tabla = "\tprivate function agregar()"."\n".
            "\t{"."\t".'$'.'consulta = "INSERT INTO '.$tabla." "; $at_valor = ')'."\n"."\t\t\t".'VALUES';
        $editar_tabla = "\tprivate function modificar()\n".
            "\t{"."\t".'$'.'consulta = "UPDATE '.$tabla." "."\n"."\t\t"."\tSET ";                         
        $et_valor = "\t\t".'$'.'this->conexion->ejecutar($consulta);'."\n\t}";
        
        $gets_query = "\tpublic function get($"."id = NULL, $"."campo = NULL, $"."condicion = NULL, $"."limite = NULL, $"."orden = NULL)"
            ."\n\t{\tif ($"."id == NULL)\n\t{\t$"."tabla = array();"
            ."\n\t\t\t$"."campo = ($"."campo == NULL)?\"*\":$"."campo;"
            ."\n\t\t\tif($"."condicion == NULL)"
            ."\n\t\t\t\t$"."condicion = \"\";"
            ."\n\t\t\telse"
            ."\n\t\t\t{\t$"."matriz = explode(\";;\", $"."condicion);"
            ."\n\t\t\t\t$"."condicion = \" WHERE \";"
            ."\n\t\t\t\tforeach($"."matriz AS $"."item)"
            ."\n\t\t\t\t\t$"."condicion .= \" \".$"."item.\" AND\";"
            ."\n\t\t\t\t$"."condicion = substr($"."condicion, 0, strlen($"."condicion)-4);"
            ."\n\t\t\t}"
            ."\n\t\t\t$"."limite = ($"."limite == NULL)?\"\":(\" LIMIT \".$"."limite.\";\");"
            ."\n\t\t\t$"."orden = ($"."orden == NULL)?\"\":(\" ORDER BY \".$"."orden.\" \");"
            ."\n\t\t\t$"."consulta = \"SELECT \".$"."campo.\" FROM ".$tabla." \".$"."condicion.$"."orden.$"."limite;"
            ."\n\t\t\t$"."datos = $"."this->conexion->datosTabla($"."consulta);"
            ."\n\t\t\tforeach ($"."datos as $"."row)"
            ."\n\t\t\t{\t$"."objeto = new ".$clase."($"."this->conex);";
        $gq_valor = "";
        foreach($fields as $item)
        {   $campos_tabla .= "\tprivate $".$item->name;
            if(++$i==1) 
            {   $campos_tabla .= " = 0";    $idPrincipal = $item->name;
            } else  {
                if($i==2)
                {   $agregar_tabla .= '('.$item->name;
                    $at_valor .= '('.$ti.'".$'.'this->'.$item->name.'."'.$ti.'';
                } else {
                    $agregar_tabla .= ', '.$item->name;
                    $at_valor .= ', '.$ti.'".$'.'this->'.$item->name.'."'.$ti.'';
                }                  
                if($i>2)  $editar_tabla .= "\t\t\t\t";
                $editar_tabla .= $item->name.' = '.$ti.'".$'.'this->'.$item->name.'."'.$ti;
                if($i<$fields_cnt)  $editar_tabla .= ', '."\n";
            }
            $campos_tabla .= "; \n";
            $set_tabla .= "\tpublic function set".ucfirst($item->name)."($".$item->name.")".
                "\n\t{\t$"."this->".$item->name." = $".$item->name.";\t}";
            $set_tabla .= " \n";
            $get_tabla .= "\tpublic function get".ucfirst($item->name)."()".
                "\n\t{\treturn $"."this->".$item->name.";\t}";
            $get_tabla .= " \n";
            
            $gets_query .= "\n\t\t\t\t$"."objeto->set".ucfirst($item->name)."($"."row['".$item->name."']);";
            $gq_valor .= "\t$"."objeto->set".ucfirst($item->name)."($"."datos[0]['".$item->name."']);"
                            ."\n\t\t\t";
        }
        
        $constructor = "\tpublic function __construct($"."con = NULL)"
            ."\n\t{\t$"."this->conex = $"."con;"
            ."\n\t\t$"."this->conexion = new Consulta($"."con);"
            ."\n\t}"
            ."\n\n\tpublic function getConexion()"
            ."\n\t{\treturn $"."this->conexion;\t}"
            ."\n\tpublic function getFuncion()"
            ."\n\t{\treturn new Funciones();\t}";	
        $guardar_tabla = "\tpublic function guardar()\n".
            "\t{\t($"."this->".$idPrincipal." == 0)?$".
            "this->agregar():$"."this->modificar();\t}";
        $agregar_tabla .= $at_valor.')";'."\n"."\t\t".'$'.'this->conexion->ejecutar($'.'consulta);'."\n"."\t}";            
        $et_valor = '".$'.'this->'.$idPrincipal.'."'.$ti.'";'."\n".$et_valor;
        $et_valor = "\n"."\t\t\t".'WHERE '.$idPrincipal.' = '.$ti.$et_valor; 
        $editar_tabla .= $et_valor;        
        $metodos = $guardar_tabla."\n\n".$agregar_tabla."\n\n".$editar_tabla;
        
        $gets_query .= "\n\t\t\t\t$"."tabla[] = $"."objeto;"
            ."\n\t\t\t}"
            ."\n\t\t\treturn $"."tabla;"
            ."\n\t\t}"
            ."\n\t\telse"
            ."\n\t\t{\t$"."consulta = \"SELECT * FROM ".$tabla." WHERE ".$idPrincipal."='\".$"."id.\"' LIMIT 1\";"
            ."\n"
            ."\n\t\t\t$"."datos = $"."this->conexion->datosTabla($"."consulta);"
            ."\n\t\t\t$"."objeto = new ".$clase."($"."this->conex);"
            ."\n\t\t\tif(count($"."datos)>0)"
            ."\n\t\t\t{".$gq_valor."}"
            ."\n\t\t\treturn $"."objeto;"
            ."\n\t\t}"
            ."\n\t}";        
        $eliminar_registro = "\tpublic function eliminar()"
            ."\n\t{\t$"."consulta = \"DELETE FROM ".$tabla." WHERE ".$idPrincipal."='\".$"."this->".$idPrincipal.".\"' LIMIT 1;\";"
            ."\n\t\t$"."this->conexion->ejecutar($"."consulta);"
            ."\n\t}";        
        $modelo_clase = "<?php"
            ."\ninclude_once '../modelo/conexionDB/conexionMySql/consulta.php';"
            ."\ninclude_once '../modelo/clases/funciones.php';"            
            ."\nclass ".$clase
            ."\n{";
            
        $modelo_clase .= $campos_tabla;
        $modelo_clase .= "\n".$constructor;        
        $modelo_clase .= "\n\n\t//SETS\n".$set_tabla;
        $modelo_clase .= "\n\t//GETS\n".$get_tabla;
        $modelo_clase .= "\n\t//METODOS GUARDAR\n".$metodos;
        $modelo_clase .= "\n\n\t//METODOS GET\n".$gets_query;
        $modelo_clase .= "\n\n\t//ELIMINAR\n".$eliminar_registro;
        $modelo_clase .= "\n}"; 
        
        $text_tarea = "<textarea rows='40' cols='110'>".$modelo_clase."</textarea><br /><br />";
        $text_tarea = "<div class='wrapper'><br />".$text_tarea."</div>";
        $html = $text_tarea;                
        echo $html;
      break;
    case "ORM_control":
        $tabla = $_REQUEST["tabla"];  $clase = $_REQUEST["clase"];
        $result = mysqli_query($conex, "SELECT * FROM ".$tabla);
        $fields_cnt = mysqli_num_fields($result);
        $row = mysqli_fetch_assoc($result);        
        $fields = mysqli_fetch_fields($result);     
        $campos_tabla = ""; $i = 0; $set_tabla = ""; $get_tabla = "";
        $agregar_tabla = "\tprivate function agregar()"."\n".
            "\t{"."\t".'$'.'consulta = "INSERT INTO '.$tabla." "; $at_valor = ')'."\n"."\t\t\t".'VALUES';
        $editar_tabla = "\tprivate function modificar()\n".
            "\t{"."\t".'$'.'consulta = "UPDATE '.$tabla." "."\n"."\t\t"."\tSET ";                         
        $et_valor = "\t\t".'$'.'this->conexion->ejecutar($consulta);'."\n\t}";
        
        $gq_valor = "";
        
        $guardar = "";    $edicion_variable = "";    
        $edicion_valores_vacio = "";  $edicion_valores = "";
        foreach($fields as $item)
        {   $campos_tabla .= "\tprivate $".$item->name;
            if(++$i==1) 
            {   $campos_tabla .= " = 0";    $idPrincipal = $item->name;
            } else  {
                $guardar .= "$"."con->set".ucfirst($item->name)
                        ."($"."_REQUEST[\"".$item->name."\"]);\n\t\t";
                $edicion_variable .= ", \"{VALUE_".strtoupper($item->name)."}\"";
                $edicion_valores_vacio .= ", \"\"";
                $edicion_valores .= "\n\t\t\t, $"."datos".$clase."->get".ucfirst($item->name)."()";
                if($i==2)
                {   $agregar_tabla .= '('.$item->name;
                    $at_valor .= '('.$ti.'".$'.'this->'.$item->name.'."'.$ti.'';
                } else {
                    $agregar_tabla .= ', '.$item->name;
                    $at_valor .= ', '.$ti.'".$'.'this->'.$item->name.'."'.$ti.'';
                }                  
                if($i>2)  $editar_tabla .= "\t\t\t\t";
                $editar_tabla .= $item->name.' = '.$ti.'".$'.'this->'.$item->name.'."'.$ti;
                if($i<$fields_cnt)  $editar_tabla .= ', '."\n";
            }
            $campos_tabla .= "; \n";
            $set_tabla .= "\tpublic function set".ucfirst($item->name)."($".$item->name.")".
                "\n\t{\t$"."this->".$item->name." = $".$item->name.";\t}";
            $set_tabla .= " \n";
            $get_tabla .= "\tpublic function get".ucfirst($item->name)."()".
                "\n\t{\treturn $"."this->".$item->name.";\t}";
            $get_tabla .= " \n";
            
            
            $gq_valor .= "\t$"."objeto->set".ucfirst($item->name)."($"."datos[0]['".$item->name."']);"
                            ."\n\t\t\t";
        }
        
        $constructor = "\tpublic function __construct($"."con = NULL)"
            ."\n\t{\t$"."this->conex = $"."con;"
            ."\n\t\t$"."this->conexion = new Consulta($"."con);"
            ."\n\t}"
            ."\n\n\tpublic function getConexion()"
            ."\n\t{\treturn $"."this->conexion;\t}"
            ."\n\tpublic function getFuncion()"
            ."\n\t{\treturn new Funciones();\t}";	
        
        $agregar_tabla .= $at_valor.')";'."\n"."\t\t".'$'.'this->conexion->ejecutar($'.'consulta);'."\n"."\t}";            
        $et_valor = '".$'.'this->'.$idPrincipal.'."'.$ti.'";'."\n".$et_valor;
        $et_valor = "\n"."\t\t\t".'WHERE '.$idPrincipal.' = '.$ti.$et_valor; 
        $editar_tabla .= $et_valor;        
        $metodos = $agregar_tabla."\n\n".$editar_tabla;
               
               
        $control = "<?php"
            ."\ninclude_once '../modelo/conexionDB/conexionMySql/conexion.php';"
            ."\n$"."Conexion = new Conexion();     $"."conex = $"."Conexion->conectar();"            
            ."\n$"."dirClase = '../modelo/clases/".$tabla.".php';"
            ."\n\nswitch($"."_REQUEST[\"accion\"])"
            ."\n{\tcase \"Template\":"
            ."\n\t\t$"."html = file_get_contents(\"../vista/capas/principal.html\");"
            ."\n\n\t\tinclude $"."dirClase;"
            ."\n\t\t$"."con = new ".$clase."($"."conex);"
            ."\n\t\t$"."datos = $"."con->get();"
            ."\n\n\t\tob_start();"
            ."\n\t\tinclude (\"../vista/capas/$tabla/plan_".$tabla."_lista.php\");"
            ."\n\t\t$"."lista_".$tabla." = ob_get_clean();"
            ."\n\n\t\t$"."html = str_replace(\"{CAPA1}\", $"."lista_".$tabla.", $"."html);"
            ."\n\t\t$"."html = str_replace(\"{CAPA2}\", \"\", $"."html);"
            ."\n\t\t$"."html = str_replace(\"{CAPA3}\", \"\", $"."html);"
            ."\n\t\t$"."html = str_replace(\"{CAPA4}\", \"\", $"."html);"
            ."\n\n\t\techo $"."html;"
            ."\n\t  break;"
            ."\n\tcase \"Listado\":"
            ."\n\t\tinclude $"."dirClase;  $"."con = new ".$clase."($"."conex);"
            ."\n\t\t$"."datos = $"."con->get();"
            ."\n\n\t\tinclude (\"../vista/capas/$tabla/plan_".$tabla."_lista.php\");"
            ."\n\n\t  break;"
            ."\n\tcase \"Nuevo\":"
            ."\n\t\tinclude $"."dirClase;  $"."con = new ".$clase."($"."conex);"
            ."\n\t\t$"."html = file_get_contents(\"../vista/capas/$tabla/plan_".$tabla."_formulario.html\");"
            ."\n\n\t\t$"."variable = array(\"{TITULO_FORMULARIO}\", \"{INPUT_ID}\"".$edicion_variable.");"
            ."\n\t\t$"."valores = array(\" Nueva - Formulario\", \"\"".$edicion_valores_vacio.");"
            ."\n\t\t$"."html = $"."con->getFuncion()->replace_form($"."html, $"."variable, $"."valores);"
            ."\n\n\t\techo $"."html;"
            ."\n\t  break;"
            ."\n\tcase \"Edicion\":"
            ."\n\t\tinclude $"."dirClase;  $"."con = new ".$clase."($"."conex);"
            ."\n\t\t$".$idPrincipal." = $"."_REQUEST[\"".$idPrincipal."\"];"
            ."\n\t\t$"."datos".$clase." = $"."con->get($".$idPrincipal.");"
            ."\n\t\t$"."html = file_get_contents(\"../vista/capas/$tabla/plan_".$tabla."_formulario.html\");"
            ."\n\n\t\t$"."variable = array(\"{TITULO_FORMULARIO}\", \"{INPUT_ID}\"".$edicion_variable.");"
            ."\n\t\t$"."valores = array(\" - Editar\""
            ."\n\t\t\t,\"<input type='hidden' id='".$idPrincipal."' name='".$idPrincipal."' value='\".$".$idPrincipal.".\"' />\""
            .$edicion_valores.");"
            ."\n\t\t$"."html = $"."con->getFuncion()->replace_form($"."html, $"."variable, $"."valores);"
            ."\n\n\t\techo $"."html;"
            ."\n\t  break;"
            ."\n\tcase \"Guardar\":"
            ."\n\t\tinclude $"."dirClase;"
            ."\n\t\t$"."con = new ".$clase."($"."conex);"
            ."\n\n\t\tif(isset($"."_REQUEST[\"".$idPrincipal."\"]))"
            ."\n\t\t\t$"."con->set".ucfirst($idPrincipal)."($"."_REQUEST[\"".$idPrincipal."\"]);"
            ."\n\n\t\t".$guardar
            ."\n\t\t$"."con->guardar();"
            ."\n\t  break;"
            ."\n\tcase \"Eliminar\":"
            ."\n\t\tinclude $"."dirClase;  $"."con = new ".$clase."($"."conex);"
            ."\n\t\t$"."con->set".ucfirst($idPrincipal)."($"."_REQUEST[\"".$idPrincipal."\"]);"
            ."\n\n\t\t$"."con->eliminar();"
            ."\n\t  break;"
            ."\n}"
            ."\n$"."Conexion->desconectar();"
            ."\n\n?>";
        
        $text_tarea = "<textarea rows='40' cols='110'>".$control."</textarea><br /><br />";
        $text_tarea = "<div class='wrapper'><br />".$text_tarea."</div>";
        $html = $text_tarea;                
        echo $html;
      break;
    case "ORM_formulario":
        $tabla = $_REQUEST["tabla"];  $clase = $_REQUEST["clase"];
        $result = mysqli_query($conex, "SELECT * FROM ".$tabla);
        $fields_cnt = mysqli_num_fields($result);
        $row = mysqli_fetch_assoc($result);        
        $fields = mysqli_fetch_fields($result);
        
        $campos = "";   $i = 0;
        foreach($fields as $item)
        {   if(++$i==1) 
            {   $idPrincipal = $item->name;
            }
            else
            {   $campos .= "\n\t\t\t\t<div class=\"sepH_a\">"
                ."\n\t\t\t\t\t<label class=\"control-label span4\">".strtoupper($item->name).": <span class=\"text-error\">*</span></label>"
                ."\n\t\t\t\t\t<input type=\"text\" class=\"span4\" "
                    ."name=\"".$item->name."\" id=\"".$item->name."\"  value=\"{VALUE_".strtoupper($item->name)."}\" />"
                ."\n\t\t\t\t</div>\n";
            }
        }        
        
        $formulario = "<script>"
            ."\n\tfunction ".$tabla."_lista()"
            ."\n\t{\t$.ajax("
            ."\n\t\t{\turl : 'controlador/control_".$tabla.".php',"
            ."\n\t\t\tdata : \"accion=Listado\","
            ."\n\t\t\tsuccess : function(respuesta)"
            ."\n\t\t\t{\t$('#capa1').html(respuesta);\t}"
            ."\n\t\t});"
            ."\n\t}"
            ."\n</script>"
            ."\n<br />"
            ."\n<div class=\"widget row-fluid\">"
            ."\n<div class=\"span8 offset2\">"
            ."\n\t<form id=\"form_".$tabla."\" class=\"form-horizontal\" action=\"javascript:void(0)\">"
            ."\n\t<fieldset>"
            ."\n\t\t{INPUT_ID}"
            ."\n\t\t<div class=\"navbar\">"
            ."\n\t\t\t<div class=\"navbar-inner\">"
            ."\n\t\t\t\t<h6><strong>".$clase."{TITULO_FORMULARIO}</strong></h6>"
            ."\n\t\t\t\t<div class=\"nav pull-right\">"
            ."\n\t\t\t\t\t<a href=\"#\" class=\"dropdown-toggle navbar-icon\" data-toggle=\"dropdown\"><i class=\"icon-cog\"></i></a>"
            ."\n\t\t\t\t\t<ul class=\"dropdown-menu pull-right\">"
            ."\n\t\t\t\t\t\t<li><a onclick=\"".$tabla."_lista()\" href=\"javascript:void(0)\">"
            ."\n\t\t\t\t\t\t\t\t<i class=\"icon-reorder\"></i>Listar Ni&ntilde;os</a></li>"
            ."\n\t\t\t\t\t</ul>"
            ."\n\t\t\t\t</div>"
            ."\n\t\t\t</div>"
            ."\n\t\t</div>"
            ."\n"
            ."\n\t\t<div class=\"well row-fluid\">"
            ."\n\t\t\t<div class=\"control-group\">"
            .$campos
            ."\n\t\t\t</div>"
            ."\n"
            ."\n\t\t\t<div class=\"form-actions\">"
            ."\n\t\t\t\t<button type=\"submit\" class=\"btn btn-success pull-left sepV_a\">Guardar</button>"
            ."\n\t\t\t\t<button type=\"reset\" class=\"btn pull-left\">Reset</button>"
            ."\n"
            ."\n\t\t\t\t<button onclick=\"".$tabla."_lista()\" type=\"button\" class=\"btn btn-danger pull-right\">Cancelar</button>"
            ."\n\t\t\t</div>"
            ."\n\t\t</div>"
            ."\n\t</fieldset>"
            ."\n\t</form>"
            ."\n\t<!-- /form validation -->"
            ."\n</div>"
            ."\n</div>"
            ."\n<script>"
            ."\n\t$(\".styled\").uniform({ radioClass: 'choice' });"
            ."\n\t$('.val_num').keyup(function () { this.value = this.value.replace(/[^0-9\.]/g,''); });"
            ."\n\t$(\"#form_".$tabla."\").validationEngine({"
            ."\n\t\tonValidationComplete: function(form, status){"
            ."\n\t\t\tif(status== true)"
            ."\n\t\t\t{\t$.ajax({"
            ."\n\t\t\t\t\turl : 'controlador/control_".$tabla.".php',"
            ."\n\t\t\t\t\tdata : \"accion=Guardar&\" + $(\"#form_".$tabla."\").serialize(),"
            ."\n\t\t\t\t\tsuccess : function(resultado) {"
            ."\n\t\t\t\t\t\t$('#capa1').html('<br /><center><img src=\"img/elements/loaders/6.gif\"/></center>');"
            ."\n\t\t\t\t\t\t$.ajax({"
            ."\n\t\t\t\t\t\t\turl : 'controlador/control_".$tabla.".php',"
            ."\n\t\t\t\t\t\t\tdata : \"accion=Listado\","
            ."\n\t\t\t\t\t\t\tsuccess : function(resultado) {"
            ."\n\t\t\t\t\t\t\t\t$(\"#capa1\").html(resultado);"
            ."\n\t\t\t\t\t\t\t},"
            ."\n\t\t\t\t\t\t});"
            ."\n\t\t\t\t\t},"
            ."\n\t\t\t\t});"
            ."\n\t\t\t}"
            ."\n\t\t}"
            ."\n\t});"
            ."\n</script>";
        $text_tarea = "<textarea rows='40' cols='110'>".$formulario."</textarea><br /><br />";
        $text_tarea = "<div class='wrapper'><br />".$text_tarea."</div>";
        $html = $text_tarea;                
        echo $html;
      break;
    case "ORM_lista":
        $tabla = $_REQUEST["tabla"];  $clase = $_REQUEST["clase"];
        $result = mysqli_query($conex, "SELECT * FROM ".$tabla);
        $fields_cnt = mysqli_num_fields($result);
        $row = mysqli_fetch_assoc($result);        
        $fields = mysqli_fetch_fields($result);
        
        $thName = $tdValor = "";   $i = 0;
        foreach($fields as $item)
        {   if(++$i==1) 
            {   $idPrincipal = $item->name;
            }
            else
            {   $thName .= "\n\t\t\t\t\t<th>".ucfirst($item->name)."</th>";
                $tdValor .= "\n\t\t\t\t\t\t<td><?php echo $"."item->get".ucfirst($item->name)."();?></td>";
            }
        }        
        
        $lista = "<script>"
            ."\n\tfunction ".$tabla."_lista()"
            ."\n\t{\t$.ajax("
            ."\n\t\t{\turl : 'controlador/control_".$tabla.".php',"
            ."\n\t\t\tdata : {accion:'Listado'},"
            ."\n\t\t\tsuccess : function(respuesta)"
            ."\n\t\t\t{\t$('#capa1').html(respuesta);\t}"
            ."\n\t\t});"
            ."\n\t}"
            ."\n"
            ."\n\tfunction ".$tabla."_nuevo_formulario()"
            ."\n\t{\t$.ajax("
            ."\n\t\t{\turl : 'controlador/control_".$tabla.".php',"
            ."\n\t\t\tdata : \"accion=Nuevo\","
            ."\n\t\t\tsuccess : function(respuesta)"
            ."\n\t\t\t{\t$('#capa1').html(respuesta);\t}"
            ."\n\t\t});"
            ."\n\t}"
            ."\n"
            ."\n\tfunction editar_".$tabla."(".$idPrincipal.")"
            ."\n\t{\t$.ajax("
            ."\n\t\t{\turl : \"controlador/control_".$tabla.".php\","
            ."\n\t\t\tdata : \"accion=Edicion&".$idPrincipal."=\" + ".$idPrincipal.","
            ."\n\t\t\tsuccess : function(respuesta)"
            ."\n\t\t\t{\t$(\"#capa1\").html(respuesta);\t}"
            ."\n\t\t});"
            ."\n\t}"
            ."\n"
            ."\n\tfunction eliminar_".$tabla."(".$idPrincipal.", obj)"
            ."\n\t{\tif(confirm(\"\u00BFSeguro de Eliminar?\"))"
            ."\n\t\t{\t$(\"#loading_".$tabla."\").show();"
            ."\n\t\t\t$.ajax("
            ."\n\t\t\t{\turl : \"controlador/control_".$tabla.".php\","
            ."\n\t\t\t\tdata : \"accion=Eliminar&".$idPrincipal."=\" + ".$idPrincipal.","
            ."\n\t\t\t\tsuccess : function(respuesta)"
            ."\n\t\t\t\t{\talert(\"Registro Eliminado\");"
            ."\n\t\t\t\t\tvar tr = obj.parentNode.parentNode.parentNode.parentNode;"
            ."\n\t\t\t\t\tvar table = tr.parentNode;"
            ."\n\t\t\t\t\ttable.removeChild(tr);"
            ."\n\t\t\t\t\t$(\"#loading_".$tabla."\").hide();"
            ."\n\t\t\t\t}"
            ."\n\t\t\t});"
            ."\n\t\t}"
            ."\n\t}"
            ."\n"
            ."\n</script>"
            ."\n<br />"
            ."\n<!-- Default datatable -->"
            ."\n<div class=\"widget row-fluid\">"
            ."\n<div class=\"span12\">"
            ."\n\t<div class=\"navbar\">"
            ."\n\t\t<div class=\"navbar-inner\">"
            ."\n\t\t\t<h6><strong>Listado de ".$clase."</strong></h6>"
            ."\n\t\t\t<div class=\"nav pull-right\">"
            ."\n\t\t\t\t<a class=\"dropdown-toggle navbar-icon\" data-toggle=\"dropdown\" href=\"javascript:void(0)\">"
            ."\n\t\t\t\t\t<i class=\"icon-cog\"></i></a>"
            ."\n\t\t\t\t<ul class=\"dropdown-menu pull-right\">"
            ."\n\t\t\t\t\t<li><a onclick=\"".$tabla."_nuevo_formulario()\" href=\"javascript:void(0)\">"
            ."\n\t\t\t\t\t\t\t<i class=\"icon-plus\"></i>Nuevo ".$clase."</a></li>"
            ."\n\t\t\t\t</ul>"
            ."\n\t\t\t</div>"
            ."\n\t\t\t<div id=\"loading_".$tabla."\" class=\"loading pull-right hide\">"
            ."\n\t\t\t\t<span>Cargando</span>"
            ."\n\t\t\t\t<img src=\"img/elements/loaders/6s.gif\" alt=\"\" />"
            ."\n\t\t\t</div>"
            ."\n\t\t</div>"
            ."\n\t</div>"
            ."\n\t<div class=\"well body\">"
            ."\n\t\t<div class=\"table-overflow\">"
            ."\n\t\t<table class=\"table table-striped table-bordered\" id=\"data-table\">"
            ."\n\t\t\t<thead>"
            ."\n\t\t\t\t<tr>"
            .$thName
            ."\n\t\t\t\t\t<th class=\"no-sort tam50\">Acci&oacute;n</th>"
            ."\n\t\t\t\t</tr>"
            ."\n\t\t\t</thead>"
            ."\n\t\t\t<tbody>"
            ."\n\t\t\t\t<?php\t$"."i = 0;"
            ."\n\t\t\t\t  foreach($"."datos as $"."item)\t{\t$"."i++;"
            ."\n\t\t\t\t\tif($"."item->get".ucfirst($idPrincipal)."()!=0)	{?>"
            ."\n\t\t\t\t\t  <tr class=\"gradeX\">"
            .$tdValor
            ."\n\t\t\t\t\t\t<td class=\"align-center\">"
            ."\n\t\t\t\t\t\t\t<ul class=\"table-controls\">"
            ."\n\t\t\t\t\t\t\t\t<li><a onclick=\"editar_".$tabla."(<?php echo $"."item->get".ucfirst($idPrincipal)."();?>)\" title=\"Editar ".$clase."\" class=\"tip\" href=\"javascript:void(0)\">"
            ."\n\t\t\t\t\t\t\t\t\t\t<i class=\"icon-edit\"></i></a> </li>"
            ."\n\t\t\t\t\t\t\t\t<li><a onclick=\"eliminar_".$tabla."(<?php echo $"."item->get".ucfirst($idPrincipal)."();?>, this)\" title=\"Eliminar ".$clase."\" class=\"tip\" href=\"javascript:void(0)\">"
            ."\n\t\t\t\t\t\t\t\t\t\t<i class=\"icon-remove\"></i></a> </li>"
            ."\n\t\t\t\t\t\t\t</ul>"
            ."\n\t\t\t\t\t\t</td>"
            ."\n\t\t\t\t\t</tr>"
            ."\n\t\t\t\t<?php } 	}?>"
            ."\n\t\t\t</tbody>"
            ."\n\t\t</table><br /><br />"
            ."\n\t\t</div>"
            ."\n\t</div>"
            ."\n</div>"
            ."\n</div>"
            ."\n<!-- /default datatable -->"
            ."\n"
            ."\n<script>"
            ."\n\t$(\"#data-table\").dataTable({"
            ."\n\t\t\"bJQueryUI\": false,"
            ."\n\t\t\"bAutoWidth\": false,"
            ."\n\t\t\"sPaginationType\": \"full_numbers\","
            ."\n\t\t//\"sDom\": '<\"datatable-header\"fl>t<\"datatable-footer\"ip>',"
            ."\n\t\t\"oLanguage\": {"
            ."\n\t\t\t\"sSearch\": \"<span>Buscar registro:</span> _INPUT_\","
            ."\n\t\t\t\"sLengthMenu\": \"<span>Mostrar:</span> _MENU_\","
            ."\n\t\t\t\"oPaginate\": { \"sFirst\": \"Primero\", \"sLast\": \"&"."Uacute;ltimo\", \"sNext\": \">\", \"sPrevious\": \"<\" }"
            ."\n\t\t},"
            ."\n\t\t\"aoColumnDefs\" : [{ \"bSortable\" : false, \"aTargets\" : [ \"no-sort\" ] }]"
            ."\n\t\t//, \"aaSorting\": [[ 2, \"desc\" ]]"
            ."\n\t});"
            ."\n</script>";
        $text_tarea = "<textarea rows='40' cols='110'>".$lista."</textarea><br /><br />";
        $text_tarea = "<div class='wrapper'><br />".$text_tarea."</div>";
        $html = $text_tarea;                
        echo $html;
      break;      
}

$Conexion->desconectar();

?>