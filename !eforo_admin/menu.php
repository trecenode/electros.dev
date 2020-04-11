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

Este programa es de Código Abierto, puedes redistribuirlo y/o modificarlo bajo los términos
de la GNU General Public License publicados por la Free Software Foundation; desde la
versión 2 de la licencia, o (si lo deseas) cualquiera más reciente.
*/

require('../foroconfig.php') ;
require('../eforo_funciones/sesion.php') ;

if(!$es_administrador) {
	echo "<script>top.location='$conf[url_foro]/$u[0]foro$u[1]$u[5]'</script>" ;
	exit ;
}
?>
<base target="contenido">
<p><a href="../<?="$u[0]foro$u[1]$u[5]"?>" target="_top" class="eforo">» Regresar al foro</a>
<p>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="15%" valign="top">
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="eforo_tabla_principal">
<tr>
<td class="eforo_tabla_titulo"><div class="eforo_titulo_1">eForo</div></td>
</tr>
<tr>
<td class="eforo_tabla_defecto"><center><a href="foros.php" class="eforo">Foros</a></center></td>
</tr>
<tr>
<td class="eforo_tabla_defecto"><center><a href="configuracion.php" class="eforo">Configuración</a></center></td>
</tr>
<tr>
<td class="eforo_tabla_defecto"><center><a href="rangos.php" class="eforo">Rangos</a></center></td>
</tr>
<tr>
<td class="eforo_tabla_defecto"><center><a href="permisos.php" class="eforo">Permisos</a></center></td>
</tr>
<tr>
<td class="eforo_tabla_defecto"><center><a href="usuarios.php" class="eforo">Usuarios</a></center></td>
</tr>
</table>
