<?php
use Usuario\usuario;
class informesController extends controlador{

	function index(){
		if (!usuario::getObj()->getPermiso('adminformes')) $this->redireccionar('#');
		$this->proyectosSinFacturar();
		$this->proyectosPorPeriodo();
		$this->proyectosPorClienteInformes();
		$this->ingresosPorClienteInformes();
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
	
	function proyectosPorPeriodo($imprimir = false){
		$proyectosxperiodo = new proyectosxperiodo($this->conexion);
		$proyectosxperiodo->traerRegistros();
		$this->vistaVars['proyectporperiod'] = $proyectosxperiodo->iterador();
	}
	
	function proyectosPorClienteInformes($imprimir = false){
		$clientes = new cliente($this->conexion);
		$clientes->buscar('BAJA', 0);		
		$proyectos = array(); 
		$cont = 0;
		foreach($clientes->iterador() as $cliente){
			array_push($proyectos, clone $cliente);
			if (!$imprimir && $cont==5) break;
			else $cont++;
		}		
		$this->vistaVars['proyectosxcliente'] = $proyectos;
		if ($imprimir){
			$impresiones = new impresionesController($this->conexion);
			$impresiones->reporteProyectosImpresiones('Cantidad de Proyectos por Cliente', $proyectos);
		}
	}
	
	function ingresosPorClienteInformes($imprimir = false){
		$clientes = new cliente($this->conexion);
		$clientes->buscar('BAJA', 0);		
		$proyectos = array(); 
		$cont = 0;
		foreach($clientes->iterador() as $cliente){
			array_push($proyectos, clone $cliente);
			if (!$imprimir && $cont==5) break;
			else $cont++;
		}		
		$this->vistaVars['ingresosxcliente'] = $proyectos;
		if ($imprimir){
			$impresiones = new impresionesController($this->conexion);
			$impresiones->reporteRankingImpresiones('Ingresos por Cliente', $proyectos);
		}
	}

}
?>