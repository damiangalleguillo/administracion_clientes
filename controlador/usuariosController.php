<?php
use Usuario\usuario;
class usuariosController extends controlador{

	function index(){
		if (!usuario::getObj()->getPermiso('admusuarios')) $this->redireccionar('#');
		$usuarios = $this->modelo;
		$usuarios->traerRegistros();
		$this->vistaVars['usuarios'] = $usuarios;
	}
	
	function getUsuarios($id){
		$usuarios = $this->modelo;
		if ($usuarios->buscar('ID', $id)){
			$usuarios->registrosXML('PERMISOS');
		} 
	}
	
	function guardarUsuarios($id, $nombre, $pass, $email, $permisos){
		$usuarios = $this->modelo;		
		if (!$usuarios->buscar('ID', $id)) $usuarios->nuevo();
		$usuarios->setNombre($nombre);
		$usuarios->setPass($pass);
		$usuarios->setEmail($email);
		if ($usuarios->getId() == null) $usuarios->insertar();
		else $usuarios->modificar();
			
		$usuarios->eliminarPermisos();
		foreach($permisos as $p){
			$usuarios->addPermiso($p);
		}
		$this->redireccionar('usuarios');
	}

}
?>