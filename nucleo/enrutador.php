<?php
/*
	modificado desde el sitio: 
	http://www.weblantropia.com/2016/07/28/enrutamiento-urls-htaccess-php/
	
*/


namespace Ruta{
	
	class ruta{
		static function getControlador(){
			$ruta = new Route();
			return $ruta->controlador? $ruta->controlador.'Controller':'';
		}
		
		static function getHost(){
			$ruta = new Route();
			return $ruta->host? $ruta->host:'';
		}
		
		static function getBaseURL(){
			$ruta = new Route();
			$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://");
			return $ruta->host? $actual_link.$_SERVER['HTTP_HOST'].$ruta->host:'';
			
		}
		
		static function getMetodo(){
			$ruta = new Route();
			return $ruta->metodo? $ruta->metodo:'';
		}			
		
	}

	class Route {
		private $basepath;
		private $uri;
		private $base_url;		
		private $routes;
		private $route;
		private $secciones;
	
		function __construct(){
			$this->cargarRutas();
		}

		private function getCurrentUri(){
			if (empty($_SERVER['REQUEST_URI'])) return '';
			else{
				$this->basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
				$this->uri = substr($_SERVER['REQUEST_URI'], strlen($this->basepath));
				if (strstr($this->uri, '?')) $this->uri = substr($this->uri, 0, strpos($this->uri, '?'));
				$this->uri = '/' . trim($this->uri, '/');
				return $this->uri;
			}
			
		}

		private function getRoutes(){
			$this->base_url = $this->getCurrentUri();
			$this->routes = explode('/', $this->base_url);
			return $this->routes;
		}
	
		private function cargarRutas(){
			$arrayRuta = $this->getRoutes();
			$this->secciones['host'] = $this->basepath;
			$this->secciones['controlador'] = isset($arrayRuta[1])?$arrayRuta[1]:'';
			$this->secciones['metodo'] = isset($arrayRuta[2])?$arrayRuta[2]:'';;
		}
	
		function __get($nombre){
			return $this->secciones[$nombre]; 
		}
	}

}



?>