<?
require 'ulogin.php' ;
?>
<table width="100%" border="0" cellpadding="3" cellspacing="0" class="tabla_t1">
<tr>
<td height="20">&nbsp;<img src="imagenes/flecha.gif" border="0" width="11" height="11" alt="Tutoriales" align="top"> <b>Tutoriales</b></td>
</tr>
<tr class="tabla_t2">
<td>
<div style="margin: 10px">
<p class="t1"><b>Editar tutorial</b>
<?
if($_COOKIE[e_id] == 1) {
if($_POST[enviar]) {
require 'quitar.php' ;
$t_titulo = quitar($_POST[t_titulo],1) ;
$t_descripcion = quitar($_POST[t_descripcion],1) ;
$t_tutorial = quitar($_POST[t_tutorial],1) ;
mysql_query("update tutoriales set titulo='$t_titulo',descripcion='$t_descripcion',tutorial='$t_tutorial' where id='$_GET[t]'") ;
echo '<p>El tutorial ha sido editado con éxito.' ;
}
$con = mysql_query("select titulo,descripcion,tutorial from tutoriales where id='$_GET[t]'") ;
$datos = mysql_fetch_assoc($con) ;
?>
<p><a href="tutoriales/t/<?=$_GET[t]?>">» Regresar al tutorial</a>
<p><a href="tutoriales">» Regresar a tutoriales</a>
<form name="formulario" method="post" action="tutorialeseditar/t/<?=$_GET[t]?>">
<b>Título:</b><br>
<input type="text" name="t_titulo" maxlength="40" value="<?=$datos[titulo]?>" class="form"><br>
<b>Descripción:</b><br>
<textarea name="t_descripcion" cols="50" rows="5" class="form"><?=$datos[descripcion]?></textarea><br>
<b>Tutorial:</b><br>
<textarea name="t_tutorial" cols="75" rows="25" class="form"><?=$datos[tutorial]?></textarea><br><br>
<input type="submit" name="enviar" value="Enviar" class="form">
</form>
<?
mysql_free_result($con) ;
}
else {
echo '<p>Sólo el administrador del sitio tiene acceso a esta área.' ;
}
?>
</div>
</td>
</tr>
</table>
