<?
/*
************************************************
*** eForo v3.0
*** Creado por: Electros <electros@electros.net>
*** Sitio web: www.electros.net
*** Licencia: GNU General Public License
************************************************

--- Página: forotemas.php ---

eForo - Una comunidad para que tus visitantes se comuniquen y se sientan parte de tu web
Copyright © 2003-2005 Daniel Osorio "Electros"

Este programa es software libre, puedes redistribuirlo y/o modificarlo bajo los términos
de la GNU General Public License publicados por la Free Software Foundation; desde la
versión 2 de la licencia, o (si lo deseas) cualquiera más reciente.
*/

require('foroconfig.php') ;
require('eforo_funciones/recientes.php') ;
require('eforo_funciones/menu.php') ;
require('eforo_funciones/epaginas.php') ;

echo "<title>$conf[foro_titulo] · $titulo_subforo_2</title>" ;

# * Se comprueba si el usuario tiene permiso para entrar al subforo seleccionado
$con = mysql_query("select p_leer from eforo_foros where id='$_GET[foro]' limit 1") ;
$datos = mysql_fetch_assoc($con) ;
if($usuario[rango] < $datos[p_leer]) {
	require('eforo_funciones/aviso.php') ;
	aviso('Nivel mínimo insuficiente','<p>No tienes suficiente nivel para entrar a este subforo. Intenta iniciar sesión desde el menú.<p><a href="javascript:history.back()" class="eforo">» Regresar</a>') ;
}
else {
# **********************************************
# *** Mostrar los temas del subforo seleccionado
# **********************************************
# * Se muestran los moderadores del subforo seleccionado
$moderadores = false ;
for($a = 0 ; $a < count($conf[admin_id]) ; $a++) {
	$con = mysql_query("select nick from $tabla_usuarios where id='{$conf[admin_id][$a]}'") ;
	$datos = mysql_fetch_assoc($con) ;
	if(!$a) {
		$moderadores[] = "<a href=\"$u[0]usuarios$u[1]$u[2]u$u[4]{$conf[admin_id][$a]}$u[5]\" class=\"eforo\">$datos[nick]</a>" ;
	}
	else {
		$moderadores[] = ", <a href=\"$u[0]usuarios$u[1]$u[2]u$u[4]{$conf[admin_id][$a]}$u[5]\" class=\"eforo\">$datos[nick]</a>" ;
	}
	mysql_free_result($con) ;
}
$con = mysql_query("select * from eforo_moderadores where id_foro='$_GET[foro]' order by id asc") ;
while($datos = mysql_fetch_assoc($con)) {
	$con2 = mysql_query("select nick from $tabla_usuarios where id='$datos[id_usuario]'") ;
	$datos2 = mysql_fetch_assoc($con2) ;
	$moderadores[] = ", <a href=\"$u[0]usuarios$u[1]$u[2]u$u[4]$datos[id_usuario]$u[5]\" class=\"eforo\">$datos2[nick]</a>" ;
	mysql_free_result($con2) ;
}
mysql_free_result($con) ;
?>
<table width="100%" border="0" cellpadding="2" cellspacing="1" align="center" class="eforo_tabla_principal">
<tr>
<td class="eforo_tabla_titulo"><div class="eforo_titulo_1">Moderadores</div></td>
</tr>
<tr>
<td class="eforo_tabla_defecto">Total de moderadores: <b><?=count($moderadores)?></b><br><br><?=implode('',$moderadores)?></td>
</tr>
</table>
<table width="100%" border="0" cellpadding="2" cellspacing="1" align="center" class="eforo_tabla_principal">
<tr>
<td colspan="2" class="eforo_tabla_titulo"><div class="eforo_titulo_1">Tema</div></td>
<td class="eforo_tabla_titulo"><div class="eforo_titulo_1">Autor</div></td>
<td class="eforo_tabla_titulo"><div class="eforo_titulo_1">Ultimo</div></td>
<td class="eforo_tabla_titulo"><div class="eforo_titulo_1">Vis</div></td>
<td class="eforo_tabla_titulo"><div class="eforo_titulo_1">Res</div></td>
</tr>
<?
# Se muestran los nuevos temas o recién respondidos del subforo seleccionado
$ePaginas = new ePaginas("select * from eforo_mensajes where id=id_tema and id_foro='{$_GET['foro']}' order by o_importante desc, fecha_ultimo desc",$conf['max_temas']) ;
$con = $ePaginas->consultar() ;
?>
<tr>
<td colspan="6" class="eforo_tabla_defecto"><?=$ePaginas->paginar()?></td>
</tr>
<?
$num = 1 ;
while($datos = mysql_fetch_assoc($con)) {
	# Se buscan mensajes que estén en el recordatorio
	$iluminar = 'foco_apagado.gif' ;
	if($_COOKIE[e_nick]) {
		$con2 = mysql_query("select * from eforo_recientes where id_usuario='$usuario[id]' and id_mensaje='$datos[id]' limit 1") ;
		if(mysql_num_rows($con2)) {
			$iluminar = 'foco_encendido.gif' ;
		}
		mysql_free_result($con2) ;
	}
	# Se obtiene el nombre del autor de cada tema
	$con2 = mysql_query("select nick from $tabla_usuarios where id='$datos[id_usuario]'") ;
	$datos2 = mysql_fetch_assoc($con2) ;
	$autor_tema = mysql_num_rows($con2) ? "<a href=\"$u[0]usuarios$u[1]$u[2]u$u[4]$datos[id_usuario]$u[5]\" class=\"eforo\">$datos2[nick]</a>" : '<i>Anónim@</i>' ;
	mysql_free_result($con2) ;
	# Se obtiene el nombre del autor del último mensaje enviado de cada tema
	$con2 = mysql_query("select id,id_usuario from eforo_mensajes where id_tema='$datos[id]' order by id desc limit 1") ;
	$datos2 = mysql_fetch_assoc($con2) ;
	$con3 = mysql_query("select nick from $tabla_usuarios where id='$datos2[id_usuario]'") ;
	$datos3 = mysql_fetch_assoc($con3) ;
	$autor_ultimo = mysql_num_rows($con3) ? "<a href=\"$u[0]usuarios$u[1]$u[2]u$u[4]$datos2[id_usuario]$u[5]\" class=\"eforo\">$datos3[nick]</a>" : '<i>Anónim@</i>' ;
	mysql_free_result($con3) ;
	# Si el tema es importante se añade una imagen
	$importante = $datos[o_importante] ? '<img src="eforo_estilo/'.$conf[estilo].'/importante.gif" border="0" align="absmiddle" alt="Tema importante"> ' : false ; 
?>
<tr>
<td class="eforo_tabla_mensaje_<?=$num?>"><center><img src="eforo_estilo/<?=$conf[estilo]?>/<?=$iluminar?>"></center></td>
<td class="eforo_tabla_mensaje_<?=$num?>"><a href="<?="$u[0]foromensajes$u[1]$u[2]foro$u[4]$_GET[foro]$u[3]tema$u[4]$datos[id]$u[5]"?>" class="eforo"><?=$importante.$datos[tema]?></a></td>
<td class="eforo_tabla_mensaje_<?=$num?>"><center><?=$autor_tema?><br><span style="font-size: 7pt"><?=fecha($datos[fecha])?></span></center></td>
<td class="eforo_tabla_mensaje_<?=$num?>">
<center>
<?
	if($datos[num_respuestas]) {
?>
<?=$autor_ultimo?><br><span style="font-size: 7pt"><?=fecha($datos[fecha_ultimo])?></span>
<?
	}
	else {
		echo '<b>Sin respuestas</b>' ;
	}
?>
</center>
</td>
<td class="eforo_tabla_mensaje_<?=$num?>"><center><?=$datos[num_visitas]?></center></td>
<td class="eforo_tabla_mensaje_<?=$num?>"><center><?=$datos[num_respuestas]?></center></td>
</tr>
<?
	mysql_free_result($con2) ;
	# Se alternan los colores para cada fila (los estilos usados son .eforo_mensajes_1 y .eforo_mensajes_2)
	$num = ($num == 1) ? 2 : 1 ;
}
mysql_free_result($con) ;
?>
<tr>
<td colspan="6" class="eforo_tabla_defecto"><?=$ePaginas->paginar()?></td>
</tr>
<tr>
<td colspan="2" class="eforo_tabla_titulo"><div class="eforo_titulo_1">Tema</div></td>
<td class="eforo_tabla_titulo"><div class="eforo_titulo_1">Autor</div></td>
<td class="eforo_tabla_titulo"><div class="eforo_titulo_1">Ultimo</div></td>
<td class="eforo_tabla_titulo"><div class="eforo_titulo_1">Vis</div></td>
<td class="eforo_tabla_titulo"><div class="eforo_titulo_1">Res</div></td>
</tr>
</table>
<?
}
?>
<br><br>
<center>
<span style="font-size: 7pt">
<a href="http://www.electros.net" target="_blank" class="eforo">eForo v3.0</a> © 2003-2005 Bajo licencia GNU General Public License<br>
<?
# * Obtener tiempo de carga del foro (la función tiempo() se encuentra en foroconfig.php)
$tiempo_b = tiempo() ;
$tiempo = round($tiempo_b - $tiempo_a,4) ;
echo 'Tiempo de carga del servidor: <b>'.$tiempo.'</b>' ;
?>
</span>
</center>