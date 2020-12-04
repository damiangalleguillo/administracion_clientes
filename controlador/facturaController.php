<?php
use Usuario\usuario;
class facturaController extends controlador{

	function index(){
		if (!usuario::getObj()->getPermiso('admfacturas')) $this->redireccionar('#');
		
		$facturas = $this->modelo;
		$facturas->traerRegistros();
		
		$clientes = new cliente($this->conexion);
		$clientes->buscar('BAJA', false);
		
		$this->vistaVars['facturas'] = $facturas;
		$this->vistaVars['clientes'] = $clientes;
		$this->vistaVars['fecha'] = date('Y-m-d');
	}
	
	function guardarFactura($proyecto_id, $hdc, $hdi, $hcc, $hci, $fecha){
		$proyecto = new proyecto($this->conexion);
		if($proyecto->buscar('ID', $proyecto_id)){
			$proyecto->addFactura($hdc, $hdi, $hcc, $hci, $fecha);
		}		
		$this->redireccionar('factura');
	}
	
	function anularFactura($id){
		$factura = new factura($this->conexion);
		if($factura->buscar('ID', $id)){
			$factura->setAnulado(true);
			$factura->modificar();
		}	
		$this->redireccionar('factura');
	}
	
	function proyectosFactura($cliente_id){
		$cliente = new cliente($this->conexion);
		$respuesta = array();
		if($cliente->buscar('ID', $cliente_id)){
			foreach($cliente->getProyectos() as $proyecto){
				$respuesta[] = array('id' => $proyecto->getId(), 'nombre' => $proyecto->getNombre());
			}
		}
		echo json_encode($respuesta);
	}

}
?>