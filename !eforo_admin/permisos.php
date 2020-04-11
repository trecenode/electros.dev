<?
/*
************************************************
*** eForo v3.0
*** Creado por: Electros <electros@electros.net>
*** Sitio web: www.electros.net
*** Licencia: GNU General Public License
************************************************

--- Página: eforo_admin/permisos.php ---

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

// * Establecer los permisos
if($_POST[permisos]) {
	foreach($_POST as $nombre => $valor) {
		if(ereg('^p_',$nombre)) {
			list(,$permiso,$id_foro) = explode('_',$nombre) ;
			mysql_query("update eforo_foros set p_$permiso='$valor' where id='$id_foro'") ;
		}
	}
	aviso('Permisos modificados','Los permisos han sido modificados con éxito.') ;
}

# * Se almacenan todos los rangos en un array
$con = mysql_query('select * from eforo_rangos order by rango asc') ;
while($datos = mysql_fetch_assoc($con)) {
	$rangos[$datos[rango]] = $datos[descripcion] ;
}
mysql_free_result($con) ;

# * Almacena el id y el nombre de las categorias en un array
$con = mysql_query('select * from eforo_categorias order by orden asc') ;
while($datos = mysql_fetch_assoc($con)) {
	$categorias[$datos[id]] = $datos[categoria] ;
}
mysql_free_result($con) ;
?>
<form method="post" action="permisos.php">
<input type="hidden" name="permisos" value="1">
<table width="100%" border="0" cellpadding="3" cellspacing="1" class="eforo_tabla_principal">
<tr>
<td colspan="7" class="eforo_tabla_titulo"><div class="eforo_titulo_1">Permisos</div></td>
</tr>
<tr>
<td colspan="7" class="eforo_tabla_defecto"><center><input type="submit" value="Modificar los permisos" class="eforo_formulario"></center></td>
</tr>
<tr>
<td class="eforo_tabla_subtitulo"><div class="eforo_titulo_1">Leer</div></td>
<td class="eforo_tabla_subtitulo"><div class="eforo_titulo_1">Nuevo tema</div></td>
<td class="eforo_tabla_subtitulo"><div class="eforo_titulo_1">Responder</div></td>
<td class="eforo_tabla_subtitulo"><div class="eforo_titulo_1">Editar</div></td>
<td class="eforo_tabla_subtitulo"><div class="eforo_titulo_1">Borrar</div></td>
<td class="eforo_tabla_subtitulo"><div class="eforo_titulo_1">Importante</div></td>
<td class="eforo_tabla_subtitulo"><div class="eforo_titulo_1">Adjuntar</div></td>
</tr>
<?
# * Se muestran los foros de cada categoria (leyendo el array previamente creado)
foreach($categorias as $categoria_id => $categoria_nombre) {
?>
<tr>
<td class="eforo_tabla_subtitulo" colspan="7"><div class="eforo_titulo_1"><?=$categoria_nombre?></div></td>
</tr>
<?
	$con2 = mysql_query("select * from eforo_foros where id_categoria='$categoria_id' order by orden asc") ;
	while($datos2 = mysql_fetch_assoc($con2)) {
		# --> Leer
		$rangos_leer = false ;
		foreach($rangos as $rango => $descripcion) {
			$sel = ($rango != $datos2[p_leer]) ? false : 'selected' ;
			$rangos_leer .= "<option value=\"$rango\"$sel>$rango $descripcion\n" ;
		}
		# --> Nuevo
		$rangos_nuevo = false ;
		foreach($rangos as $rango => $descripcion) {
			if($rango != -1) {
				$sel = ($rango != $datos2[p_nuevo]) ? false : 'selected' ;
				$rangos_nuevo .= "<option value=\"$rango\"$sel>$rango $descripcion\n" ;
			}
		}
		# --> Responder
		$rangos_responder = false ;
		foreach($rangos as $rango => $descripcion) {
			if($rango != -1) {
				$sel = ($rango != $datos2[p_responder]) ? false : 'selected' ;
				$rangos_responder .= "<option value=\"$rango\"$sel>$rango $descripcion\n" ;
			}
		}
		// --> Editar
		$rangos_editar = false ;
		foreach($rangos as $rango => $descripcion) {
			if($rango != -1 && $rango != 0) {
				$sel = ($rango != $datos2[p_editar]) ? false : 'selected' ;
				$rangos_editar .= "<option value=\"$rango\"$sel>$rango $descripcion\n" ;
			}
		}
		// --> Borrar
		$rangos_borrar = false ;
		foreach($rangos as $rango => $descripcion) {
			if($rango != -1 && $rango != 0) {
				$sel = ($rango != $datos2[p_borrar]) ? false : 'selected' ;
				$rangos_borrar .= "<option value=\"$rango\"$sel>$rango $descripcion\n" ;
			}
		}
		// --> Importante
		$rangos_importante = false ;
		foreach($rangos as $rango => $descripcion) {
			if($rango != -1 && $rango != 0) {
				$sel = ($rango != $datos2[p_importante]) ? false : 'selected' ;
				$rangos_importante .= "<option value=\"$rango\"$sel>$rango $descripcion\n" ;
			}
		}
		// --> Adjuntar
		$rangos_adjuntar = false ;
		foreach($rangos as $rango => $descripcion) {
			if($rango != -1 && $rango != 0) {
				$sel = ($rango != $datos2[p_adjuntar]) ? false : 'selected' ;
				$rangos_adjuntar .= "<option value=\"$rango\"$sel>$rango $descripcion\n" ;
			}
		}
?>
<tr>
<td class="eforo_tabla_defecto" colspan="7"><b><?=$datos2[foro]?></b></td>
</tr>
<tr>
<td class="eforo_tabla_defecto"><select name="p_leer_<?=$datos2[id]?>" class="eforo_formulario" style="font-size: 7pt"><?=$rangos_leer?></select></td>
<td class="eforo_tabla_defecto"><select name="p_nuevo_<?=$datos2[id]?>" class="eforo_formulario" style="font-size: 7pt"><?=$rangos_nuevo?></select></td>
<td class="eforo_tabla_defecto"><select name="p_responder_<?=$datos2[id]?>" class="eforo_formulario" style="font-size: 7pt"><?=$rangos_responder?></select></td>
<td class="eforo_tabla_defecto"><select name="p_editar_<?=$datos2[id]?>" class="eforo_formulario" style="font-size: 7pt"><?=$rangos_editar?></select></td>
<td class="eforo_tabla_defecto"><select name="p_borrar_<?=$datos2[id]?>" class="eforo_formulario" style="font-size: 7pt"><?=$rangos_borrar?></select></td>
<td class="eforo_tabla_defecto"><select name="p_importante_<?=$datos2[id]?>" class="eforo_formulario" style="font-size: 7pt"><?=$rangos_importante?></select></td>
<td class="eforo_tabla_defecto"><select name="p_adjuntar_<?=$datos2[id]?>" class="eforo_formulario" style="font-size: 7pt"><?=$rangos_adjuntar?></select></td>
</tr>
<?
	}
mysql_free_result($con2) ;
}
?>
<tr>
<td colspan="7" class="eforo_tabla_defecto"><center><input type="submit" value="Modificar los permisos" class="eforo_formulario"></center></td>
</tr>
</table>
</form>
