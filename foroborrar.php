<?
/*
************************************************
*** eForo v3.0
*** Creado por: Electros <electros@electros.net>
*** Sitio web: www.electros.net
*** Licencia: GNU General Public License
************************************************

--- Página: foroborrar.php ---

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

# * Comprobar permiso de usuario (esto se ignora si el usuario es un moderador o administrador)
if(!$es_moderador) {
	permiso('p_borrar') ;
}

# * Comprobar si el mensaje a borrar se trata de un tema o de una respuesta
$con = mysql_query("select count(id) from eforo_mensajes where id=id_tema and id='$_GET[mensaje]'") ;
$tema_inicial = mysql_result($con,0,0) ? 1 : 2 ;
mysql_free_result($con) ;
switch($tema_inicial) {
	# * Eliminar un tema completo
	case 1 :
		# --> Se comprueba si el tema pertenece al usuario
		$con = mysql_query("select id_usuario from eforo_mensajes where id='$_GET[tema]'") ;
		$datos = mysql_fetch_assoc($con) ;
		if($datos[id_usuario] != $usuario[id] && !$es_moderador) {
			aviso('Error','<p>Tu no puedes borrar este tema.') ;
			exit ;
		}
		$con = mysql_query("select id,id_usuario from eforo_mensajes where id_tema='$_GET[tema]' order by id asc") ;
		while($datos = mysql_fetch_assoc($con)) {
			mysql_query("update $tabla_usuarios set mensajes=mensajes-1 where id='$datos[id_usuario]'") ;
			$con2 = mysql_query("select id from eforo_adjuntos where id_mensaje='$datos[id]'") ;
			$datos2 = mysql_fetch_assoc($con2) ;
			if(file_exists("eforo_adjuntos/$datos2[id].dat")) {
				unlink("eforo_adjuntos/$datos2[id].dat") ;
			}
			mysql_free_result($con2) ;
			mysql_query("delete from eforo_adjuntos where id_mensaje='$datos[id]'") ;
		}
		$total_borrados = mysql_num_rows($con) ;
		mysql_free_result($con) ;
		mysql_query("delete from eforo_mensajes where id_tema='$_GET[tema]'") ;
		mysql_query("update eforo_foros set num_temas=num_temas-1,num_mensajes=num_mensajes-$total_borrados where id='$_GET[foro]'") ;
		aviso('Tema borrado',"<p>El tema y todos sus mensajes han sido borrados.<p><a href=\"$u[0]forotemas$u[1]$u[2]foro$u[4]$_GET[foro]$u[5]\" class=\"eforo\">» Regresar al foro</a>") ;
		break ;
	# * Eliminar un mensaje
	case 2 :
		# --> Se comprueba si el mensaje pertenece al usuario
		$con = mysql_query("select id_usuario from eforo_mensajes where id='$_GET[mensaje]' limit 1") ;
		$datos = mysql_fetch_assoc($con) ;
		if($datos[id_usuario] != $usuario[id] && !$es_moderador) {
			aviso('Error','<p>Tu no puedes borrar este mensaje.') ;
			exit ;
		}
		mysql_query("update $tabla_usuarios set mensajes=mensajes-1 where id='$datos[id_usuario]'") ;
		mysql_free_result($con) ;
		$con = mysql_query("select id from eforo_adjuntos where id_mensaje='$_GET[mensaje]'") ;
		$datos = mysql_fetch_assoc($con) ;
		if(file_exists("eforo_adjuntos/$datos[id].dat")) {
			unlink("eforo_adjuntos/$datos[id].dat") ;
		}
		mysql_free_result($con) ;
		mysql_query("delete from eforo_adjuntos where id_mensaje='$_GET[mensaje]'") ;
		mysql_query("delete from eforo_mensajes where id='$_GET[mensaje]'") ;
		mysql_query("update eforo_mensajes set num_respuestas=num_respuestas-1 where id='$_GET[tema]'") ;
		mysql_query("update eforo_foros set num_mensajes=num_mensajes-1 where id='$_GET[foro]'") ;
		aviso('Mensaje borrado',"<p>El mensaje ha sido borrado.<p><a href=\"$u[0]foromensajes$u[1]$u[2]foro$u[4]$_GET[foro]$u[3]tema$u[4]$_GET[tema]$u[3]p$u[4]$_GET[p]$u[5]\" class=\"eforo\">» Regresar al tema</a><p><a href=\"$u[0]forotemas$u[1]$u[2]foro$u[4]$_GET[foro]$u[5]\" class=\"eforo\">» Regresar al foro</a>") ;
}
?>
