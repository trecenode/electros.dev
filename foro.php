<?
/*
************************************************
*** eForo v3.0
*** Creado por: Electros <electros@electros.net>
*** Sitio web: www.electros.net
*** Licencia: GNU General Public License
************************************************

--- Página: foro.php ---

eForo - Una comunidad para que tus visitantes se comuniquen y se sientan parte de tu web
Copyright © 2003-2005 Daniel Osorio "Electros"

Este programa es software libre, puedes redistribuirlo y/o modificarlo bajo los términos
de la GNU General Public License publicados por la Free Software Foundation; desde la
versión 2 de la licencia, o (si lo deseas) cualquiera más reciente.
*/

require('foroconfig.php') ;

echo "<title>$conf[foro_titulo]</title>" ;

# * Marcar los subforos como leídos
if($_GET[leidos]) {
	mysql_query("delete from eforo_recientes where id_usuario='$usuario[id]'") ;
}

require('eforo_funciones/recientes.php') ;
require('eforo_funciones/menu.php') ;

# **************************************************
# *** Mostrar todos los subforos (sección principal)
# **************************************************

# * Almacena el id y el nombre de las categorias en un array
$con = mysql_query('select * from eforo_categorias order by orden asc') ;
while($datos = mysql_fetch_assoc($con)) {
	$categorias[$datos[id]] = $datos[categoria] ;
}
mysql_free_result($con) ;
?>
<table width="100%" border="0" cellpadding="3" cellspacing="1" align="center" class="eforo_tabla_principal">
<tr>
<td colspan="2" class="eforo_tabla_subtitulo"><div class="eforo_titulo_1">Estadísticas</div></td>
</tr>
<tr>
<td width="50%" valign="top" class="eforo_tabla_defecto">
Nos visitan <b><?=$total_enlinea[2]?></b> usuarios: <b><?=$total_enlinea[1]?></b> registrados y <b><?=$total_enlinea[0]?></b> anónimos
<br><br>
<?=$reg_enlinea?>
</td>
<?
$con = mysql_query("select count(id) from $tabla_usuarios") ;
$total_reg = mysql_result($con,0,0) ;
mysql_free_result($con) ;
$con = mysql_query("select id,nick from $tabla_usuarios order by id desc limit 1") ;
$datos = mysql_fetch_assoc($con) ;
$ultimo_reg = "<a href=\"$u[0]usuarios$u[1]$u[2]u$u[4]$datos[id]$u[5]\" class=\"eforo\">$datos[nick]</a>" ;
mysql_free_result($con) ;
?>
<td width="50%" valign="top" class="eforo_tabla_defecto">
Total de usuarios: <b><?=$total_reg?></b><br>
Ultimo registrado: <b><?=$ultimo_reg?></b><br>
<a href="<?="$u[0]usuarios$u[1]$u[5]"?>" class="eforo">» Lista de usuarios</a>
</td>
</tr>
</table>
<table width="100%" border="0" cellpadding="3" cellspacing="1" align="center" class="eforo_tabla_principal">
<tr>
<td colspan="2" class="eforo_tabla_titulo"><div class="eforo_titulo_1">Categoria</div></td>
<td class="eforo_tabla_titulo"><div class="eforo_titulo_1">Ultimo mensaje</div></td>
<td class="eforo_tabla_titulo"><div class="eforo_titulo_1">Tem</div></td>
<td class="eforo_tabla_titulo"><div class="eforo_titulo_1">Men</div></td>
</tr>
<?
# * Se muestran los foros de cada categoria (leyendo el array previamente creado)
foreach($categorias as $categoria_id => $categoria_nombre) {
?>
<tr>
<td colspan="5" class="eforo_tabla_subtitulo"><div class="eforo_titulo_2"><?=eregi_replace('^(.{1})(.*)$','<span class="eforo_titulo_2a">\\1</span>\\2',$categoria_nombre)?></div></td>
</tr>
<?
	$con = mysql_query("select * from eforo_foros where id_categoria='$categoria_id' order by orden asc") ;
	$num = 1 ;
	while($datos = mysql_fetch_assoc($con)) {
		# Se obtienen los datos del último mensaje enviado en cada foro
		$con2 = mysql_query("select id,id_tema,id_usuario,fecha from eforo_mensajes where id_foro='$datos[id]' order by fecha desc limit 1") ;
		$datos2 = mysql_fetch_assoc($con2) ;
		$con3 = mysql_query("select nick from $tabla_usuarios where id='$datos2[id_usuario]'") ;
		$datos3 = mysql_fetch_assoc($con3) ;
		$nick_usuario = mysql_num_rows($con3) ? "<a href=\"$u[0]usuarios$u[1]$u[2]u$u[4]$datos2[id_usuario]$u[5]\" class=\"eforo\">$datos3[nick]</a>" : '<i>Anónim@</i>' ;
		mysql_free_result($con3) ;
		# Se buscan mensajes de este subforo que correspondan a los que están guardados en el recordatorio
		$iluminar = 'foco_apagado.gif' ;
		if($_COOKIE[e_nick]) {
			$con3 = mysql_query("select * from eforo_recientes where id_usuario='$usuario[id]' and id_foro='$datos[id]' limit 1") ;
			if(mysql_num_rows($con3)) {
				$iluminar = 'foco_encendido.gif' ;
			}
			mysql_free_result($con3) ;
		}
?>
<tr>
<td class="eforo_tabla_mensaje_<?=$num?>"><center><img src="eforo_estilo/<?=$conf[estilo]?>/<?=$iluminar?>" alt="Hay mensajes nuevos"></center></td>
<td class="eforo_tabla_mensaje_<?=$num?>"><a href="<?="$u[0]forotemas$u[1]$u[2]foro$u[4]$datos[id]$u[5]"?>" class="eforo"><?=$datos[foro]?></a><br><?=$datos[descripcion]?></td>
<td class="eforo_tabla_mensaje_<?=$num?>">
<center>
<?
		if($datos2[id]) {
?>
<?=$nick_usuario?> <a href="<?="$u[0]foromensajes$u[1]$u[2]foro$u[4]$datos[id]$u[3]tema$u[4]$datos2[id_tema]$u[5]#$datos2[id]"?>" class="eforo">»</a><br><span style="font-size: 7pt"><?=fecha($datos2[fecha])?></span>
<?
		}
		else {
			echo '<b>No hay mensajes</b>' ;
		}
?>
</center>
</td>
<td class="eforo_tabla_mensaje_<?=$num?>"><center><?=$datos[num_temas]?></center></td>
<td class="eforo_tabla_mensaje_<?=$num?>"><center><?=$datos[num_mensajes]?></center></td>
</tr>
<?
		mysql_free_result($con2) ;
		# Se alternan los colores para cada fila (los estilos usados son .eforo_mensajes_1 y .eforo_mensajes_2)
		$num = ($num == 1) ? 2 : 1 ;
	}
	mysql_free_result($con) ;
}
?>
<tr>
<td colspan="2" class="eforo_tabla_titulo"><div class="eforo_titulo_1">Categoria</div></td>
<td class="eforo_tabla_titulo"><div class="eforo_titulo_1">Ultimo mensaje</div></td>
<td class="eforo_tabla_titulo"><div class="eforo_titulo_1">Tem</div></td>
<td class="eforo_tabla_titulo"><div class="eforo_titulo_1">Men</div></td>
</tr>
</table>
<br>
<div align="right"><a href="<?="$u[0]foro$u[1]$u[2]leidos$u[4]1$u[5]"?>" class="eforo">» Marcar los subforos como leídos</a></div>
<br><br>
<center>
<span style="font-size: 7pt">
<a href="http://www.electros.net" target="_blank" class="eforo">eForo v3.0</a> © 2003-2005 Bajo licencia GNU General Public License<br>
<?
# * Obtener tiempo de carga del foro (la función tiempo() se encuentra en foroconfig.php)
$tiempo_b = tiempo() ;
$tiempo = round($tiempo_b - $tiempo_a,4) ;
echo 'Tiempo de carga del servidor: <b>'.$tiempo.'</b> seg' ;
?>
</span>
</center>
