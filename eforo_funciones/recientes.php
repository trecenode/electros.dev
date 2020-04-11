<?
/*
************************************************
*** eForo v3.0
*** Creado por: Electros <electros@electros.net>
*** Sitio web: www.electros.net
*** Licencia: GNU General Public License
************************************************

--- Página: recientes.php ---

eForo - Una comunidad para tus visitantes
Copyright © 2003-2004 Daniel Osorio "Electros"

Este programa es software libre, puedes redistribuirlo y/o modificarlo bajo los términos
de la GNU General Public License publicados por la Free Software Foundation; desde la
versión 2 de la licencia, o (si lo deseas) cualquiera más reciente.
*/

# * Revisar si hay mensajes nuevos desde la última visita del usuario
if($_COOKIE[e_nick]) {
	# Borrar mensajes del recordatorio (mensajes nuevos sin leer)
	$max_tiempo = 3600 ; # <-- Tiempo en segundos durante el cuál se recordarán los mensajes (por defecto 1 hora)
	$max_tiempo = $fecha-$max_tiempo ;
	mysql_query("delete from eforo_recientes where fecha<'$max_tiempo'") ;
	# Guardar los mensajes nuevos en el recordatorio
	$max_mensajes = 20 ; # <-- El máximo de mensajes nuevos que se recordarán por subforo
	$con = mysql_query("select id from eforo_foros order by id asc") ;
	while($datos = mysql_fetch_assoc($con)) {
		$id_foros[$datos[id]] = $datos[id] ;
	}
	mysql_free_result($con) ;
	foreach($id_foros as $id_foro) {
		$con = mysql_query("select id from eforo_mensajes where id=id_tema and id_foro='$id_foro' and fecha_ultimo>'$usuario[ultima_vez]' order by fecha_ultimo desc limit $max_mensajes") ;
		while($datos = mysql_fetch_assoc($con)) {
			mysql_query("insert into eforo_recientes (id_usuario,fecha,id_foro,id_mensaje) values ('$usuario[id]','$fecha','$id_foro','$datos[id]')") ;
		}
	}
	mysql_query("update $tabla_usuarios set conectado='$fecha' where id='$usuario[id]'") ;
}
?>
