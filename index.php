<?php
include 'nucleo/indexController.php';
use Usuario\usuario;
use Ruta\ruta;

class indexAplication extends indexController{
	function index(){		
		parent::index();
		$this->setTitulo('Clientes');
		$usuario = ''; 									
		$this->vistaVars = array('contenido' => $this->contenido(), 'usuario' => $usuario);
		
		
		echo $this->vista();	
	}
}

new indexAplication();
?>

