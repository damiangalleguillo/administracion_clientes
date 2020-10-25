<?php
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

	function getCliente_id(){
 		return $this->CLIENTE_ID;
	}

	function setCliente_id($Cliente_id){
 		$this->CLIENTE_ID = $Cliente_id;
	}
	
	function getClienteNombre(){
		return $this->getParent('cliente', 'CLIENTE_ID')->getNombreCompleto();
	}


}
?>