<?
ob_start('ob_gzhandler') ;
?>
<html>
<head>
<title>Valoración</title>
<style>
body {
background: #ffce91 ;
font-family: verdana ;
font-size: 8pt ;
color: #000000 ;
text-align: justify ;
}
.form {
font-family: verdana ;
font-size: 8pt ;
color: #000000 ;
border: #484848 1px solid ;
background: #fee1c9 url('imagenes/fondo_f.gif') repeat-x ;
}
</style>
</head>
<body>
<?
if($_POST['puntos']) {
	if(!$_COOKIE["scriptv$_POST[s]"]) {
		include('config.php') ;
		$con = mysql_query("select votos,puntos from scripts where id='$_POST[s]'") ;
		$datos = mysql_fetch_assoc($con) ;
		$votos = $datos['votos'] + 1 ;
		$puntos = $datos['puntos'] + $_POST['puntos'] ;
		$valoracion = round($puntos/$votos,2) ;
		mysql_query("update scripts set votos='$votos',puntos='$puntos',valoracion='$valoracion' where id='$_POST[s]'") ;
		mysql_free_result($con) ;
		mysql_close($conectar) ;
		setcookie("scriptv$_POST[s]",1,time()+64800) ;
		echo "<center><b>Gracias por tu voto</b></center>\n" ;
	}
	else {
		echo "<center><b>Sólo se permite un voto por día</b></center>\n" ;
	}
}
else {
?>
<center>
<p><b>Valora este script</b>
<form method="post" action="scriptsvotar.php">
<input type="hidden" name="s" value="<?=$_GET['s']?>">
<select name="puntos" class="form" onchange="submit()"><option value="0">Valoración ...<option value="5">:: 5 ::<option value="4">:: 4 ::<option value="3">:: 3 ::<option value="2">:: 2 ::<option value="1">:: 1 ::</select>
</form>
</center>
<?
}
?>
</body>
</html>
