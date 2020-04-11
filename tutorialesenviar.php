<?
#if(!empty($_GET)) extract($_GET);
#if(!empty($_POST)) extract($_POST);
require 'ulogin.php' ;
?>
<table width="100%" border="0" cellpadding="3" cellspacing="0" class="tabla_t1">
<tr>
<td height="20">&nbsp;<img src="imagenes/flecha.gif" border="0" width="11" height="11" alt="Tutoriales" align="top"> <b>Tutoriales</b></td>
</tr>
<tr class="tabla_t2">
<td>
<div style="margin: 10px">
<p class="t1"><b>Enviar tutorial</b>
<?
if($_COOKIE['e_id'] == 1) {
if($_POST[enviar]) {
require 'quitar.php' ;
$t_titulo = quitar($_POST[t_titulo]) ;
$t_descripcion = quitar($_POST[t_descripcion]) ;
$t_tutorial = quitar($_POST[t_tutorial]) ;
mysql_query("insert into tutoriales (fecha,usuario,titulo,descripcion,tutorial) values ('$fecha','$_COOKIE[e_nick]','$t_titulo','$t_descripcion','$t_tutorial')") ;
header("location: {$url_base}tutoriales") ;
exit ;
}
else {
?>
<form name="formulario" method="post" action="tutorialesenviar">
<b>Título:</b><br>
<input type="text" name="t_titulo" maxlength="40" class="form"><br>
<b>Descripción:</b><br>
<textarea name="t_descripcion" cols="50" rows="5" class="form"></textarea><br>
<b>Tutorial:</b><br>
<textarea name="t_tutorial" cols="75" rows="25" class="form"></textarea><br><br>
<input type="submit" name="enviar" value="Enviar" class="form">
</form>
<?
}
}
else {
echo '<p>Sólo el administrador del sitio tiene acceso a esta área.' ;
}
?>
</div>
</td>
</tr>
</table>
