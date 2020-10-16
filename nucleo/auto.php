<?php
require_once __DIR__.'/controlador.php';
require_once __DIR__.'/configuraciones.php';
require_once __DIR__.'/enrutador.php';
use Configuraciones\configuraciones;
$dbcnx = configuraciones::getDBConfiguracion();
require_once __DIR__.'/db/'.$dbcnx['motor_db'].'/db.php';		

class autoController extends controlador{
	function index(){
		
	}
	
	function crearModelo($argv){
		foreach($argv as $indice => $clase) 
			if($indice > 1){
				$nombre = '../modelo/'.$clase.'.php';
				if (!file_exists($nombre)){
				$archivo = fopen($nombre, 'w+');				
				fwrite($archivo, "<?php\nclass $clase extends dataset{\n\n}\n?>");		
				fclose($archivo);
				include $nombre;
				$obj = new $clase($this->conexion);
				$archivo = fopen($nombre, 'w+');
				fwrite($archivo, "<?php\nclass $clase extends dataset{\n\n");		
				foreach($obj->getFieldsList() as $campo){
					$metodo = ucwords(strtolower($campo));
					$parametro = strtoupper($campo);
					fwrite($archivo, "\tfunction get$metodo(){\n ");
					
					fwrite($archivo, "\t\treturn \$this->$parametro;\n\t}\n\n");		
					
					$campo = ucwords(strtolower($campo));
					fwrite($archivo, "\tfunction set$metodo(\$$metodo){\n ");
					$campo = strtoupper($campo);
					fwrite($archivo, "\t\t\$this->$parametro = \$$metodo;\n\t}\n\n");
				}	
				fwrite($archivo, "\n}\n?>");
				printf("$clase se creo satisfactoriamente en $nombre\n");
			}else printf("Archivo Existente no se puede sobreescribir.\n");
		} 	
	}	
	
	function crearControlador($argv){
		foreach($argv as $indice => $controlador) 
			if($indice > 1){
				$nombre = '../controlador/'.$controlador.'Controller.php';
				if (!file_exists($nombre)){
					$archivo = fopen($nombre, 'w+');					
					fwrite($archivo, "<?php\nclass ".$controlador."Controller extends controlador{\n\n\tfunction index(){\n\n\t}\n\n}\n?>");		
					fclose($archivo);
					printf("$controlador se creo satisfactoriamente en $nombre\n");					
				}else printf("Archivo Existente no se puede sobreescribir.\n");
			} 	
	}	

}

if (count($argv) > 1){
	$auto = new autoController();
	switch($argv[1]){
		case 'modelar': $auto->crearModelo($argv);
								break;
		case 'controlador': $auto->crearControlador($argv);
										break;
	}
} 

//

?>