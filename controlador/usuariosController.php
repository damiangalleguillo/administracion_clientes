<?php
class usuariosController extends controlador{

	function index(){
		$usuarios = $this->modelo;
		$usuarios->traerRegistros();
		$this->vistaVars['usuarios'] = $usuarios;
	}
	
	function getUsuarios($id){
		$usuarios = $this->modelo;
		if ($usuarios->buscar('ID', $id)){
			$usuarios->registrosXML();
		} 
	}
	
	function guardarUsuarios($id, $nombre, $pass, $email){
		$usuarios = $this->modelo;
		if (!$usuarios->buscar('ID', $id)) $usuarios->nuevo();
		$usuarios->setNombre($nombre);
		$usuarios->setPass($pass);
		$usuarios->setEmail($email);
		if ($usuarios->getId() == null) $usuarios->insertar();
		else $usuarios->modificar();
		$this->redireccionar('usuarios');
	}

}
?>