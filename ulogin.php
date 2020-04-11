<?
$con = mysql_query("select count(id) from usuarios where id='{$_COOKIE['e_id']}' and nick='{$_COOKIE['e_nick']}' and contrasena='{$_COOKIE['e_contrasena']}'") ;
if(!mysql_result($con,0,0)) {
	header("location: {$url_base}acceso_denegado.php") ;
	exit ;
}
mysql_free_result($con) ;
?>
