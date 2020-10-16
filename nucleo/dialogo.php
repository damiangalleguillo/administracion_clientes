<?php
class dialogo{
	private $ventana;
	private $mensaje;
	private $titulo;
	private $tipo;
	private $botones = '';
	public const MSG_ERROR = 0;
	public const MSG_PRECAUCION = 1;
	public const MSG_INFORMACION = 2;
	public const MSG_DIALOGO = 3;
	
	public const BTN_CERRAR = 0;
	
	
	function __construct($msg, $titulo, $tipo){
		$this->mensaje = $msg;
		$this->titulo = $titulo;
		$this->tipo = $tipo;
	}
	
	function setMensaje($msg){
		$this->mensaje = $msg;
	}
	
	function setTipo($tipo){
		$this->tipo = $tipo;
	}
	
	function setTitulo($titulo){
		$this->titulo = $titulo;
	}
	
	function agregarBoton($nombre, $accion){
		
		switch ($accion){
			case 'BTN_CERRAR': $accion = 'ocultar(\'msgBox\')'; break;		
		}
		
		$this->botones .= "<button onclick=\"$accion\">$nombre</button>
		";
	}
	
	function mostrarDialogo(){
		if ($this->botones == '') $this->agregarBoton('Ok', dialogo::BTN_CERRAR);
		$this->ventana = 
		"<div id='msgBox' class = 'ventana'>
			<div class = 'ventana-titulo' data-ventanafija>$this->titulo</div>
			<div class='dialogo-icono dialogo-tipo$this->tipo'></div><span class='dialogo-mensaje'>$this->mensaje</span>
			$this->botones
		 </div>
		 <script type=\"text/javascript\">
			mostrar('msgBox');
		 </script>
		";
		echo $this->ventana;	
	}
	
}
?>