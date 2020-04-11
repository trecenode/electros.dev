<?
/*
************************************************
*** eForo v3.0
*** Creado por: Electros <electros@electros.net>
*** Sitio web: www.electros.net
*** Licencia: GNU General Public License
************************************************

--- Página: eforo_admin/usuarios.php ---

eForo - Una comunidad para tus visitantes
Copyright © 2003-2004 Daniel Osorio "Electros"

Este programa es software libre, puedes redistribuirlo y/o modificarlo bajo los términos
de la GNU General Public License publicados por la Free Software Foundation; desde la
versión 2 de la licencia, o (si lo deseas) cualquiera más reciente.
*/

require('../foroconfig.php') ;
require('../eforo_funciones/sesion.php') ;
require('../eforo_funciones/aviso.php') ;
require('../eforo_funciones/epaginas.php') ;

if(!$es_administrador) {
	echo "<script>top.location='$conf[url_foro]/$u[0]foro$u[1]$u[5]'</script>" ;
	exit ;
}

# * Designar moderador
if($_POST[designar]) {
	mysql_query("delete from eforo_moderadores where id_usuario='$_POST[id_moderador]'") ;
	foreach($_POST as $nombre => $valor) {
		if(ereg('^foro_',$nombre)) {
			mysql_query("insert into eforo_moderadores (id_foro,id_usuario) values('$valor','$_POST[id_moderador]')") ;
		}
	}
	mysql_query("update $tabla_usuarios set rango='500',rango_fijo='1' where id='$_POST[id_moderador]'") ;
	$con = mysql_query("select nick from $tabla_usuarios where id='$_POST[id_moderador]'") ;
	$datos = mysql_fetch_assoc($con) ;
	$nick_usuario = $datos[nick] ;
	mysql_free_result($con) ;
	aviso('Moderador designado','El usuario <b>'.$nick_usuario.'</b> ha sido designado moderador.') ;
}

# * Quitar moderador
if($_GET[quitar]) {
	mysql_query("delete from eforo_moderadores where id_usuario='$_GET[quitar]'") ;
	mysql_query("update $tabla_usuarios set rango='1',rango_fijo='0' where id='$_GET[quitar]'") ;
	$con = mysql_query("select nick from $tabla_usuarios where id='$_GET[quitar]'") ;
	$datos = mysql_fetch_assoc($con) ;
	$nick_usuario = $datos[nick] ;
	mysql_free_result($con) ;
	aviso('Moderador quitado','Se han quitado los privilegios de moderación a <b>'.$nick_usuario.'</b>.') ;
}

# * Asignar rangos
if($_POST[rango]) {
	if($_POST[rango] != 'defecto') {
		foreach($_POST as $a => $b) {
			if(ereg('^id_',$a)) {
				mysql_query("update $tabla_usuarios set rango='$_POST[rango]',rango_fijo='1' where id='$b'") ;
			}
		}
	}
	else {
		foreach($_POST as $a => $b) {
			if(ereg('^id_',$a)) {
				mysql_query("update $tabla_usuarios set rango='1',rango_fijo='0' where id='$b'") ;
			}
		}
	}
	aviso('Rango asignado','El rango ha sido asignado.') ;
}

# * Borrar usuario
if($_GET[borrar]) {
	mysql_query("delete from eforo_enlinea where id_usuario='$_GET[borrar]'") ;
	mysql_query("delete from eforo_recientes where id_usuario='$_GET[borrar]'") ;
	mysql_query("delete from eforo_moderadores where id_usuario='$_GET[borrar]'") ;
	mysql_query("delete from eforo_privados where id_destinario='$_GET[borrar]'") ;
	mysql_query("delete from eforo_mensajes where id_usuario='$_GET[borrar]'") ;
	$con = mysql_query("select nick from $tabla_usuarios where id='$_GET[quitar]'") ;
	$datos = mysql_fetch_assoc($con) ;
	$nick_usuario = $datos[nick] ;
	mysql_free_result($con) ;
	mysql_query("delete from $tabla_usuarios where id='$_GET[borrar]'") ;
	aviso('Usuario borrado','El usuario <b>'.$nick_usuario.'</b> ha sido borrado.') ;
}

# * Se almacenan todos los rangos en un array
$con = mysql_query('select * from eforo_rangos order by rango asc') ;
while($datos = mysql_fetch_assoc($con)) {
	$rangos[$datos[rango]] = $datos[minimo].'||'.$datos[descripcion] ;
}
mysql_free_result($con) ;

