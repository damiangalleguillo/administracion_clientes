<?php
use Usuario\usuario;
class proyectoController extends controlador{

	function index($busqueda = ''){
		if (!usuario::getObj()->getPermiso('admproyectos')) $this->redireccionar('#');
		$proyectos = $this->modelo;
		$proyectos->buscar('BAJA', false);
		$proyectos->buscar('NOMBRE', $busqueda, false, true);
		$clientes = new cliente($this->conexion);
		$clientes->buscar('BAJA', false);
		$this->vistaVars['proyectos']  = $proyectos;
		$this->vistaVars['clientes']  = $clientes;
		$this->vistaVars['fecha']  = date('Y-m-d');	
		$this->vistaVars['busqueda']  = $busqueda;			
		$this->vistaVars['clt_id']  = '\'\'';	
	}
	
	function guardarProyecto($id, $fecha, $nombre, $descripcion, $cliente_id){
		$cliente = new cliente($this->conexion);
		if ($cliente->buscar('ID', $cliente_id)) $cliente->updateProyecto($id, $fecha, $nombre, $descripcion);
		$this->redireccionar('proyecto');
	}
	
	function getProyecto($id){
		$proyecto = new proyecto($this->conexion);
		if ($proyecto->buscar('ID', $id)) $proyecto->registrosXML();
	}
	
	function nuevoProyecto($id){
		$this->vistaVars['clt_id']  = $id;	
		
	}
	
	function eliminarProyecto($id){
		$proyecto = new proyecto($this->conexion);
		if ($proyecto->buscar('ID', $id)){
			$proyecto->setBaja(true);
			$proyecto->modificar();
		}
		$this->redireccionar('proyecto');	
	}
	
	function getFacturasProyecto($id){
		$respuesta = array();
		$proyecto = new proyecto($this->conexion);
		$proyecto->buscar('ID', $id);
		foreach($proyecto->getFacturas() as $factura){
			array_push($respuesta, array('id' => $factura->getId(), 'numero' => $factura->getNumero(), 'fecha' => $proyecto->getFecha(),
														'cliente' => $factura->getCliente()->getNombreCompleto(), 'importe' => $factura->getImporte()));
		}
		
		echo json_encode($respuesta);
	}

}
?>