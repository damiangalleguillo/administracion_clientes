<?php
use Usuario\usuario;

class proyecto extends dataset{

	function getId(){
 		return $this->ID;
	}

	function setId($Id){
 		$this->ID = $Id;
	}

	function getNombre(){
 		return $this->NOMBRE;
	}

	function setNombre($Nombre){
 		$this->NOMBRE = $Nombre;
	}

	function getDescripcion(){
 		return $this->DESCRIPCION;
	}

	function setDescripcion($Descripcion){
 		$this->DESCRIPCION = $Descripcion;
	}
	
	function getCliente(){
		return $this->getParent('cliente', 'CLIENTE_ID');
	}

	function getCliente_id(){
 		return $this->CLIENTE_ID;
	}

	function setCliente_id($Cliente_id){
 		$this->CLIENTE_ID = $Cliente_id;
	}
	
	function getClienteNombre(){
		return $this->getParent('cliente', 'CLIENTE_ID')->getNombreCompleto();
	}
	
	function addFactura($hdc, $hdi, $hcc, $hci, $fecha){
		$factura = new factura($this->conexion);
		$factura->setFecha($fecha);
		$factura->setHs_capacitacion_cant($hcc);
		$factura->setHs_capacitacion_imp($hci);
		$factura->setHs_desararollo_cant($hdc);
		$factura->setHs_desararollo_imp($hdi);
		$factura->setProyecto_id($this->ID);
		$factura->setUsuario_id(usuario::getId());
		$factura->insertar();
	}

}
?>