$letra = ereg('[a-z]{1}',$_GET[letra]) ? " where nick like '$_GET[letra]%'" : false ;
$campos = array('nick','id','email') ;
$por = ereg('[0-9]+',$_GET[por]) ? $_GET[por] : 0 ;
$orden = ($por == 1) ? 'desc' : 'asc' ;
$ePaginas = new ePaginas("select * from $tabla_usuarios$letra order by $campos[$por] $orden",30) ;
$ePaginas->u[1] = '?' ;
$ePaginas->u[2] = '&' ;
$ePaginas->u[3] = '=' ;
$ePaginas->u[4] = '' ;
$con = $ePaginas->consultar() ;
?>
<table width="100%" border="0" cellpadding="3" cellspacing="1" class="eforo_tabla_principal">
<tr>
<td colspan="9" class="eforo_tabla_titulo"><div class="eforo_titulo_1">Usuarios</div></td>
</tr>
<?
if(!$_GET[moderador]) {
?>
<form method="post" action="usuarios.php?<?=$_SERVER[QUERY_STRING]?>">
<tr>
<td colspan="9" class="eforo_tabla_defecto">
<b>Mostrar por:</b>
<input type="button" value="Orden alfabético" onclick="location='usuarios.php'" class="eforo_formulario">
<input type="button" value="Fecha de registro" onclick="location='usuarios.php?por=1'" class="eforo_formulario">
<input type="button" value="Email" onclick="location='usuarios.php?por=2'" class="eforo_formulario">
<br><br>
<a href="usuarios.php?letra=a" class="eforo">A</a> |
<a href="usuarios.php?letra=b" class="eforo">B</a> |
<a href="usuarios.php?letra=c" class="eforo">C</a> |
<a href="usuarios.php?letra=d" class="eforo">D</a> |
<a href="usuarios.php?letra=e" class="eforo">E</a> |
<a href="usuarios.php?letra=f" class="eforo">F</a> |
<a href="usuarios.php?letra=g" class="eforo">G</a> |
<a href="usuarios.php?letra=h" class="eforo">H</a> |
<a href="usuarios.php?letra=i" class="eforo">I</a> |
<a href="usuarios.php?letra=j" class="eforo">J</a> |
<a href="usuarios.php?letra=k" class="eforo">K</a> |
<a href="usuarios.php?letra=l" class="eforo">L</a> |
<a href="usuarios.php?letra=m" class="eforo">M</a> |
<a href="usuarios.php?letra=n" class="eforo">N</a> |
<a href="usuarios.php?letra=o" class="eforo">O</a> |
<a href="usuarios.php?letra=p" class="eforo">P</a> |
<a href="usuarios.php?letra=q" class="eforo">Q</a> |
<a href="usuarios.php?letra=r" class="eforo">R</a> |
<a href="usuarios.php?letra=s" class="eforo">S</a> |
<a href="usuarios.php?letra=t" class="eforo">T</a> |
<a href="usuarios.php?letra=u" class="eforo">U</a> |
<a href="usuarios.php?letra=v" class="eforo">V</a> |
<a href="usuarios.php?letra=w" class="eforo">W</a> |
<a href="usuarios.php?letra=x" class="eforo">X</a> |
<a href="usuarios.php?letra=y" class="eforo">Y</a> |
<a href="usuarios.php?letra=z" class="eforo">Z</a>
<br><br>
<b>Asignar rangos:</b>
<select name="rango" onchange="if(this.value) submit()" class="eforo_formulario">
<option>...
<?
foreach($rangos as $a => $b) {
	list(,$descripcion) = explode('||',$b) ;
	echo "<option value=\"$a\">$a $descripcion\n" ;
}
?>
<option value="defecto">Asignar por defecto
</select>
</td>
</tr>
<tr>
<td class="eforo_tabla_titulo">&nbsp;</td>
<td class="eforo_tabla_titulo"><b>ID</b></td>
<td class="eforo_tabla_titulo"><b>Rango</b></td>
<td class="eforo_tabla_titulo"><b>Nick</b></td>
<td class="eforo_tabla_titulo"><b>Email</b></td>
<td class="eforo_tabla_titulo"><b>Fecha de registro</b></td>
<td class="eforo_tabla_titulo"><b>IP</b></td>
<td class="eforo_tabla_titulo">&nbsp;</td>
<td class="eforo_tabla_titulo">&nbsp;</td>
</tr>
<tr>
<td colspan="9" class="eforo_tabla_defecto"><?=$ePaginas->paginar()?></td>
</tr>
<?
	$num = 1 ;
	while($datos = mysql_fetch_assoc($con)) {
		if($datos[rango_fijo]) {
			$usuario_rango = $datos[rango] ;
		}
		else {
			$usuario_rango = $datos[rango] ;
			foreach($rangos as $a => $b) {
				list($minimo) = explode('||',$rangos[$a]) ;
				if($minimo != 0 && $datos[mensajes] >= $minimo) {
					$usuario_rango = $a ;
				}
			}
		}
?>
<tr>
<td class="eforo_tabla_mensaje_<?=$num?>"><input type="checkbox" name="id_<?=$datos[id]?>" value="<?=$datos[id]?>"></td>
<td class="eforo_tabla_mensaje_<?=$num?>"><?=$datos[id]?></td>
<td class="eforo_tabla_mensaje_<?=$num?>"><?=$usuario_rango?></td>
<td class="eforo_tabla_mensaje_<?=$num?>"><?=$datos[nick]?></td>
<td class="eforo_tabla_mensaje_<?=$num?>"><a href="mailto:<?=$datos[email]?>" class="eforo"><?=$datos[email]?></a></td>
<td class="eforo_tabla_mensaje_<?=$num?>"><nobr><?=fecha($datos[fecha])?></nobr></td>
<td class="eforo_tabla_mensaje_<?=$num?>"><?=$datos[ip]?></td>
<td class="eforo_tabla_mensaje_<?=$num?>"><input type="button" value="&nbsp;M&nbsp;" onclick="location='usuarios.php?moderador=<?=$datos[id]?>'" class="eforo_formulario"></td>
<td class="eforo_tabla_mensaje_<?=$num?>"><input type="button" value="&nbsp;B&nbsp;" onclick="if(confirm('¿Deseas borrar a este usuario junto con todos sus mensajes?')) location='usuarios.php?borrar=<?=$datos[id]?>'" class="eforo_formulario"></td>
</tr>
<?
		$num = ($num == 1) ? 2 : 1 ;
	}
?>
</form>
<tr>
<td colspan="9" class="eforo_tabla_defecto"><?=$ePaginas->paginar()?></td>
</tr>
<tr>
<td colspan="9" class="eforo_tabla_titulo"><div class="eforo_titulo_1">Ayuda</div></td>
</tr>
<tr>
<td colspan="9" class="eforo_tabla_defecto">
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
<b>¿Como se designan moderadores?</b><br>
Para designar a un moderador haz clic en el botón M y luego selecciona los subforos en donde tendrá
privilegios de moderación. Para quitar estos privilegios a un usuario que ya es moderador haz click
en M y después en la opción Quitar Moderador.<br><br>
<b>¿Como se asignan rangos fijos?</b><br>
Para asignar un rango fijo selecciona las casillas al lado de cada usuario y luego selecciona de la
lista el rango deseado. Este rango no variará con el número de mensajes (si es un rango normal sólo
será fijo para los usuarios seleccionados). Para que su rango sea normal de nuevo haz click en Asignar por defecto.<br><br>
<b>¿Al eliminar a un usuario se borran también sus mensajes?</b><br>
No, se elimina toda la información excepto sus mensajes ya que pudo haber iniciado temas y al
eliminarlos se borrarían automáticamente todas las respuestas en estos. Los mensajes de este usuario
</div>
</td>
</tr>
<?
}
else {
# * Función para designar moderadores en el foro
?>
<tr>
<td class="eforo_tabla_subtitulo"><div class="eforo_titulo_2">Designar moderador</div></td>
</tr>
<tr>
<td class="eforo_tabla_defecto">
<p><a href="usuarios.php" class="eforo">» Regresar a Usuarios</a>
<?
	$con = mysql_query("select nick from $tabla_usuarios where id='$_GET[moderador]'") ;
	$datos = mysql_fetch_assoc($con) ;
	$nick_moderador = $datos[nick] ;
	mysql_free_result($con) ;
?>
<p>Debes seleccionar los subforos en donde desees que <b><?=$nick_moderador?></b> sea moderador.
<p>
<form method="post" action="usuarios.php">
<input type="hidden" name="id_moderador" value="<?=$_GET[moderador]?>">
<?
	# * Almacena el id y el nombre de las categorias en un array
	$con = mysql_query('select * from eforo_categorias order by orden asc') ;
	while($datos = mysql_fetch_assoc($con)) {
		$categorias[$datos[id]] = $datos[categoria] ;
	}
	mysql_free_result($con) ;
	foreach($categorias as $categoria_id => $categoria_nombre) {
?>
<b><?=$categoria_nombre?></b><br>
<?
		$con = mysql_query("select id,foro from eforo_foros where id_categoria='$categoria_id' order by orden asc") ;
		while($datos = mysql_fetch_assoc($con)) {
			$con2 = mysql_query("select id from eforo_moderadores where id_foro='$datos[id]' and id_usuario='$_GET[moderador]'") ;
			$sel = mysql_num_rows($con2) ? ' checked' : false ;
			mysql_free_result($con2) ;
?>
<input type="checkbox" name="foro_<?=$datos[id]?>" value="<?=$datos[id]?>"<?=$sel?>> <?=$datos[foro]?><br>
<?
		}
		mysql_free_result($con) ;
	}
?>
<br>
<center>
<input type="submit" name="designar" value="Designar Moderador" class="eforo_formulario">
<input type="button" value="Quitar Moderador" onclick="if(confirm('¿Deseas quitar los privilegios de moderación a <?=$nick_moderador?>?')) location = 'usuarios.php?quitar=<?=$_GET[moderador]?>'" class="eforo_formulario">
</center>
</form>
</td>
</tr>
<?
}
?>
</table>
