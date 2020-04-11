<table width="100%" border="0" cellpadding="3" cellspacing="0" class="tabla_t1">
<tr>
<td height="20">&nbsp;<img src="imagenes/flecha.gif" border="0" width="11" height="11" alt="Tutoriales" align="top"> <b>Tutoriales</b></td>
</tr>
<tr class="tabla_t2">
<td>
<div style="margin: 10px">
<p class="t1">:. Tutoriales
<?
if($_COOKIE[e_id] == 1) echo '<p><a href="tutorialesenviar">Enviar tutorial</a>' ;
if(!ereg('^[0-9]+$',$_GET[t])) {
$con = mysql_query("select id,titulo,descripcion from tutoriales order by titulo asc") ;
while($datos = mysql_fetch_assoc($con)) {
echo "<p><a href=\"tutoriales/t/$datos[id]\">$datos[titulo]</a><br>$datos[descripcion]" ;
}
}
else {
$con = mysql_query("select fecha,titulo,tutorial from tutoriales where id='$_GET[t]'") ;
$datos = mysql_fetch_assoc($con) ;
$f = &$datos[fecha] ;
$meses = array('','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic') ;
$fecha = date('d',$f).' '.$meses[date('n',$f)].' '.date('Y',$f).' '.date('h:i A',$f) ;
require 'codigot.php' ;
if($_COOKIE[e_id] == 1) echo " | <a href=\"tutorialeseditar/t/$_GET[t]\">Editar tutorial</a>" ;
?>
<p><a href="tutoriales">» Regresar</a>
<p class="t1"><?=$datos[titulo]?>
<p>Enviado el: <b><?=$fecha?></b>
<p><?=codigo($datos[tutorial])?>
<?
}
mysql_free_result($con) ;
?>
</div>
</td>
</tr>
</table>
