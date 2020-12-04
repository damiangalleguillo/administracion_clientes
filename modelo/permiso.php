<?php
class permiso extends dataset{

	function getId(){
 		return $this->ID;
	}

	function setId($Id){
 		$this->ID = $Id;
	}

	function getUsuario_id(){
 		return $this->USUARIO_ID;
	}

	function setUsuario_id($Usuario_id){
 		$this->USUARIO_ID = $Usuario_id;
	}

	function getPermiso(){
 		return $this->PERMISO;
	}

	function setPermiso($Permiso){
 		$this->PERMISO = $Permiso;
	}

	function getActivo(){
 		return $this->ACTIVO;
	}

	function setActivo($Activo){
 		$this->ACTIVO = $Activo;
	}


}
?>