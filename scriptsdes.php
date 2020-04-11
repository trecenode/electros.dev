<?
// Página no enlazada
include('config.php') ;
$con = mysql_query("select archivo from scriptsdes where id='$_GET[s]'") ;
if(mysql_num_rows($con)) {
	$datos = mysql_fetch_assoc($con) ;
	mysql_query("update scriptsdes set descargas=descargas+1 where id='$_GET[s]'") ;
	header("location: archivos/$datos[archivo].zip") ;
}
else {
?>
<style>
body { font-family: verdana ; font-size: 10pt ; margin: 100 ; text-align: center }
a { text-decoration: underline ; }
</style>
<p>No existe la descarga.
<p><a href="javascript:history.back()">Regresar</a>
<?
}
mysql_close($conectar) ;
?>
