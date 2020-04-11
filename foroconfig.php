<?
/*
************************************************
*** eForo v3.0
*** Creado por: Electros <electros@electros.net>
*** Sitio web: www.electros.net
*** Licencia: GNU General Public License
************************************************

--- Página: foroconfig.php ---

eForo - Una comunidad para que tus visitantes se comuniquen y se sientan parte de tu web
Copyright © 2003-2005 Daniel Osorio "Electros"

Este programa es software libre, puedes redistribuirlo y/o modificarlo bajo los términos
de la GNU General Public License publicados por la Free Software Foundation; desde la
versión 2 de la licencia, o (si lo deseas) cualquiera más reciente.
*/

# * El reporte de errores mostrará todo menos variables sin inicializar
error_reporting(E_WARNING | E_PARSE | E_ERROR) ;

# * Medir el tiempo de carga del foro
function tiempo() {
	list($a,$b) = explode(' ',microtime()) ;
	$tiempo = $b + $a ;
	return $tiempo ;
}
$tiempo_a = tiempo() ;

require('config.php') ;

$u = false ;

# *** Configuración avanzada

# * Integrar eForo dentro de tu web
# Si deseas integrar eForo como una sección de tu web y usas enlaces del tipo
# index.php?id=seccion debes poner la siguiente configuración:
# $u[0] = 'index.php?id=' ;
# $u[1] = '' ;
# $u[2] = '&' ;
# En caso de que no necesites esta opción deja los valores tal como están.
$u[0] = '' ;
$u[1] = '' ;
$u[2] = '/' ;

# * Uso con Mod_Rewrite de Apache
# Si deseas utilizar Mod_Rewrite puedes modificar también las siguientes variables de URL.
# Ejemplo: "index.php?seccion=foro&foro=1&tema=1" puede ser "index.php/seccion/foro/1/tema/1.html".
# En este caso necesitaremos las siguientes variables:
# $u[3] = '/' ;
# $u[4] = '/' ;
# $u[4] = '.html' ;
# Si no necesitas esta opción deja los valores tal como están.
$u[3] = '/' ;
$u[4] = '/' ;
$u[5] = '' ;

# * Compatibilidad con el sistema "Registro de usuarios" (que puedes encontrar en www.electros.net)
# Si ya estás usando el sistema "Registro de usuarios" y quieres aprovechar esta base de datos para que
# tus usuarios no se tengan que registrar 2 veces, escribe el nombre de la tabla donde se almacenan tus
# usuarios.
$tabla_usuarios = 'usuarios' ;

# *** Fin configuración avanzada

# * Comprobar datos insertados mediante la URL (ejem. foro.php?foro=1&tema=1)
# Con esto se evitarán ataques de SQL Injection y otros parecidos
$error = false ;
if($_GET[foro]) {
	if(ereg('^[0-9]+$',$_GET[foro])) {
		# --> Comprueba si existe el subforo
		$con = mysql_query("select id from eforo_foros where id='$_GET[foro]'") ;
		if(!mysql_num_rows($con)) {
			$error = 'No existe el subforo.' ;
		}
		mysql_free_result($con) ;
	}
	else {
		$error = 'Intento de ataque.' ;
	}
}
if($_GET[tema]) {
	if(ereg('^[0-9]+$',$_GET[tema])) {
		# --> Comprueba si existe el tema
		$con = mysql_query("select id from eforo_mensajes where id_foro='$_GET[foro]' and id='$_GET[tema]'") ;
		if(!mysql_num_rows($con)) {
			$error = 'No existe el tema.' ;
		}
		mysql_free_result($con) ;
	}
	else {
		$error = 'Intento de ataque.' ;
	}
}
if($_GET[mensaje]) {
	if(ereg('^[0-9]+$',$_GET[mensaje])) {
		# --> Comprueba si existe el mensaje
		$con = mysql_query("select id from eforo_mensajes where id_foro='$_GET[foro]' and id_tema='$_GET[tema]' and id='$_GET[mensaje]'") ;
		if(!mysql_num_rows($con)) {
			$error = 'No existe el mensaje.' ;
		}
		mysql_free_result($con) ;
	}
	else {
		$error = 'Intento de ataque.' ;
	}
}
if($error) {
?>
<style>
body { font-family: verdana ; font-size: 10pt }
</style>
<p><b>Error</b>
<p><?=$error?>
<script>setTimeout('history.back()',1500)</script>
<?
exit ;
}

