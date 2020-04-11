<?
include 'fecha.php' ;
include 'codigo.php' ;
require 'eforo_funciones/epaginas.php' ;
# * Mostrar las noticias
if(empty($_GET['n']) || !ereg('^[0-9]+$',$_GET['n'])) {
?>
<script type="text/javascript">
maximo = 1024 ;
function regresar(a) {
	a.direction='down' ; a.scrollAmount=8 ;
}
function adelantar(a) {
	a.direction='up' ; a.scrollAmount=8 ;
}
function continuar(a) {
	a.direction='up' ; a.scrollAmount=2 ;
}
</script>
<table width="780" border="0" cellpadding="0" cellspacing="3" align="center" class="tabla_f">
<tr>
<td width="200" rowspan="2" valign="top">
<!-- Ultimos mensajes del foro -->
<table width="100%" border="0" cellpadding="3" cellspacing="0" class="tabla_s1">
<tr>
<td class="tabla_s2"><img src="imagenes/u_mensajes.gif" border="0" width="10" height="10" alt="Ultimos mensajes del foro"></td>
<td width="100%" class="tabla_s3"><b>Ultimos mensajes del foro</b></td>
</tr>
<tr>
<td colspan="2">
<marquee width="100%" height="250" direction="up" scrollamount="2" id="m" onmouseover="stop()" onmouseout="start()">
<table width="100%" border="0" cellpadding="3" cellspacing="1" style="font-size: 7pt">
<?
$con = mysql_query("select * from eforo_mensajes order by id desc limit 10") ;
for($num = 1 ; $datos = mysql_fetch_assoc($con) ; $num++) {
	$datos['mensaje'] = preg_replace('/\[.*\]/iU','',$datos['mensaje']) ;
	if(strlen($datos['mensaje']) > 255) {
		$datos['mensaje'] = substr($datos['mensaje'],0,255).'...' ;
	}
	$palabras = explode(' ',$datos['mensaje']) ;
	$total = count($palabras) ;
	for($p = 0 ; $p < $total ; $p++) {
		if(strlen($palabras[$p]) > 25) {
			$palabras[$p] = chunk_split($palabras[$p],25,' ') ;
		}
	}
	$datos['mensaje'] = implode(' ',$palabras) ;
	$con2 = mysql_query("select nick from usuarios where id='{$datos['id_usuario']}'") ;
	if(mysql_num_rows($con2)) {
		$datos2 = mysql_fetch_assoc($con2) ;
		$autor_mensaje = $datos2['nick'] ;
		mysql_free_result($con2) ;
	}
	else {
		$autor_mensaje = '<i>Invitad@</i>' ;
	}
?>
<tr>
<td class="tabla_s1"><a href="foromensajes/foro/<?=$datos['id_foro']?>/tema/<?=$datos['id_tema']?>/#<?=$datos['id']?>"><img src="imagenes/usuario.gif" width="6" height="8" border="0" alt="Usuario"><span style="margin-right: 3px"></span><?=$autor_mensaje?></a> <?=$datos['mensaje']?><br><div align="right"><a href="foromensajes/foro/<?=$datos['id_foro']?>/tema/<?=$datos['id_tema']?>/#<?=$datos['id']?>">Ir al mensaje</a></div></td>
</tr>
<?
}
?>
</table>
</marquee><br><br>
<div align="center"><a name="#a"></a>
<a href="javascript:regresar(m)" onmouseover="regresar(m)" onmouseout="continuar(m)"><img src="imagenes/flecha_izq.gif" width="15" height="15" border="0" alt="Regresar"></a>
<a href="javascript:adelantar(m)" onmouseover="adelantar(m)" onmouseout="continuar(m)"><img src="imagenes/flecha_der.gif" width="15" height="15" border="0" alt="Adelantar"></a>
</div>
</td>
</tr>
</table>
</td>
<td width="290" valign="top">
<!-- Ultimos 10 scripts enviados -->
<table width="100%" border="0" cellpadding="3" cellspacing="0" class="tabla_s1">
<tr>
<td class="tabla_s2"><img src="imagenes/u_scripts.gif" border="0" width="11" height="13" alt="Ultimos 10 scripts enviados"></td>
<td width="100%" class="tabla_s3"><b>Ultimos 10 scripts enviados</b></td>
</tr>
<tr>
<td colspan="2">
<?
# --> Máximo de caractéres por título
$max_titulo = 30 ;
$con = mysql_query("select id,id_categoria,titulo from scripts where validar='1' order by id desc limit 10") ;
while($datos = mysql_fetch_assoc($con)) {
	if(strlen($datos['titulo']) > $max_titulo) {
		$datos['titulo'] = substr($datos['titulo'],0,$max_titulo).'...' ;
	}
?>
<div style="margin: 3px"></div>
<a href="scripts/c/<?=$datos['id_categoria']?>/s/<?=$datos['id']?>"><img src="imagenes/script.gif" border="0" width="11" height="14" alt="Script · <?=$datos['titulo']?>" align="top"> <?=$datos['titulo']?></a>
<?
}
mysql_free_result($con) ;
?>
</td>
</tr>
</table>
</td>
<td width="290" valign="top">
<!-- Top 10 scripts más descargados -->
<table width="100%" border="0" cellpadding="3" cellspacing="0" class="tabla_s1">
<tr>
<td class="tabla_s2"><img src="imagenes/u_top.gif" border="0" width="12" height="12" alt="Top 10 scripts más descargados"></td>
<td width="100%" class="tabla_s3"><b>Top 10 scripts más descargados</b></td>
</tr>
<tr>
<td colspan="2">
<?
# --> Máximo de caractéres por título
$max_titulo = 30 ;
$con = mysql_query("select scripts.id,id_categoria,titulo from scripts join scriptsdes on scripts.id=scriptsdes.id_script where validar='1' order by scriptsdes.descargas desc limit 10") ;
while($datos = mysql_fetch_assoc($con)) {
	if(strlen($datos['titulo']) > $max_titulo) {
		$datos['titulo'] = substr($datos['titulo'],0,$max_titulo).'...' ;
	}

?>
<div style="margin: 3px"></div>
<a href="scripts/c/<?=$datos['id_categoria']?>/s/<?=$datos['id']?>"><img src="imagenes/script.gif" border="0" width="11" height="14" alt="Script · <?=$datos['titulo']?>" align="top"> <?=$datos['titulo']?></a>
<?
}
mysql_free_result($con) ;
?>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td colspan="2" valign="top">
<!-- Noticias -->
<?
$con = mysql_query("select * from noticias where validar='1' order by id desc limit 10") ;
while($datos = mysql_fetch_assoc($con)) {
$datos['noticia'] = preg_replace('/\[.*\]/iU','',$datos['noticia']) ;
if(strlen($datos['noticia']) > 512) {
	$datos['noticia'] = substr($datos['noticia'],0,512).'...' ;
}
$con2 = mysql_query("select count(id) from noticiascom where id_noticia='{$datos['id']}'") ;
$total_comentarios = mysql_result($con2,0,0) ;
mysql_free_result($con2) ;
?>
<table width="100%" border="0" cellpadding="2" cellspacing="0" class="tabla_t1">
<tr>
<td height="20">&nbsp;<img src="imagenes/flecha.gif" border="0" width="11" height="11" alt="Noticia · <?=$datos['titulo']?>" align="top"> <b><?=$datos['titulo']?></b></td>
</tr>
<tr class="tabla_t2">
<td>
<table width="100%" border="0" cellpadding="5" cellspacing="0">
<tr>
<td valign="top"><img src="imagenes/noticias/<?=$datos['imagen']?>" alt="Noticia · <?=$datos['titulo']?>" class="e"></td>
<td valign="top">
<?=$datos['noticia']?>
<p><a href="noticias/n/<?=$datos['id']?>">Leer más</a> | Comentarios: <b><?=$total_comentarios?></b> | Por: <b><?=usuario($datos['usuario'])?></b>
</td>
</tr>
</table>
</td>
</tr>
</table>
<div style="margin: 3px"></div>
<?
}
mysql_free_result($con) ;
}
else {
	# * Mostrar la noticia seleccionada
	$con = mysql_query("select count(id) from noticias where id='{$_GET['n']}'") ;
	if(!mysql_result($con,0,0)) exit('La noticia no existe.') ;
	mysql_free_result($con) ;
	$con = mysql_query("select * from noticias where id='{$_GET['n']}'") ;
	$datos = mysql_fetch_assoc($con) ;
?>
<table width="780" border="0" cellpadding="3" cellspacing="0" align="center" class="tabla_t1">
<tr>
<td height="20">&nbsp;<img src="imagenes/flecha.gif" border="0" width="11" height="11" alt="Noticia · <?=$datos['titulo']?>" align="top"> <b><?=$datos['titulo']?></b></td>
</tr>
<tr class="tabla_t2">
<td>
<table width="100%" border="0" cellpadding="5" cellspacing="0">
<tr>
<td valign="top"><img src="imagenes/noticias/<?=$datos['imagen']?>" align="left" class="e"></td>
<td valign="top">
<a href="./">» Regresar a noticias</a>
<p>Por: <b><?=usuario($datos['usuario'])?></b>
<p><?=codigo($datos['noticia'])?>
<p><?=codigo($datos['noticiaext'])?>
<p>
</td>
</tr>
</table>
</td>
</tr>
</table>
<div style="margin: 3px"></div>
<table width="780" border="0" cellpadding="3" cellspacing="0" class="tabla_s1">
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
<form name="formulario" method="post" action="noticiascom.php?n=<?=$_GET['n']?>" onsubmit="return revisar()" style="display: inline">
<textarea name="comentario" cols="30" rows="5" onkeyup="caracteres(this)" class="form"></textarea><br>
<input type="text" name="contador" size="4" class="form" readonly> <b>Máximo 1024 caractéres</b><br><br>
<input type="submit" name="enviar" value="Enviar comentario" class="form">
</form>
</td>
</tr>
</table>
<div style="margin: 3px"></div>
<a name="c"></a>
<table width="780" border="0" cellpadding="3" cellspacing="0" class="tabla_s1">
<tr>
<td class="tabla_s2"><img src="imagenes/u_comentarios.gif" border="0" width="11" height="11" alt="Comentarios"></td>
<td width="100%" class="tabla_s3"><b>Comentarios</b></td>
</tr>
<tr>
<td colspan="2">
<?
	mysql_free_result($con) ;
	# * Mostrar los comentarios
	$ePaginas = new ePaginas("select * from noticiascom where id_noticia='{$_GET['n']}' order by id desc",10) ;
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
<tr>
<td><b><?=usuario($datos['usuario'])?></b></td>
<td><div align="right"><b><?=fecha($datos['fecha'])?></b></div></td>
</tr>
<tr>
<td colspan="2"><?=str_replace("\n",'<br>',$datos['comentario'])?></td>
</tr>
</table>
<div style="margin: 3px"></div>
<?
		}
		mysql_free_result($con) ;
?>
<?=$ePaginas->paginar()?>
<?
	}
}
if(!empty($_COOKIE['e_id']) && $_COOKIE['e_id'] == 1) {
	require 'ulogin.php' ;
?>
<p align="center"><a href="noticiasadmin">Administrar noticias</a> | <a href="sitemap.php">Crear Google SiteMap</a>
<p>
<?
}
?>
</td>
</tr>
</table>