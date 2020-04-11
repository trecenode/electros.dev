<?
/*
************************************************
*** eForo v3.0
*** Creado por: Electros <electros@electros.net>
*** Sitio web: www.electros.net
*** Licencia: GNU General Public License
************************************************

--- Página: privados.php ---

eForo - Una comunidad para que tus visitantes se comuniquen y se sientan parte de tu web
Copyright © 2003-2005 Daniel Osorio "Electros"

Este programa es software libre, puedes redistribuirlo y/o modificarlo bajo los términos
de la GNU General Public License publicados por la Free Software Foundation; desde la
versión 2 de la licencia, o (si lo deseas) cualquiera más reciente.
*/
require('foroconfig.php') ;
require('eforo_funciones/aviso.php') ;
require('eforo_funciones/sesion.php') ;
require('eforo_funciones/epaginas.php') ;

if($_POST[enviar]) {
	require('eforo_funciones/quitar.php') ;
	$p_destinatario = quitar($_POST[p_destinatario]) ;
	$p_mensaje = quitar($_POST[p_mensaje]) ;
	$con = mysql_query("select id from $tabla_usuarios where nick='$p_destinatario'") ;
	if(mysql_num_rows($con)) {
		$datos = mysql_fetch_assoc($con) ;
		$con2 = mysql_query("select count(id) from eforo_privados where id_destinatario='$datos[id]'") ;
		if(mysql_result($con2,0,0) < $conf[max_privados]) {
			mysql_query("insert into eforo_privados (fecha,id_remitente,id_destinatario,mensaje) values ('$fecha','$usuario[id]','$datos[id]','$p_mensaje')") ;
			aviso('Mensaje enviado','El mensaje ha sido enviado a <b>'.$_POST[p_destinatario].'</b>') ;
		}
		else {
			aviso('Error','La bandeja de <b>'.$_POST[p_destinatario].'</b> está llena. No se pudo enviar el mensaje.') ;
		}
		mysql_free_result($con2) ;
	}
	else {
		aviso('Error','El destinatario <b>'.$_POST[p_destinatario].'</b> no existe.') ;
	}
	mysql_free_result($con) ;
}
if(ereg('^[0-9]+$',$_GET[borrar])) {
	mysql_query("delete from eforo_privados where id='$_GET[borrar]'") ;
	aviso('Mensaje eliminado',"<p>El mensaje ha sido eliminado.<p><a href=\"$u[0]privados$u[1]$u[5]\">» Regresar</a>") ;
	exit ;
}
?>
<table width="100%" border="0" cellpadding="3" cellspacing="1" class="eforo_tabla_principal">
<tr>
<td colspan="2" class="eforo_tabla_defecto"><a href="<?="$u[0]foro$u[1]$u[5]"?>" class="eforo">» Regresar</a></td>
</tr>
<tr>
<td colspan="2" class="eforo_tabla_titulo"><div class="eforo_titulo_1">Mensajes privados</div></td>
</tr>
<tr>
<td colspan="2" valign="top" class="eforo_tabla_defecto">
<?
$ePaginas = new ePaginas("select * from eforo_privados where id_destinatario='$usuario[id]' order by id desc",10) ;
$con = $ePaginas->consultar() ;
$barra_porcentaje = round($ePaginas->total_res/$conf[max_privados],2) * 100 ;
if($barra_porcentaje < 90) {
	$barra_color = '#00c000' ;
}
else {
	$barra_color = '#c00000' ;
}
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td valign="top">
<script>
maximo = 1024 ;
function caracteres(texto) {
	if(texto.value.length > maximo)
	texto.value = texto.value.substring(0,maximo) ;
	else
	document.p_formulario.p_contador.value = maximo - texto.value.length ;
}
function revisar() {
	if(document.p_formulario.p_destinatario.value.length < 3) {
		alert('Debes escribir un destinatario') ;
		return false ;
	}
	if(document.p_formulario.p_mensaje.value.length < 1) {
		alert('Debes escribir un mensaje') ;
		return false ;
	}
}
</script>
<p><b>Enviar mensaje</b>
<p>
<form name="p_formulario" method="post" action="<?="$u[0]privados$u[1]$u[5]"?>" onsubmit="return revisar()">
<b>Destinatario:</b><br>
<input type="text" name="p_destinatario" value="<?=$p_destinatario?>" maxlength="20" size="15" class="eforo_formulario"><br>
<b>Mensaje:</b><br>
<textarea name="p_mensaje" cols="30" rows="5" onkeypress="caracteres(this)" class="eforo_formulario"><?=$p_mensaje?></textarea><br>
<input type="text" name="p_contador" size="5" class="eforo_formulario"><br><br>
<input type="submit" name="enviar" value="Enviar Mensaje" class="eforo_formulario">
</td></form>
<td valign="top">
<div align="right">
<p>Mensajes: <b><?=$ePaginas->total_res?>/<?=$conf[max_privados]?></b>
<table width="100" cellpadding="0" cellspacing="0" style="border: #000000 ; border-width: 1px ; border-style: solid ; background: #ffffff">
<td>
<table width="<?=$barra_porcentaje?>%" style="background: <?=$barra_color?>"><td></td></table>
</td>
</table>
</div>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td colspan="2" class="eforo_tabla_defecto"><?=$ePaginas->paginar()?></td>
</tr>
<?
$num = 1 ;
while($datos = mysql_fetch_assoc($con)) {
	$con2 = mysql_query("select id,nick,avatar from $tabla_usuarios where id='$datos[id_remitente]'") ;
	$datos2 = mysql_fetch_assoc($con2) ;
	$datos2[mensaje] = str_replace("\n",'<br>',$datos2[mensaje]) ;
	$avatar = $datos2[avatar] ? "<img src=\"eforo_imagenes/avatares/$datos2[id].$datos2[avatar]\" alt=\"Avatar de $datos2[nick]\">" : false ;
?>
<tr>
<td width="20%" valign="top" class="eforo_tabla_mensaje_<?=$num?>"><a href="<?="$u[0]usuarios$u[1]$u[2]u$u[4]$datos[id]$u[5]"?>" target="_blank" class="eforo"><?=$datos2[nick]?></a><br><br><?=$avatar?></td>
<td width="80%" valign="top" class="eforo_tabla_mensaje_<?=$num?>"><b>Fecha:</b> <?=fecha($datos[fecha])?><hr width="100" color="505050" align="left"><?=$datos[mensaje]?></td>
</tr>
<tr>
<td class="eforo_tabla_mensaje_<?=$num?>">&nbsp;</td>
<td class="eforo_tabla_mensaje_<?=$num?>">
<input type="button" value="Responder" onclick="document.p_formulario.p_destinatario.value = '<?=urlencode($datos2[nick])?>' ; document.p_formulario.p_mensaje.focus() ; location = '#'" class="eforo_formulario">
<input type="button" value="Borrar" onclick="if(confirm('¿Deseas borrar el mensaje?'))location='<?="$conf[url_foro]/$u[0]privados$u[1]$u[2]borrar$u[4]$datos[id]$u[5]"?>'" class="eforo_formulario">
</td>
</tr>
<?
	if(!$datos[leido]) {
		mysql_query("update eforo_privados set leido='1' where id='$datos[id]'") ;
	}
	mysql_free_result($con2) ;
	$num = ($num == 1) ? 2 : 1 ;
}
mysql_free_result($con) ;
?>
<tr>
<td colspan="2" class="eforo_tabla_defecto"><?=$ePaginas->paginar()?></td>
</tr>
</table>
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
