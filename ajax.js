/*************************************************/
/* CODIGO AJAX                                   */
/* Desarrollador: DeLaF www.delaf.tk             */
/* Mail: esteban.delaf@gmail.com                 */
/* Ultima version: 20-02-08                      */
/*************************************************/

function objetoAjax () {
	var xmlhttp=false;
	try {
		xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	} catch (e) {
		try {
		   xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
		} catch (E) {
			xmlhttp = false;
  		}
	}

	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}

function mostrar_capa (capa) {
 document.getElementById(capa).style.visibility="visible";
}

function ocultar_capa(capa) {
 document.getElementById(capa).style.visibility="hidden";
}

function vacio (texto) {  
	for (i=0;i<texto.length;i++) {  
		if (texto.charAt(i)!=" ")
			return false;
	}
	return true;
}

function disableForm (formulario,valor,campo) {
	if(valor==0)
		document.forms[formulario][campo].disabled = false;
	else
		document.forms[formulario][campo].disabled = true;
}

function sendForm (url,parametros,capa,formulario) {
	// recibir datos y enviarlos a la base de datos
	var divShowForm = document.getElementById(capa);
	ajax=objetoAjax();
	ajax.open('POST',url,true);
	ajax.onreadystatechange=function(){
		if (ajax.readyState==1) {
			divShowForm.innerHTML = '<p class="center"><img src="./images/progress.gif" alt="Cargando" /></p>';
		} else {
			if (ajax.readyState==4) {
				  divShowForm.innerHTML = ajax.responseText;
			}
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(parametros);
	// resetar formulario y salir
	formulario.reset();
	return false;
}

function eliminar(id,name,url) {
	if(confirm('Confirmar el borrado de ' + name)) {
		document.location.href= url + '?eliminar=' + id;
	}
}

function validarGroup (formulario){
	if (vacio(formulario.name.value) || vacio(formulario.shortName.value) || vacio(formulario.about.value)) {
		alert('Todos los campos son obligatorios!');
		return false;
	}
	return true;
}

function validarDisc (formulario){
	if (vacio(formulario.name.value)) {
		alert('Nombre del disco obligatorio!');
		return false;
	}
	return true;
}

function validarRegistro (formulario){
	if (vacio(formulario.usuario.value) || vacio(formulario.clave1.value) || vacio(formulario.clave2.value) || vacio(formulario.email1.value) || vacio(formulario.email2.value) || vacio(formulario.reg_ver.value)) {
		alert('Todos los campos son obligatorios!');
		return false;
	}
	return true;
}

function agregarLista (aid) {
	alert("No disponible");
	return false;
}

function validarUpload(formulario,maxFileSize) {
	if(vacio(formulario.archivo.value)) {
		alert("Debes indicar un archivo a subir!");
		return false;
	}
	extOk = new Array(".mp3");
	ext = (formulario.archivo.value.substring(formulario.archivo.value.lastIndexOf("."))).toLowerCase();
	permitida = false;
	for (var i = 0; i < extOk.length; i++) {
		if (extOk[i] == ext) {
			permitida = true;
			break;
		}
	}
	if (!permitida) {
		alert("La extensión del archivo es " + ext + " y no es permitida!");
		return false;
	}
	return true;
}

function validarAvatar(formulario,maxFileSize) {
	if(vacio(formulario.archivo.value)) {
		alert("Debes indicar un archivo a subir!");
		return false;
	}
	extOk = new Array(".jpg",".jpeg",".png",".gif");
	ext = (formulario.archivo.value.substring(formulario.archivo.value.lastIndexOf("."))).toLowerCase();
	permitida = false;
	for (var i = 0; i < extOk.length; i++) {
		if (extOk[i] == ext) {
			permitida = true;
			break;
		}
	}
	if (!permitida) {
		alert("La extensión del archivo es " + ext + " y no es permitida!");
		return false;
	}
	return true;
}

function validarProfile (formulario){
	if (vacio(formulario.pass.value)) {
		alert('Debes ingresar la contraseña para efectuar cambios!');
		return false;
	}
	return true;
}

function validarChPass (formulario){
	if (vacio(formulario.pass.value) || vacio(formulario.pass1.value) || vacio(formulario.pass2.value)) {
		alert('Todos los campos son obligatorios!');
		return false;
	}
	if (formulario.pass1.value != formulario.pass2.value) {
		alert('Las contraseñas no son iguales!');
		return false;
	}
	return true;
}

function validarDel(formulario){
	if(vacio(formulario.pass.value)){
		alert('Debes ingresar la clave!');
		return false;
	} else {
		if(confirm('¿Confirmar el borrado del usuario ' + formulario.user.value + '?'))
			return true;
		else
			return false;			
	}
}