<?
/*
************************************************
*** eForo v3.0
*** Creado por: Electros <electros@electros.net>
*** Sitio web: www.electros.net
*** Licencia: GNU General Public License
************************************************

--- Página: foroadjuntos.php ---

eForo - Una comunidad para que tus visitantes se comuniquen y se sientan parte de tu web
Copyright © 2003-2005 Daniel Osorio "Electros"

Este programa es software libre, puedes redistribuirlo y/o modificarlo bajo los términos
de la GNU General Public License publicados por la Free Software Foundation; desde la
versión 2 de la licencia, o (si lo deseas) cualquiera más reciente.
*/

# * Mostrar siempre un aviso de confirmación antes de descargar el adjunto
require('config.php') ;
if(!ereg('^[0-9]+$',$_GET[id])) {
	echo 'Intento de ataque.' ;
	exit ;
}
# --> Se suma una descarga al adjunto
mysql_query("update eforo_adjuntos set descargas=descargas+1 where id='$_GET[id]'") ;
# --> Se obtiene el nombre real del archivo y se renombra el actual al momento de descargarlo
$con = mysql_query("select archivo from eforo_adjuntos where id='$_GET[id]'") ;
$datos = mysql_fetch_assoc($con) ;
if(file_exists("eforo_adjuntos/$_GET[id].dat")) {
	header("content-disposition: attachment; filename=$datos[archivo]") ;
	readfile("eforo_adjuntos/$_GET[id].dat") ;
}
else {
	require('foroconfig.php') ;
	require('eforo_funciones/aviso.php') ;
	aviso('Error','<p>El archivo adjunto no se subió correctamente.<p><a href="'.$_SERVER[HTTP_REFERER].'" class="eforo">» Regresar al mensaje</a>') ;
}
mysql_close($conectar) ;
?>
