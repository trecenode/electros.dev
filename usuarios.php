<?
/*
************************************************
*** eForo v3.0
*** Creado por: Electros <electros@electros.net>
*** Sitio web: www.electros.net
*** Licencia: GNU General Public License
************************************************

--- Página: usuarios.php ---

eForo - Una comunidad para que tus visitantes se comuniquen y se sientan parte de tu web
Copyright © 2003-2005 Daniel Osorio "Electros"

Este programa es software libre, puedes redistribuirlo y/o modificarlo bajo los términos
de la GNU General Public License publicados por la Free Software Foundation; desde la
versión 2 de la licencia, o (si lo deseas) cualquiera más reciente.
*/

require('foroconfig.php') ;
require('eforo_funciones/epaginas.php') ;
?>
<p><a href="<?="$u[0]foro$u[1]$u[5]"?>" class="eforo">» Regresar</a>
<p>
<table width="100%" border="0" cellpadding="3" cellspacing="1" align="center" class="eforo_tabla_principal">
<tr>
<td colspan="8" class="eforo_tabla_titulo"><div class="eforo_titulo_1">Lista de usuarios</div></td>
</tr>
<?
if(!ereg('^[0-9]+$',$_GET[u])) {
	$letra = ereg('[a-z]{1}',$_GET[letra]) ? " where nick like '$_GET[letra]%'" : false ;
	$orden = $letra ? 'nick asc' : 'id desc' ;
	$ePaginas = new ePaginas("select * from $tabla_usuarios$letra order by $orden",30) ;
	$con = $ePaginas->consultar() ;
?>
<tr>
<td colspan="8" class="eforo_tabla_defecto">
<center>
<a href="<?="$u[0]usuarios$u[1]$u[5]"?>" class="eforo">Más recientes</a> |
<a href="<?="$u[0]usuarios$u[1]$u[2]letra$u[4]a$u[5]"?>" class="eforo">A</a> |
<a href="<?="$u[0]usuarios$u[1]$u[2]letra$u[4]b$u[5]"?>" class="eforo">B</a> |
<a href="<?="$u[0]usuarios$u[1]$u[2]letra$u[4]c$u[5]"?>" class="eforo">C</a> |
<a href="<?="$u[0]usuarios$u[1]$u[2]letra$u[4]d$u[5]"?>" class="eforo">D</a> |
<a href="<?="$u[0]usuarios$u[1]$u[2]letra$u[4]e$u[5]"?>" class="eforo">E</a> |
<a href="<?="$u[0]usuarios$u[1]$u[2]letra$u[4]f$u[5]"?>" class="eforo">F</a> |
<a href="<?="$u[0]usuarios$u[1]$u[2]letra$u[4]g$u[5]"?>" class="eforo">G</a> |
<a href="<?="$u[0]usuarios$u[1]$u[2]letra$u[4]h$u[5]"?>" class="eforo">H</a> |
<a href="<?="$u[0]usuarios$u[1]$u[2]letra$u[4]i$u[5]"?>" class="eforo">I</a> |
<a href="<?="$u[0]usuarios$u[1]$u[2]letra$u[4]j$u[5]"?>" class="eforo">J</a> |
<a href="<?="$u[0]usuarios$u[1]$u[2]letra$u[4]k$u[5]"?>" class="eforo">K</a> |
<a href="<?="$u[0]usuarios$u[1]$u[2]letra$u[4]l$u[5]"?>" class="eforo">L</a> |
<a href="<?="$u[0]usuarios$u[1]$u[2]letra$u[4]m$u[5]"?>" class="eforo">M</a> |
<a href="<?="$u[0]usuarios$u[1]$u[2]letra$u[4]n$u[5]"?>" class="eforo">N</a> |
<a href="<?="$u[0]usuarios$u[1]$u[2]letra$u[4]o$u[5]"?>" class="eforo">O</a> |
<a href="<?="$u[0]usuarios$u[1]$u[2]letra$u[4]p$u[5]"?>" class="eforo">P</a> |
<a href="<?="$u[0]usuarios$u[1]$u[2]letra$u[4]q$u[5]"?>" class="eforo">Q</a> |
<a href="<?="$u[0]usuarios$u[1]$u[2]letra$u[4]r$u[5]"?>" class="eforo">R</a> |
<a href="<?="$u[0]usuarios$u[1]$u[2]letra$u[4]s$u[5]"?>" class="eforo">S</a> |
<a href="<?="$u[0]usuarios$u[1]$u[2]letra$u[4]t$u[5]"?>" class="eforo">T</a> |
<a href="<?="$u[0]usuarios$u[1]$u[2]letra$u[4]u$u[5]"?>" class="eforo">U</a> |
<a href="<?="$u[0]usuarios$u[1]$u[2]letra$u[4]v$u[5]"?>" class="eforo">V</a> |
<a href="<?="$u[0]usuarios$u[1]$u[2]letra$u[4]w$u[5]"?>" class="eforo">W</a> |
<a href="<?="$u[0]usuarios$u[1]$u[2]letra$u[4]x$u[5]"?>" class="eforo">X</a> |
<a href="<?="$u[0]usuarios$u[1]$u[2]letra$u[4]y$u[5]"?>" class="eforo">Y</a> |
<a href="<?="$u[0]usuarios$u[1]$u[2]letra$u[4]z$u[5]"?>" class="eforo">Z</a>
</center>
</td>
</tr>
<tr>
<td colspan="8" class="eforo_tabla_defecto"><?=$ePaginas->paginar()?></td>
</tr>
<tr>
<td class="eforo_tabla_titulo"><b>Nick</b></td>
<td class="eforo_tabla_titulo"><b>Sexo</b></td>
<td class="eforo_tabla_titulo"><b>Edad</b></td>
<td class="eforo_tabla_titulo"><b>País</b></td>
<td class="eforo_tabla_titulo"><b>Fecha de registro</b></td>
</tr>
<?
	$num = 1 ;
	while($datos = mysql_fetch_assoc($con)) {
?>
<tr>
<td class="eforo_tabla_mensaje_<?=$num?>"><a href="<?="$u[0]usuarios$u[1]$u[2]u$u[4]$datos[id]$u[5]"?>" class="eforo"><?=$datos[nick]?></a></td>
<td class="eforo_tabla_mensaje_<?=$num?>"><?=!$datos[sexo] ? 'Masculino' : 'Femenino'?></td>
<td class="eforo_tabla_mensaje_<?=$num?>"><?=$datos[edad] ? $datos[edad] : '&nbsp;'?></td>
<td class="eforo_tabla_mensaje_<?=$num?>"><?=$datos[pais] ? $datos[pais] : '&nbsp;'?></td>
<td class="eforo_tabla_mensaje_<?=$num?>"><?=fecha($datos[fecha])?></td>
</tr>
<?
		$num = ($num == 1) ? 2 : 1 ; 
	}
	mysql_free_result($con) ;
?>
<tr>
<td colspan="8" class="eforo_tabla_defecto"><?=$ePaginas->paginar()?></td>
</tr>
<?
}
else {
	# * Se almacenan todos los rangos en un array
	$con = mysql_query('select * from eforo_rangos order by rango asc') ;
	while($datos = mysql_fetch_assoc($con)) {
		$rangos[$datos[rango]] = $datos[minimo].'||'.$datos[descripcion] ;
	}
	mysql_free_result($con) ;
	$con = mysql_query("select * from $tabla_usuarios where id='$_GET[u]'") ;
	if(mysql_num_rows($con)) {
		$datos = mysql_fetch_assoc($con) ;
		$avatar = !$datos[avatar] ? '0.gif' : $datos[id].'.'.$datos[avatar] ;
		$datos[descripcion] = str_replace("\n",'<br>',$datos[descripcion]) ;
		$datos[firma] = str_replace("\n",'<br>',$datos[firma]) ;
		$datos[firma] = preg_replace('/\[.*\]/U','',$datos[firma]) ;
		if($datos[rango_fijo]) {
			list($minimo,$descripcion) = explode('||',$rangos[$datos[rango]]) ;
			$usuario_rango = $datos[rango].' - '.$descripcion ;
		}
		else {
			list($minimo,$descripcion) = explode('||',$rangos[1]) ;
			$usuario_rango = '1 - '.$descripcion ;
			foreach($rangos as $a => $b) {
				list($minimo,$descripcion) = explode('||',$b) ;
				if($minimo != 0 && $datos[mensajes] >= $minimo) {
					$usuario_rango = $a.' - '.$descripcion ;
				}
			}
		}
?>
<tr>
<td class="eforo_tabla_subtitulo"><div class="eforo_titulo_2">Perfil de <?=$datos[nick]?></div></td>
</tr>
<tr>
<td class="eforo_tabla_defecto">
<p><a href="<?="$u[0]usuarios$u[1]$u[5]"?>" class="eforo">» Regresar a Lista de usuarios</a>
<p>
<table width="100%" border="0" cellpadding="5" cellspacing="0">
<tr>
<td width="30%" valign="top"><b>Sexo:</b></td>
<td width="30%" valign="top"><?=!$datos[sexo] ? 'Masculino' : 'Femenino'?></td>
<td width="35%" rowspan="6" valign="top"><img src="eforo_imagenes/avatares/<?=$avatar?>" alt="Avatar de <?=$datos[nick]?>"></td>
</tr>
<tr>
<td valign="top"><b>Edad:</b></td>
<td valign="top"><?=$datos[edad] ? $datos[edad] : '<i>No indicada</i>'?></td>
</tr>
<tr>
<td valign="top"><b>País:</b></td>
<td valign="top"><?=$datos[pais] ? $datos[pais] : '<i>No indicado</i>'?></td>
</tr>
<tr>
<td valign="top"><b>Descripción:</b></td>
<td valign="top"><?=$datos[descripcion] ? $datos[descripcion] : '<i>Sin descripción</i>'?></td>
</tr>
<tr>
<td valign="top"><b>Web:</b></td>
<td valign="top"><?=$datos[web] ? "<a href=\"$datos[web]\" target=\"_blank\" class=\"eforo\">$datos[web]</a>" : '<i>Sin web</i>'?></td>
</tr>
<tr>
<td valign="top"><b>Firma en los mensajes:</b></td>
<td valign="top"><?=$datos[firma] ? $datos[firma] : '<i>Sin firma</i>'?></td>
</tr>
<tr>
<td valign="top"><b>Mensajes publicados:</b></td>
<td valign="top"><?=$datos[mensajes]?></td>
</tr>
<tr>
<td valign="top"><b>Rango o nivel:</b></td>
<td valign="top"><?=$usuario_rango?></td>
</tr>
<tr>
<td valign="top"><b>Fecha de registro:</b></td>
<td valign="top"><?=fecha($datos[fecha])?></td>
</tr>
<tr>
<td valign="top"><b>Conectado por última vez:</b></td>
<td valign="top"><?=fecha($datos[conectado])?></td>
</tr>
</table>
</td>
</tr>
<?
	}
	else {
?>
<tr>
<td class="eforo_tabla_defecto"><center><b>El usuario no existe.</b></center></td>
</tr>
<?
	}
}
?>
</table>
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
