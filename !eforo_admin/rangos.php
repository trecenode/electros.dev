<?
/*
************************************************
*** eForo v3.0
*** Creado por: Electros <electros@electros.net>
*** Sitio web: www.electros.net
*** Licencia: GNU General Public License
************************************************

--- Página: eforo_admin/rangos.php ---

eForo - Una comunidad para tus visitantes
Copyright © 2003-2004 Daniel Osorio "Electros"

Este programa es de Código Abierto, puedes redistribuirlo y/o modificarlo bajo los términos
de la GNU General Public License publicados por la Free Software Foundation; desde la
versión 2 de la licencia, o (si lo deseas) cualquiera más reciente.
*/

require('../foroconfig.php') ;
require('../eforo_funciones/sesion.php') ;
require('../eforo_funciones/aviso.php') ;

if(!$es_administrador) {
	echo "<script>top.location='$conf[url_foro]/$u[0]foro$u[1]$u[5]'</script>" ;
	exit ;
}

switch(true) {
	case $_POST[agregar] :
		$con = mysql_query("select rango from eforo_rangos where rango='$_POST[r_rango]'") ;
		if(mysql_num_rows($con)) {
			aviso('Error','Ya existe este rango.') ;
		}
		else {
			mysql_query("insert into eforo_rangos (rango,minimo,descripcion) values ('$_POST[r_rango]','$_POST[r_minimo]','$_POST[r_descripcion]')") ;
			aviso('Rango agregado','El rango <b>'.$_POST[r_rango].'</b> ha sido agregado con éxito.') ;
		}
		break ;
	case isset($_POST[modificar]) :
		mysql_query("update eforo_rangos set minimo='$_POST[r_minimo]',descripcion='$_POST[r_descripcion]' where rango='$_POST[modificar]'") ;
		aviso('Rango modificado','El rango <b>'.$_POST[modificar].'</b> ha sido modificado con éxito.') ;
		break ;
	case $_GET[borrar] :
		mysql_query("delete from eforo_rangos where rango='$_GET[borrar]'") ;
		aviso('Rango borrado','El rango <b>'.$_GET[borrar].'</b> ha sido borrado con éxito.') ;
}
?>
<table width="100%" border="0" cellpadding="3" cellspacing="1" class="eforo_tabla_principal">
<tr>
<td colspan="4" class="eforo_tabla_titulo"><div class="eforo_titulo_1">Rangos</div></td>
</tr>
<tr>
<td width="15%" class="eforo_tabla_subtitulo"><div class="eforo_titulo_1">Rango</div></td>
<td width="15%" class="eforo_tabla_subtitulo"><div class="eforo_titulo_1">Mínimo</div></td>
<td width="50%" class="eforo_tabla_subtitulo"><div class="eforo_titulo_1">Descripción</div></td>
<td width="20%" class="eforo_tabla_subtitulo">&nbsp;</td>
</tr>
<?
$con = mysql_query("select * from eforo_rangos order by rango asc") ;
while($datos = mysql_fetch_assoc($con)) {
	$bloquear = false ;
	if($datos[rango] == -1 || $datos[rango] == 0 || $datos[rango] == 1 || $datos[rango] == 500 || $datos[rango] == 999) {
		$bloquear = ' disabled' ;
	}
?>
<tr>
<td class="eforo_tabla_defecto"><form method="post" action="rangos.php"><input type="hidden" name="modificar" value="<?=$datos[rango]?>"><?=$datos[rango]?></td>
<td class="eforo_tabla_defecto"><input type="text" name="r_minimo" size="5" maxlength="5" value="<?=$datos[minimo]?>" class="eforo_formulario"<?=$bloquear?>></td>
<td class="eforo_tabla_defecto"><input type="text" name="r_descripcion" size="25" maxlength="100" value="<?=$datos[descripcion]?>" class="eforo_formulario"> <input type="submit" value="Modificar" class="eforo_formulario"></td>
<td class="eforo_tabla_defecto"></form><? if(!$bloquear) { ?><div align="center"><a href="rangos.php?borrar=<?=$datos[rango]?>" class="eforo">Borrar</a></div><? } else { ?>&nbsp;<? } ?></td>
</tr>
<?
}
mysql_free_result($con)
?>
<tr>
<td colspan="4" class="eforo_tabla_subtitulo"><div class="eforo_titulo_1">Agregar nuevo rango</div></td>
</tr>
<tr>
<td class="eforo_tabla_defecto"><form method="post" action="rangos.php"><input type="text" name="r_rango" size="3" maxlength="3" class="eforo_formulario"></td>
<td class="eforo_tabla_defecto"><input type="text" name="r_minimo" size="5" maxlength="5" class="eforo_formulario"></td>
<td class="eforo_tabla_defecto"><input type="text" name="r_descripcion" size="25" maxlength="100" class="eforo_formulario"> <input type="submit" name="agregar" value="Agregar" class="eforo_formulario"></td>
<td class="eforo_tabla_defecto"></form>&nbsp;</td>
</tr>
<tr>
<td colspan="4" class="eforo_tabla_subtitulo"><div class="eforo_titulo_1">Ayuda</div></td>
</tr>
<tr>
<td colspan="4" class="eforo_tabla_defecto">
<script>
a = 0 ;
function ayuda() {
if(a == 0) {
document.all.ayuda_enlace.value = 'Ocultar >>' ;
document.all.ayuda_texto.style.position = 'relative' ;
document.all.ayuda_texto.style.visibility = 'visible' ;
a++ ;
}
else {
document.all.ayuda_enlace.value = 'Ver más >>' ;
document.all.ayuda_texto.style.position = 'absolute' ;
document.all.ayuda_texto.style.visibility = 'hidden' ;
a-- ;
}
}
</script>
<input type="button" id="ayuda_enlace" value="Ver más >>" onclick="ayuda()" class="eforo_formulario">
<div id="ayuda_texto" style="position: absolute ; visibility: hidden">
<br>
<b>¿ Qué son los rangos ?</b><br>
Los rangos sirven para que los usuarios tengan un cierto nivel ya sea al llegar a un número de mensajes o al ser
designado manualmente. Con esto puedes crear restricciones como por ejemplo que sólo usuarios con nivel 100 tengan
acceso a un subforo.<br><br>
<b>¿ Como se agrega un rango ?</b><br>
Debes escribir un número de rango entre 1 y 999 y un número mínimo de mensajes para llegar a ese rango. Si deseas crear
rangos fijos debes poner un mínimo de cero, pero estos sólo podrán ser asignados manualmente. Los rangos normales también
pueden usarse como rangos fijos y a la vez funcionar como siempre para los demás usuarios.<br><br>
<b>Aviso:</b> El nivel mínimo de mensajes siempre debe estar en forma proporcional al rango definido, por ejemplo lo siguiente
no es válido:<br><br>
<b>10 - 125 - Intermedio<br>
<font color="aa0000">20 - 175 - Avanzado</font><br>
30 - 150 - Experto</b><br><br>
Si te fijas en este caso al llegar a 175 mensajes, el usuario pasaría del rango 30 al rango 20 con lo que sería un error.<br><br>
<b>¿ El rango 500 o 999 hace moderador o administrador a un usuario ?</b><br>
No, el rango 500 y 999 son sólo descriptivos y se asignan automáticamente al usuario que es nombrado moderador o administrador.
Para asignar moderadores y administradores debes hacerlo desde la opción <b>Usuarios</b>.<br><br>
<b>¿ Cómo se comportan los rangos ?</b><br>
-1 - Usuarios que han sido expulsados - Pueden leer mensajes.<br>
0 - Usuarios no registrados - Pueden leer, escribir nuevos temas y responder mensajes.<br>
1 al 999 - Usuarios registrados - Pueden leer, escribir nuevos temas, responder mensajes, editar y borrar sus propios mensajes.<br>
Este es el permiso máximo que pueden tener los usuarios que tengan esos rangos, pero también depende del permiso que hayas
elegido por subforo.
</div>
