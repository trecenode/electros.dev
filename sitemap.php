<?
include('config.php') ;
include('ulogin.php') ;
if($_COOKIE[e_id] == 1) {
# * Generador de Google Sitemap para Electros PHP/MySQL
# 11 Ago 2005 9:47 pm

# --> Variable que almacenará el sitemap generado hasta su compresión mediante gzip
$sitemap =
'<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">
	<url>
		<loc>http://electros.net/noticias</loc>
		<changefreq>weekly</changefreq>
		<priority>0.7</priority>
	</url>
	<url>
		<loc>http://electros.net/scripts</loc>
		<changefreq>weekly</changefreq>
		<priority>0.9</priority>
	</url>
	<url>
		<loc>http://electros.net/usuarios</loc>
		<changefreq>weekly</changefreq>
		<priority>0.5</priority>
	</url>
	<url>
		<loc>http://electros.net/foro</loc>
		<changefreq>weekly</changefreq>
		<priority>0.8</priority>
	</url>
' ;

# * Scripts > Categorías
$con = mysql_query("select id from scriptscat order by id asc") ;
while($datos = mysql_fetch_assoc($con)) {
$sitemap .=
"	<url>
		<loc>http://electros.net/scripts/c/$datos[id]</loc>
		<changefreq>weekly</changefreq>
		<priority>0.9</priority>
	</url>
" ;
}
mysql_free_result($con) ;

# * Scripts > Categorías > Scripts Individuales
$con = mysql_query("select id,id_categoria from scripts order by id desc") ;
while($datos = mysql_fetch_assoc($con)) {
$sitemap .=
"	<url>
		<loc>http://electros.net/scripts/c/$datos[id_categoria]/s/$datos[id]</loc>
		<changefreq>weekly</changefreq>
		<priority>0.9</priority>
	</url>
" ;
}
mysql_free_result($con) ;

# * Foros > Subforos > Temas
$con = mysql_query("select id from eforo_foros order by id desc") ;
while($datos = mysql_fetch_assoc($con)) {
$sitemap .=
"	<url>
		<loc>http://electros.net/forotemas/foro/$datos[id]</loc>
		<changefreq>weekly</changefreq>
		<priority>0.8</priority>
	</url>
" ;
}
mysql_free_result($con) ;

# * Foros > Subforos > Temas > Mensajes
$con = mysql_query("select id_foro,id_tema from eforo_mensajes where id=id_tema order by id desc") ;
while($datos = mysql_fetch_assoc($con)) {
$sitemap .=
"	<url>
		<loc>http://electros.net/foromensajes/foro/$datos[id_foro]/tema/$datos[id_tema]</loc>
		<changefreq>weekly</changefreq>
		<priority>0.8</priority>
	</url>
" ;
}
mysql_free_result($con) ;

# * Noticias
$con = mysql_query("select id from noticias order by id desc") ;
while($datos = mysql_fetch_assoc($con)) {
$sitemap .=
"	<url>
		<loc>http://electros.net/noticias/n/$datos[id]</loc>
		<changefreq>weekly</changefreq>
		<priority>0.7</priority>
	</url>
" ;
}
mysql_free_result($con) ;

$sitemap .=
'</urlset>
' ;
$sitemap = gzencode($sitemap,9) ;
$archivo = fopen('sitemap.gz',w) ;
fwrite($archivo,$sitemap) ;
fclose($archivo) ;
echo 'El archivo <a href="sitemap.gz">sitemap.gz</a> ha sido generado.' ;
}
?>
