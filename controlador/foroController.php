<?php
class foroController extends controlador{

	function index(){

	}
	
	function crearIndicadorForo($politica, $proceso, $objetivos, $meta, $descripcion, $indicador, $nombre, $documentos, $medio, $periodicidad, $responsable_medicion){
		if ($politica && $proceso&& $objetivos&&$meta&& 
			$descripcion&& $indicador&& $nombre&& 
			$documentos&& $medio&& $periodicidad&& $responsable_medicion) echo 'registro creado satisfactoriamente';
		else echo 'error al crear';
	}

}
?>