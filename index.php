<?php
include 'nucleo/indexController.php';
use Usuario\usuario;
use Ruta\ruta;

class indexAplication extends indexController{
	function index(){		
		parent::index();
		$this->setTitulo('Clientes');
		$usuario = '';
		if (!empty(usuario::getId())){
			$usuario = usuario::getObj()->getNombre();
			$contenido = $this->contenido();
		} 
		else $contenido = (new loginController($this->conexion))->vista();
		
		$this->vistaVars = array('contenido' => $contenido, 'usuario' => $usuario);
		
		
		echo $this->vista();	
	}
}

new indexAplication();
?>

