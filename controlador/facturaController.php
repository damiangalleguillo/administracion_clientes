<?php
class facturaController extends controlador{

	function index(){
		$facturas = $this->modelo;
		$facturas->traerRegistros();
		$this->vistaVars['facturas'] = $facturas;
	}

}
?>