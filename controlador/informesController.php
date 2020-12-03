<?php
class informesController extends controlador{

	function index(){
		$this->proyectosSinFacturar();
		$this->proyectosPorPeriodo();
		$this->proyectosPorCliente();
		$this->ingresosPorCliente();
	}
	
	private function proyectosSinFacturar(){
		$proyectos = new proyecto($this->conexion);
		$proyectos->traerRegistros();
		$total = $proyectos->cantidad();
		$sinfact = 0;
		$proyectSinFac = array(); 
		foreach($proyectos->iterador() as $proyecto){
			if (count($proyecto->getFacturas()) == 0){
				$sinfact++;
				array_push($proyectSinFac, clone $proyecto);
			}
		}
		
		$this->vistaVars['sinfacturar'] = (int) ($sinfact * 100 / $total);
		$this->vistaVars['sinfacturar_fact'] = $proyectSinFac;
	}
	
	private function proyectosPorPeriodo(){
		$proyectosxperiodo = new proyectosxperiodo($this->conexion);
		$proyectosxperiodo->traerRegistros();
		$this->vistaVars['proyectporperiod'] = $proyectosxperiodo->iterador();
	}
	
	private function proyectosPorCliente(){
		$clientes = new cliente($this->conexion);
		$clientes->buscar('BAJA', 0);		
		$proyectos = array(); 
		foreach($clientes->iterador() as $cliente){
			array_push($proyectos, clone $cliente);
		}		
		$this->vistaVars['proyectosxcliente'] = $proyectos;
	}
	
	private function ingresosPorCliente(){
		$clientes = new cliente($this->conexion);
		$clientes->buscar('BAJA', 0);		
		$proyectos = array(); 
		foreach($clientes->iterador() as $cliente){
			array_push($proyectos, clone $cliente);
		}		
		$this->vistaVars['ingresosxcliente'] = $proyectos;
	}

}
?>