<?
unset($error) ;
require('eforo_funciones/epaginas.php') ;
switch(true) {
	case !empty($_GET['c']) && !ereg('^[0-9]*$',$_GET['c']) :
		$error = 'Nada de SQL Injection por favor' ;
		break ;
	case !empty($_GET['s']) && !ereg('^[0-9]*$',$_GET['s']) :
		$error = 'Nada de SQL Injection por favor' ;
		break ;
	default :
		if($_GET['c']) {
			$con = mysql_query("select count(id) from scriptscat where id='{$_GET['c']}'") ;
			if(!mysql_result($con,0,0)) {
				$error = 'No existe esta categoría<br>' ;
			}
			mysql_free_result($con) ;
		}
		if($_GET['s']) {
			$con = mysql_query("select count(id) from scripts where id='{$_GET['s']}' and validar='1'") ;
			if(!mysql_result($con,0,0) && !empty($_COOKIE['e_id']) && $_COOKIE['e_id'] != 1) {
				$error .= 'No existe este script<br>' ;
			}
			mysql_free_result($con) ;
			$con = mysql_query("select id_categoria from scripts where id='{$_GET['s']}'") ;
			$datos = mysql_fetch_assoc($con) ;
			if($_GET['c'] != $datos['id_categoria']) {
				header("location: scripts/c/{$datos['id_categoria']}/s/{$_GET['s']}") ;
				exit ;
			}
			mysql_free_result($con) ;
		}
}
if($error) {
	echo $error ;
	exit ;
}
?>
<table width="100%" border="0" cellpadding="3" cellspacing="0" class="tabla_t1">
<tr>
<td height="20">&nbsp;<img src="imagenes/flecha.gif" border="0" width="11" height="11" alt="Scripts" align="top"> <b>Scripts</b></td>
</tr>
<tr class="tabla_t2">
<td>
<div style="margin: 10px">
<?
switch(true) {
	// * Mostrar las categorías
	case !$_GET['c'] && !$_GET['s'] :
?>
<p>Aquí encontrarás todo tipo de scripts, desde los más básicos para aprender PHP y MySQL de la forma más sencilla, hasta los más
complejos para usuarios avanzados. 
<p style="color: #aa0000"><b>Atención:</b> Para no tener que estar modificando constantemente los datos para conectarse
a una base de datos, hemos decidido usar un sólo archivo en todos los scripts que lo requieran, para crearlo haz click en
la siguiente dirección:
<p><a href="ayuda" style="color: #aa0000">Crear el archivo config.php</a>
<p>Selecciona la categoría del script que estás buscando:
<p>
<table width="95%" border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
<td valign="top">
<?
		# --> Número de columnas
		$columnas = 3 ;
		$con = mysql_query("select * from scriptscat order by nombre asc") ;
		$res_columna = ceil(mysql_num_rows($con)/$columnas) ;
		for($i = 1 ; $datos = mysql_fetch_assoc($con) ; $i++) {
			$con2 = mysql_query("select count(id) from scripts where validar='1' and id_categoria='{$datos['id']}'") ;
			$total = mysql_result($con2,0,0) ;
			mysql_free_result($con2) ;
?>
<a href="scripts/c/<?=$datos['id']?>"><img src="imagenes/carpeta.gif" width="15" height="13" border="0" alt="Carpeta"> <?=$datos['nombre']?> (<?=$total?>)</a><br><br>
<?
			if($i % $res_columna == 0) {
?>
</td>
<td valign="top">
<?
			} 
		}
?>
</td>
</tr>
</table>
<?
		break ;
	// * Mostrar los scripts de la categoría seleccionada
	case $_GET['c'] && !$_GET['s'] :
		$con = mysql_query("select * from scriptscat where id='{$_GET['c']}'") ;
		$datos = mysql_fetch_assoc($con) ;
?>
<p class="t1"><a href="scripts">Scripts</a> >> <a href="scripts/c/<?=$_GET['c']?>"><?=$datos['nombre']?></a>
<p><div style="border: #000000 1px solid ; background: #ffecca"><div style="margin: 3px"><b>Descripción:</b><br><?=$datos['descripcion']?></div></div>
<p>
<?
		mysql_free_result($con) ;
		$ePaginas = new ePaginas("select * from scripts where validar='1' and id_categoria='{$_GET['c']}' order by titulo asc",15) ;
		$con = $ePaginas->consultar() ;
?>
<p><?=$ePaginas->paginar()?>
<p>
<?
		while($datos = mysql_fetch_assoc($con)) {
			$abrir = fopen("scripts_a/{$datos['id']}.txt",'r') ;
			$script = fread($abrir,200) ;
			fclose($abrir) ;
			$script = preg_replace('/\[.*\]/iU','',$script) ;
			#$script = str_replace("\n",'<br>',$script) ;
?>
<table width="100%" border="0" cellpadding="3" cellspacing="0" class="tabla_s1">
<tr>
<td colspan="2"><a href="scripts/c/<?=$_GET['c']?>/s/<?=$datos['id']?>"><img src="imagenes/flecha.gif" border="0" width="11" height="11" alt="Script · <?=$datos['titulo']?>" align="top"> <b><?=$datos['titulo']?></b></a></td>
</tr>
<tr>
<td colspan="2">
<?=$script?>...
</td>
</tr>
<tr>
<td>Por: <b><?=usuario($datos['usuario'])?></b></td>
<?
			$con2 = mysql_query("select count(id) from scriptscom where id_script='{$datos['id']}'") ;
			$comentarios = mysql_result($con2,0,0) ;
			mysql_free_result($con2) ;
?>
<td><div align="right">Visitas: <b><?=$datos['visitas']?></b> | Valoración: <b><?=$datos['valoracion']?></b> | Comentarios: <b><?=$comentarios?></b></div></td>
</tr>
</table><br>
<?
		}
		mysql_free_result($con) ;
?>
<p><?=$ePaginas->paginar()?>
<p>
<?
		if(!empty($_COOKIE['e_id']) && $_COOKIE['e_id'] == 1) {
			require 'ulogin.php' ;
?>
<p class="t1"><b>Scripts ocultos o no visibles</b>
<p>
<?
			$con = mysql_query("select * from scripts where validar='0' and id_categoria='{$_GET['c']}' order by id desc") ;
			if(!mysql_num_rows($con)) {
				echo '<p><b>No hay scripts ocultos.</b>' ;
			}
			else {
				while($datos = mysql_fetch_assoc($con)) {
?>
<table width="598" border="0" cellpadding="0" cellspacing="2" style="border: #000000 1px solid ; background: #ffe3af">
<tr>
<td valign="top"><a href="scripts/c/<?=$_GET['c']?>/s/<?=$datos['id']?>"><?=$datos['titulo']?></a></td>
</tr>
</table>
<div style="margin: 3px"></div>
<?
				}
				mysql_free_result($con) ;
			}
		}
		break ;
	// * Mostrar el script seleccionado
	case $_GET['c'] && $_GET['s'] :
		include 'fecha.php' ;
		include 'codigo.php' ;
		if(!$_GET['p']) {
			mysql_query("update scripts set visitas=visitas+1 where id='{$_GET['s']}'") ;
		}
		$con = mysql_query("select count(id) from scripts where id='{$_GET['s']}' and usuario='{$_COOKIE['e_id']}'") ;
		if(mysql_result($con,0,0) || (!empty($_COOKIE['e_id']) && $_COOKIE['e_id'] == 1)) {
?>
<p><input type="button" value="Edita tu script" onclick="location='<?=$url_base?>scriptseditar/c/<?=$_GET[c]?>/s/<?=$_GET[s]?>'" class="form">
<p>
<?
		}
		mysql_free_result($con) ;
		if(!empty($_COOKIE['e_id']) && $_COOKIE['e_id'] == 1) {
			require 'ulogin.php' ;
?>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" style="border: #ffb42a 1px dashed ; background: #ffda94">
<tr>
<td><b>Moderadores:</b></td>
<td>
<form method="post" action="scriptsadmin.php" style="display: inline">
<input type="hidden" name="que" value="mover">
<input type="hidden" name="s" value="<?=$_GET['s']?>">
<select name="id_categoria" class="form" onchange="submit()">
<option value="0">Mover a ...
<?
			$con = mysql_query("select * from scriptscat order by nombre asc") ;
			while($datos = mysql_fetch_assoc($con)) {
?>
<option value="<?=$datos['id']?>"><?=$datos['nombre']?>
<?
			}
			mysql_free_result($con) ;
?>
</select>
<?
			$con = mysql_query("select validar from scripts where id='{$_GET['s']}'") ;
			$datos = mysql_fetch_assoc($con) ;
			if($datos['validar'] == 1) {
				$estado = '<b>Visible</b>' ;
			}
			else {
				$estado = '<span style="color: #aa0000"><b>Oculto</b></span>' ;
			}
			mysql_free_result($con) ;
?>
</form>
</td>
<td>Estado actual: <?=$estado?></td>
<td>
<div align="right">
<input type="button" value="Validar" onclick="location = '<?=$url_base?>scriptsadmin.php?c=<?=$_GET[c]?>&s=<?=$_GET[s]?>&que=1'" class="form">
<input type="button" value="Ocultar" onclick="location = '<?=$url_base?>scriptsadmin.php?c=<?=$_GET[c]?>&s=<?=$_GET[s]?>&que=2'" class="form">
<input type="button" value="Borrar" onclick="if(confirm('¿Deseas borrar el script?')) location = '<?=$url_base?>scriptsadmin.php?c=<?=$_GET[c]?>&s=<?=$_GET[s]?>&que=3'" class="form">
</div>
</td>
</tr>
</table>
<?
		}
?>
<script>
function abrir(s) {
	largo = 250 ;
	altura = 100 ;
	izq = (screen.width-largo)/2 ; sup = (screen.height-altura)/2 ;
	open('<?=$url_base?>scriptsvotar.php?s='+s,'','left='+izq+',top='+sup+',width='+largo+',height='+altura) ;
}
</script>
<?
		$con = mysql_query("select nombre from scriptscat where id='$_GET[c]'") ;
		$datos = mysql_fetch_assoc($con) ;
		$categoria = $datos[nombre] ;
		mysql_free_result($con) ;
		$con = mysql_query("select * from scripts where id='$_GET[s]'") ;
		$datos = mysql_fetch_assoc($con) ;
		$texto = file_get_contents("scripts_a/$datos[id].txt") ;
		$texto = codigo($texto) ;
?>
<p class="t1"><a href="scripts">Scripts</a> >> <a href="scripts/c/<?=$_GET[c]?>"><?=$categoria?></a> >> <a href="scripts/c/<?=$_GET[c]?>/s/<?=$_GET[s]?>"><?=$datos[titulo]?></a>
<p align="right">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="49%" valign="top">
<table width="100%" border="0" cellpadding="2" cellspacing="0" style="border: #ffb42a 1px dashed ; background: #ffda94 ; text-align: left">
<tr>
<td width="70%"valign="top">
Enviado por: <b><?=usuario($datos[usuario])?></b><br>
Fecha: <b><?=fecha($datos[fecha])?></b><br>
Actualizado: <b><?=fecha($datos[ultima])?></b><br>
¿Usa base de datos?: <b><?=!$datos[basededatos] ? 'No' : 'Sí'?></b>
</td>
<td width="2%"></td>
<td width="28%" valign="top">
Visitas: <b><?=$datos[visitas]?></b><br>
Votos: <b><?=$datos[votos]?></b><br>
Valoración: <b><?=$datos[valoracion]?></b><br>
<a href="javascript:abrir('<?=$datos[id]?>')">» Valorar «</a>
</td>
</tr>
</table>
</td>
<td width="2%"></td>
<td width="49%" valign="top">
<table width="100%" border="0" cellpadding="2" cellspacing="0" style="border: #ffb42a 1px dashed ; background: #ffda94 ; text-align: left">
<tr>
<td><b>Versión</b></td>
<td><b>Subido/Actualizado</b></td>
<td><b>Descargas</b></td>
</tr>
<?
		$con2 = mysql_query("select * from scriptsdes where id_script='$_GET[s]' order by version desc") ;
		if(!mysql_num_rows($con2)) {
?>
<tr>
<td colspan="3"><center>No disponible para descargar.</center></td>
</tr>
<?
		}
		else {
		while($datos2 = mysql_fetch_assoc($con2)) {
?>
<tr>
<td><a href="scriptsdes.php?s=<?=$datos2[id]?>"><?=$datos2[version]?></a></td>
<td><?=fecha($datos2[fecha])?></td>
<td><?=$datos2[descargas]?></td>
</tr>
<?
		}
		mysql_free_result($con2) ;
		}
?>
</table>
</td>
</tr>
</table>
<p><?=$texto?>
<p>
</div>
</td>
</tr>
</table>
<div style="margin: 3px"></div>
<table width="100%" border="0" cellpadding="3" cellspacing="0" class="tabla_s1">
<tr>
<td class="tabla_s2"><img src="imagenes/u_comentarios.gif" border="0" width="11" height="11" alt="Escribir comentario"></td>
<td width="100%" class="tabla_s3"><b>Escribir comentario</b></td>
</tr>
<tr>
<td colspan="2">
<script type="text/javascript">
maximo = 1024 ;
function caracteres(texto) {
	if(texto.value.length > maximo)
		texto.value = texto.value.substring(0,maximo) ;
	else
		document.formulario.contador.value = maximo - texto.value.length ; 
}
function revisar() {
	if(document.formulario.comentario.value == '') {
		alert('Debes escribir un comentario.') ;
		return false ;
	}
}
</script>
<form name="formulario" method="post" action="scriptscom.php?c=<?=$_GET[c]?>&s=<?=$_GET[s]?>" onsubmit="return revisar()" style="display: inline">
<textarea name="comentario" cols="30" rows="5" onkeyup="caracteres(this)" class="form"></textarea><br>
<input type="text" name="contador" size="4" class="form" readonly> <b>Máximo 1024 caractéres</b><br><br>
<input type="submit" name="enviar" value="Enviar comentario" class="form">
</form>
</td>
</tr>
</table>
<div style="margin: 3px"></div>
<a name="c"></a>
<table width="100%" border="0" cellpadding="3" cellspacing="0" class="tabla_s1">
<tr>
<td class="tabla_s2"><img src="imagenes/u_comentarios.gif" border="0" width="11" height="11" alt="Comentarios"></td>
<td width="100%" class="tabla_s3"><b>Comentarios</b></td>
</tr>
<tr>
<td colspan="2">
<div style="margin: 3px"></div>
<?
		# * Mostrar los comentarios
		$ePaginas = new ePaginas("select * from scriptscom where id_script='$_GET[s]' order by id desc",10) ;
		$con = $ePaginas->consultar() ;
		if(!mysql_num_rows($con)) {
			echo '<center><b>No se encontraron comentarios.</b></center>' ;
		}
		else {
?>
<?=$ePaginas->paginar()?>
<?
			echo '<div style="margin: 3px"></div>' ;
			while($datos = mysql_fetch_assoc($con)) {
?>
<table width="100%" border="0" cellpadding="3" cellspacing="0" class="tabla_s1">
<tr style="background: #ffda94 ; font-size: 7pt">
<td><b><?=usuario($datos[usuario])?></b></td>
<td><div align="right"><b><?=fecha($datos[fecha])?></b></div></td>
</tr>
<tr>
<td colspan="2"><?=str_replace("\n",'<br>',$datos[comentario])?></td>
</tr>
</table>
<div style="margin: 3px"></div>
<?
			}
?>
<?=$ePaginas->paginar()?>
<?
		}
}
?>
</td>
</tr>
</table>