<?
/*
************************************************
*** eForo v3.0
*** Creado por: Electros <electros@electros.net>
*** Sitio web: www.electros.net
*** Licencia: GNU General Public License
************************************************

--- P�gina: eforo_admin/configuracion.php ---

eForo - Una comunidad para tus visitantes
Copyright � 2003-2004 Daniel Osorio "Electros"

Este programa es de C�digo Abierto, puedes redistribuirlo y/o modificarlo bajo los t�rminos
de la GNU General Public License publicados por la Free Software Foundation; desde la
versi�n 2 de la licencia, o (si lo deseas) cualquiera m�s reciente.
*/

require('../foroconfig.php') ;
require('../eforo_funciones/sesion.php') ;
require('../eforo_funciones/aviso.php') ;

if(!$es_administrador) {
	echo "<script>top.location='$conf[url_foro]/$u[0]foro$u[1]$u[5]'</script>" ;
	exit ;
}

// --> Configuraci�n
if($_POST[enviar]) {
	require('../eforo_funciones/quitar.php') ;
	$c_htmlcab = quitar($_POST[c_htmlcab],1) ;
	$c_htmlpie = quitar($_POST[c_htmlpie],1) ;
	mysql_query("update eforo_config set
	administrador='$_POST[c_administrador]',
	email='$_POST[c_administrador_email]',
	titulo='$_POST[c_titulo_foro]',
	temas='$_POST[c_temas]',
	urlforo='$_POST[c_url_foro]',
	mensajes='$_POST[c_mensajes]',
	ultimos='$_POST[c_ultimos]',
	codigo='$_POST[c_codigo]',
	caretos='$_POST[c_caretos]',
	url='$_POST[c_url]',
	firma='$_POST[c_firma]',
	censurar='$_POST[c_censurar]',
	notificacion='$_POST[c_notificacion]',
	estilo='$_POST[c_estilo]',
	avatarlargo='$_POST[c_avatarlargo]',
	avatarancho='$_POST[c_avatarancho]',
	avatartamano='$_POST[c_avatartamano]',
	privados='$_POST[c_privados]',
	adjuntotamano='$_POST[c_adjuntotamano]',
	adjuntoext='$_POST[c_adjuntoext]',
	adjuntonombre='$_POST[c_adjuntonombre]'") ;
	aviso('Configuraci�n guardada','La configuraci�n ha sido guardada. Para regresar haz click <a href="configuracion.php" class="eforo">aqu�</a>.') ;
}
else {
?>
<form method="post" action="configuracion.php">
<table width="100%" border="0" cellpadding="3" cellspacing="1" class="tabla_principal">
<tr>
<td colspan="2" class="eforo_tabla_titulo"><div class="eforo_titulo_1">Configuraci�n</div></td>
</tr>
<tr>
<td colspan="2" class="eforo_tabla_subtitulo"><div class="eforo_titulo_1">General</div></td>
</tr>
<tr>
<td width="50%" class="eforo_tabla_defecto"><b>Administrador:</b><br>ID del administrador (ej. 5. Para 2 o m�s separa por comas ej. 5,12,150).</td>
<td width="50%" class="eforo_tabla_defecto"><input type="text" name="c_administrador" value="<?=implode(',',$conf[admin_id])?>" maxlength="20" class="eforo_formulario"></td>
</tr>
<tr>
<td class="eforo_tabla_defecto"><b>Email:</b><br>Este email se usar� como firma en algunas funciones del foro.</td>
<td class="eforo_tabla_defecto"><input type="text" name="c_administrador_email" value="<?=$conf[admin_email]?>" maxlength="100" class="eforo_formulario"></td>
</tr>
<tr>
<td class="eforo_tabla_defecto"><b>URL del foro:</b><br>Direcci�n web del foro (ej. http://www.pagina.com/carpeta).</td>
<td class="eforo_tabla_defecto"><input type="text" name="c_url_foro" value="<?=$conf[url_foro]?>" maxlength="100" class="eforo_formulario"></td>
</tr>
<tr>
<td class="eforo_tabla_defecto"><b>T�tulo:</b><br>T�tulo del foro.</td>
<td class="eforo_tabla_defecto"><input type="text" name="c_titulo_foro" value="<?=$conf[foro_titulo]?>" maxlength="100" class="eforo_formulario"></td>
</tr>
<tr>
<td class="eforo_tabla_defecto"><b>Temas:</b><br>Numero de temas a mostrar.</td>
<td class="eforo_tabla_defecto"><input type="text" name="c_temas" value="<?=$conf[max_temas]?>" maxlength="3" class="eforo_formulario"></td>
</tr>
<tr>
<td class="eforo_tabla_defecto"><b>Mensajes:</b><br>Mensajes a mostrar por tema.</td>
<td class="eforo_tabla_defecto"><input type="text" name="c_mensajes" value="<?=$conf[max_mensajes]?>" maxlength="3" class="eforo_formulario"></td>
</tr>
<tr>
<td class="eforo_tabla_defecto"><b>Ultimos mensajes:</b><br>Al querer responder un tema se ver�n los �ltimos mensajes.</td>
<td class="eforo_tabla_defecto"><input type="text" name="c_ultimos" value="<?=$conf[max_ultimos]?>" maxlength="3"class="eforo_formulario"></td>
</tr>
<tr>
<td class="eforo_tabla_defecto"><b>Permitir c�digo especial:</b><br>El c�digo especial sirve para personalizar los mensajes sin necesidad de usar HTML.</td>
<td class="eforo_tabla_defecto">
<input type="radio" name="c_codigo" value="0"<? if(!$conf[permitir_codigo]) echo ' checked' ; ?>>No
<input type="radio" name="c_codigo" value="1"<? if($conf[permitir_codigo]) echo ' checked' ; ?>>S�
</td>
</tr>
<tr>
<td class="eforo_tabla_defecto"><b>Permitir caretos:</b><br>Permitir uso de caretos en los mensajes.</td>
<td class="eforo_tabla_defecto">
<input type="radio" name="c_caretos" value="0"<? if(!$conf[permitir_caretos]) echo ' checked' ; ?>>No
<input type="radio" name="c_caretos" value="1"<? if($conf[permitir_caretos]) echo ' checked' ; ?>>S�
</td>
</tr>
<tr>
<td class="eforo_tabla_defecto"><b>Transformar URLs en enlaces:</b><br>Transforma autom�ticamente toda direcci�n del tipo http:// a un enlace.</td>
<td class="eforo_tabla_defecto">
<input type="radio" name="c_url" value="0"<? if(!$conf[transformar_url]) echo ' checked' ; ?>>No
<input type="radio" name="c_url" value="1"<? if($conf[transformar_url]) echo ' checked' ; ?>>S�
</td>
</tr>
<tr>
<td class="eforo_tabla_defecto"><b>Permitir firma en los mensajes:</b><br>Permite que se muestre una firma al final de cada mensaje.</td>
<td class="eforo_tabla_defecto">
<input type="radio" name="c_firma" value="0"<? if(!$conf[permitir_firma]) echo ' checked' ; ?>>No
<input type="radio" name="c_firma" value="1"<? if($conf[permitir_firma]) echo ' checked' ; ?>>S�
</td>
</tr>
<tr>
<td class="eforo_tabla_defecto"><b>Censurar palabras:</b><br>Sustituye las palabras censuradas por otras palabras que puedes definir.</td>
<td class="eforo_tabla_defecto">
<input type="radio" name="c_censurar" value="0"<? if(!$conf[censurar_palabras]) echo ' checked' ; ?>>No
<input type="radio" name="c_censurar" value="1"<? if($conf[censurar_palabras]) echo ' checked' ; ?>>S�
</td>
</tr>
<tr>
<td class="eforo_tabla_defecto"><b>Notificaci�n por email:</b><br>Permite que un usuario pueda pedir notificaci�n por email cuando
haya respuestas a su mensaje.</td>
<td class="eforo_tabla_defecto">
<input type="radio" name="c_notificacion" value="0"<? if(!$conf[notificacion_email]) echo ' checked' ; ?>>No
<input type="radio" name="c_notificacion" value="1"<? if($conf[notificacion_email]) echo ' checked' ; ?>>S�
</td>
</tr>
<tr>
<td class="eforo_tabla_defecto"><b>Estilo del foro:</b><br>Selecciona el estilo del foro (tipo de letra, colores, formularios).</td>
<td class="eforo_tabla_defecto">
<select name="c_estilo" class="eforo_formulario">
<?
$directorio = opendir('../eforo_estilo') ;
while($archivo = readdir($directorio)) {
if($archivo != '.' && $archivo != '..') {
if($archivo == $conf[estilo]) {
echo "<option value=\"$archivo\" selected>$archivo" ;
}
else {
echo "<option value=\"$archivo\">$archivo" ;
}
}
}
closedir($directorio) ;
?>
</select>
</td>
</tr>
<tr>
<td colspan="2" class="eforo_tabla_subtitulo"><div class="eforo_titulo_1">Mensajes privados</div></td>
</tr>
<tr>
<td class="eforo_tabla_defecto"><b>M�ximo de mensajes privados:</b><br>Es el n�mero m�ximo de mensajes privados que cada usuario podr� recibir.</td>
<td class="eforo_tabla_defecto"><input type="text" name="c_privados" value="<?=$conf[max_privados]?>" maxlength="3" class="eforo_formulario"></td>
</tr>
<tr>
<td colspan="2" class="eforo_tabla_subtitulo"><div class="eforo_titulo_1">Avatares</div></td>
</tr>
<tr>
<td class="eforo_tabla_defecto"><b>Tama�o de largo:</b><br>El largo en pixeles de la imagen.</td>
<td class="eforo_tabla_defecto"><input type="text" name="c_avatarlargo" value="<?=$conf[avatar_largo]?>" maxlength="3" class="eforo_formulario"></td>
</tr>
<tr>
<td class="eforo_tabla_defecto"><b>Tama�o de ancho:</b><br>El ancho en pixeles de la imagen.</td>
<td class="eforo_tabla_defecto"><input type="text" name="c_avatarancho" value="<?=$conf[avatar_ancho]?>" maxlength="3" class="eforo_formulario"></td>
</tr>
<tr>
<td class="eforo_tabla_defecto"><b>Tama�o del archivo</b><br>Tama�o del archivo en KB.</td>
<td class="eforo_tabla_defecto"><input type="text" name="c_avatartamano" value="<?=$conf[avatar_tamano]?>" maxlength="3" class="eforo_formulario"></td>
</tr>
<tr>
<td colspan="2" class="eforo_tabla_subtitulo"><div class="eforo_titulo_1">Adjuntos</div></td>
</tr>
<tr>
<td class="eforo_tabla_defecto"><b>Tama�o del archivo:</b><br>Tama�o del archivo adjunto en KB. El valor m�ximo permitido por
el servidor es de <b><?=@ini_get('upload_max_filesize') ? str_replace('M','',ini_get('upload_max_filesize')) * 1024 : 2048?> KB</b>. Este s�lo podr� ser modificado
desde el archivo de configuraci�n de PHP php.ini.</td>
<td class="eforo_tabla_defecto"><input type="text" name="c_adjuntotamano" value="<?=$conf[adjunto_tamano]?>" maxlength="5" class="eforo_formulario"></td>
</tr>
<tr>
<td class="eforo_tabla_defecto"><b>Extensiones:</b><br>Extensiones permitidas (escr�belas en min�sculas y separadas por saltos de l�nea).</td>
<td class="eforo_tabla_defecto"><textarea name="c_adjuntoext" cols="30" rows="5" class="eforo_formulario"><?=$conf[adjunto_ext]?></textarea></td>
</tr>
<tr>
<td class="eforo_tabla_defecto"><b>Longitud del nombre:</b><br>Longitud m�xima de caract�res en el nombre de archivo.</td>
<td class="eforo_tabla_defecto"><input type="text" name="c_adjuntonombre" value="<?=$conf[adjunto_nombre]?>" maxlength="2" class="eforo_formulario"></td>
</tr>
<tr>
<td colspan="2" class="eforo_tabla_defecto"><div align="center"><input type="submit" name="enviar" value="Guardar Configuraci�n" class="eforo_formulario"></div></td>
</tr>
</table>
</form>
<?
}
?>
