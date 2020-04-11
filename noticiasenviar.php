<?
require 'ulogin.php' ;
?>
<table width="780" border="0" cellpadding="3" cellspacing="0" class="tabla_t1">
<tr>
<td height="20">&nbsp;<img src="imagenes/flecha.gif" border="0" width="11" height="11" alt="Noticias · Enviar" align="top"> <b>Enviar noticia</b></td>
</tr>
<tr class="tabla_t2">
<td>
<div style="margin: 10px">
<?
$sel_imagen = '1.jpg' ;
if($_POST[enviar]) {
	require 'quitar.php' ;
	$n_titulo = quitar($_POST[n_titulo],1) ;
	$n_noticia = quitar($_POST[n_noticia],1) ;
	$n_imagen = quitar($_POST[n_imagen],1) ;
	if(!$n_imagen) $n_imagen = $sel_imagen ;
	$validar = $_COOKIE[e_id] == 1 ? '1' : '0' ;
	mysql_query("insert into noticias (fecha,usuario,titulo,noticia,imagen,validar) values ('$fecha','$_COOKIE[e_id]','$n_titulo','$n_noticia','$n_imagen','$validar')") ;
	echo 'La noticia ha sido enviada con éxito. Haz click <a href="noticias">aquí</a> para regresar a la página principal.' ;
}
?>
<p><span style="color: #aa0000"><b>Lee con atención las condiciones</b></span>
<p>· Toda noticia deberá estar relacionada con alguna novedad sobre informática, programación y computación en general.<br>
· Si deseas escribir algún comentario o sugerencia sobre la web hazlo en el foro.<br>
· No se permite promocionar webs ni cosas por el estilo.<br>
<p>Puedes usar el siguiente código especial para tu noticia:
<p>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td><b>Negrita</b></td>
<td><b>[b]</b>texto<b>[/b]</b></td>
</tr>
<tr>
<td><b>Imagen</b></td>
<td><b>[img]</b>http://www.pagina.com/imagenes/imagen.jpg<b>[/img]</b></td>
</tr>
<tr>
<td><b>Color</b></td>
<td><b>[color=red]</b>texto<b>[/color]</b></td>
</tr>
<tr>
<td><b>URL</b></td>
<td><b>[url]</b>http://www.scalianza.com<b>[/url]</b></td>
</tr>
</table>
<p style="font-size: 7pt">En el color puedes poner un color hexadecimal como #ff0000
<p>
<form method="post" action="noticiasenviar">
<b>Título:</b><br>
<input type="text" name="n_titulo" size="30" maxlength="100" class="form"><br>
<b>Noticia:</b><br>
<textarea name="n_noticia" cols="50" rows="15" class="form"></textarea><br>
<select name="n_imagen" class="form" onchange="if(value) imagen.src = 'imagenes/noticias/'+options[selectedIndex].value"><br>
<option value="">Selecciona una imagen ...
<?
$directorio = opendir('imagenes/noticias') ;
while($archivo = readdir($directorio)) {
	if($archivo != "." && $archivo != "..") {
		echo $archivo == $sel_imagen ? "<option value=\"$archivo\" selected>$archivo\n" : "<option value=\"$archivo\">$archivo\n" ;
	}
}
closedir($directorio) ;
?>
</select><br><br>
<img id="imagen" src="imagenes/noticias/1.jpg"><br><br>
<input type="submit" name="enviar" value="Enviar Noticia" class="form">
</form>
</div>
</td>
</tr>
</table>
