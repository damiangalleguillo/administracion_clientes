<?php
class cliente extends dataset{

	function getId(){
 		return $this->ID;
	}

	function setId($Id){
 		$this->ID = $Id;
	}

	function getApellido(){
 		return $this->APELLIDO;
	}

	function setApellido($Apellido){
 		$this->APELLIDO = $Apellido;
	}

	function getNombres(){
 		return $this->NOMBRES;
	}

	function setNombres($Nombres){
 		$this->NOMBRES = $Nombres;
	}
	
	function getNombreCompleto(){
		return $this->APELLIDO.', '.$this->NOMBRES;
	}

	function getDni(){
 		return $this->DNI;
	}

	function setDni($Dni){
 		$this->DNI = $Dni;
	}

	function getEmail(){
 		return $this->EMAIL;
	}

	function setEmail($Email){
 		$this->EMAIL = $Email;
	}

	function getTelefono(){
 		return $this->TELEFONO;
	}

	function setTelefono($Telefono){
 		$this->TELEFONO = $Telefono;
	}

	function getDomicilio(){
 		return $this->DOMICILIO;
	}

	function setDomicilio($Domicilio){
 		$this->DOMICILIO = $Domicilio;
	}

	function getLocalidad(){
 		return $this->LOCALIDAD;
	}

	function setLocalidad($Localidad){
 		$this->LOCALIDAD = $Localidad;
	}
	
	function	setCuit($cuit){
		$this->CUIT = $cuit;
	}
	
	function	getCuit(){
		return $this->CUIT;
	}
	
	function setCondicion($condicion){
		$this->CONDICION = $condicion;
	}
	
	function getCondicion(){
		return $this->CONDICION;
	}
	
	function getCondicionText(){
		switch($this->CONDICION){
			case 0: return 'Consumidor Final'; break;
			case 1: return 'Monotributista'; break;
			case 2: return 'Responsable Inscripto'; break;
			case 3: return 'Exento'; break;
		}
	}
	
	function setBaja($baja){
		$this->BAJA = $baja;
	}
	
	function isBaja(){
		return $this->BAJA;
	}
	
	function updateProyecto($id, $fecha, $nombre, $descripcion){
		$proyectos = new proyecto($this->conexion);
		if(!$proyectos->buscar('ID', $id)) $proyectos->nuevo();		
		$proyectos->setFecha($fecha);
		$proyectos->setNombre($nombre);
		$proyectos->setDescripcion($descripcion);
		$proyectos->setCliente_id($this->ID);
		$proyectos->setBaja(false);
		if($proyectos->getId()==null) $proyectos->insertar();
		else $proyectos->modificar();
	}
	
	function getProyectos(){
		$retorno = array();
		$proyectos = $this->getChilds('proyecto', 'CLIENTE_ID');
		foreach($proyectos as $proyecto)
			if (!$proyecto->isBaja()) array_push($retorno, clone $proyecto);
			
		return $retorno;		
	}
	
	function getImporteFacturacionTotal(){
		$total  = 0;
		foreach($this->getProyectos() as $proyecto){
			foreach($proyecto->getFacturas() as $factura){
				$total += $factura->getImporte();
			}			
		}
		
		return $total;
	}
	
	function getProyectosPorc(){
		$total = 0;
		$informe = new cliente($this->conexion);
		$informe->buscar('BAJA', 0);
		foreach($informe->iterador() as $cliente){
			$total += count($cliente->getProyectos());
		}		
		return $total > 0?(int)(count($this->getProyectos()) * 100 / $total): 0;
	}
	
	function getIngresosPorc(){
		$total = 0;
		$informe = new cliente($this->conexion);
		$informe->buscar('BAJA', 0);
		foreach($informe->iterador() as $cliente){
			$total += $cliente->getImporteFacturacionTotal();
		}		
		
		return $total > 0?(int)($this->getImporteFacturacionTotal() * 100 / $total): 0;
	}


}
?>