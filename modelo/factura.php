<?php
class factura extends dataset{

	function getId(){
 		return $this->ID;
	}

	function setId($Id){
 		$this->ID = $Id;
	}

	function getFecha(){
 		return $this->FECHA;
	}

	function setFecha($Fecha){
 		$this->FECHA = $Fecha;
	}

	function getHs_capacitacion_cant(){
 		return $this->HS_CAPACITACION_CANT;
	}

	function setHs_capacitacion_cant($Hs_capacitacion_cant){
 		$this->HS_CAPACITACION_CANT = $Hs_capacitacion_cant;
	}

	function getHs_capacitacion_imp(){
 		return $this->HS_CAPACITACION_IMP;
	}

	function setHs_capacitacion_imp($Hs_capacitacion_imp){
 		$this->HS_CAPACITACION_IMP = $Hs_capacitacion_imp;
	}

	function getHs_desararollo_cant(){
 		return $this->HS_DESARAROLLO_CANT;
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


}
?>