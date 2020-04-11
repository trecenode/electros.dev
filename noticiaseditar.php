<?
require 'ulogin.php' ;
if($_COOKIE[e_id] != 1) {
	header("location: {url_foro}acceso_denegado.php") ;
	exit ;
}
?>
<table width="780" border="0" cellpadding="3" cellspacing="0" class="tabla_t1">
<tr>
<td height="20">&nbsp;<img src="imagenes/flecha.gif" border="0" width="11" height="11" alt="Noticias · Administrar" align="top"> <b>Administrar noticias</b></td>
</tr>
<tr class="tabla_t2">
<td>
<div style="margin: 10px">
<?
if($_POST[enviar]) {
	mysql_query("update noticias set titulo='$_POST[titulo]',noticia='$_POST[noticia]',imagen='$_POST[imagen]' where id='$_GET[n]'") ;
	echo '<p>La noticia ha sido actualizada.<p><a href="noticias/n/$_GET[n]">» Regresar a la noticia</a>' ;
}
?>
<p><a href="noticias">» Regresar a noticias</a>
<p><a href="noticiasadmin">» Regresar a administrar noticias</a>
<p>
<?
$con = mysql_query("select * from noticias where id='$_GET[n]'") ;
$datos = mysql_fetch_assoc($con) ;
?>
<form method="post" action="noticiaseditar/n/<?=$_GET[n]?>">
<b>Título:</b><br>
<input type="text" name="titulo" size="30" maxlenght="100" value="<?=$datos[titulo]?>" class="form"><br>
<b>Noticia:</b><br>
<textarea name="noticia" cols="50" rows="10" class="form"><?=$datos[noticia]?></textarea><br>
<select class="form" onchange="if(value) imagen.src = 'imagenes/noticias/'+options[selectedIndex].value"><br>
<option>Selecciona una imagen ...
<?
$directorio = opendir('imagenes/noticias') ;
while($archivo = readdir($directorio)) {
	if($archivo != '.' && $archivo != '..') {
		if($archivo == $datos[imagen]) {
			echo "<option value=\"$archivo\" selected>$archivo\n" ;
			$imagen = $archivo ;
		}
		else {
			echo "<option value=\"$archivo\">$archivo\n" ;
		}
	}
}
closedir($directorio) ;
?>
</select><br><br>
<img id="imagen" src="imagenes/noticias/<?=$imagen?>"><br><br>
<input type="submit" name="enviar" value="Enviar" class="form">
</form>
<?
mysql_free_result($con) ;
?>
</div>
</td>
</tr>
</table>
