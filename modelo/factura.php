<?php
class factura extends dataset{

	function getId(){
 		return $this->ID;
	}

	function setId($Id){
 		$this->ID = $Id;
	}

	function getFecha(){
 		return $this->mostrarFecha($this->FECHA);
	}

	function setFecha($Fecha){
 		$this->FECHA = $Fecha;
	}
	
	function getIva(){
		return $this->getTipo() == 'A'? 21: 0;
	}

	function getHs_capacitacion_cant(){
 		return $this->HS_CAPACITACION_CANT;
	}

	function setHs_capacitacion_cant($Hs_capacitacion_cant){
 		$this->HS_CAPACITACION_CANT = $Hs_capacitacion_cant;
	}

	function getHs_capacitacion_imp(){
 		return $this->getIva() == 0? $this->HS_CAPACITACION_IMP: $this->HS_CAPACITACION_IMP * (-1* $this->getIva() / 100);
	}

	function setHs_capacitacion_imp($Hs_capacitacion_imp){
 		$this->HS_CAPACITACION_IMP = $Hs_capacitacion_imp;
	}

	function getHs_desararollo_cant(){
 		return $this->getIva() == 0? $this->HS_DESARAROLLO_CANT: $this->HS_DESARAROLLO_CANT * (-1* $this->getIva() / 100);
	}

	function setHs_desararollo_cant($Hs_desararollo_cant){
 		$this->HS_DESARAROLLO_CANT = $Hs_desararollo_cant;
	}

	function getHs_desararollo_imp(){
 		return $this->HS_DESARAROLLO_IMP;
	}

	function setHs_desararollo_imp($Hs_desararollo_imp){
 		$this->HS_DESARAROLLO_IMP = $Hs_desararollo_imp;
	}

	function getProyecto_id(){
 		return $this->PROYECTO_ID;
	}

	function setProyecto_id($Proyecto_id){
 		$this->PROYECTO_ID = $Proyecto_id;
	}

	function getUsuario_id(){
 		return $this->USUARIO_ID;
	}

	function setUsuario_id($Usuario_id){
 		$this->USUARIO_ID = $Usuario_id;
	}
	
	function setAnulado(){
		$this->ANULADO = true;		
	}
	
	function isAnuladoTxt(){
		return $this->ANULADO?'Si':'No';
	}
	
	function getNumero(){
		$number = $this->ID;
		return '00001-'.str_repeat('0', 6-strlen($number)).$number;
	}
	
	function getCliente(){
		return $this->getProyecto()->getCliente();
	}
	
	function getClienteNombre(){
		return $this->getCliente()->getNombreCompleto();
	}
	
	function getProyecto(){
		return $this->getParent('proyecto', 'PROYECTO_ID');
	}
	
	function getProyectoNombre(){
		return $this->getProyecto()->getNombre();
	}
	
	function getTipo(){
		switch($this->getCliente()->getCondicion()){
			case 0: return 'B'; break;
			case 1: return 'B'; break;
			case 2: return 'A'; break;
			case 3: return 'B'; break;
		}
	}
	
	function getImporte(){		
		return ($this->getHs_capacitacion_cant() * $this->getHs_capacitacion_imp())+($this->getHs_desararollo_cant() * $this->getHs_desararollo_imp());
	}
}
?>