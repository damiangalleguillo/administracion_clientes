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
	
	function eliminarPermisos(){
		$permisos = new permiso($this->conexion);
		$permisos->buscar('USUARIO_ID', $this->getId());
		foreach($permisos->iterador() as $permiso){
			$permiso->setActivo(false);
			$permiso->modificar();
		}
	}
	
	function addPermiso($permiso){
		$permisos = new permiso($this->conexion);
		$permisos->buscar('USUARIO_ID', $this->getId());
		if(!$permisos->buscar('PERMISO', $permiso)) $permisos->nuevo();
		$permisos->setUsuario_id($this->getId());
		$permisos->setPermiso($permiso);
		$permisos->setActivo(true);
		
		if(empty($permisos->getId())) $permisos->insertar();
		else $permisos->modificar();
	}
	
	function getPermisos(){
		$resultado = array();
		
		$permisos = new permiso($this->conexion);
		$permisos->buscar('USUARIO_ID', $this->getId());
		foreach($permisos->iterador() as $permiso){
			$resultado[$permiso->getPermiso()] = $permiso->getActivo();
		}
		return $resultado;
	}

	function getPermiso($permiso){
		$permisos = new permiso($this->conexion);
		$permisos->buscar('USUARIO_ID', $this->getId());
		$permisos->buscar('PERMISO', $permiso);
		
		return $permisos->getActivo();
	}
}
?>