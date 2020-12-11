<?php
use Configuraciones\configuraciones;
use Ruta\ruta;
use Usuario\usuario;

abstract class controlador{
	
	private $vista;
	private $nombre;
	private $titulo;
	protected $modelo;
	protected $conexion;
	public $vistaVars = array();
	
	function __construct($cnx=null){
		/*set_exception_handler(array($this, 'exceptionManager'));
		set_error_handler(array($this, 'errorManager'));
		*/
		try{
			if ($cnx===null){
				$dbcnx = configuraciones::getDBConfiguracion();
				$this->conexion = new moduloDeDatos($dbcnx['servidor'], $dbcnx['nomb_db'], $dbcnx['usr'], $dbcnx['pass']);
			}
			else{
				$this->conexion = $cnx;
			}
		}catch(Exception $e){
			throw new Exception('No se pudo crear la conexion con la base de datos');
		}
		
		
		$this->nombre = substr(get_class($this), 0, -10);		
		$this->titulo = $this->nombre;
		$this->vista = 'vista/'.$this->nombre.'.html';			
		$clase = 'modelo/'.$this->nombre;
		
		if (file_exists($clase.'.php')){
			require_once $clase.'.php';
			$this->modelo = new $this->nombre($this->conexion);
		}
		$this->ejecutarMetodo($this, 'index');
		if (ruta::getMetodo() != ''){
			$metodo = ruta::getMetodo().ucfirst($this->nombre);			
			if (method_exists($this, $metodo)) $this->ejecutarMetodo($this, $metodo);			
		}		
		
	}
	
	private function ejecutarMetodo($clase, $metodo, $parametros = array()) {		
		/*if (count($_POST)>0 && (!isset($_POST['_token']) || !usuario::checkToken($_POST['_token']))){
			echo "error de seguridad";
			die();
		} */
		
		$miMetodo = new ReflectionMethod($clase, $metodo);
		
		foreach ($miMetodo->getParameters() as $param) {			
			if (isset($_GET[$param->name]))
				$parametros[] = $_GET[$param->name];   
			else 
				if (isset($_POST[$param->name]))
					$parametros[] = $_POST[$param->name];
		}		
		
		foreach($parametros as $param){
			if (is_array($param)) foreach($param as $p) $p = htmlspecialchars($p, ENT_QUOTES, 'UTF-8');
			else $param = htmlspecialchars($param, ENT_QUOTES, 'UTF-8');
		}
		return $miMetodo->invokeArgs($clase, $parametros);
	}
	
	public function setTitulo($titulo){
			$this->titulo = $titulo;
	}
	
	public function getTitulo(){
			return $this->titulo;
	}
	
	public function vista(){
		if (!isset($_GET['noweb'])) 
			if (file_exists($this->vista)) return $this->prepararVista($this->nombre, $this->vistaVars);		
			else return '';
	}
	
	private function getValorVariableVista($variable, $variables = array()){			
		$varInicial = $variable;
		$posFlecha = strpos($variable, '->');
		if ($posFlecha !== false){
			$objeto = substr($variable, 0, $posFlecha);				
			$atributo = substr($variable, $posFlecha+2);
			$parametros = array();
			if (substr($atributo, -1, 1) == ')'){				
				$posParentesis = strpos($atributo, '(');
				$metodo = substr($atributo, 0, $posParentesis);
				$parametros = substr($atributo, $posParentesis+1, -1);				
				$parametros = explode(',', $parametros);	
				if ($parametros[0] != ''){
					foreach($parametros as $indice => $p){
						$parametros[$indice] = $this->getValorVariableVista($p, $variables);
					}
				}else $parametros = array();					
			}			
			if (isset($variables[$objeto])) return $this->ejecutarMetodo($variables[$objeto], $metodo, $parametros);
			else $variable = $objeto;
		}
		else{
			if(is_numeric($variable)) return $variable;
			else if (isset($variables[$variable])) return $variables[$variable];
		}
		echo "<b>$variable</b> no esta definida como una Variable de Vista<br>";
		return '{{'.$varInicial.'}}';
	}

	public function prepararVista($vista, $variables = array()){
			$archivo = 'vista/'.str_replace('.', '/', $vista).'.html';
			if (file_exists($archivo)){
				$recurso = fopen($archivo, 'r');
				$contenido = fread($recurso, filesize($archivo));
			}else $contenido = $vista; 			
				
			preg_match_all('/@if[\S\s]+?@endif/', $contenido, $arrayCondiciones, PREG_SET_ORDER);
			foreach($arrayCondiciones as $indice => $valor)
				foreach($valor as $condicion){					
					preg_match('/(@if\s)[\S]+/', $condicion, $variable);
					$condAux = substr($variable[0], 4);
					if ($condAux[0] == '!') $condAux = !$this->getValorVariableVista(substr($condAux, 1), $variables);
					else $condAux = $this->getValorVariableVista(substr($condAux, 0), $variables);
					if ($condAux){
						$resultado = str_replace($variable[0], '', $condicion);
						$resultado = str_replace('@endif', '', $resultado);						
						$contenido = str_replace($condicion, $resultado, $contenido);
					}						
					else $contenido = str_replace($condicion, '', $contenido);
				}			

			preg_match_all('/@foreach[\S\s]+?@endforeach/', $contenido, $arrayRepeticiones, PREG_SET_ORDER);			
			foreach($arrayRepeticiones as $indice => $valor)
				foreach($valor as $repeticion){
					preg_match('/@foreach\s[\S]+\sin\s[\S]+/', $repeticion, $parametros);
					preg_match('/@foreach\s[\S]+\sin\s/', $parametros[0], $iterable);
					
					$bloqueRepeticion = str_replace($parametros[0], '', $repeticion);					
					$bloqueRepeticion = str_replace('@endforeach', '', $bloqueRepeticion);
					
					$iterable = str_replace($iterable, '', $parametros[0]);
					$variable = str_replace($iterable, '', $parametros[0]);
					$variable = str_replace('@foreach ', '', $variable);
					$variable = trim(str_replace(' in', '', $variable));
					
					$iterable = $this->getValorVariableVista($iterable, $variables);
					$salidaRepeticion = '';					
					foreach($iterable as $index => $var){
						$salidaRepeticion .= $this->prepararVista($bloqueRepeticion, array('index' => $index, $variable => $var));						
					}
					
					$contenido = str_replace($repeticion, $salidaRepeticion, $contenido);					
				}						
				
				preg_match_all('/{{[A-Za-z->0-9_(), ;]+[\}]{2}/', $contenido, $array, PREG_SET_ORDER);							
				foreach($array as $indice => $cadena)
					foreach($cadena as $valor){
						$resultado = $this->getValorVariableVista(substr($valor, 2, -2), $variables);
						$contenido = str_replace($valor, $resultado, $contenido);					
					}
			
			
			return $contenido;
	}
	
	
	function redireccionar($url){
		
	if (substr($url, 0, 4) != 'http') $url = ruta::getBaseURL().$url;
		
   	 if (!headers_sent())
    	{    
        	header('Location: '.$url);
        	exit;
        }
    else
        {  
        	echo '<script type="text/javascript">';
        	echo 'window.location.href="'.$url.'";';
        	echo '</script>';
        	echo '<noscript>';
        	echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
        	echo '</noscript>'; exit;
    	}
	}
	
	function exceptionManager($excepcion){
		echo $this->prepararVista('index.exception', array('excepcion' => $excepcion));
	}
	
	function errorManager($errno, $errstr, $errfile, $errline){
		$e = new Exception($errstr, $errno);
		//$e->setLine($errline);
		throw $e;
	}
	
}

?>