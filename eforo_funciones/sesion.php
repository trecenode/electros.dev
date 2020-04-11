<?
/*
************************************************
*** eForo v3.0
*** Creado por: Electros <electros@electros.net>
*** Sitio web: www.electros.net
*** Licencia: GNU General Public License
************************************************

--- Página: sesion.php ---

eForo - Una comunidad para tus visitantes
Copyright © 2003-2004 Daniel Osorio "Electros"

Este programa es software libre, puedes redistribuirlo y/o modificarlo bajo los términos
de la GNU General Public License publicados por la Free Software Foundation; desde la
versión 2 de la licencia, o (si lo deseas) cualquiera más reciente.
*/

# * Comprueba si el usuario ha iniciado sesión y si los datos de las "cookies" están correctos
if($_COOKIE[e_nick]) {
	$con = mysql_query("select id from $tabla_usuarios where nick='$_COOKIE[e_nick]' and contrasena='$_COOKIE[e_contrasena]'") ;
	if(!mysql_num_rows($con)) {
		header("location: $url_foro/$u[0]usuario$u[1]$u[2]que$u[4]entrar$u[5]") ;
		exit ;
	}
	mysql_free_result($con) ;
}

# * Si el usuario es un moderador o administrador se ignora su rango o nivel
$es_moderador = false ;
$con = mysql_query("select id from eforo_moderadores where id_foro='$_GET[foro]' and id_usuario='$usuario[id]'") ;
foreach($conf[admin_id] as $id_admin) {
	if(mysql_num_rows($con) != 0 || $id_admin == $usuario[id]) {
		$es_moderador = true ;
		break ;
	}
}
mysql_free_result($con) ;

# * Se comprueba si el usuario tiene el permiso suficiente para realizar una determinada acción
if(!$es_moderador) {
	function permiso($permiso) {
		$con = mysql_query("select $permiso from eforo_foros where id='$_GET[foro]'") ;
		$datos = mysql_fetch_assoc($con) ;
		if($GLOBALS[usuario][rango] < $datos[$permiso]) {
			aviso('Nivel mínimo insuficiente',"<p>No tienes suficiente nivel. Intenta ingresar como usuario desde el menú.<p><a href=\"javascript:history.back()\" class=\"eforo\">» Regresar</a>") ;
			exit ;
		}
		mysql_free_result($con) ;
	}
}

# * Si el usuario es un administrador se le otorgarán privilegios de administración
$es_administrador = false ;
foreach($conf[admin_id] as $id_admin) {
	if($id_admin == $usuario[id]) {
		$es_administrador = true ;
		break ;
	}
}
?>
