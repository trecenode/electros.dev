<?
require 'config.php' ;
require 'ulogin.php' ;
if($_POST[enviar]) {
	require 'quitar.php' ;
	$comentario = substr(quitar($_POST[comentario],1),0,1024) ;
	mysql_query("insert into noticiascom (id_noticia,fecha,usuario,comentario) values ('$_GET[n]','$fecha','$_COOKIE[e_id]','$comentario')") ;
	mysql_close($conectar) ;
	header("location: {$url_base}noticias/n/$_GET[n]/#c") ;
}
?>
