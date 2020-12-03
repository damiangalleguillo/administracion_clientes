<?php
class clienteController extends controlador{

	function index($busqueda=''){
		$clientesList = '';
		$clientes = new cliente($this->conexion);
		$clientes->buscar('BAJA', false);		
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
		$cliente->setBaja(false);
		if ($cliente->getId() == null) $cliente->insertar();
		$cliente->modificar();
		
		$this->redireccionar('cliente');
	}
	
	function getCliente($id){
		$cliente = new cliente($this->conexion);
		$cliente->buscar('ID', $id);
		$cliente->registrosXML();
	}
	
	function eliminarCliente($id){
		$cliente = new cliente($this->conexion);
		$cliente->buscar('ID', $id);
		$cliente->setBaja(true);
		$cliente->modificar();
		$this->redireccionar('cliente');
	}
	
	function getProyectosCliente($id){
		$respuesta = array();
		$cliente = new cliente($this->conexion);
		$cliente->buscar('ID', $id);
		foreach($cliente->getProyectos() as $proyecto){
			array_push($respuesta, array('id' => $proyecto->getId(), 'nombre' => $proyecto->getNombre(), 'fecha' => $proyecto->getFecha()));
		}
		
		echo json_encode($respuesta);
	}
	

}
?>
