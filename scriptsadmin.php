<?
include 'config.php' ;
include 'ulogin.php' ;
if($_COOKIE[e_id] != 1) exit ;
if($_POST[que] == 'mover') {
	mysql_query("update scripts set id_categoria='$_POST[id_categoria]' where id='$_POST[s]'") ;
	header("location: scripts/c/$_POST[id_categoria]/s/$_POST[s]") ;
	exit ;
}
switch($_GET[que]) {
	case 1 :
		mysql_query("update scripts set validar='1' where id='$_GET[s]'") ;
		break ;
	case 2 :
		mysql_query("update scripts set validar='0' where id='$_GET[s]'") ;
		break ;
	case 3 :
		mysql_query("delete from scripts where id='$_GET[s]'") ;
		mysql_query("delete from scriptscom where id_script='$_GET[s]'") ;
		$con = mysql_query("select archivo from scriptsdes where id_script='$_GET[s]' order by id asc") ;
		while($datos = mysql_fetch_assoc($con)) {
			unlink("archivos/$datos[archivo].zip") ;
		}
		mysql_free_result($con) ;
		mysql_query("delete from scriptsdes where id_script='$_GET[s]'") ;
}
if($_GET[que] != 3) {
	header("location: scripts/c/$_GET[c]/s/$_GET[s]") ;
}
else {
	header("location: scripts/c/$_GET[c]") ;
}
?>
