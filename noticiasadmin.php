<?
require 'ulogin.php' ;
if($_COOKIE[e_id] != 1) {
	header("location: {url_foro}acceso_denegado.php") ;
	exit ;
}
require 'eforo_funciones/epaginas.php' ;
?>
<table width="780" border="0" cellpadding="3" cellspacing="0" class="tabla_t1">
<tr>
<td height="20">&nbsp;<img src="imagenes/flecha.gif" border="0" width="11" height="11" alt="Noticias · Administrar" align="top"> <b>Administrar noticias</b></td>
</tr>
<tr class="tabla_t2">
<td>
<div style="margin: 10px">
<?
if($_POST[ocultar]) {
	foreach($_POST as $a1 => $a2) {
		if(ereg('^noticia',$a1)) {
			mysql_query("update noticias set validar='0' where id='$a2'") ;
		}
	}
	echo '<p><b>Noticias ocultadas con éxito.</b>' ;
}
if($_POST[validar]) {
	foreach($_POST as $a1 => $a2) {
		if(ereg('^noticia',$a1)) {
			mysql_query("update noticias set validar='1' where id='$a2'") ;
		}
	}
	echo '<p><b>Noticias validadas con éxito.</b>' ;
}
if($_POST[borrar]) {
	foreach($_POST as $a1 => $a2) {
		if(ereg('^noticia',$a1)) {
			mysql_query("delete from noticias where id='$a2'") ;
			mysql_query("delete from noticiascom where noticia='$a2'") ;
		}
	}
	echo '<p><b>Noticias borradas con éxito.</b>' ;
}
?>
<p><a href="noticias">» Regresar a noticias</a>
<p>
<?
$ePaginas = new ePaginas("select * from noticias order by id desc",15) ;
$con = $ePaginas->consultar() ;
?>
<?=$ePaginas->paginar()?>
<form method="post" action="noticiasadmin">
<?
while($datos = mysql_fetch_assoc($con)) {
	$oculta = !$datos[validar] ? ' style="color: #ffc9af"' : false ;
?>
<input type="checkbox" name="noticia<?=$datos[id]?>" value="<?=$datos[id]?>"><a href="noticiaseditar/n/<?=$datos[id]?>">Editar</a> | <a href="noticias/n/<?=$datos[id]?>"<?=$oculta?>><?=$datos[titulo]?></a><br>
<?
}
mysql_free_result($con) ;
?>
<p>
<input type="submit" name="ocultar" value="Ocultar" class="form">
<input type="submit" name="validar" value="Validar" class="form">
<input type="submit" name="borrar" value="Borrar" class="form">
</form>
<?=$ePaginas->paginar()?>
</div>
</td>
</tr>
</table>
