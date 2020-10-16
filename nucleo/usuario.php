<?php
namespace Usuario{
use Configuraciones\configuraciones;
	class usuario{
			
			static function getId(){				
				return isset($_SESSION[configuraciones::getProject('nombre')]['ID'])? $_SESSION[configuraciones::getProject('nombre')]['ID'] : null;
			}
			
			static function setId($id){
				$_SESSION[configuraciones::getProject('nombre')]['ID'] = $id;
			}
			
			static function getObj(){
				return unserialize($_SESSION[configuraciones::getProject('nombre')]['OBJ']);
			}
			
			static function setObj($obj){
				$_SESSION[configuraciones::getProject('nombre')]['OBJ'] = serialize($obj);
			}
			
			static function cerrarSesion(){
				$_SESSION[configuraciones::getProject('nombre')]['ID'] = null;
				$_SESSION[configuraciones::getProject('nombre')]['OBJ'] = null;
			}
			
			static function genToken(){								
				if (!isset($_SESSION[configuraciones::getProject('nombre')]['SECURITY']))
					$_SESSION[configuraciones::getProject('nombre')]['SECURITY'] = rand();
				return password_hash($_SESSION[configuraciones::getProject('nombre')]['SECURITY'], PASSWORD_DEFAULT);
			}
			
			static function checkToken($token){						
				return password_verify($_SESSION[configuraciones::getProject('nombre')]['SECURITY'], $token);
			}
	}

}
?>
