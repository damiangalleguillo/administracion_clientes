<?php
require_once 'nucleo/db/baseDataset.php';

 class dataset extends baseDataset{  
 
  private function getLlavePrimaria(){
	$filas = $this->conexion->fetch_array($this->conexion->consultar("select ix.rdb\$index_name as index_name,  
																																sg.rdb\$field_name as field_name,  
																																rc.rdb\$relation_name as table_name
																													from    rdb\$indices ix
																																left join rdb\$index_segments sg on ix.rdb\$index_name = sg.rdb\$index_name
																																left join rdb\$relation_constraints rc on rc.rdb\$index_name = ix.rdb\$index_name
																													where
																																rc.rdb\$constraint_type = 'PRIMARY KEY' 
																																and rc.rdb\$relation_name = upper('".get_class($this)."') "));  
	if ($filas) $this->llavePrimaria = trim($filas[0]['FIELD_NAME']);
	 
  }
  
  protected function getCampos(){
 	$filas = $this->conexion->fetch_array($this->conexion->consultar("select RDB\$RELATION_FIELDS.RDB\$FIELD_NAME as NOMBRE,
CASE RDB\$FIELDS.RDB\$FIELD_TYPE
 WHEN 7 THEN 'SMALLINT'
 WHEN 8 THEN 'INTEGER'
 WHEN 9 THEN 'QUAD'
 WHEN 10 THEN 'FLOAT'
 WHEN 11 THEN 'D_FLOAT'
 WHEN 12 THEN 'DATE'
 WHEN 13 THEN 'TIME'
 WHEN 14 THEN 'CHAR'
 WHEN 16 THEN 'INT64'
 WHEN 27 THEN 'DOUBLE'
 WHEN 35 THEN 'TIMESTAMP'
 WHEN 37 THEN 'VARCHAR'
 WHEN 40 THEN 'CSTRING'
 WHEN 261 THEN 'BLOB'
 ELSE 'UNKNOWN'
END AS TIPO
from RDB\$RELATION_FIELDS left join
RDB\$FIELDS on RDB\$FIELDS.rdb\$field_name = RDB\$RELATION_FIELDS.rdb\$field_source 
where RDB\$RELATION_FIELDS.RDB\$RELATION_NAME = upper('".get_class($this)."') "));  
	if ($filas){
		foreach($filas as $valor){
			$this->registro[trim($valor['NOMBRE'])]['value'] = '';    	 
			$this->registro[trim($valor['NOMBRE'])]['type'] = '';
		}
		$this->getLlavePrimaria();      
	}
	else{
		$filas = $this->conexion->fetch_array($this->conexion->consultar("select RDB\$PARAMETER_NAME as NOMBRE,  RDB\$PARAMETER_TYPE as TIPO from RDB\$PROCEDURE_PARAMETERS where RDB\$PROCEDURE_NAME = upper('".get_class($this)."') "));
		if ($filas){
			foreach($filas as $valor)
				if ($valor['TIPO'] == 1){
					$this->registro[trim($valor['NOMBRE'])]['value'] = '';    	 
					//$this->registro[trim($valor['NOMBRE'])]['type'] = '';
				} 
				else $this->parametros[trim($valor['NOMBRE'])] = '';	
			if (count($this->registro) == 0) $this->procedimiento = true;
		}		
	}	 
  }
  
  function insertar($retorno=NULL){ 
    $consulta = '';
	$campos = '';
	$valores = ''; 
	$c = count($this->registro);
	$coma = '';
	foreach($this->registro as $indice => $valor){
	 if ($c > 1) $coma = ', ';	
	 else $coma = '';	
	 $campos .= $indice.$coma;	 
	 $valores .= '?'.$coma;	 
	 $c--;
	}	
	
	$consulta = "insert into ".get_class($this)."($campos) values ($valores)".(isset($retorno)? ' returning '.$retorno: '');
	$resultado = $this->conexion->consultar($consulta, $this->registro);			
    if ($retorno) return $this->conexion->fetch_array($resultado)[0][$retorno];
  }
  
  function eliminar(){
   $id = $this->registro[$this->llavePrimaria]['value'];
   return $this->conexion->consultar("delete from ".get_class($this)." where ".$this->llavePrimaria." = $id");  	  
  }
  
  function modificar(){
	$consulta = '';
	$campos = '';
	$id = $this->registro[$this->llavePrimaria]['value'];
	$c = count($this->registro);
	$coma = '';
	foreach($this->registro as $indice => $valor){
	 if ($c > 1) $coma = ', ';	
	 else $coma = '';
	 $campos .= '$indice = ?'.$coma;	
	 $c--;
	}	
	
	$consulta = "update ".get_class($this)." set $campos where ".$this->llavePrimaria." = $id"; 	
	return $this->conexion->consultar($consulta, $this->registro);  	  
  }
  
   function buscar($campo = null, $dato, $sensible = true, $parcial = false, $orden = null, $asc=true){
	$where = '';
   $com = '';
   $datoBusc = '?';
   $variables = array();
   if ($parcial) $dato="%$dato%";   
   if ($campo) {	
	if (!$sensible) {				
		$campo = "upper($campo)";
		$datoBusc = "upper(?)";
	}
	$where = "where $campo"; 	
	if (!isset($dato)) $where .= ' is NULL';
	else{
		$variables[] = array('value' => $dato, 'type' => 'varchar');
		if ($parcial) $where .= " like $datoBusc";	
		else $where .= " = $datoBusc";	
	}
		
   }
   if ($orden){	 
	 $where .= " order by $orden";  
   } 
   if (!$asc) $where .= ' desc';
 
   $parametros = $this->cargarParametros();      
   $this->registros = $this->conexion->fetch_array($this->conexion->consultar('select * from '.get_class($this)."$parametros $where", $variables));      
   $this->seleccionar();   
   $retorno = count($this->registros);
   return $retorno>0? $retorno : false;
	}
	
  protected function cargarParametros(){
	$parametros = '';
	foreach($this->parametros as $valor) $parametros .= "$valor,";
	if (strlen($parametros)>0) $parametros = '('.substr($parametros, 0, strlen($parametros)-1).')';
	return $parametros;
  }
  
  function leerBlob($campo){
	 $info_blob = ibase_blob_info($campo);
     return ibase_blob_get(ibase_blob_open($campo), $info_blob["length"]);
  }
  
  function crearFecha($fecha){
	$fecha = explode("/", $fecha);
	if (count($fecha)>2){
		$fecha = "$fecha[1]/$fecha[0]/$fecha[2]";   
	} 	
	else $fecha = NULL;
 
	return $fecha;
  }
   
 }
?>