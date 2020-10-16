<?php 
use Configuraciones\configuraciones;

abstract class baseDataset{
  public $conexion;
  protected $registro;
  public $registros;
  public $parametros;
  protected $reg_no = 0;
  protected $llavePrimaria;
  protected $procedimiento = false;
 	 
  function __construct($conexion){
	  $this->conexion = $conexion;	  
	  $this->registro = array();
	  $this->registros = array();
	  $this->parametros = array();
	  $this->getCampos();
  }
  
  function getFieldsList(){
	  $campos = array();
	  foreach($this->registro as $campo => $contenido)
		array_push($campos, $campo);
	  return $campos;
  }
    
  public function __get($nombre){
	  if (isset($this->registro[$nombre]))
		return $this->registro[$nombre]['value'];
	  else throw new Exception("Atributo $nombre no Encontrado");
  }
  
  public function __set($nombre, $valor){
      if (isset($this->registro[$nombre]))
		return $this->registro[$nombre]['value'] = $valor;
	  else throw new Exception("Atributo $nombre no Encontrado");
  }
  
  function primero(){
	 return $this->seleccionar(0);
  }
  
  function ultimo(){
	 return $this->seleccionar(count($this->registros)-1);
  }
  
  function siguiente(){
	return $this->seleccionar($this->reg_no+1);
  }
  
  function anterior(){
	return $this->seleccionar($this->reg_no-1);
  }
  
  function nuevo(){ 
   foreach($this->registro as $indice => $valor)
    $this->registro[$indice]['value'] = NULL;
  }
  
   function seleccionar($reg_nro = 0){	   
	if (count($this->registros) > 0 && 
	    $reg_nro >= 0 && 
		$reg_nro < count($this->registros)){
			foreach ($this->registro as $indice => $valor){			
				$this->registro[$indice]['value'] = $this->registros[$reg_nro][$indice];
			}
			$this->reg_no = $reg_nro;
			return true;
			
	}
	else return false;	
  }
  
  function traerRegistros(){  
   $parametros = $this->cargarParametros();
   if (!$this->procedimiento){
	$this->registros = $this->conexion->fetch_array($this->conexion->consultar("select * from ".get_class($this)."$parametros"));  
	$this->seleccionar(0);   
   }else $this->conexion->consultar('execute procedure '.get_class($this).$parametros);	
  }
 
  function registrosXML(... $otros){
	  $nombre = get_class($this);
	  $respuesta = "<$nombre>";
		
		foreach($this->registros as $registro){
			$respuesta .= '<registro>';
			foreach($registro as $campo => $valor){
				$metodo = 'get'.ucfirst(strtolower($campo));				
				if (method_exists($this, $metodo)) $valor = $this->$metodo();
				$respuesta.="<$campo>$valor</$campo>";
			}
			foreach($otros as $campo){
				$metodo = 'get'.ucfirst(strtolower($campo));				
				if (method_exists($this, $metodo)){
					$valor = $this->$metodo();
					if (is_array($valor))  
						foreach($valor as $indice => $dato)
							$respuesta.="<$campo$indice>$dato</$campo$indice>";								
					else
						$respuesta.="<$campo>$valor</$campo>";
				} 
			}
			$respuesta.='</registro>';
		}			
		
		$respuesta .= "</$nombre>";		
		
		header("content-type: text/xml");
		echo utf8_encode($respuesta);
  }
  
  function cantidad(){
	  return count($this->registros);
  }
 
  function mostrarFecha($fecha){
	  if (strtotime($fecha))  return date_format(new DateTime($fecha), 'd/m/Y');
	  else return '';
  }
  
  function mostrarHora($fecha){
	  if (strtotime($fecha))  return date_format(new DateTime($fecha), 'H:i');
	  else return '';
  }
  
  function autoAsign($array){
	  foreach($array as $indice => $valor)
		$this->registro[$indice] = $valor;
  }
  
  protected function getChilds($tabla, $campo=null){
	  	$retorno = array();
		$hijos = new $tabla($this->conexion);
		if ($campo === null){
			$campo = strtoupper(get_class($this)).'_'.strtoupper($this->llavePrimaria);
		}				
		$llavePrimaria = $this->llavePrimaria;
		
		$hijos->buscar($campo, $this->$llavePrimaria);		
		$llavePrimariaHijo = $hijos->getPrimaryKey();
		for ($i=0; $i<$hijos->cantidad(); $i++){			
			$hijoAux = clone $hijos;			
			array_push($retorno, $hijoAux);
			$hijos->siguiente();
		}		
		return $retorno;
  }
  
  function iterador(){
	  $resultado = array();
	  for($i=0;$i<$this->cantidad(); $i++){
		  array_push($resultado, clone $this);
		  $this->siguiente();
	  }
	  return $resultado;
  }
  
  protected function getParent($tabla, $campo = null){	  
	  $padre = new $tabla($this->conexion);
	  $padre->buscar($padre->getPrimaryKey(), $this->$campo);
	  return $padre;
  }
  
  function getPrimaryKey(){
	  return $this->llavePrimaria;
  }
  
  function __wakeup(){
	  $dbcnx = configuraciones::getDBConfiguracion();
	  $this->conexion = new moduloDeDatos($dbcnx['servidor'], $dbcnx['nomb_db'], $dbcnx['usr'], $dbcnx['pass']);
  }
  
  abstract protected function getCampos();
  abstract function insertar($retorno=NULL);
  abstract function eliminar();
  abstract function modificar();
  abstract function leerBlob($campo);
  abstract function crearFecha($fecha);
}
?>