<?
/*
************************************************
*** eForo v3.0
*** Creado por: Electros <electros@electros.net>
*** Sitio web: www.electros.net
*** Licencia: GNU General Public License
************************************************

--- Página: aviso.php ---

eForo - Una comunidad para tus visitantes
Copyright © 2003-2004 Daniel Osorio "Electros"

Este programa es software libre, puedes redistribuirlo y/o modificarlo bajo los términos
de la GNU General Public License publicados por la Free Software Foundation; desde la
versión 2 de la licencia, o (si lo deseas) cualquiera más reciente.
*/

# * Formato para mostrar avisos en confirmaciones o errores
function aviso($a_titulo,$a_mensaje) {
?>
<table width="100%" border="0" cellpadding="3" cellspacing="1" align="center" class="eforo_tabla_principal">
<tr>
<td class="eforo_tabla_titulo"><div align="center" class="eforo_titulo_1"><?=$a_titulo?></div></td>
</tr>
<tr>
<td class="eforo_tabla_defecto"><?=$a_mensaje?></td>
</tr>
</table>
<?
}
?>
