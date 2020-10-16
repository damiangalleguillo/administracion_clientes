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
		set_exception_handler(array($this, 'exceptionManager'));
		set_error_handler(array($this, 'errorManager'));
		
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
		if (count($_POST)>0 && (!isset($_POST['_token']) || !usuario::checkToken($_POST['_token']))){
			echo "error de seguridad";
			die();
		} 
		
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

	public function prepararVista($vista, $variables = array()){
			$vista = 'vista/'.str_replace('.', '/', $vista).'.html';			
			$recurso = fopen($vista, 'r');
			$contenido = fread($recurso, filesize($vista));
			preg_match_all('/{{[A-Za-z->0-9_(), ]+[\}]{2}/', $contenido, $array, PREG_SET_ORDER);
				
			foreach($array as $indice => $cadena)
				foreach($cadena as $valor){
					$pos = strpos($valor, '->');
					if ($pos !== false){
						$objeto = substr($valor, 2, $pos-2);
						$propiedad = substr($valor, $pos+2, -2);
						if (substr($propiedad, -1, 1) == ')'){
							$parentesis = strpos($propiedad, '(');
							$cadena = substr($propiedad, $parentesis+1, -1);
							$parametros = array();
							if($cadena != '') $parametros = explode(',', $cadena);							
							foreach($parametros as $indice => $param){
								$parametros[$indice] = $variables[$param]??$param;
							}
							$metodo = substr($propiedad, 0, $parentesis);														
							$retorno = $this->ejecutarMetodo($variables[$objeto], $metodo, $parametros);
						} 
						else{
							$retorno = $variables[$objeto]->$propiedad;
						}											
						$contenido = str_replace($valor, $retorno, $contenido);
					}
					else{
						$variable = substr($valor, 2, -2);
						if (isset($variables[$variable]))
							$contenido = str_replace($valor, $variables[$variable], $contenido);
						else	echo "<b>$variable</b> no esta definida como una Variable de Vista<br>";
					}					
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
		throw new Exception($errstr, $errno);
	}
	
}

?>