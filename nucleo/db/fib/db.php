<?php

require_once "dataset.php";

class moduloDeDatos{
 
public $Conexion;

function conectar($svr, $db, $usr, $pass){
   $conexion = ibase_connect("$svr:$db", $usr, $pass);
   if ($conexion) return $conexion;
   else return false;
 }
 
 function cerrar(){
  ibase_close();
 }
 
 function consultar($consulta, $registro = null){	 
	$parametros = array(); 
	$stmt = ibase_prepare($consulta);	
	if (isset($registro)){
		foreach($registro as $valor){
			$parametros[] = $valor['value'];
		}
	}
	array_unshift($parametros, $stmt);
	if ($stmt) return call_user_func_array('ibase_execute', $parametros);	
	return false;
 }
 
 function fetch_array($result_query){	
	$result = array();
	$i = 0;	
	while($fetch = ibase_fetch_assoc($result_query)){
     $result[$i] = array();
	 foreach ($fetch as $campo => $valor)
	  $result[$i][$campo]=$valor;	 
	  $i++;
	}	
	return $result;
 }
 
function __construct($server, $database, $usuario, $password){
 $this->Conexion = $this->conectar($server, $database, $usuario, $password);
}
 
}
?>