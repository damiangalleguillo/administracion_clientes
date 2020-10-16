<?php
require_once __DIR__.'/../baseDataset.php';
use Configuraciones\configuraciones;
 class dataset extends baseDataset{
   
  private function getLlavePrimaria(){
	$filas = $this->conexion->fetch_array($this->conexion->consultar('select COLUMN_NAME as NOMBRE from INFORMATION_SCHEMA.COLUMNS where TABLE_SCHEMA = \''.configuraciones::getDBConfiguracion()['nomb_db'].'\' and upper(TABLE_NAME) = upper(\''.get_class($this).'\') and COLUMN_KEY = \'PRI\''));  
	if ($filas) $this->llavePrimaria = trim($filas[0]['NOMBRE']);
  }
  
  protected function getCampos(){
 	$filas = $this->conexion->fetch_array($this->conexion->consultar('select COLUMN_NAME as NOMBRE, DATA_TYPE as TIPO from INFORMATION_SCHEMA.COLUMNS where TABLE_SCHEMA = \''.configuraciones::getDBConfiguracion()['nomb_db'].'\' and upper(TABLE_NAME) = upper(\''.get_class($this).'\') '));  
	if ($filas){
		foreach($filas as $valor){
			$this->registro[trim($valor['NOMBRE'])]['value'] = '';    	 
			$this->registro[trim($valor['NOMBRE'])]['type'] = $valor['TIPO'];
		}
		$this->getLlavePrimaria();      
	}/* SIN SOPORTE PARA STORED PROCEDURE
	else{
		$filas = $this->conexion->fetch_array($this->conexion->consultar("select RDB\$PARAMETER_NAME as NOMBRE,  RDB\$PARAMETER_TYPE as TIPO from RDB\$PROCEDURE_PARAMETERS where RDB\$PROCEDURE_NAME = upper('".get_class($this)."') "));
		foreach($filas as $valor)
		if ($valor['TIPO'] == 1) $this->registro[trim($valor['NOMBRE'])] = '';
		else $this->parametros[trim($valor['NOMBRE'])] = '';
	}*/
	 
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
	
	$consulta = 'insert into '.get_class($this)."($campos) values ($valores)"; 		
	$resultado = $this->conexion->consultar($consulta, $this->registro);	
	$ult_id = $this->conexion->ultimo_id();	
	if ($retorno) {
		$consulta = $this->conexion->consultar("select $retorno from ".get_class($this).' where '.$this->llavePrimaria." = $ult_id");				
		return $this->conexion->fetch_array($consulta)[0][$retorno];
	}
  }
  
  function eliminar(){
   $id = $this->registro[$this->llavePrimaria]['value'];
   return $this->conexion->consultar("delete from ".get_class($this)." where ".($this->llavePrimaria)." = $id");  	  
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
	 $campos .= "$indice = ?".$coma;
	 $c--;
	}		
	$consulta = 'update '.get_class($this)." set $campos where ".$this->llavePrimaria." = $id"; 	
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
  
  /*NO SOPORTADO LECTURA DE CAMPOS BLOB*/
  function leerBlob($campo){
	 $info_blob = ibase_blob_info($campo);
     return ibase_blob_get(ibase_blob_open($campo), $info_blob["length"]);
  
  }
  
  function crearFecha($fecha){
	$fecha = explode("/", $fecha);
	if (count($fecha)>2){
		$fecha = "$fecha[2]-$fecha[1]-$fecha[0]";   
	} 	
	else $fecha = NULL;
 
	return $fecha;
  }
 
 }
?>