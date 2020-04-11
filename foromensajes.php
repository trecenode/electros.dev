<?
/*
************************************************
*** eForo v3.0
*** Creado por: Electros <electros@electros.net>
*** Sitio web: www.electros.net
*** Licencia: GNU General Public License
************************************************

--- Página: foromensajes.php ---

eForo - Una comunidad para que tus visitantes se comuniquen y se sientan parte de tu web
Copyright © 2003-2005 Daniel Osorio "Electros"

Este programa es software libre, puedes redistribuirlo y/o modificarlo bajo los términos
de la GNU General Public License publicados por la Free Software Foundation; desde la
versión 2 de la licencia, o (si lo deseas) cualquiera más reciente.
*/

require('foroconfig.php') ;
require('eforo_funciones/recientes.php') ;
require('eforo_funciones/codigo.php') ;
require('eforo_funciones/menu.php') ;
require('eforo_funciones/epaginas.php') ;

echo "<title>$conf[foro_titulo] · $titulo_subforo_2 · $titulo_tema_2</title>" ;

# * Se comprueba si el usuario tiene permiso para entrar al subforo seleccionado
$con = mysql_query("select p_leer from eforo_foros where id='$_GET[foro]' limit 1") ;
$datos = mysql_fetch_assoc($con) ;
if($usuario[rango] < $datos[p_leer]) {
	require('eforo_funciones/aviso.php') ;
	aviso('Nivel insuficiente','No tienes suficiente nivel para entrar a este subforo. Intenta iniciar sesión desde el menú.') ;
}
else {
# * Se almacenan todos los rangos en un array
$con = mysql_query('select * from eforo_rangos order by rango asc') ;
while($datos = mysql_fetch_assoc($con)) {
$rangos[$datos[rango]] = $datos[minimo].'||'.$datos[descripcion] ;
}
mysql_free_result($con) ;

# * Guardar lista de usuarios en línea en un array (para ahorrar consultas MySQL al servidor)
$con = mysql_query("select id_usuario from eforo_enlinea order by fecha asc") ;
while($datos = mysql_fetch_assoc($con)) {
	$enlinea[] = $datos[id_usuario] ;
}
mysql_free_result($con) ;

# * Mostrar el título del tema
$con = mysql_query("select tema from eforo_mensajes where id='$_GET[tema]'") ;
$datos = mysql_fetch_assoc($con) ;
?>
<title><?=$conf[foro_titulo]?> · Foros · <?=$datos[tema]?></title>
<?
mysql_free_result($con) ;

# **********************************************
# *** Mostrar los mensajes del tema seleccionado
# **********************************************

# * Notificación por email cuando haya respuestas
if($conf[notificacion_email]) {
	# Después de enviar la notificación se dejan de enviar más hasta que el usuario visite el tema
	$con = mysql_query("select id_usuario,o_notificacion,o_notificacion_email from eforo_mensajes where id='$_GET[tema]'") ;
	$datos = mysql_fetch_assoc($con) ;
	# Si el usuario ya está visitando el tema se reactivan las notificaciones
	if($datos[id_usuario] == $usuario[id] && $datos[o_notificacion] == 1 && $datos[o_notificacion_email] == 0) {
		mysql_query("update eforo_mensajes set o_notificacion_email='1' where id='$_GET[tema]'") ;
	}
	mysql_free_result($con) ;
}

# * Se suma una visita al tema seleccionado
mysql_query("update eforo_mensajes set num_visitas=num_visitas+1 where id='$_GET[tema]'") ;
# * Se considera visto el tema y se elimina del recordatorio
if($_COOKIE[e_nick]) {
	mysql_query("delete from eforo_recientes where id_usuario='$usuario[id]' and id_mensaje='$_GET[tema]'") ;
}
$ePaginas = new ePaginas("select * from eforo_mensajes where id_tema='$_GET[tema]' order by id asc",$conf['max_mensajes']) ;
$con = $ePaginas->consultar() ;
?>
<script>
function borrar(foro,tema,mensaje,tema_inicial) {
	if(tema_inicial == 1) {
		aviso = 'el tema junto con todos sus mensajes' ;
	}
	else {
		aviso = 'este mensaje' ;
	}
	if(confirm('¿Deseas borrar '+aviso+'?')) {
		location = '<?="$conf[url_foro]/$u[0]foroborrar$u[1]$u[2]foro$u[4]'+foro+'$u[3]tema$u[4]'+tema+'$u[3]mensaje$u[4]'+mensaje+'$u[3]p$u[4]$_GET[p]$u[5]"?>' ;
	}
}

function proteger_email(usuario,servidor) {
	document.write('<a href="mailto:'+usuario+'@'+servidor+'" class="eforo">'+usuario+'@'+servidor+'</a>') ;
}
</script>
<table width="100%" border="0" cellpadding="3" cellspacing="1" align="center" class="eforo_tabla_principal">
<tr>
<td class="eforo_tabla_titulo"><div class="eforo_titulo_1">Autor</div></td>
<td class="eforo_tabla_titulo"><div class="eforo_titulo_1">Mensaje</div></td>
</tr>
<tr>
<td colspan="2" class="eforo_tabla_defecto"><?=$ePaginas->paginar()?></td>
</tr>
<?
$num = 1 ;
while($datos = mysql_fetch_assoc($con)) {
	# Se obtienen los datos del autor del mensaje
	$con2 = mysql_query("select nick,avatar,mensajes,rango,rango_fijo from $tabla_usuarios where id='$datos[id_usuario]'") ;
	if(mysql_num_rows($con2)) {
		$datos2 = mysql_fetch_assoc($con2) ;
		# --> Autor
		$autor_nick = "<a href=\"$u[0]usuarios$u[1]$u[2]u$u[4]$datos[id_usuario]$u[5]\" class=\"eforo\">$datos2[nick]</a>" ;
		# --> Rango (se usa el array $rangos creado más arriba)
		if($datos2[rango_fijo]) {
			list($minimo,$descripcion) = explode('||',$rangos[$datos2[rango]]) ;
			$autor_rango = $descripcion ;
		}
		else {
			list($minimo,$descripcion) = explode('||',$rangos[1]) ;
			$autor_rango = $descripcion ;
			foreach($rangos as $a) {
				list($minimo,$descripcion) = explode('||',$a) ;
				if($minimo != 0 && $datos2[mensajes] >= $minimo) {
					$autor_rango = $descripcion ;
				}
			}
		}
		$autor_rango = "<span style=\"font-size: 7pt\"><b>$autor_rango</b></span>" ;
		# --> Total de mensajes enviados
		$autor_total = "<span style=\"font-size: 7pt\">Mensajes: <b>$datos2[mensajes]</b></span>" ;
		# --> Avatar
		if($datos2[avatar]) {
			$autor_avatar = "<img src=\"eforo_imagenes/avatares/$datos[id_usuario].$datos2[avatar]\" alt=\"Avatar\">" ;
		}
		else {
			$autor_avatar = false ;
		}
		# --> Estado en línea
		foreach($enlinea as $a) {
			if($a == $datos[id_usuario]) {
				$autor_enlinea = 'Conectad@' ;
				break ;
			}
			else {
				$autor_enlinea = 'Desconectad@' ;
			}
		}
		$autor_enlinea = "<span style=\"font-size: 7pt\">Estado: <b>$autor_enlinea</b></span>" ;
	}
	else {
		# --> Si no es un usuario registrado sólo se mostrará "Invitad@"
		$autor_nick = '<i>Anónim@</i>' ;
		$autor_rango = false ;
		$autor_total = false ;
		$autor_enlinea = false ;
		$autor_avatar = false ;
	}
	mysql_free_result($con2) ;
	if(!$datos[tema]) {
		$datos[tema] = 'RE: '.$titulo_tema_2 ; # <-- $titulo_tema_2 creado en foromenu.php
	}
	# Se usan las funciones del código especial
	if($conf[permitir_codigo] && $datos[o_codigo]) {
		$datos[mensaje] = codigo($datos[mensaje]) ;
	}
	if($conf[permitir_caretos] && $datos[o_caretos]) {
		$datos[mensaje] = caretos($datos[mensaje]) ;
	}
	if($conf[transformar_url] && $datos[o_url]) {
		$datos[mensaje] = url($datos[mensaje]) ;
	}
	if($conf[permitir_firma] && $datos[o_firma]) {
		$con2 = mysql_query("select firma from $tabla_usuarios where id='$datos[id_usuario]'") ;
		$datos2 = mysql_fetch_assoc($con2) ;
		if($firma = $datos2[firma]) {
			$firma = '<hr width="100" color="#505050" align="left">'.str_replace("\n",'<br>',codigo($firma)) ;
		}
		else {
			$firma = false ;
		}
	}
	else {
		$firma = false ;
	}
	if($conf[censurar_palabras]) {
		$datos[mensaje] = censurar($datos[mensaje]) ;
	}
	# Crea los saltos de línea reemplazando los caractéres especiales por código HTML
	$datos[mensaje] = str_replace("\r",'<br>',$datos[mensaje]) ;
	# Si el mensaje ha sido editado se muestra la fecha de la última vez que se editó
	if($datos[fecha_editado] > $datos[fecha]) {
		$editado = '<br><br><i><b>Editado: '.fecha($datos[fecha_editado]).'</b></i>' ;
	}
	else {
		$editado = false ;
	}
	# Si el mensaje es el tema inicial la opción de borrar eliminará el tema junto con todos sus mensajes
	if($datos[id] == $datos[id_tema]) {
		$tema_inicial = 1 ;
	}
	else {
		$tema_inicial = 2 ;
	}
	# * Si el mensaje tiene un archivo adjunto se muestra para poder descargarlo
	$con2 = mysql_query("select id,archivo from eforo_adjuntos where id_mensaje='$datos[id]' limit 1") ;
	if(mysql_num_rows($con2)) {
		$datos2 = mysql_fetch_assoc($con2) ;
		$adjunto = '<br><br><hr width="100" color="505050" align="left">Archivo adjunto: <a href="foroadjuntos.php?id='.$datos2[id].'" class="eforo">'.$datos2[archivo].'</a>' ;
	}
	else {
		$adjunto = false ;
	}
?>
<tr>
<td width="20%" valign="top" class="eforo_tabla_mensaje_<?=$num?>">
<?=$autor_nick?><br>
<?=$autor_rango?><br>
<?=$autor_total?><br>
<?=$autor_enlinea?><br><br>
<?=$autor_avatar?>
<a name="<?=$datos[id]?>"></a>
</td>
<td width="80%" valign="top" class="eforo_tabla_mensaje_<?=$num?>">
Tema: <b><?=$datos[tema]?></b>
<hr width="100%" color="505050"><?=$datos[mensaje].$adjunto.$firma.$editado?>
</td>
</tr>
<tr>
<td class="eforo_tabla_mensaje_<?=$num?>"><center><span style="font-size: 7pt"><b><?=fecha($datos[fecha])?></b></span></center></td>
<td class="eforo_tabla_mensaje_<?=$num?>">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td>
<input type="button" value="Editar" onclick="location='<?="$conf[url_foro]/$u[0]foroescribir$u[1]$u[2]foro$u[4]$_GET[foro]$u[3]tema$u[4]$_GET[tema]$u[3]mensaje$u[4]$datos[id]$u[3]p$u[4]$_GET[p]$u[5]"?>'" class="eforo_formulario">
<input type="button" value="Borrar" onclick="borrar(<?=$_GET[foro]?>,<?=$_GET[tema]?>,<?=$datos[id]?>)" class="eforo_formulario">
</td>
<td><div align="right"><input type="button" value="Citar Mensaje" onclick="location='<?="$conf[url_foro]/$u[0]foroescribir$u[1]$u[2]foro$u[4]$_GET[foro]$u[3]tema$u[4]$_GET[tema]$u[3]citar$u[4]$datos[id]$u[5]"?>'" class="eforo_formulario"></div></td>
</tr>
</table>
</td>
</tr>
<?
	# Se alternan los colores para cada fila (los estilos usados son .eforo_mensajes_1 y .eforo_mensajes_2)
	$num = ($num == 1) ? 2 : 1 ;
}
mysql_free_result($con) ;
?>
<tr>
<td colspan="2" class="eforo_tabla_defecto"><?=$ePaginas->paginar()?></td>
</tr>
<tr>
<td class="eforo_tabla_titulo"><div class="eforo_titulo_1">Autor</div></td>
<td class="eforo_tabla_titulo"><div class="eforo_titulo_1">Mensaje</div></td>
</tr>
</table>
<script>
enviado = 0 ;
function revisar() {
document.m_formulario.m_mensaje.value = document.m_formulario.m_mensaje.value.replace(/^\s*|\s*$/g,'') ;
if(document.m_formulario.m_mensaje.value.length < 3) { alert('Debes escribir un mensaje') ; return false ; }
if(enviado == 0) { enviado++ ; } else { alert('El mensaje se está enviando por favor espera.') ; return false ; }
}
</script>
<?
$m_pag = $_GET[p] ? "$u[3]p$u[4]$_GET[p]" : false ;
?>
<form method="post" action="<?="$u[0]foroescribirpro$u[1]$u[2]foro$u[4]$_GET[foro]$u[3]tema$u[4]$_GET[tema]$m_pag$u[5]"?>" onsubmit="return revisar()">
<input type="hidden" name="que" value="2">
<input type="hidden" name="permiso" value="p_responder">
<input type="hidden" name="m_caretos" value="1">
<input type="hidden" name="m_codigo" value="1">
<input type="hidden" name="m_url" value="1">
<input type="hidden" name="m_firma" value="1">
<table width="100%" border="0" cellpadding="3" cellspacing="1" class="eforo_tabla_principal">
<tr>
<td class="eforo_tabla_titulo"><div class="eforo_titulo_1">Respuesta rápida</div></td>
</tr>
<tr>
<td class="eforo_tabla_defecto"><textarea name="m_mensaje" cols="50" rows="10" class="eforo_formulario"></textarea></td>
</tr>
<tr>
<td class="eforo_tabla_defecto"><center><input type="submit" name="enviar" value="Enviar Mensaje" class="eforo_formulario"></center></td>
</tr>
</table>
</form>
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