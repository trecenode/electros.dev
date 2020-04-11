<?
require 'ulogin.php' ;
require 'aviso.php' ;
if(isset($_POST[enviar])) {
	require 'quitar.php' ;
	$s_titulo = quitar($_POST[s_titulo],1) ;
	$s_contenido = quitar($_POST[s_contenido],1) ;
	$s_bd = quitar($_POST[s_bd],1) ;
	mysql_query("insert into scripts (id_categoria,basededatos,fecha,usuario,titulo,ultima) values ('19','$s_bd','$fecha','$_COOKIE[e_id]','$s_titulo','$fecha')") ;
	$id_ultimo = mysql_insert_id() ;
	file_put_contents("scripts_a/$id_ultimo.txt",$s_contenido) ;
	aviso('Script enviado','<p>El script ha sido enviado.<p><a href="scripts/c/19/s/'.$id_ultimo,'">» Ir al script</a>') ;
	if($_POST[n_version] && $_FILES[n_archivo][name]) {
		$error = false ;
		preg_match('/^(.+)\.(\w+)$/',$_FILES[n_archivo][name],$a) ;
		list(,$nom,$ext) = $a ;
		require 'quitar_a.php' ;
		$nom = quitar_a($nom) ;
		if($ext != 'zip') $error = 'Sólo se permite extensión .zip.<br>' ;
		if(!$_FILES[n_archivo][size] || $_FILES[n_archivo][size] > 524288) $error .= 'El archivo debe ser menor o igual a 512 KB.<br>' ;
		if(strlen($nom) > 32) $error .= 'El nombre de archivo debe ser menor o igual a 32 caractéres.<br>' ;
		$con = mysql_query("select count(id) from scriptsdes where archivo='$nom'") ;
		if(mysql_result($con,0,0)) $error .= 'Ya existe un archivo con este nombre.<br>' ;
		mysql_free_result($con) ;
		if($error) exit(aviso('Error al agregar el archivo',"<p>$error<p><a href=\"$_SERVER[HTTP_REFERER]\">» Regresar</a>")) ;
		$version = quitar($_POST[n_version],1) ;
		mysql_query("insert into scriptsdes (id_script,fecha,archivo,version) values ('$id_ultimo','$fecha','$nom','$version')") ;
		move_uploaded_file($_FILES[n_archivo][tmp_name],'archivos/'.$_FILES[n_archivo][name]) ;
	}
}
?>
<script type="text/javascript">
/*
function codigo(a,b) {
	else if(seleccionado = document.getSelection()) {
		document.getSelection() = a + seleccionado + b ;
		document.s_formulario.s_contenido.focus() ;
	}
	
}
*/
function codigo(a,b) {
	if(document.selection) {
		if(seleccionado = document.selection.createRange().text) {
			document.selection.createRange().text = a+seleccionado+b ;
			document.s_formulario.s_contenido.focus() ;
		}
		else {
			document.s_formulario.s_contenido.focus() ;
			document.selection.createRange().text = a+b ;
		}
	}
	else if(window.getSelection) {
		if(seleccionado = window.getSelection()) {
			seleccionado = a+seleccionado+b ;
			document.s_formulario.s_contenido.focus() ;
		}
		else {
			document.s_formulario.s_contenido.focus() ;
			window.getSelection() = a+b ;
		}
	}
	else if(document.getSelection) {
		if(seleccionado = document.getSelection()) {
			seleccionado = a+seleccionado+b ;
			document.s_formulario.s_contenido.focus() ;
		}
		else {
			document.s_formulario.s_contenido.focus() ;
			document.getSelection() = a+b ;
		}
	}
}
function ayuda1(a) {
	document.getElementById('m_ayuda').innerHTML = '<b>'+a+'</b>' ;
}
function ayuda2() {
	document.getElementById('m_ayuda').innerHTML = '' ;
}
enviando = 0 ;
function validar() {
	if(document.s_formulario.s_titulo.value == '') {
		alert('Debes poner un título.') ;
		return false ;
	}
	if(document.s_formulario.s_contenido.value == '') {
		alert('Debes escribir un contenido.') ;
		return false ;
	}
	if(document.s_formulario.n_version.value == '' && document.s_formulario.n_archivo.value != '') {
		alert('Debes escribir una versión.') ;
		return false ;
	}
	if(enviando == 0) {
		enviando++ ;
		return true ;
	}
	else {
		alert('El script se está enviando.') ;
		return false ;
	}
}
</script>
<table width="100%" border="0" cellpadding="3" cellspacing="0" class="tabla_t1">
<tr>
<td height="20">&nbsp;<img src="imagenes/flecha.gif" border="0" width="11" height="11" alt="Scripts" align="top"> <b>Scripts</b></td>
</tr>
<tr class="tabla_t2">
<td>
<div style="margin: 10px">
<p class="t1"><b>Enviar script</b>
<p>Para enviar un script debes de aceptar los siguientes términos y condiciones:
<p>
<div class="tabla_s1">
<b>1.</b> Debes explicar como funciona el script, si es posible hacer una breve descripción de cada archivo.<br>
<b>2.</b> Si el script pertenece a otra web o es de un autor distinto, debes agregar los créditos correspondientes al
principio de la explicación, así como mantener los créditos originales del script.<br>
<b>3.</b> Para evitar que el usuario modifique constantemente sus datos de conexión a la base de datos, hemos decidido utilizar un único
archivo que se encargará de realizar la conexión, para esto sólo inserta el siguiente código en cada parte del script que lo
requiera.<br><br>
<b>&lt;? require 'config.php' ?&gt;</b><br><br>
Si te interesa cerrar la conexión, usa el siguiente código:<br><br>
<b>&lt;? mysql_close($conectar) ?&gt;</b><br><br>
Este archivo se encargará de crearlo el mismo usuario, según las instrucciones indicadas en la portada de la sección Scripts.<br>
<b>4.</b> Se recomienda el uso de variables superglobales ($_GET, $_POST, $_COOKIE, étc.) sobre las variables globales, además de ser un riesgo de seguridad llamar directamente a una variable sin indicar su ámbito, en futuras versiones de PHP, según sus autores, ya no será posible utilizar variables globales.<br>
<b>5.</b> Si los administradores o moderadores encuentran algún contenido inapropiado lo eliminarán sin previo consentimiento del autor.
</div>
<p>Los campos marcados con asterisco (*) son obligatorios.
<p>
<form name="s_formulario" method="post" action="scriptsenviar" enctype="multipart/form-data" onsubmit="return validar()">
<b>* Título del script:</b><br>
<input type="text" name="s_titulo" maxlength="100" class="form"><br>
<b>Formato:</b><br>
<input type="button" onclick="codigo('[b]','[/b]')" value="[b]" onmouseover="ayuda1('Texto en negrita')" onmouseout="ayuda2()" title="[b]Texto en negrita[/b]" class="form">
<input type="button" onclick="codigo('[i]','[/i]')" value="[i]" onmouseover="ayuda1('Texto en cursiva')" onmouseout="ayuda2()" title="[b]Texto en cursiva[/b]" class="form">
<input type="button" onclick="codigo('[u]','[/u]')" value="[u]" onmouseover="ayuda1('Texto subrayado')" onmouseout="ayuda2()" title="[b]Texto subrayado[/b]" class="form">
<input type="button" onclick="codigo('[img]','[/img]')" value="[img]" onmouseover="ayuda1('Poner una imagen')" onmouseout="ayuda2()" title="[img]http://...imagen.jpg[/img]" class="form">
<input type="button" onclick="codigo('[url]','[/url]')" value="[url]" onmouseover="ayuda1('Crear un enlace')" onmouseout="ayuda2()" title="[url]http://...[/url]" class="form">
<input type="button" onclick="codigo('[codigo]','[/codigo]')" value="[codigo]" onmouseover="ayuda1('Colorear código (HTML, PHP, étc.)')" onmouseout="ayuda2()" title="[cod]&lt;? echo $a ; ?&gt;[/cod]" class="form">
<span id="m_ayuda"></span>
<br>
<div style="font-size: 7pt">El color puede ser un color en inglés o un color hexadecimal como #0000ff (Azul)</div>
<b>* Contenido del script:</b><br>
<textarea name="s_contenido" cols="85" rows="20" class="form" style="font-family: courier new, serif"></textarea><br>
<b>¿Usa base de datos?:</b>
<input type="radio" name="s_bd" value="0" checked> No <input type="radio" name="s_bd" value="1"> Sí<br><br>
<b>Archivo:</b><br>
<input type="file" name="n_archivo" class="form"><br>
<b>Versión:</b><br>
<input type="text" name="n_version" size="5" class="form"><br><br>
<input type="submit" name="enviar" value="Enviar Script" class="form">
</form>
</div>
</td>
</tr>
</table>
