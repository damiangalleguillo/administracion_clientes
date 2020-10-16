
function AJAXCrearObjeto(){
 var xhttp;
  if (window.XMLHttpRequest) {    
    xhttp = new XMLHttpRequest();
    } else {
     alert("Navegador no soportado.");
  }
 return xhttp;  
}

function detectarNavegador(){    
    var userAgent = navigator.userAgent || navigator.vendor || window.opera;
 
    if (/android/i.test(userAgent) || /iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
         document.getElementById("botonera").className='movil';
		 document.getElementById("contenido").className='movil';
		 var botonVolver = document.getElementsByClassName("back");
		 if (botonVolver) botonVolver[0].style.display='inline';
    }	
	
}

function sender(){
	return event.srcElement;
}

function eliminarFilas(tabla){
	for(var k=tabla.getElementsByTagName("tr").length-1; k > 0; k--)
			tabla.deleteRow(k);	
}

function eliminarFila(tabla, index){
	tabla.deleteRow(index);	
}


function enter(){
 var tecla = (document.all) ? event.keyCode : event.which;
 if (tecla==13) return true;
 else return false;	
}

function key(){
 var tecla = (document.all) ? event.keyCode : event.which; 
 return tecla;
}

function fechaHoy(){
	var fecha  = new Date();
	return ("0"+(fecha.getDate())).slice(-2)+"/"+("0"+(fecha.getMonth()+1)).slice(-2)+"/"+fecha.getFullYear();
	
}

function popup(url, titulo, ancho, alto) {
 var posicion_x; 
 var posicion_y; 
 posicion_x=(screen.width/2)-(ancho/2); 
 posicion_y=(screen.height/2)-(alto/2); 
 window.open(url, titulo, "width="+ancho+",height="+alto+",menubar=0,toolbar=0,directories=0,scrollbars=no,resizable=no,left="+posicion_x+",top="+posicion_y+"");
}

function hablar($txt){
	var texto = new SpeechSynthesisUtterance($txt);
	window.speechSynthesis.speak(texto);
}

function seleccionarFila(){
	var fila = event.currentTarget;
	var tabla = fila.parentNode.parentNode;
	if (!tabla.dataset.seleccionado) tabla.dataset.seleccionado = 0;
	tabla.rows[tabla.dataset.seleccionado].className = "";
	fila.className = "seleccionado";
	tabla.dataset.seleccionado = fila.rowIndex;
}

function celdaSeleccionada(tabla, celda){
	return tabla.rows[tabla.dataset.seleccionado].cells[celda].innerHTML;
}

function traerAlFrente(elemento, index){
	e = document.getElementById(elemento);
	e.style.zIndex = index;
}

function cargarTabla(tabla, url){
		eliminarFilas(tabla);
		var oXML = AJAXCrearObjeto();
		oXML.open("GET", url, true);		
		oXML.onreadystatechange = function() {
		if (oXML.readyState == 4 && oXML.status == 200) {	  			
				var respuesta = oXML.responseXML;
				var busqueda = respuesta.getElementsByTagName('busqueda');
				for (i=0; i<busqueda.length; i++){
					for (j=0; j< busqueda[i].childNodes.length; j++){
						var nodo = busqueda[i].childNodes[j];
						var elemento = document.getElementById(nodo.tagName);						
						if (elemento) elemento.value = nodo.firstChild? nodo.firstChild.data : '';				
					}					
				}
				
				var registros = respuesta.getElementsByTagName('registro');
				for(i=0; i<registros.length; i++){					
					var fila = tabla.insertRow(i+1);
					for (k=0; k< tabla.rows[0].cells.length; k++){					
						var indice = tabla.rows[0].cells[k].dataset.campo;
						fila.insertCell(k).innerHTML = registros[i].childNodes[indice].firstChild.data;
					}
					fila.onclick = seleccionarFila;
				}
			}
		};
		oXML.send();	  
}

