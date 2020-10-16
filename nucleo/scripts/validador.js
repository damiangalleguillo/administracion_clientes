function flashElemento(elemento){
	location.hash = '#'+elemento.id;
	elemento.style.backgroundColor = "rgb(255, 190, 190)";	
}

function validarFechas(valor){
 if (valor.length == 0) return true;
 var resultado = false;
 var fecha = valor.split("/");
 if (fecha.length==3){
  var dia = fecha[0];	 
  var mes = fecha[1];
  var ani = fecha[2];
  resultado = mes>0 && mes<13 && ani>0 && ani < 32768 && dia> 0 && dia < 32 && new Date(ani, mes, dia);  
 } 
 
 if (!resultado) {
  alert("No es una Fecha Valida");
  return false;  
 }
 else return true; 	
}

function validarHoras(valor){
 if (valor.length == 0) return true;
 var resultado = false;
 var hora = valor.split(":"); 
 if (hora.length == 2){
  var h = hora[0];	 
  var m = hora[0];
  resultado = h>=0 && h<=23 && m >=0 && m<=59;
 }
 if (!resultado) {
  alert("No es una Hora Valida");
  return false;
 }
 else return true;
 
}

function validarPassword(elemento){
	var pass_a = elemento.value;
	var pass_b = document.getElementById(elemento.dataset.password).value;
	
	if (pass_a != pass_b){
		alert('La contraseña no se corresponde con su verificacion.');
		return false;
	}
	
	return true;
}

function validarLongitud(valor, longitud){ 
 if (valor.length == undefined || valor.length > longitud){
  alert("La longitud maxima para este campo es de "+longitud);
  return false;	 
 }
 return true; 
}

function validarEntero(valor){
 if (parseInt(valor) == NaN){
  alert("no es un Valor Entero Valido");	 
  return false
 }
 return true;
}

function validar(formulario){
 var resultado = true;	
 var frm = document.getElementById(formulario);
 var i;
 var e;
 
 for (i=0; i<frm.elements.length; i++){
   e=frm.elements[i];	 
   
   if (e.dataset.nonulo && (e.value == undefined || e.value.length == 0)){	   
	alert("Este Campo No Puede Ser Nulo");   
	flashElemento(e);
	return false;   
   }
   if (e.dataset.longitud && !validarLongitud(e.value, e.dataset.longitud)){
    flashElemento(e);	   
    return false;
   }
   
   if (e.dataset.password) {
	if (!validarPassword(e)){
		flashElemento(e);
		flashElemento(document.getElementById(e.dataset.password));
		return false;
	}
   }

   
   switch (e.dataset.validar){
	case "fecha":resultado = validarFechas(e.value);
	            break;   
	case "hora" :resultado = validarHoras(e.value);
	            break; 
	case "entero":resultado = validarEntero(e.value);
	            break;	
   }
   if (!resultado){
	flashElemento(e);
    return false;	
   }
 }
 
 return true; 
}









