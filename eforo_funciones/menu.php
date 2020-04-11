<?
/*
************************************************
*** eForo v3.0
*** Creado por: Electros <electros@electros.net>
*** Sitio web: www.electros.net
*** Licencia: GNU General Public License
************************************************

--- Página: menu.php ---

eForo - Una comunidad para tus visitantes
Copyright © 2003-2004 Daniel Osorio "Electros"

Este programa es software libre, puedes redistribuirlo y/o modificarlo bajo los términos
de la GNU General Public License publicados por la Free Software Foundation; desde la
versión 2 de la licencia, o (si lo deseas) cualquiera más reciente.
*/

# * Menú de navegación del foro
$titulo_subforo = false ;
if($_GET[foro]) {
	$con = mysql_query("select foro from eforo_foros where id='$_GET[foro]'") ;
	$datos = mysql_fetch_assoc($con) ;
	$titulo_subforo = " · <a href=\"$u[0]forotemas$u[1]$u[2]foro$u[4]$_GET[foro]$u[5]\" class=\"eforo\">$datos[foro]</a>" ;
	$titulo_subforo_2 = $datos[foro] ;
	mysql_free_result($con) ;
}
$titulo_tema = false ;
if($_GET[foro] && $_GET[tema]) {
	$con = mysql_query("select tema from eforo_mensajes where id='$_GET[tema]'") ;
	$datos = mysql_fetch_assoc($con) ;
	$titulo_tema = " · <a href=\"$u[0]foromensajes$u[1]$u[2]foro$u[4]$_GET[foro]$u[3]tema$u[4]$_GET[tema]$u[5]\" class=\"eforo\">$datos[tema]</a>" ;
	$titulo_tema_2 = $datos[tema] ;
	mysql_free_result($con) ;
}
?>
<table width="100%" border="0" cellpadding="5" cellspacing="1" align="center" class="eforo_tabla_defecto">
<tr>
<td><a href="<?="$u[0]foro$u[1]$u[5]"?>" class="eforo">Indice de subforos</a><?=$titulo_subforo.$titulo_tema?></td>
</tr>
<tr>
<td>
<?
if($_GET[foro]) {
	echo "<a href=\"$u[0]foroescribir$u[1]$u[2]foro$u[4]$_GET[foro]$u[5]\" class=\"eforo\">Nuevo tema</a>" ;
}
if($_GET[foro] && $_GET[tema]) {
	echo " | <a href=\"$u[0]foroescribir$u[1]$u[2]foro$u[4]$_GET[foro]$u[3]tema$u[4]$_GET[tema]$u[5]\" class=\"eforo\">Responder</a>" ;
}
?>
</td>
</tr>
</table>