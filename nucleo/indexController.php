<?php
session_start();
require_once 'nucleo/usuario.php';
require_once 'nucleo/enrutador.php';
require_once 'nucleo/controlador.php';
require_once 'nucleo/dialogo.php';
require_once 'nucleo/configuraciones.php';

use Configuraciones\configuraciones;
use Usuario\usuario;
use Ruta\ruta;

$dbcnx = configuraciones::getDBConfiguracion();
require_once 'nucleo/db/'.$dbcnx['motor_db'].'/db.php';		

$controladores = scandir('controlador');
foreach($controladores as $archivo){
	$arch = 'controlador/'.$archivo;
	if (is_file($arch)) require_once $arch;
}

$modelos = scandir('modelo');
foreach($modelos as $archivo){
	$arch = 'modelo/'.$archivo;
	if (is_file($arch)) require_once $arch;
}

class indexController extends controlador{
	function index(){
		date_default_timezone_set("America/Argentina/Tucuman");	
		
	}
	
	function contenido(){
		$controlador = ruta::getControlador();
		if ($controlador != 'indexController' && class_exists($controlador)){
			$controller = new $controlador($this->conexion);
			$this->setTitulo($controller->getTitulo());
			return $controller->vista();
		} 
		else if ($controlador==='') return $this->prepararVista('index.carrusel');
			   else return "controlador <b>$controlador</b> no definido";	
	}	
	
	public function prepararVista($vista, $variables=array()){
		$contenido = parent::prepararVista($vista, $variables);
		$contenido = $this->antiCSRF($contenido);
		$contenido = $this->setCabecera($contenido);
		return $contenido;
	}

	private function antiCSRF($contenido){
		$token = '<input type="hidden" name="_token" value="'.usuario::genToken().'">';
		
		preg_match_all('/<form[\S\s]+?\n/', $contenido, $array, PREG_SET_ORDER);
		foreach($array as $indice => $cadena){
			foreach($cadena as $valor){						
				if (stripos($valor, 'post') && !stripos($valor, 'input'))
					$contenido = str_replace($valor, $valor.' '.$token, $contenido);
			}				
		}
		return $contenido;
	}
	
	private function setCabecera($contenido){				
		
		$miTitulo = '<BASE href="'.ruta::getBaseURL().'"> '.'<title>'.$this->getTitulo().'</title>';
		
		if (preg_match('/<title[A-Za-z="\/ ->]*/', $contenido, $array) === 1){			
			$contenido = str_replace($array[0], $miTitulo, $contenido);
		}
		else{			
			if (preg_match('/<head[A-Za-z="\/ ->]*/', $contenido, $array) === 1){				
				$contenido = str_replace($array[0], $array[0].' '.$miTitulo, $contenido);
			}	
		}		
		return  $contenido;
	}
}
?>