# * Cargar configuración del foro (todo se guardará en un array llamado $conf)
$conf = false ;
$con = mysql_query('select * from eforo_config limit 1') ;
$datos = mysql_fetch_assoc($con) ;
$conf[admin_id] = explode(',',$datos[administrador]) ;
$conf[admin_email] = $datos[email] ;
$conf[url_foro] = $datos[urlforo] ;
$conf[foro_titulo] = $datos[titulo] ;
$conf[max_temas] = $datos[temas] ;
$conf[max_mensajes] = $datos[mensajes] ;
$conf[max_ultimos] = $datos[ultimos] ;
$conf[permitir_codigo] = $datos[codigo] ;
$conf[permitir_caretos] = $datos[caretos] ;
$conf[transformar_url] = $datos[url] ;
$conf[permitir_firma] = $datos[firma] ;
$conf[censurar_palabras] = $datos[censurar] ;
$conf[notificacion_email] = $datos[notificacion] ;
$conf[estilo] = $datos[estilo] ;
$conf[avatar_largo] = $datos[avatarlargo] ;
$conf[avatar_ancho] = $datos[avatarancho] ;
$conf[avatar_tamano] = $datos[avatartamano] ;
$conf[max_privados] = $datos[privados] ;
$conf[adjunto_tamano] = $datos[adjuntotamano] ;
$conf[adjunto_ext] = explode("\r\n",$datos[adjuntoext]) ;
$conf[adjunto_nombre] = $datos[adjuntonombre] ;

# * Obtener datos del usuario que está conectado en este momento
if($_COOKIE[e_nick]) {
	$con = mysql_query("select id,mensajes,rango,rango_fijo,conectado,gmt from $tabla_usuarios where nick='$_COOKIE[e_nick]'") ;
	$datos = mysql_fetch_assoc($con) ;
	# Id del usuario
	$usuario[id] = $datos[id] ;
	# Rango actual
	if(!$datos[rango_fijo]) {
		$usuario[rango] = 1 ;
		$con2 = mysql_query("select rango,minimo from eforo_rangos where minimo!='0' order by rango asc") ;
		while($datos2 = mysql_fetch_assoc($con2)) {
			if($datos[mensajes] >= $datos2[minimo]) {
				$usuario[rango] = $datos2[rango] ;
				break ;
			}
		}
		mysql_free_result($con2) ;
	}
	else {
		$usuario[rango] = $datos[rango] ;
	}
	# Fecha de la ultima vez que estuvo en el foro
	$usuario[ultima_vez] = $datos[conectado] ;
	# La zona horaria o GMT (diferencia de horas)
	$usuario[gmt] = $datos[gmt] ;
	mysql_free_result($con) ;
}
else {
	$usuario[rango] = 0 ;
	$usuario[gmt] = 0 ;
}

# * La fecha que será usada en el foro (por defecto se usará la fecha GMT)
# Esta fecha se guardará en todos los mensajes así como en todas las funciones del foro,
# después mediante la zona GMT que eligió el usuario se le sumará o restará la diferencia
# de horas a la fecha guardada y con una simple llamada a date() obtendremos esa fecha
$fecha = strtotime(gmdate('d M Y H:i:s')) ;

