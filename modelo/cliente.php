<?php
class cliente extends dataset{
	
	function __construct($conexion, $dni=null, $apellido=null, $nombres=null){
		parent::__construct($conexion);
		if ($dni){
			$this->nuevo();
			$this->setDni($dni);
			$this->setApellido($apellido);
			$this->setNombres($nombres);
			$this->setCuil(0);
			$this->setUsuario_id(1);
			$this->insertar();	
		}		
	}

	function getId(){
 		return $this->ID;
	}

	function setId($Id){
 		$this->ID = $Id;
	}

	function getApellido(){
 		return $this->APELLIDO;
	}

	function setApellido($Apellido){
 		$this->APELLIDO = $Apellido;
	}

	function getNombres(){
 		return $this->NOMBRES;
	}

	function setNombres($Nombres){
 		$this->NOMBRES = $Nombres;
	}

	function getDni(){
 		return $this->DNI;
	}

	function setDni($Dni){
 		$this->DNI = $Dni;
	}

	function getCuil(){
 		return $this->CUIL;
	}

	function setCuil($Cuil){
 		$this->CUIL = $Cuil;
	}

	function getUsuario_id(){
 		return $this->USUARIO_ID;
	}

	function setUsuario_id($Usuario_id){
 		$this->USUARIO_ID = $Usuario_id;
	}
	
	function getNombreCompleto(){
		return $this->APELLIDO.', '.$this->NOMBRES;
	}


}
?>