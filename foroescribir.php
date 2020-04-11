<?
/*
************************************************
*** eForo v3.0
*** Creado por: Electros <electros@electros.net>
*** Sitio web: www.electros.net
*** Licencia: GNU General Public License
************************************************

--- Página: foroescribir.php ---

eForo - Una comunidad para que tus visitantes se comuniquen y se sientan parte de tu web
Copyright © 2003-2005 Daniel Osorio "Electros"

Este programa es software libre, puedes redistribuirlo y/o modificarlo bajo los términos
de la GNU General Public License publicados por la Free Software Foundation; desde la
versión 2 de la licencia, o (si lo deseas) cualquiera más reciente.
*/

require('foroconfig.php') ;
require('eforo_funciones/aviso.php') ;
require('eforo_funciones/sesion.php') ;
require('eforo_funciones/menu.php') ;

# * Mostrar la cabecera (definida desde el panel de control en foroadmin.php)
if($conf[html_cabecera]) {
	echo $conf[html_cabecera] ;
}

# * Comportamiento del formulario (escribir, responder o editar un mensaje)
$permiso = false ;
switch(true) {
	# Escribir nuevo tema
	case $_GET[foro] && !$_GET[tema] && !$_GET[mensaje] :
	$que = 1 ;
	$permiso = 'p_nuevo' ;
	$form_titulo = 'Escribir nuevo tema' ;
	break ;
	# Responder al tema
	case $_GET[foro] && $_GET[tema] && !$_GET[mensaje] :
	$que = 2 ;
	$permiso = 'p_responder' ;
	$form_titulo = 'Responder al tema' ;
	break ;
	# Editar el mensaje
	case $_GET[foro] && $_GET[tema] && $_GET[mensaje] :
	$que = 3 ;
	$permiso = 'p_editar' ;
	$form_titulo = 'Editar el mensaje' ;
}

if(get_magic_quotes_gpc()) $_POST[m_tema] = stripslashes($_POST[m_tema]) ;
if(get_magic_quotes_gpc()) $_POST[m_mensaje] = stripslashes($_POST[m_mensaje]) ;
$_POST[m_tema] = htmlspecialchars(trim($_POST[m_tema])) ;
$_POST[m_mensaje] = htmlspecialchars(trim($_POST[m_mensaje])) ;

if($que != 3) {
	$form_tema = $_POST[m_tema] ;
	$form_mensaje = $_POST[m_mensaje] ;
	$form_caretos = !$_GET[vistaprevia] ? 1 : $_POST[m_caretos] ;
	$form_codigo = !$_GET[vistaprevia] ? 1 : $_POST[m_codigo] ;
	$form_url = !$_GET[vistaprevia] ? 1 : $_POST[m_url] ;
	$form_firma = !$_GET[vistaprevia] ? 1 : $_POST[m_firma] ;
	$form_imp = $_POST[m_importante] ;
	$form_not = $_POST[m_notificacion] ;
}

# * Comprobar permiso de usuario
if(!$es_moderador) {
	permiso($permiso) ;
}

# * Se comprueba si el mensaje pertenece al usuario (se ignora la comprobación en caso de ser moderador o administrador)
if($que == 3) {
	$con = mysql_query("select * from eforo_mensajes where id='$_GET[mensaje]'") ;
	$datos = mysql_fetch_assoc($con) ;
	if($datos[id_usuario] != $usuario[id] && !$es_moderador) {
		aviso('Error','<p>Tu no puedes editar este mensaje.<p><a href="javascript:history.back()" class="eforo">» Regresar</a>') ; exit ;
	}
	if($datos[id] == $datos[id_tema]) {
		$es_tema = true ;
	}
	# --> Se rellenan los campos del formulario con el mensaje a editar
	$form_tema = !$_POST[m_tema] ? $datos[tema] : $_POST[m_tema] ;
	$form_mensaje = !$_POST[m_mensaje] ? $datos[mensaje] : $_POST[m_mensaje] ;
	$form_caretos = !$_POST[m_caretos] ? $datos[o_caretos] : $_POST[m_caretos] ;
	$form_codigo = !$_POST[m_codigo] ? $datos[o_codigo] : $_POST[m_codigo] ;
	$form_url = !$_POST[m_url] ? $datos[o_url] : $_POST[m_url] ;
	$form_firma = !$_POST[m_firma] ? $datos[o_firma] : $_POST[m_firma] ;
	$form_imp = !$_POST[m_importante] ? $datos[o_importante] : $_POST[m_importante] ;
	$form_not = !$_POST[m_notificacion] ? $datos[o_notificacion] : $_POST[m_notificacion] ;
	mysql_free_result($con) ;
}