# * Fecha corta (Formato: 1 Ene 2004 12:00 AM)
function fecha($a) {
	$fecha_actual = $GLOBALS[fecha] - $a ;
	switch (true) {
		case $fecha_actual > 0 && $fecha_actual < 3600 :
			$minutos = round($fecha_actual / 60) ;
			return ($minutos == 0 || $minutos == 1) ? 'Hace 1 minuto' : "Hace $minutos minutos" ;
			break ;
		case $fecha_actual >= 3600 && $fecha_actual < 86400 :
			$horas = round($fecha_actual / 3600) ;
			return ($horas == 1) ? 'Hace 1 hora' : "Hace $horas horas" ;
			break ;
		default :
			$gmt = $a + 3600 * $GLOBALS[usuario][gmt] ;
			$meses = array('','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic') ;
			return date('j',$gmt).' '.$meses[date('n',$gmt)].' '.date('Y',$gmt).' '.date('h:i A',$gmt) ;
	}
}

# * Se obtienen los usuarios en línea en el foro
$tiempo_limite = 600 ; # <-- Tiempo en segundos en el cuál se mantendrá al usuario en línea
$fecha_limite = $fecha - $tiempo_limite ;
# --> Se eliminan los usuarios que superaron el tiempo límite
mysql_query("delete from eforo_enlinea where fecha<'$fecha_limite'") ;
# --> Si es un usuario registrado se guarda su ID
if($_COOKIE[e_nick]) {
	$con = mysql_query("select * from eforo_enlinea where id_usuario='$usuario[id]'") ;
	if(mysql_num_rows($con)) {
		mysql_query("update eforo_enlinea set fecha='$fecha' where id_usuario='$usuario[id]'") ;
	}
	else {
		mysql_query("delete from eforo_enlinea where ip='$_SERVER[REMOTE_ADDR]'") ;
		mysql_query("insert into eforo_enlinea (fecha,id_usuario) values ('$fecha','$usuario[id]')") ;
	}
	mysql_free_result($con) ;
}
# --> Si es un usuario no registrado se guarda su IP
else {
	$con = mysql_query("select * from eforo_enlinea where ip='$_SERVER[REMOTE_ADDR]'") ;
	if(mysql_num_rows($con)) {
		mysql_query("update eforo_enlinea set fecha='$fecha' where ip='$_SERVER[REMOTE_ADDR]'") ;
	}
	else {
		mysql_query("insert into eforo_enlinea (fecha,ip) values ('$fecha','$_SERVER[REMOTE_ADDR]')") ;
	}
	mysql_free_result($con) ;
}
# --> Se obtiene el total de usuarios
# No registrados
$con = mysql_query("select ip from eforo_enlinea where ip!=''") ;
$total_enlinea[0] = mysql_num_rows($con) ;
mysql_free_result($con) ;
# Registrados
$con = mysql_query("select id_usuario from eforo_enlinea where id_usuario!='0' order by fecha asc") ;
$total_enlinea[1] = mysql_num_rows($con) ;
# Total
$total_enlinea[2] = $total_enlinea[0] + $total_enlinea[1] ;
# --> Se obtiene el nick de los usuarios registrados en línea
$reg_enlinea = false ;
for($i = 1 ; $datos = mysql_fetch_assoc($con) ; $i++) {
	$con2 = mysql_query("select nick from $tabla_usuarios where id='$datos[id_usuario]'") ;
	$datos2 = mysql_fetch_assoc($con2) ;
	if($i < $total_enlinea[1]) {
		$reg_enlinea .= "<a href=\"$u[0]usuarios$u[1]$u[2]u$u[4]$datos[id_usuario]$u[5]\" class=\"eforo\">$datos2[nick]</a>, " ;
	}
	else {
		$reg_enlinea .= "<a href=\"$u[0]usuarios$u[1]$u[2]u$u[4]$datos[id_usuario]$u[5]\" class=\"eforo\">$datos2[nick]</a>" ;
	}
	mysql_free_result($con2) ;
}
mysql_free_result($con) ;

# * Cargar estilo del foro (fuentes, colores, diseño de tablas)
require("eforo_estilo/$conf[estilo]/$conf[estilo].php") ;
?>
