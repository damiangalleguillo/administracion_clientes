<?php
class proyectoController extends controlador{

	function index($busqueda = ''){
		$proyectos = $this->modelo;
		$proyectos->buscar('NOMBRE', $busqueda, false, true);
		$clientes = new cliente($this->conexion);
		$clientes->traerRegistros();
		$this->vistaVars['proyectos']  = $proyectos;
		$this->vistaVars['clientes']  = $clientes;
		$this->vistaVars['fecha']  = date('Y-m-d');	
		$this->vistaVars['busqueda']  = $busqueda;	
		
	}
	
	function guardarProyecto($id, $nombre, $descripcion, $cliente_id){
		$cliente = new cliente($this->conexion);
		if ($cliente->buscar('ID', $cliente_id)) $cliente->updateProyecto($id, $nombre, $descripcion);
		$this->redireccionar('proyecto');
	}
	
	function getProyecto($id){
		$proyecto = new proyecto($this->conexion);
		if ($proyecto->buscar('ID', $id)) $proyecto->registrosXML();
	}

}
?>