$u_tema = $_GET[tema] ? "$u[3]tema$u[4]$_GET[tema]" : false ;
$u_mensaje = $_GET[mensaje] ? "$u[3]mensaje$u[4]$_GET[mensaje]" : false ;

# * Vista previa del mensaje
if($_GET[vistaprevia]) {
	require('eforo_funciones/codigo.php') ;
	if($que != 3) {
		$con = mysql_query("select nick from $tabla_usuarios where id='$usuario[id]'") ;
		$datos = mysql_fetch_assoc($con) ;
		$autor_nick = !$datos[nick] ? '<i>Anónim@</i>' : "<a href=\"$u[0]usuarios$u[1]$u[2]u$u[4]$usuario[id]$u[5]\" target=\"_blank\" class=\"eforo\">$datos[nick]</a>" ;
		mysql_free_result($con) ;
	}
	else {
		$con = mysql_query("select id_usuario from eforo_mensajes where id='$_GET[mensaje]'") ;
		$datos = mysql_fetch_assoc($con) ;
		$con2 = mysql_query("select nick from $tabla_usuarios where id='$datos[id_usuario]'") ;
		$datos2 = mysql_fetch_assoc($con2) ;
		$autor_nick = !$datos2[nick] ? '<i>Anónim@</i>' : "<a href=\"$u[0]usuarios$u[1]$u[2]u$u[4]$datos[id_usuario]$u[5]\" target=\"_blank\" class=\"eforo\">$datos2[nick]</a>" ;
		mysql_free_result($con2) ;
		mysql_free_result($con) ;
	}
	if($conf[permitir_codigo] && $_POST[m_codigo]) {
		$_POST[m_mensaje] = codigo($_POST[m_mensaje]) ;
	}
	if($conf[permitir_caretos] && $_POST[m_caretos]) {
		$_POST[m_mensaje] = caretos($_POST[m_mensaje]) ;
	}
	if($conf[transformar_url] && $_POST[m_url]) {
		$_POST[m_mensaje] = url($_POST[m_mensaje]) ;
	}
	if($conf[censurar_palabras]) {
		$_POST[m_mensaje] = censurar($_POST[m_mensaje]) ;
	}
	$_POST[m_mensaje] = str_replace("\r",'<br>',$_POST[m_mensaje]) ;
?>
<script>
function proteger_email(usuario,servidor) {
	document.write('<a href="mailto:'+usuario+'@'+servidor+'" class="eforo">'+usuario+'@'+servidor+'</a>') ;
}
</script>
<table width="100%" border="0" cellpadding="3" cellspacing="1" align="center" class="eforo_tabla_principal">
<tr>
<td colspan="2" class="eforo_tabla_titulo"><div class="eforo_titulo_1">Vista previa</div></td>
</tr>
<tr>
<td class="eforo_tabla_titulo"><div class="eforo_titulo_1">Autor</div></td>
<td class="eforo_tabla_titulo"><div class="eforo_titulo_1">Mensaje</div></td>
</tr>
<tr>
<td width="20%" valign="top" class="eforo_tabla_mensaje_1"><?=$autor_nick?></td>
<td width="80%" valign="top" class="eforo_tabla_mensaje_1">
Tema: <b><?=$_POST[m_tema]?></b>
<hr width="100%" color="505050">
<?=$_POST[m_mensaje]?>
</td>
</tr>
</table>
<?
}
?>
<script type="text/javascript">
function codigo(a,b) {
	if(navigator.appName == 'Microsoft Internet Explorer') {
		if(seleccionado = document.selection.createRange().text) {
			document.selection.createRange().text = a + seleccionado + b ;
			document.m_formulario.m_mensaje.focus() ;
		}
		else {
			document.m_formulario.m_mensaje.focus() ;
			document.selection.createRange().text = a + b ;
		}
	}
	else {
		document.m_formulario.m_mensaje.value += a + b ;
		document.m_formulario.m_mensaje.focus() ;
	}
}
function ayuda1(a) {
	m_ayuda.innerHTML = '<b>'+a+'</b>' ;
}
function ayuda2() {
	m_ayuda.innerHTML = '' ;
}
function caretos(a) {
	if(navigator.appName == 'Microsoft Internet Explorer') {
		document.m_formulario.m_mensaje.focus() ;
		document.selection.createRange().text = a ;
	}
	else {
		document.m_formulario.m_mensaje.value += a ;
		document.m_formulario.m_mensaje.focus() ;
	}
}
function vista_previa() {
	document.m_formulario.action = '<?="$u[0]foroescribir$u[1]$u[2]foro$u[4]$_GET[foro]$u_tema$u_mensaje$u[3]vistaprevia$u[4]1$u[5]"?>' ;
	document.m_formulario.submit() ;
}
enviado = 0 ;
function revisar() {
<? if($que == 1 || $es_tema) { echo "document.m_formulario.m_tema.value = document.m_formulario.m_tema.value.replace(/^\s*|\s*$/g,'') ;\n" ; } ?>
	document.m_formulario.m_mensaje.value = document.m_formulario.m_mensaje.value.replace(/^\s*|\s*$/g,'') ;
<? if($que == 1 || $es_tema) { echo "if(document.m_formulario.m_tema.value.length < 3) { alert('Debes escribir un tema') ; return false ; }\n" ; } ?>
	if(document.m_formulario.m_mensaje.value.length < 3) { alert('Debes escribir un mensaje') ; return false ; }
	if(enviado == 0) { enviado++ ; } else { alert('El mensaje se está enviando por favor espera.') ; return false ; }
}
</script>
<?
?>
<table width="100%" border="0" cellpadding="3" cellspacing="1" align="center" class="eforo_tabla_principal">
<form name="m_formulario" method="post" action="<?="$u[0]foroescribirpro$u[1]$u[2]foro$u[4]$_GET[foro]$u_tema$u_mensaje$u[5]"?>" enctype="multipart/form-data" onsubmit="return revisar()">
<input type="hidden" name="que" value="<?=$que?>">
<tr>
<td colspan="2" class="eforo_tabla_titulo"><div align="center" class="eforo_titulo_1"><?=$form_titulo?></div></td>
</tr>
<tr>
<td valign="top" class="eforo_tabla_defecto"><b>Título:</b><br>Título del mensaje.</td>
<td valign="top" class="eforo_tabla_defecto"><input type="text" name="m_tema" size="75" value="<?=$form_tema?>" maxlength="60" class="eforo_formulario"></td>
</tr>
<tr>
<td valign="top" class="eforo_tabla_defecto"><b>Mensaje:</b><br>Contenido del mensaje.</td>
<td valign="top" class="eforo_tabla_defecto">
<b>Código especial:</b>
<div style="margin-top: 3"></div>
<input type="button" onclick="codigo('[b]','[/b]')" value="[b]" onmouseover="ayuda1('Texto en negrita')" onmouseout="ayuda2()" class="eforo_formulario">
<input type="button" onclick="codigo('[i]','[/i]')" value="[i]" onmouseover="ayuda1('Texto en cursiva')" onmouseout="ayuda2()" class="eforo_formulario">
<input type="button" onclick="codigo('[u]','[/u]')" value="[u]" onmouseover="ayuda1('Texto subrayado')" onmouseout="ayuda2()" class="eforo_formulario">
<input type="button" onclick="codigo('[img]','[/img]')" value="[img]" onmouseover="ayuda1('Poner una imagen')" onmouseout="ayuda2()" class="eforo_formulario">
<input type="button" onclick="codigo('[url]','[/url]')" value="[url]" onmouseover="ayuda1('Crear un enlace')" onmouseout="ayuda2()" class="eforo_formulario">
<input type="button" onclick="codigo('[cod]','[/cod]')" value="[cod]" onmouseover="ayuda1('Colorear código (HTML, PHP, étc.)')" onmouseout="ayuda2()" class="eforo_formulario">
<span id="m_ayuda"></span>
<div style="margin-top: 3"></div>
<a href="javascript:caretos(':D')"><img src="eforo_imagenes/caretos/alegre.gif" width="15" height="15" border="0"></a>
<a href="javascript:caretos(':8')"><img src="eforo_imagenes/caretos/asustado.gif" width="15" height="15" border="0"></a>
<a href="javascript:caretos(':P')"><img src="eforo_imagenes/caretos/burla.gif" width="15" height="15" border="0"></a>
<a href="javascript:caretos(':S')"><img src="eforo_imagenes/caretos/confundido.gif" width="15" height="15" border="0"></a>
<a href="javascript:caretos(':(1')"><img src="eforo_imagenes/caretos/demonio.gif" width="15" height="15" border="0"></a>
<a href="javascript:caretos(':(2')"><img src="eforo_imagenes/caretos/demonio2.gif" width="15" height="15" border="0"></a>
<a href="javascript:caretos(':?')"><img src="eforo_imagenes/caretos/duda.gif" width="15" height="15" border="0"></a>
<a href="javascript:caretos(':-\(')"><img src="eforo_imagenes/caretos/enojado.gif" width="15" height="15" border="0"></a>
<a href="javascript:caretos(';)')"><img src="eforo_imagenes/caretos/guino.gif" width="15" height="15" border="0"></a>
<a href="javascript:caretos(':\'(')"><img src="eforo_imagenes/caretos/llorar.gif" width="15" height="15" border="0"></a>
<a href="javascript:caretos(':lol:')"><img src="eforo_imagenes/caretos/lol.gif" width="15" height="15" border="0"></a>
<a href="javascript:caretos(':M')"><img src="eforo_imagenes/caretos/moda.gif" width="15" height="15" border="0"></a>
<a href="javascript:caretos(':|')"><img src="eforo_imagenes/caretos/neutral.gif" width="15" height="15" border="0"></a>
<a href="javascript:caretos(':)')"><img src="eforo_imagenes/caretos/risa.gif" width="15" height="15" border="0"></a>
<a href="javascript:caretos(':-)')"><img src="eforo_imagenes/caretos/sonrisa.gif" width="15" height="15" border="0"></a>
<a href="javascript:caretos(':R')"><img src="eforo_imagenes/caretos/sonrojado.gif" width="15" height="15" border="0"></a>
<a href="javascript:caretos(':O')"><img src="eforo_imagenes/caretos/sorprendido.gif" width="15" height="15" border="0"></a>
<a href="javascript:caretos(':(')"><img src="eforo_imagenes/caretos/triste.gif" width="15" height="15" border="0"></a>
<div style="margin-top: 3"></div>
<?
if($que == 2 && ereg('^[0-9]+$',$_GET[citar])) {
	$con = mysql_query("select id_usuario,mensaje from eforo_mensajes where id='$_GET[citar]'") ;
	$datos = mysql_fetch_assoc($con) ;
	$con2 = mysql_query("select nick from $tabla_usuarios where id='$datos[id_usuario]'") ;
	$datos2 = mysql_fetch_assoc($con2) ;
	$form_mensaje = "[citar autor=$datos2[nick]]\r\n$datos[mensaje]\r\n[/citar]" ;
	mysql_free_result($con2) ;
	mysql_free_result($con) ;
?>
<script>
function citar() {
	document.m_formulario.m_mensaje.focus() ;
	document.m_formulario.m_mensaje.value += '\n\n' ;
}
onload = citar ;
</script>
<?
}
?>
<textarea name="m_mensaje" cols="75" rows="25" class="eforo_formulario"><?=$form_mensaje?></textarea>
</td>
</tr>
<?
# * Borrar archivo adjunto
if($_GET[borraradjunto]) {
	$con = mysql_query("select id from eforo_adjuntos where id_mensaje='$_GET[mensaje]'") ;
	$datos = mysql_fetch_assoc($con) ;
	mysql_query("delete from eforo_adjuntos where id='$datos[id]'") ;
	mysql_free_result($con) ;
	unlink('eforo_adjuntos/'.$id_adjunto.'.dat') ;
}
unset($adjuntar) ;
# * Comprobar si el usuario tiene permiso para adjuntar archivos
$con = mysql_query("select p_adjuntar from eforo_foros where id='$_GET[foro]'") ;
$datos = mysql_fetch_assoc($con) ;
if($usuario[rango] >= $datos[p_adjuntar] || $es_moderador) {
	$adjuntar = true ;
	$con = mysql_query("select archivo from eforo_adjuntos where id_mensaje='$_GET[mensaje]' limit 1") ;
	if(mysql_num_rows($con)) {
		$datos = mysql_fetch_assoc($con) ;
		$adjuntar_titulo = '<b>Archivo adjunto:</b><br>Nombre del archivo adjunto.' ;
		$adjuntar_contenido = "<b>$datos[archivo]</b><br><br><input type=\"button\" value=\"Borrar\" onclick=\"location='$conf[url_foro]/$u[0]foroescribir$u[1]$u[2]foro$u[4]$_GET[foro]$u_tema$u_mensaje$u[5]'\" class=\"eforo_formulario\">" ;
		}
	else {
		$adjuntar_titulo = '<b>Adjuntar archivo (Máx. '.$conf[adjunto_tamano].' KB):</b><br>Anexar un archivo a tu mensaje.' ;
		$adjuntar_contenido = '<input type="file" name="m_archivo" size="50" class="eforo_formulario">' ;
	}
}
mysql_free_result($con) ;
if($adjuntar) {
?>
<tr>
<td valign="top" class="eforo_tabla_defecto"><?=$adjuntar_titulo?></td>
<td valign="top" class="eforo_tabla_defecto"><?=$adjuntar_contenido?></td>
</tr>
<?
}
?>
<tr>
<td valign="top" class="eforo_tabla_defecto">&nbsp;</td>
<td valign="top" class="eforo_tabla_defecto">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="50%" valign="top">
<? if($conf[permitir_caretos]) { ?><input type="checkbox" name="m_caretos" value="1" id="m_caretos" <? if($form_caretos) { echo ' checked' ; } ?>><label for="m_caretos"><b>Usar caretos en el mensaje</b></label><br><? } ?>
<? if($conf[permitir_codigo]) { ?><input type="checkbox" name="m_codigo" value="1" id="m_codigo" <? if($form_codigo) { echo ' checked' ; } ?>><label for="m_codigo"><b>Usar código especial en el mensaje</b></label><br><? } ?>
<? if($conf[transformar_url]) { ?><input type="checkbox" name="m_url" value="1" id="m_url" <? if($form_url) { echo ' checked' ; } ?>><label for="m_url"><b>Transformar URLs en enlaces</b></label><br><? } ?>
</td>
<td width="50%" valign="top">
<input type="checkbox" name="m_firma" value="1" id="m_firma"<? if($form_firma) { echo ' checked' ; } ?>><label for="m_firma"><b>Agregar firma en el mensaje</b></label><br>
<?
$con = mysql_query("select p_importante from eforo_foros where id='$_GET[foro]'") ;
$datos = mysql_fetch_assoc($con) ;
if($usuario[rango] >= $datos[p_importante] || $es_moderador) {
?>
<input type="checkbox" name="m_importante" value="1" id="m_importante"<? if($form_imp) { echo ' checked' ; } ?>><label for="m_importante"><b>Marcar el tema como importante</b></label><br>
<?
}
mysql_free_result($con) ;
?>
<? if($_GET[tema] == $_GET[mensaje] && $conf[notificacion_email]) { ?><input type="checkbox" name="m_notificacion" value="1" id="m_notificacion"<? if($form_not) { echo ' checked' ; } ?>><label for="m_notificacion"><b>Notificarme por email cuando haya respuestas</b></label><br><? } ?>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td class="eforo_tabla_defecto">&nbsp;</td>
<td class="eforo_tabla_defecto">
<center><input type="button" onclick="vista_previa()" value="Vista Previa" class="eforo_formulario"> <input type="submit" name="enviar" value="Enviar Mensaje" class="eforo_formulario"></center>
</td>
</tr>
</table>
</form>
<?
# * Si el formulario está en modo "responder" se muestran los últimos mensajes del tema
if($que == 2) {
	require_once('eforo_funciones/codigo.php') ;
?>
<script>
function proteger_email(usuario,servidor) {
	document.write('<a href="mailto:'+usuario+'@'+servidor+'" class="eforo">'+usuario+'@'+servidor+'</a>') ;
}
</script>
<table width="100%" border="0" cellpadding="3" cellspacing="1" align="center" class="eforo_tabla_principal">
<tr>
<td colspan="2" class="eforo_tabla_titulo"><div class="eforo_titulo_1">Ultimos <?=$conf[max_ultimos]?> mensajes del tema</div></td>
</tr>
<tr>
<td class="eforo_tabla_subtitulo"><div class="eforo_titulo_1">Autor</div></td>
<td class="eforo_tabla_subtitulo"><div class="eforo_titulo_1">Mensaje</div></td>
</tr>
<?
	$con = mysql_query("select * from eforo_mensajes where id_tema='$_GET[tema]' order by id desc limit $conf[max_ultimos]") ;
	while($datos = mysql_fetch_assoc($con)) {
		if($num == 2) {
			$num-- ;
		}
		else {
			$num++ ;
		}
		$con2 = mysql_query("select nick from $tabla_usuarios where id='$datos[id_usuario]'") ;
		if(mysql_num_rows($con2)) {
			$datos2 = mysql_fetch_assoc($con2) ;
			$autor_mensaje = "<a href=\"$u[0]usuarios$u[1]$u[2]miembro$u[4]$datos[id_usuario]$u[5]\" target=\"_blank\" class=\"eforo\">$datos2[nick]</a>" ;
		}
		else {
			$autor_mensaje = 'Invitad@' ;
		}
		if($conf[permitir_caretos] && $datos[o_caretos]) {
			$datos[mensaje] = caretos($datos[mensaje]) ;
		}
		if($conf[permitir_codigo] && $datos[o_codigo]) {
			$datos[mensaje] = codigo($datos[mensaje]) ;
		}
		if($conf[transformar_url] && $datos[o_url]) {
			$datos[mensaje] = url($datos[mensaje]) ;
		}
		if($conf[censurar_palabras]) {
			$datos[mensaje] = censurar($datos[mensaje]) ;
		}
		$datos[mensaje] = str_replace("\r",'<br>',$datos[mensaje]) ;
		if($datos[fecha_editado] > $datos[fecha]) {
			$editado = '<br><br><i><b>Editado por última vez el '.fecha($datos[fecha_editado]).'</b></i>' ;
		}
		else {
			$editado = false ;
		}
?>
<tr>
<td width="20%" valign="top" class="eforo_tabla_mensaje_<?=$num?>">
<?=$autor_mensaje?><br>
</td>
<td width="80%" valign="top" class="eforo_tabla_mensaje_<?=$num?>">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<td>Tema: <b><?=$datos[tema]?></b></td>
<td><div align="right">Fecha: <b><?=fecha($datos[fecha])?></b></div></td>
</tr>
</table>
<hr width="100%" color="505050"><?=$datos[mensaje].$editado?>
</td>
</tr>
<?
	}
?>
</table>
<?
}
?>
<br><br>
<center>
<span style="font-size: 7pt">
<a href="http://www.electros.net" target="_blank" class="eforo">eForo v3.0</a> © 2003-2005 Bajo licencia GNU General Public License<br>
<?
# * Obtener tiempo de carga del foro (la función tiempo() se encuentra en foroconfig.php)
$tiempo_b = tiempo() ;
$tiempo = round($tiempo_b - $tiempo_a,4) ;
echo 'Tiempo de carga del servidor: <b>'.$tiempo.'</b>' ;
?>
</span>
</center>
