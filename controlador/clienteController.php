<?php
class clienteController extends controlador{

	function index($busqueda=''){
		$clientesList = '';
		$clientes = new cliente($this->conexion);
		$clientes->buscar('APELLIDO', $busqueda, false, true);		
		//$clientes->buscar('NOMBRES', $busqueda, false, true);		
		$this->vistaVars = array('clientes' => $clientes);
	}
	
	
	function guardarCliente($id, $dni, $apellido, $nombres, $email, $telefono, $domicilio, $localidad, $cuit, $condicion){
		$cliente = new cliente($this->conexion);
		if (!$cliente->buscar('ID', $id)) $cliente->nuevo();		
		$cliente->setDni($dni);
		$cliente->setApellido($apellido);
		$cliente->setNombres($nombres);
		$cliente->setEmail($email);
		$cliente->setTelefono($telefono);
		$cliente->setDomicilio($domicilio);
		$cliente->setLocalidad($localidad);
		$cliente->setCuit($cuit);
		$cliente->setCondicion($condicion);
		if ($cliente->getId() == null) $cliente->insertar();
		$cliente->modificar();
		
		$this->redireccionar('cliente');
	}
	
	function getCliente($id){
		$cliente = new cliente($this->conexion);
		$cliente->buscar('ID', $id);
		$cliente->registrosXML();
	}

}
?>
