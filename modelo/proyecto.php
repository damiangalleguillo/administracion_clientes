<?php
use Usuario\usuario;

class proyecto extends dataset{

	function getId(){
 		return $this->ID;
	}

	function setId($Id){
 		$this->ID = $Id;
	}
	
	function setFecha($fecha){
		$this->FECHA = $fecha;
	}
	
	function getFecha(){
		return date('Y-m-d', strtotime($this->FECHA));
	}
	
	function getFechaUser(){
		return date('d/m/Y', strtotime($this->FECHA));
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
	
	function setBaja($baja){
		$this->BAJA = $baja;
	}
	
	function isBaja(){
		return $this->BAJA;
	}
	
	function addFactura($hdc, $hdi, $hcc, $hci, $fecha){
		$factura = new factura($this->conexion);
		$factura->setFecha($fecha);
		$factura->setHs_capacitacion_cant($hcc);
		$factura->setHs_capacitacion_imp($hci);
		$factura->setHs_desararollo_cant($hdc);
		$factura->setHs_desararollo_imp($hdi);
		$factura->setTipo($this->getTipoFact());
		$factura->setProyecto_id($this->ID);
		$factura->setUsuario_id(usuario::getId());
		$factura->setAnulado(false);
		$factura->insertar();
	}
	
	function getFacturas(){
		$retorno = array();
		$facturas = $this->getChilds('factura', 'PROYECTO_ID');
		foreach($facturas as $factura)
			if (!$factura->isAnulado()) array_push($retorno, clone $factura);
			
		return $retorno;
	}
	
	private function getTipoFact(){
		switch($this->getCliente()->getCondicion()){
			case 0: return 'B'; break;
			case 1: return 'B'; break;
			case 2: return 'A'; break;
			case 3: return 'B'; break;
		}
	}

}
?>