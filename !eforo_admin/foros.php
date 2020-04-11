<?
/*
************************************************
*** eForo v3.0
*** Creado por: Electros <electros@electros.net>
*** Sitio web: www.electros.net
*** Licencia: GNU General Public License
************************************************

--- Página: foros.php ---

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

switch($_POST[agregar]) {
	case 1 :
		# * Agregar categoría
		mysql_query("insert into eforo_categorias (categoria) values ('$_POST[categoria]')") ;
		$con = mysql_query("select id from eforo_categorias") ;
		$orden = mysql_num_rows($con) * 10 ;
		mysql_free_result($con) ;
		mysql_query("update eforo_categorias set orden='$orden' where id='".mysql_insert_id()."'") ;
		aviso('Categoría agregada','La categoría <b>'.$_POST[categoria].'</b> ha sido agregada.') ;
		break ;
	case 2 :
		# * Agregar foro
		mysql_query("insert into eforo_foros (id_categoria,foro,descripcion) values ('$_POST[categoria]','$_POST[foro]','$_POST[descripcion]')") ;
		$con = mysql_query("select id from eforo_foros where id_categoria='$_POST[categoria]'") ;
		$orden = mysql_num_rows($con) * 10 ;
		mysql_free_result($con) ;
		mysql_query("update eforo_foros set orden='$orden' where id='".mysql_insert_id()."'") ;
		aviso('Foro agregado','El foro <b>'.$_POST[foro].'</b> ha sido agregado.') ;
}
switch($_GET[ordenar]) {
	case 1 :
		# * Cambiar categoría de orden 
		($_GET[cambiar] == 1) ? mysql_query("update eforo_categorias set orden=orden+15 where id='$_GET[id]'") : mysql_query("update eforo_categorias set orden=orden-15 where id='$_GET[id]'") ;
		$con = mysql_query("select id from eforo_categorias order by orden asc") ;
		for($a = 10 ; $datos = mysql_fetch_assoc($con) ; $a += 10) {
			mysql_query("update eforo_categorias set orden='$a' where id='$datos[id]'") ;
		}
		mysql_free_result($con) ;
		aviso('Categoría movida','La categoría ha sido cambiada de orden.') ;
		break ;
	case 2 :
		# * Cambiar foro de orden 
		($_GET[cambiar] == 1) ? mysql_query("update eforo_foros set orden=orden+15 where id='$_GET[id]'") : mysql_query("update eforo_foros set orden=orden-15 where id='$_GET[id]'") ;
		$con = mysql_query("select id from eforo_foros where id_categoria='$_GET[c]' order by orden asc") ;
		for($a = 10 ; $datos = mysql_fetch_assoc($con) ; $a += 10) {
			mysql_query("update eforo_foros set orden='$a' where id='$datos[id]'") ;
		}
		mysql_free_result($con) ;
		aviso('Foro cambiado','El foro ha sido cambiado de orden.') ;
}
switch($_GET[borrar]) {
	case 1 :
		# * Borrar categoría
		$con = mysql_query("select id from eforo_foros where id_categoria='$_GET[id]'") ;
		if(!mysql_num_rows($con)) {
			mysql_query("delete from eforo_categorias where id='$_GET[id]'") ;
			aviso('Categoría borrada','La categoría ha sido borrada.') ;
		}
		else {
			aviso('Error','Debes eliminar todos los foros de esta categoría.') ;
		}
		break ;
	case 2 :
		# * Borrar foro
		mysql_query("delete from eforo_mensajes where id_foro='$_GET[id]'") ;
		mysql_query("delete from eforo_foros where id='$_GET[id]'") ;
		aviso('Foro borrado','El foro y todos sus mensajes han sido borrados.') ;
}
# * Editar título y descripción de categorías y foros
if($_POST[editar]) {
	foreach($_POST as $nombre => $valor) {
		switch(true) {
			case ereg('^cat_',$nombre) :
				list(,$id) = explode('_',$nombre) ;
				mysql_query("update eforo_categorias set categoria='$valor' where id='$id'") ;
				break ;
			case ereg('^foro_',$nombre) :
				list(,$id) = explode('_',$nombre) ;
				mysql_query("update eforo_foros set foro='$valor' where id='$id'") ;
				break ;
			case ereg('^des_',$nombre) :
				list(,$id) = explode('_',$nombre) ;
				mysql_query("update eforo_foros set descripcion='$valor' where id='$id'") ;
		}
	}
}
if($_GET[mover]) {
	list($id_categoria,$id_foro) = explode('_',$_GET[mover]) ;
	mysql_query("update eforo_foros set id_categoria='$id_categoria' where id='$id_foro'") ;
	aviso('Foro movido','El foro ha sido movido a la categoría seleccionada.') ;
}
?>
<br>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td valign="top">
<form method="post" action="foros.php">
<input type="hidden" name="agregar" value="1">
<b>Categoría:</b><br>
<input type="text" name="categoria" maxlength="100" size="30" class="eforo_formulario"><br><br>
<input type="submit" value="Agregar Categoría" class="eforo_formulario">
</td></form>
<td valign="top">
<form method="post" action="foros.php">
<input type="hidden" name="agregar" value="2">
<b>Foro:</b><br>
<input type="text" name="foro" maxlength="100" size="30" class="eforo_formulario"><br>
<b>Categoría:</b><br>
<select name="categoria" class="eforo_formulario">
<?
$con = mysql_query("select id,categoria from eforo_categorias order by orden asc") ;
while($datos = mysql_fetch_assoc($con)) {
	echo "<option value=\"$datos[id]\">$datos[categoria]\n" ;
}
mysql_free_result($con) ;
?>
</select>
</td>
<td valign="top">
<b>Descripción:</b><br>
<textarea name="descripcion" cols="30" rows="5" class="eforo_formulario"></textarea><br><br>
<input type="submit" value="Agregar Foro" class="eforo_formulario">
</td></form>
</tr>
</table><br>
<form method="post" action="foros.php">
<input type="hidden" name="editar" value="1">
<table width="100%" border="0" cellpadding="5" cellspacing="1" class="eforo_tabla_principal">
<tr>
<td width="20%" class="eforo_tabla_titulo"><div class="eforo_titulo_1" align="center">Orden</div></td>
<td width="65%" class="eforo_tabla_titulo"><div class="eforo_titulo_1" align="center">Categoría/Subforo</div></td>
<td width="15%" class="eforo_tabla_titulo">&nbsp;</td>
</tr>
<tr>
<td colspan="3" class="eforo_tabla_defecto"><center><input type="submit" value="Guardar Modificaciones" class="eforo_formulario"></center></td>
</tr>
<?
# * Almacena el id y el nombre de las categorias en un array
$con = mysql_query('select * from eforo_categorias order by orden asc') ;
while($datos = mysql_fetch_assoc($con)) {
	$categorias[$datos[id]] = $datos[categoria] ;
}
mysql_free_result($con) ;
foreach($categorias as $categoria_id => $categoria_nombre) {
?>
<tr>
</td>
<td class="eforo_tabla_titulo">
<center>
<input type="button" value="Bajar" onclick="location='foros.php?id=<?=$categoria_id?>&ordenar=1&cambiar=1'" class="eforo_formulario">
<input type="button" value="Subir" onclick="location='foros.php?id=<?=$categoria_id?>&ordenar=1&cambiar=2'" class="eforo_formulario">
</center>
</td>
<td class="eforo_tabla_titulo"><input type="text" name="cat_<?=$categoria_id?>" value="<?=$categoria_nombre?>" size="30" maxlength="100" class="eforo_formulario"></td>
<td class="eforo_tabla_titulo"><center><input type="button" value="Borrar" onclick="if(confirm('¿Deseas borrar la categoría?')) location = 'foros.php?id=<?=$categoria_id?>&borrar=1'" class="eforo_formulario"></center></td>
</tr>
<?
	$con = mysql_query("select * from eforo_foros where id_categoria='$categoria_id' order by orden asc") ;
	while($datos = mysql_fetch_assoc($con)) {
?>
<tr>
<td class="eforo_tabla_defecto">
<center>
<input type="button" value="Bajar" onclick="location='foros.php?id=<?=$datos[id]?>&c=<?=$categoria_id?>&ordenar=2&cambiar=1'" class="eforo_formulario">
<input type="button" value="Subir" onclick="location='foros.php?id=<?=$datos[id]?>&c=<?=$categoria_id?>&ordenar=2&cambiar=2'" class="eforo_formulario">
<br><br>
<select onchange="if(this.value != 0) location = 'foros.php?mover='+this.options[this.selectedIndex].value" class="eforo_formulario">
<option value="0">Mover a ...
<?
		foreach($categorias as $a => $b) {
			echo "<option value=\"$a"."_$datos[id]\">$b\n" ;
		}
?>
</select>
</center>
</td>
<td class="eforo_tabla_defecto">
<input type="text" name="foro_<?=$datos[id]?>" size="30" maxlength="100" value="<?=$datos[foro]?>" class="eforo_formulario">
<br><br>
<textarea name="des_<?=$datos[id]?>" cols="30" rows="3" class="eforo_formulario"><?=$datos[descripcion]?></textarea>
</td>
<td class="eforo_tabla_defecto"><center><input type="button" value="Borrar" onclick="if(confirm('¿Deseas borrar el foro y todos sus mensajes?')) location='foros.php?id=<?=$datos[id]?>&borrar=2'" class="eforo_formulario"></center></td>
</tr>
<?
	}
	mysql_free_result($con) ;
}
?>
<tr>
<td colspan="3" class="eforo_tabla_defecto"><center><input type="submit" value="Guardar Modificaciones" class="eforo_formulario"></center></td>
</tr>
</table>
<form>
