<?php
use Usuario\usuario;
class loginController extends controlador{

	function index(){		
		$this->vistaVars['error'] = '';
		$this->vistaVars['usr'] = '';
	}
	
	function iniciarLogin($usr, $pass){
		$this->vistaVars['usr'] = $usr;
		$usuario = new usuarios($this->conexion);
		if($usuario->buscar('EMAIL', $usr)){
			if ($usuario->checkPass($pass)){
				usuario::setId($usuario->getId());
				usuario::setObj($usuario);
				$this->redireccionar('#');
			}else $this->vistaVars['error'] = '*Contraseña no Valida';
		}else $this->vistaVars['error'] = '*Usuario no Registrado';
	}
	
	function cerrarLogin(){
		usuario::setId(null);
		usuario::setObj(null);
		$this->redireccionar('#');		
	}

}
?>