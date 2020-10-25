<?php

require_once __DIR__.'/dataset.php';
use Configuraciones\configuraciones;

class moduloDeDatos{
 
	private $conexion;

	function __construct($server, $database, $usuario, $password){
		$this->conexion = $this->conectar($server, $database, $usuario, $password);
	}
	
	private function conectar($svr, $db, $usr, $pass){
	
		$conexion = new mysqli($svr, $usr, $pass, $db);
		if ($conexion) return $conexion;
		else return false;
	}
 
	function cerrar(){
		mysqli_close($this->conexion);
	}
	
	public function startTransaction(){
		$this->conexion->autocommit(false);		
	}
	
	public function endTransactionRollback(){
		$this->conexion->rollback();
		$this->conexion->autocommit(true);		
	}
	
	public function endTransactionCommit(){
		$this->conexion->commit();
		$this->conexion->autocommit(true);		
	}
	
	private function getBindTipo($mysqlTipo){			
		
		switch($mysqlTipo){
			case 'varchar': return 's';
								  break;
			case 'date': return 's';
							  break;
			case 'time': return 's';
							  break;
			case 'timestamp': return 's';
							  break;
			case 'datetime': return 's';
							  break;
			case 'int': return 'i';
						   break;
			case 'smallint': return 'i';
						   break;
			case 'tinyint': return 'i';
						   break;
			case 'decimal': return 'd';
								  break;
			case 'blob': return 's';
								  break;								  
		}
	}
 
	function consultar($consulta, $registro = null){				
			$tipos = '';						
			$stmt = $this->conexion->prepare($consulta);
			$parametros = array();
			if (isset($registro)){				
				foreach($registro as $valor){				
					$tipos .= $this->getBindTipo($valor['type']);
					$parametros[] = &$valor['value'];	
				}
							
				array_unshift($parametros, $tipos);				
				if ($stmt) call_user_func_array(array($stmt, 'bind_param'), $parametros);
				else return;
			}
			
			$stmt->execute();
			return $stmt->get_result();
			$stmt->close();
	}
	
	function ultimo_id(){
		return $this->conexion->insert_id;
	}
 
	function fetch_array($result_query){
	
		$result = array();
		$i = 0;	
		while($fetch = mysqli_fetch_assoc($result_query)){
			$result[$i] = array();
			foreach ($fetch as $campo => $valor)
				$result[$i][$campo]=$valor;	 
			$i++;
		}		
		return $result;
	}
 
}
?>