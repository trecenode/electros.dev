<?
require 'config.php' ;
require 'ulogin.php' ;
if($_POST[enviar]) {
	require 'quitar.php' ;
	$comentario = substr(quitar($_POST[comentario],1),0,1024) ;
	mysql_query("insert into scriptscom (id_script,fecha,usuario,comentario) values ('$_GET[s]','$fecha','$_COOKIE[e_id]','$comentario')") ;
	mysql_close($conectar) ;
	header("location: {$url_base}scripts/c/$_GET[c]/s/$_GET[s]/#c") ;
}
?>
