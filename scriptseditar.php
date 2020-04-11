<?
require 'ulogin.php' ;
require 'aviso.php' ;
$con = mysql_query("select usuario from scripts where id='$_GET[s]' and usuario='$_COOKIE[e_id]'") ;
if(mysql_num_rows($con) || $_COOKIE[e_id] == 1) {
	$id_usuario = mysql_result($con,0,0) ;
}
else {
	header("location: {$url_base}acceso_denegado.php") ;
}
mysql_free_result($con) ;
if($_POST[enviar]) {
	unset($error) ;
	require 'quitar.php' ;
	$s_titulo = quitar($_POST[s_titulo],1) ;
	$s_contenido = quitar($_POST[s_contenido]) ;
	$s_bd = quitar($_POST[s_bd],1) ;
	mysql_query("update scripts set titulo='$s_titulo',basededatos='$s_bd',ultima='$fecha' where id='$_GET[s]'") ;
	file_put_contents("scripts_a/$_GET[s].txt",$s_contenido) ;
	aviso('Script editado',"<p>El contenido del script ha sido editado.<p><a href=\"scripts/c/$_GET[c]/s/$_GET[s]\">» Ir al script</a>") ;
	# --> Editar los archivos subidos
	foreach($_FILES as $llave => $valor) {
		if(ereg('^archivo([0-9]+)$',$llave,$a) && $_FILES["archivo$a[1]"][name] && !$_POST["borrar$a[1]"]) {
			$a = $a[1] ;
			if($_FILES["archivo$a"][name]) {
				preg_match('/^(.+)\.(\w+)$/',$_FILES["archivo$a"][name],$b) ;
				list(,$nom,$ext) = $b ;
				require 'quitar_a.php' ;
				$nom = quitar_a($nom) ;
				if($ext != 'zip') $error = 'Sólo se permite extensión .zip.<br>' ;
				if($_FILES["archivo$a"][size] > 524288) $error .= 'El archivo debe ser menor o igual a 512 KB.<br>' ;
				if(strlen($nom) > 32) $error .= 'El nombre de archivo debe ser menor o igual a 32 caractéres.<br>' ;
				$con = mysql_query("select count(id) from scriptsdes where id='$a' and archivo='$nom'") ;
				if(!mysql_result($con,0,0)) $error .= 'El archivo actualizado debe tener el mismo nombre del anterior.<br>' ;
				mysql_free_result($con) ;
				if($error) {
					aviso('Error al editar el archivo',"<p>$error<p><a href=\"$_SERVER[HTTP_REFERER]\">» Regresar</a>") ;
					exit ;
				}
				mysql_query("update scriptsdes set fecha='$fecha' where id='$a'") ;
				move_uploaded_file($_FILES["archivo$a"][tmp_name],'archivos/'.$_FILES["archivo$a"][name]) ;
			}
		}
	}
	# --> Borrar archivos
	foreach($_POST as $llave => $valor) {
		if(ereg('^borrar[0-9]+$',$llave)) {
			$con = mysql_query("select archivo from scriptsdes where id='$valor'") ;
			unlink('archivos/'.mysql_result($con,0,0).'.zip') ;
			mysql_query("delete from scriptsdes where id='$valor'") ;
			mysql_free_result($con) ;
		}
	}
	# --> Agregar un nuevo archivo
	if($_POST[n_version] && $_FILES[n_archivo][name]) {
		unset($error) ;
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
		if($error) {
			aviso('Error al agregar el archivo',"<p>$error<p><a href=\"$_SERVER[HTTP_REFERER]\">» Regresar</a>") ;
			exit ;
		}
		$version = quitar($_POST[n_version],1) ;
		mysql_query("insert into scriptsdes (id_script,fecha,archivo,version) values ('$_GET[s]','$fecha','$nom','$version')") ;
		move_uploaded_file($_FILES[n_archivo][tmp_name],'archivos/'.$_FILES[n_archivo][name]) ;
	}
}
$con = mysql_query("select * from scripts where id='$_GET[s]'") ;
$datos = mysql_fetch_assoc($con) ;
?>
<script>
function codigo(a,b) {
	if(navigator.appName == 'Microsoft Internet Explorer') {
		if(seleccionado = document.selection.createRange().text) {
			document.selection.createRange().text = a + seleccionado + b ;
			document.s_formulario.s_contenido.focus() ;
		}
		else {
			document.s_formulario.s_contenido.focus() ;
			document.selection.createRange().text = a + b ;
		}
	}
	else {
		document.s_formulario.s_contenido.value += a + b ;
		document.s_formulario.s_contenido.focus() ;
	}
}
function ayuda1(a) {
	m_ayuda.innerHTML = '<b>'+a+'</b>' ;
}
function ayuda2() {
	m_ayuda.innerHTML = '' ;
}
enviando = 0 ;
function validar() {
	if(document.s_formulario.s_titulo.value == '') {
		alert('Debes poner un título') ;
		return false ;
	}
	if(document.s_formulario.s_contenido.value == '') {
		alert('Debes escribir un contenido') ;
		return false ;
	}
	if(document.s_formulario.n_version.value == '' && document.s_formulario.n_archivo.value != '') {
		alert('Debes escribir una versión') ;
		return false ;
	}
	if(enviando == 0) {
		enviando++ ;
		return true ;
	}
	else {
		alert('El script se está enviando') ;
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
<p class="t1"><b>Editar script</b>
<p>
<form name="s_formulario" method="post" action="scriptseditar/c/<?=$_GET[c]?>/s/<?=$_GET[s]?>" enctype="multipart/form-data" onsubmit="return validar()">
<b>Título del script:</b><br>
<input type="text" name="s_titulo" value="<?=$datos[titulo]?>" maxlength="100" class="form"><br>
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
<b>Contenido del script:</b><br>
<textarea name="s_contenido" cols="85" rows="20" class="form" style="font-family: courier new, serif"><?=file_get_contents("scripts_a/$datos[id].txt")?></textarea><br>
<b>¿Usa base de datos?:</b>
<input type="radio" name="s_bd" value="0" <? if(!$datos[basededatos]) echo ' checked' ?>> No <input type="radio" name="s_bd" value="1" <? if($datos[basededatos]) echo ' checked' ?>> Sí<br><br>
<b>Archivos:</b><br><br>
<table width="100%" border="0" cellpadding="3" cellspacing="0" class="tabla_s1" style="border: #ffa500 1px solid">
<?
$con = mysql_query("select * from scriptsdes where id_script='$_GET[s]' order by version desc") ;
if(!mysql_num_rows($con)) {
?>
<tr>
<td><center><b>No hay archivos.</b></center></td>
</tr>
<?
}
else {
	require('fecha.php') ;
?>
<tr>
<td><b>Versión</b></td>
<td><b>Archivo</b></td>
<td><b>Subido/Actualizado</b></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<?
	while($datos = mysql_fetch_assoc($con)) {
?>
<tr>
<td><b><?=$datos[version]?></b></td>
<td><b><?=$datos[archivo]?>.zip</b></td>
<td><?=fecha($datos[fecha])?></td>
<td><b>Editar:</b> <input type="file" name="archivo<?=$datos[id]?>" class="form"></td>
<td><input type="checkbox" name="borrar<?=$datos[id]?>" value="<?=$datos[id]?>" onclick="if(checked) archivo<?=$datos[id]?>.disabled = 1 ; else archivo<?=$datos[id]?>.disabled = 0"> <b>Borrar</b></td>
</tr>
<?
	}
	mysql_free_result($con) ;
}
?>
</table><br>
<b>Agregar nueva versión:</b><br><br>
<b>Archivo:</b><br>
<input type="file" name="n_archivo" class="form"><br>
<b>Versión:</b><br>
<input type="text" name="n_version" size="5" class="form"><br><br>
<input type="submit" name="enviar" value="Editar Script" class="form">
</form>
</div>
</td>
</tr>
</table>
