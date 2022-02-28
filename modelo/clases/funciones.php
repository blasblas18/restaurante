<?php
class Funciones {
    private $mesCompleto = array("00"=>"", "01"=>"Enero", "02"=>"Febrero", "03"=>"Marzo"
        , "04"=>"Abril", "05"=>"Mayo", "06"=>"Junio", "07"=>"Julio", "08"=>"Agosto"
        , "09"=>"Septiembre", "10"=>"Octubre", "11"=>"Noviembre", "12"=>"Diciembre");
    private $mesCorto = array("00"=>"", "01"=>"Ene", "02"=>"Feb", "03"=>"Mar", "04"=>"Abr"
        , "05"=>"May", "06"=>"Jun", "07"=>"Jul", "08"=>"Ago", "09"=>"Sep", "10"=>"Oct"
        , "11"=>"Nov", "12"=>"Dic");
    
    private $bd_sismore = "salud_sismore";    
    private $bd_maestro = "salud_maestro";    
    private $bd_bdhiscons = "salud_bdhisconsolidado";
    private $bd_sis = "salud_sis";
    
    public function getBdSismore()              {   return $this->bd_sismore;       }
    public function getBdMaestro()              {   return $this->bd_maestro;       }
    public function getBdHisCons()              {   return $this->bd_bdhiscons;     }
    public function getBdSis()                  {   return $this->bd_sis;           }
    
    public function replace_form($html, $variable, $valor) 
    {	for($i = 0; $i<count($variable); $i++)
        {   $html = str_replace($variable[$i],$valor[$i],$html);
        }
        return $html;
    }
    
    public function miles($a,$i)
    {   return number_format($a,$i,".",",");
    }
    
    public function getEstadoEstilo($id)
    {   $mensaje = "<span class=\"label label-";
        if($id == "1")
            $mensaje .= "success\">Activo";
        else
            $mensaje .= "important\">Inactivo";
        $mensaje .= "</span>";
        return $mensaje;        
    }
    
    public function getInArray($datos )
    {   $arrAux = array(0,0,0,0,0,0,0,0,0,0,0,0,0);
        foreach($datos AS $item)
            $arrAux[$item[0]] = $item[1]; 
        return $arrAux;        
    }
    
    public function getInArrayAcumulado($arr)
    {   $arrAux = array(0,0,0,0,0,0,0,0,0,0,0,0,0);
        for($i=1; $i<=12; $i++)
            $arrAux[$i] = $arrAux[$i-1] + $arr[$i]; 
        return $arrAux;        
    }
    
