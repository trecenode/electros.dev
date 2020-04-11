<?
ob_start('ob_gzhandler') ;
$id = &$_GET['id'] ;
$id = ereg_replace('<[^>]*>','',$id) ;
$id = ereg_replace('.*//','',$id) ;
if(!$id) header('location: ./noticias') ;
require 'config.php' ;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Electros PHP/MySQL >> Actualización al 90% y avanzando...</title>
<meta name="description" content="Electros PHP/MySQL. Tutoriales y scripts para tu web. Sistemas de usuarios, libros de visitas, tagboards, contadores, foros de discusión y mucho más.">
<meta name="keywords" content="electros, php, mysql, php mysql, script, scripts, tutorial, tutoriales, sistema de usuarios, registro de usuarios, foro, foros, tagboard, libro de visitas, contador, minichat">
<base href="<?=$url_base?>">
<link rel="stylesheet" type="text/css" href="estilo.css">
</head>
<body>
<map name="logo">
<area shape="rect" coords="6,6,381,72" href="noticias" alt="Electros PHP/MySQL · Página principal">
<area shape="rect" coords="125,97,249,117" href="scripts" alt="Scripts · Scripts en PHP con o sin MySQL listos para usar">
<area shape="rect" coords="253,97,377,117" href="tutoriales" alt="Tutoriales · Introducción al PHP, Tutoriales násicos y avanzados">
<area shape="rect" coords="381,97,505,117" href="enlaces" alt="Enlaces · Enlaces a otros sitios">
<area shape="rect" coords="508,97,633,117" href="usuarios" alt="Usuarios · Usuarios registrados en la web">
<area shape="rect" coords="638,97,759,117" href="foro" alt="Foros · Comunidad de foros para preguntar todas tus dudas sobre PHP y MySQL o simplemente pasar el rato">
</map>
<table width="780" border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
<td><img src="imagenes/logo1.gif" border="0" width="780" height="125" alt="Electros PHP/MySQL" usemap="#logo"></td>
</tr>
<tr>
<td><img src="imagenes/logo2.gif" border="0" width="780" height="3" alt="Electros PHP/MySQL · Logo 3"></td>
</tr>
</table>
<table width="780" border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
<td width="31"><img src="imagenes/logo3.gif" border="0" width="31" height="90" alt="Electros PHP/MySQL · Logo 3"></td>
<td width="728"><a href="http://chileservidores.org"><img src="http://www.xtreme-web.net/images/chileservidores.jpg" border="0"></a></td>
<td width="21"><img src="imagenes/logo4.gif" border="0" width="21" height="90" alt="Electros PHP/MySQL · Logo 4"></td>
</tr>
</table>
<table width="780" border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
<td><img src="imagenes/logo2.gif" border="0" width="780" height="3" alt="Electros PHP/MySQL · Logo 2"></td>
</tr>
<tr>
<td><img src="imagenes/logo5.gif" border="0" width="780" height="10" alt="Electros PHP/MySQL · Logo 5"></td>
</tr>
</table>
<!-- *** Menú -->
<?
if(empty($_COOKIE['e_nick'])) {
?>
<table width="780" border="0" cellpadding="5" cellspacing="0" align="center" style="border: #ff6600 1px solid ; background: #ffffff">
<tr>
<td width="20%">Bienvenid@ <b>Anónim@</b></td>
<td width="45%">
<form method="post" action="usuariopro.php?que=entrar" style="display: inline">
<b>Nick:</b>
<input type="text" name="u_nick" size="10" maxlength="20" class="form">
<b>Contraseña:</b>
<input type="password" name="u_contrasena" size="10" maxlength="10" class="form">
<input type="submit" name="enviar" value="Entrar" class="form">
</form>
</td>
<td width="35%"><div align="right"><a href="usuario/que/registrar">Nuevo usuario</a> | <a href="usuario/que/contrasena">Recuperar contraseña</a></div></td>
</tr>
</table>
<?
}
else {
?>
<table width="780" border="0" cellpadding="5" cellspacing="0" align="center" style="border: #ff6600 1px solid ; background: #ffffff">
<tr>
<td>Bienvenid@ <b><?=preg_replace('/<.*>/iU','',$_COOKIE['e_nick'])?></b></td>
<td>
<select onchange="if(value) location = '<?=$url_base?>'+options[selectedIndex].value" class="form">
<option>Opción ...
<option value="noticiasenviar">Enviar noticia
<option value="scriptsenviar">Enviar script
<option value="privados">Mensajes privados
<option value="usuario/que/perfil/u/<?=$_COOKIE['e_id']?>">Editar mi perfil
</select>
</td>
<td><div align="right"><a href="usuariopro.php?que=salir" class="eforo">Salir del foro</a></div></td>
</tr>
</table>
<?
}
?>
<table width="780" border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
<td>
<!-- *** Contenido -->
<?
function usuario($a) {
	$con = mysql_query("select nick from usuarios where id='$a'") ;
	$datos = mysql_fetch_row($con) ;
	return $datos[0] ? $datos[0] : '<i>Anónim@</i>' ;
	mysql_free_result($con) ;
}
define('p',$id.'.php') ;
if(file_exists(p)) require p ;
mysql_close($conectar) ;
?>
</td>
</tr>
</table>
<br><br>
<div style="text-align: center">
<!-- Begin Nedstat Basic code -->
<!-- Title: Electros PHP/MySQL -->
<!-- URL: http://www.electros.net/ -->
<script type="text/javascript" src="http://m1.nedstatbasic.net/basic.js"></script>
<script type="text/javascript">nedstatbasic('ACm2LQOvMjxdps9IH7RwFSYFmlAg', 0)</script>
<noscript>
<a target="_blank" href="http://www.nedstatbasic.net/stats?ACm2LQOvMjxdps9IH7RwFSYFmlAg"><img src="http://m1.nedstatbasic.net/n?id=ACm2LQOvMjxdps9IH7RwFSYFmlAg" border="0" width="18" height="18" alt="Webstats4U - Web site estadísticas gratuito El contador para sitios web particulares"></a><br>
<a target="_blank" href="http://www.nedstatbasic.net/">Contador gratuito</a>
</noscript>
<!-- End Nedstat Basic code -->
<br><br>
Agradezco a LoveMaster, webmaster de <a href="http://www.xtreme-web.net" target="_blank">www.xtreme-web.net</a> por haberme<br>
permitido hospedar Electros PHP/MySQL en su servidor. Muchas gracias :D
<br><br>
</div>
</body>
</html>