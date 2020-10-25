<?php
class usuarios extends dataset{

	function getId(){
 		return $this->ID;
	}

	function setId($Id){
 		$this->ID = $Id;
	}

	function getEmail(){
 		return $this->EMAIL;
	}

	function setEmail($Email){
 		$this->EMAIL = $Email;
	}

	function getPass(){
 		return $this->PASS;
	}

	function setPass($Pass){
 		$this->PASS = $Pass;
	}

	function getNombre(){
 		return $this->NOMBRE;
	}

	function setNombre($Nombre){
 		$this->NOMBRE = $Nombre;
	}
	
	function checkPass($pass){
		return $this->PASS == $pass;
	}


}
?>