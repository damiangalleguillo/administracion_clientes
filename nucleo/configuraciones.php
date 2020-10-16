<?php
namespace Configuraciones{
		
	abstract class configuraciones{

		private static function leerConfiguraciones(){
			$confFile = __DIR__.'/config.ini';
			if (file_exists($confFile))
				return parse_ini_file($confFile, true);
		}
	
		static function getDBConfiguracion(){
			$configuracion = self::leerConfiguraciones();
			$entorno = $configuracion['proyecto']['entorno'];			
			return  $configuracion[$entorno];
		}
	
		static function getConfig($campo){
			$configuracion = self::leerConfiguraciones();
			$entorno = $configuracion['proyecto']['entorno'];			
			return  $configuracion[$entorno][$campo];
		}
	
		static function getProject($field){
			$configuracion = self::leerConfiguraciones();		
			return  $configuracion['proyecto'][$field];
			}

		}
}


?>