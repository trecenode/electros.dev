<?
/*
************************************************
*** eForo v3.0
*** Creado por: Electros <electros@electros.net>
*** Sitio web: www.electros.net
*** Licencia: GNU General Public License
************************************************

--- Página: quitar.php ---

eForo - Una comunidad para tus visitantes
Copyright © 2003-2004 Daniel Osorio "Electros"

Este programa es software libre, puedes redistribuirlo y/o modificarlo bajo los términos
de la GNU General Public License publicados por la Free Software Foundation; desde la
versión 2 de la licencia, o (si lo deseas) cualquiera más reciente.
*/

# * Función que limpia la información enviada de formularios para evitar ataques
function quitar($texto,$comprobar=0) {
$texto = trim($texto) ;
$texto = htmlspecialchars($texto) ;
$texto = str_replace(chr(160),'',$texto) ; # <-- Elimina espacios forzados, como caractéres normales pero invisibles
if(!$texto && $comprobar) {
?>
<p>Debes llenar los campos correctamente.
<p><a href="javascript:history.back()" class="eforo">» Regresar</a>
<?
exit ;
}
return $texto ;
}
?>