function cargarFormulario(url, funcion = null){		
		var oXML = AJAXCrearObjeto();
		oXML.open("GET", url, true);
		oXML.onreadystatechange = function() {		
		if (oXML.readyState == 4 && oXML.status == 200) {	  			
			var respuesta = oXML.responseXML;	
				var registros = respuesta.getElementsByTagName('registro');				
				for(i=0; i<registros.length; i++){								
					for (j=0; j<registros[i].childNodes.length; j++){
						var nodo = registros[i].childNodes[j];
						var elemento = document.getElementById(nodo.tagName);								
						if (elemento){
							if (elemento.tagName == 'INPUT' || elemento.tagName == 'SELECT' || elemento.tagName == 'TEXTAREA')
								if  (elemento.type == 'checkbox') elemento.checked = nodo.firstChild? nodo.firstChild.data==1?true:false : false;
								else	elemento.value = nodo.firstChild? nodo.firstChild.data : '';
							if (elemento.tagName == 'IMG') elemento.src = nodo.firstChild? nodo.firstChild.data : '';
							if (elemento.tagName == 'DIV') elemento.innerHTML = nodo.firstChild? nodo.firstChild.data : '';
							if (elemento.tagName == 'UL') elemento.innerHTML = nodo.firstChild? nodo.firstChild.data : '';
						}						   
					}					
				}
				if (funcion !== null) funcion();	
			}
			
		};
		oXML.send();	 					
}

function cargarCombo(combo, url, campo, clave){
		var oXML = AJAXCrearObjeto();
		oXML.open("GET", url, true);
		oXML.onreadystatechange = function() {
		if (oXML.readyState == 4 && oXML.status == 200) {	  			
			var respuesta = oXML.responseXML;	
				var registros = respuesta.getElementsByTagName('registro');
				for(i=0; i<registros.length; i++){														
					var opcion = document.createElement('option');
					var nodo = registros[i].getElementsByTagName(campo)[0];
					opcion.text = nodo.firstChild? nodo.firstChild.data : '';
					nodo = registros[i].getElementsByTagName(clave)[0];
					opcion.value = nodo.firstChild? nodo.firstChild.data : '';
					combo.add(opcion);
				}
			}
		};
		oXML.send();	 	
}

function cargarVista(url, destino){
		var oXML = AJAXCrearObjeto();
		oXML.open("GET", url, true);
		oXML.onreadystatechange = function() {
		if (oXML.readyState == 4 && oXML.status == 200) {	  			
				destino.innerHTML = oXML.responseText;					
			}
		};
		oXML.send();	 	
}

function llamadaAJAX(url, metodo, funcion, data=null){
		var oXML = AJAXCrearObjeto();
		oXML.open(metodo, url, true);
		oXML.onreadystatechange = function() {
			if (oXML.readyState == 4 && oXML.status == 200) {	  			
				funcion(oXML);
			}
		};
		if (data!=null){
			oXML.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			oXML.send(data);	 	
		}	
		else oXML.send();
}

function crearFondoVentana(){
	var fondo = document.createElement('div');
	fondo.setAttribute('class', 'ventana-fondo');
	fondo.setAttribute('id', 'ventana_fondo');
	document.getElementsByTagName('body')[0].appendChild(fondo);
}

function mostrar(ventana){
	document.getElementById(ventana).setAttribute('class', 'ventana visible');
	if (document.getElementById('ventana_fondo') == null) crearFondoVentana();
	ventana_fondo.style.display = 'block';
}

function ocultar(ventana){
	document.getElementById(ventana).setAttribute('class', 'ventana');
	ventana_fondo.style.display = 'none';
}

function inicializarVentanas(){
	titulosVentanas = document.getElementsByClassName('ventana-titulo');	
	for (var i=0; i<titulosVentanas.length; i++){		
		if (!titulosVentanas[i].dataset.ventanafija){
			var a =	document.createElement('A');
			a.setAttribute('class', 'bntCerrar');
			var arr = titulosVentanas[i].parentElement.className.split(" ");
			if (arr.indexOf('estatica') != -1){
				a.setAttribute('onclick', titulosVentanas[i].parentElement.dataset.onclick);
			}else
				a.setAttribute('onclick', 'ocultar("'+titulosVentanas[i].parentElement.id+'")');
			titulosVentanas[i].appendChild(a);	
		}		
	}	
}

function vistaPrevia(img){
	var input = sender();
	var fReader = new FileReader();
	fReader.readAsDataURL(input.files[0]);
	fReader.onloadend = function(event){
		img.src = event.target.result;	
	}
 }
 
 function removeClass(elemento, clase){
	 return elemento.className.replace('/\b'+clase+'b/g', "");
 }
 
 function addClass(elemento, clase){
	var arr = elemento.className.split(" ");
	if (arr.indexOf(clase) == -1) {
		elemento.className += " "+ clase;
	}		
 }