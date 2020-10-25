<?php
class usuariosController extends controlador{

	function index(){
		$usuarios = $this->modelo;
		$usuarios->traerRegistros();
		$this->vistaVars['usuarios'] = $usuarios;
	}

}
?>