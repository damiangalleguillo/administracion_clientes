<?php
class clienteController extends controlador{

	function index($busqueda=''){
		$clientesList = '';
		$clientes = new cliente($this->conexion);
		$clientes->buscar('APELLIDO', $busqueda, false, true);
		foreach($clientes->iterador() as $c){
			$clientesList .= $this->prepararVista('cliente.lineacliente', array('cliente' => $c));
		}
		
		$this->vistaVars = array('clientesList' => $clientesList);
	}
	
	
	function guardarCliente($dni, $apellido, $nombres){
		$cliente = new cliente($this->conexion, $dni, $apellido, $nombres);
		
		$this->redireccionar('cliente');
	}
	
	function getCliente($id){
		$cliente = new cliente($this->conexion);
		$cliente->buscar('ID', $id);
		$cliente->registrosXML();
	}

}
?>