    public function ejecutarCargaVacuna($datos, $conC)
    {   $i = 0; $query = ""; 
        $queryCab = "INSERT INTO ".$this->sismore.".atencion_vacuna (cod_2000, anio, mes, vacuna, grupo, dosis, cantidad) VALUES ";
        foreach($datos AS $item)
        {   $i++; if($i==1)   $query .= $queryCab; if($i>1)   $query .= " , "; 
            $query .= " ('".$item["cod_2000"]."', '".$item["anio"]."', '".$item["mes"]."', '".$item["vacuna"]."', '".$item["grupo"]."', 
                 '".$item["control"]."', '".$item["cantidad"]."') " ;
            if($i==200)  { $conC->ejecutar($query); $i = 0; /* echo $query."<br /><br />"; */ $query = "";    }   
        }
        if($i>0 and $i<200)  { $conC->ejecutar($query); $i = 0;  /*echo $query."<br /><br />"; */ $query = "";    }         
    }
    function colegioNombreCorto($id)
    {   $datos = array("00"=>"Tec", "01"=>"Med", "02"=>"Quim", "03"=>"Odon", "04"=>"Biol", "05"=>"Obst", "06"=>"Enf", "07"=>"T.Soc", "08"=>"Psic", "09"=>"T.Med", "10"=>"Nut", "11"=>"M.Vet");
        return $datos[$id];    }
    
    
    public function getFechaDMY($fecha, $tipo = NULL)
    {   switch($tipo)
        {   case "1": // Fecha Completa con Mes Nombre Corto
                $fecha = date("d-m-Y",strtotime($fecha));
                $aux = explode("-",$fecha);
                $fecha = $aux[0]."&nbsp;&nbsp;&nbsp;".$this->mesCorto[$aux[1]]."&nbsp;&nbsp;&nbsp;".$aux[2];
                break;
            case "2": // Fecha Corta con Mes Nombre Corto
                $fecha = date("d-m-Y",strtotime($fecha));
                $aux = explode("-",$fecha);
                $fecha = $aux[0]."&nbsp;".$this->mesCorto[$aux[1]];
                break;
            default:
                $fecha = date("d-m-Y",strtotime($fecha)); 
                break;    
            
        }
        return $fecha;     
    }
    public function getFechaYMD($fecha)
    {   if($fecha!='')
        {   $aux = explode(" ", $fecha);  // echo $fecha."-";
            $fecha = $aux[0];            
            $fecha = str_replace("/","-",$fecha);
            $fec = explode("-", $fecha);
            return ($fec[2]."-".$fec[1]."-".$fec[0]).((count($aux)>1)?(" ".$aux[1]):"");            
        }
    }
    public function diasMes($mes, $anio) {
       return date("d",mktime(0,0,0,$mes+1,0,$anio));
    }
    function mesNombreCompleto($mes)
    {   return $this->mesCompleto[str_pad($mes,2,"0",STR_PAD_LEFT)];    }
    function mesNombreCorto($mes)
    {   return $this->mesCorto[str_pad($mes,2,"0",STR_PAD_LEFT)];    }
    
    public function getDataAnio($anioI,$anioF = NULL)
    {   $datos = array(); $anioF = ($anioF==NULL)?date("Y",time()):$anioF;
        $anioI = ($anioI==NULL)?date("Y",time()):$anioI;
        for($i=$anioF; $i>=$anioI; $i--) $datos[] = array($i, $i);
        return $datos;
    }
    
    public function getDataMes()
    {   $datos = array(); 
        for($i=1; $i<=12; $i++) $datos[] = array($i, $this->mesCompleto[str_pad($i,2,"0",STR_PAD_LEFT)]);
        return $datos;
    }
    
    public function getOption($datos, $id = NULL, $tipLetra = NULL)
    {   $opciones = "";
		foreach ($datos as $row)
		{	$opciones .= "<option ";
			$opciones .= ($id == $row[0])?"selected='selected'":"";
			$opciones .= " value='".$row[0]."'>";
            if($tipLetra=="UD") //utf8_decode
                $opciones .= utf8_encode($row[1]);
            else
                $opciones .= htmlentities($row[1]);
			$opciones .= "</option>";
		}
		return $opciones;
    }
    
    public function sexoNombre($sexo)
    {   
        return ($sexo=="M")?"Masculino":"Femenino";
    }
    
    public function bisiesto($anio_actual)
    {	
        $bisiesto=false;  
        if (checkdate(2,29,$anio_actual)) 
            $bisiesto=true; 
            
        return $bisiesto;
    }
    
    public function calculo_edad($fecha_de_nacimiento, $fecha_actual, $tipo = NULL )
    {
        $array_nacimiento = explode ( "-", $fecha_de_nacimiento ); 
        $array_actual = explode ( "-", $fecha_actual ); 
        
        $anos =  $array_actual[0] - $array_nacimiento[0]; // calculamos años 
        $meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses 
        $dias =  $array_actual[2] - $array_nacimiento[2]; // calculamos días 
        
        //ajuste de posible negativo en $días 
        if ($dias < 0) 
        { 
            --$meses;     
            //ahora hay que sumar a $dias los dias que tiene el mes anterior de la fecha actual 
            switch ($array_actual[1]) { 
                   case 1:     $dias_mes_anterior=31; break; 
                   case 2:     $dias_mes_anterior=31; break; 
                   case 3:  
                        if ($this->bisiesto($array_actual[0])) 
                        { 
                            $dias_mes_anterior=29; break; 
                        } else { 
                            $dias_mes_anterior=28; break; 
                        } 
                   case 4:     $dias_mes_anterior=31; break; 
                   case 5:     $dias_mes_anterior=30; break; 
                   case 6:     $dias_mes_anterior=31; break; 
                   case 7:     $dias_mes_anterior=30; break; 
                   case 8:     $dias_mes_anterior=31; break; 
                   case 9:     $dias_mes_anterior=31; break; 
                   case 10:     $dias_mes_anterior=30; break; 
                   case 11:     $dias_mes_anterior=31; break; 
                   case 12:     $dias_mes_anterior=30; break; 
            }     
            $dias=$dias + $dias_mes_anterior; 
        }     
        //ajuste de posible negativo en $meses 
        if ($meses < 0) 
        { 
            --$anos; 
            $meses=$meses + 12; 
        } 
        
        $letraTipo = array(" a", " m", " d");
        if($tipo=="CRED" or $tipo=="SUPLE")   $letraTipo = array(" ANIO", " MES", " DIA");
        
        if($anos>0)
            $edad = $anos.$letraTipo[0];
        else
            if($meses>0)
                $edad = $meses.$letraTipo[1];
            else
                $edad = $dias.$letraTipo[2];
        
        switch($tipo)
        {   case "CRED":
                if($anos>0 && $anos<5)
                    $edad = ($anos*12 + $meses)." MES";
                    
                break;
	       case "SUPLE":
                if($anos>0 && $anos<12)
                    $edad = ($anos*12 + $meses)." MES";
                    
                break;	
            case "DIAS":
                $dias	= (strtotime($fecha_de_nacimiento)-strtotime($fecha_actual))/86400;
                $dias 	= abs($dias); $dias = floor($dias);
                $edad = $dias.(($dias==1)?" dia":" dias");
                    
                break;
            case "MESES":
                if($anos>0)
                    $meses = ($anos*12 + $meses);
                if($meses == "1")
                    $edad = $meses." mes";
                else
                    $edad = $meses." meses";
                    
                break;
            case "COMPLETO":
                $edad  =     $anos.$letraTipo[0];
                if($meses<10) $meses = "0".$meses;
                $edad .= ", ".$meses.$letraTipo[1];
                if($dias<10) $dias = "0".$dias;
                $edad .= ", ".$dias.$letraTipo[2];
                
                break;            
        }       
                      
        return $edad;
    }
}
?>
