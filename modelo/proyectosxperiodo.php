<?php
class proyectosxperiodo extends dataset{

	function getPeriodo(){
 		return $this->PERIODO;
	}

	function setPeriodo($Periodo){
 		$this->PERIODO = $Periodo;
	}

	function getCantidad(){
 		return $this->CANTIDAD;
	}

	function setCantidad($Cantidad){
 		$this->CANTIDAD = $Cantidad;
	}
	
	function getPorcentaje(){
		$total = 0;
		$informe = new proyectosxperiodo($this->conexion);
		$informe->traerRegistros();
		foreach($informe->iterador() as $anio){
			$total += $anio->getCantidad();
		}
		
		return (int)($this->getCantidad() * 100 / $total);
	}


}
?>