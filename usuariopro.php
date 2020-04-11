<?
/*
************************************************
*** eForo v3.0
*** Creado por: Electros <electros@electros.net>
*** Sitio web: www.electros.net
*** Licencia: GNU General Public License
************************************************

--- Página: usuariopro.php ---

eForo - Una comunidad para que tus visitantes se comuniquen y se sientan parte de tu web
Copyright © 2003-2005 Daniel Osorio "Electros"

Este programa es software libre, puedes redistribuirlo y/o modificarlo bajo los términos
de la GNU General Public License publicados por la Free Software Foundation; desde la
versión 2 de la licencia, o (si lo deseas) cualquiera más reciente.
*/

ob_start() ;

require('foroconfig.php') ;
require('eforo_funciones/quitar.php') ;
require('eforo_funciones/aviso.php') ;

function contrasena($texto) {
	if(!eregi('^[0-9a-z]+$',$texto)) {
		aviso('Contraseña no válida','<p>La contraseña sólo debe contener caractéres alfanuméricos (0-9 y a-z).<p><a href="'.$_SERVER[HTTP_REFERER].'" class="eforo">» Regresar</a>') ;
		exit ;
	}
	return md5(md5($texto)) ;
}

switch($_GET[que]) {
	case 'entrar' :
		if($_POST[enviar]) {
			$nick = quitar($_POST[u_nick]) ;
			$contrasena = contrasena($_POST[u_contrasena]) ;
			$con = mysql_query("select id,contrasena from $tabla_usuarios where nick='$nick'") ;
			if(mysql_num_rows($con) != 0) {
				$datos = mysql_fetch_assoc($con) ;
				if($datos[contrasena] == $contrasena) {
					setcookie('e_id',$datos[id],time()+1296000) ;
					setcookie('e_nick',$nick,time()+1296000) ;
					setcookie('e_contrasena',$contrasena,time()+1296000) ;
					if($_POST[url_regresar]) {
						header("location: $_POST[url_regresar]") ;
					}
					else {
						header("location: $_SERVER[HTTP_REFERER]") ;
					}
				}
				else {
					aviso('Contraseña incorrecta','La contraseña es incorrecta. Haz click <a href="javascript:history.back()" class="eforo">aquí</a> para regresar.') ;
				}
			}
			else {
				aviso('Usuario no encontrado','Este usuario no existe en la base de datos. Haz click <a href="javascript:history.back()" class="eforo">aquí</a> para regresar.') ;
			}
		}
		else {
			header("location: $_SERVER[HTTP_REFERER]") ;
		}
		mysql_close($conectar) ;
		break ;
	case 'salir' :
		setcookie('e_id') ;
		setcookie('e_nick') ;
		setcookie('e_contrasena') ;
		mysql_query("delete from eforo_enlinea where id_usuario='$usuario[id]'") ;
		header("location: noticias") ;
		break ;
	case 'perfil' :
		if($_POST[enviar]) {
			# * Subir el avatar
			if($_FILES[u_archivo][name] && !$_POST[borrar]) {
				# --> Se revisa que la extensión del archivo sólo sea .gif .jpg y .png
				$extensiones = explode('.',$_FILES[u_archivo][name]) ;
				$num = count($extensiones) - 1 ;
				if($extensiones[$num] != 'gif' && $extensiones[$num] != 'jpg' && $extensiones[$num] != 'png') {
					aviso('Error','<p>Sólo se permiten imágenes .gif .jpg y .png.<p><a href="javascript:history.back()" class="eforo">» Regresar</a>') ;
					exit ;
				}
				# --> Tamaño del archivo
				$tam_actual = round($_FILES[u_archivo][size] / 1024) ;
				if(!$tam_actual || $tam_actual > $conf[avatar_tamano]) {
					aviso('Error',"<p>El archivo debe ser menor de $conf[avatar_tamano] KB.<p><a href=\"javascript:history.back()\" class=\"eforo\">» Regresar</a>") ;
					exit ;
				}
				# --> Tamaño de la imagen medido en pixeles
				move_uploaded_file($_FILES[u_archivo][tmp_name],"eforo_imagenes/avatares/defecto.$extensiones[$num]") ;
				list($largo,$ancho) = getimagesize("eforo_imagenes/avatares/defecto.$extensiones[$num]") ;
				if($largo > $conf[avatar_largo] || $ancho > $conf[avatar_ancho]) {
					unlink("eforo_imagenes/avatares/defecto.$extensiones[$num]") ;
					aviso('Error',"<p>El tamaño de la imagen debe ser menor de $conf[avatar_largo] x $conf[avatar_ancho] pixeles.<p><a href=\"javascript:history.back()\" class=\"eforo\">» Regresar</a>") ;
					exit ;
				}
				# --> Se elimina el avatar anterior
				$con = mysql_query("select avatar from $tabla_usuarios where id='$_POST[u]'") ;
				$datos = mysql_fetch_assoc($con) ;
				if($datos[avatar]) {
					unlink("eforo_imagenes/avatares/$_POST[u].$datos[avatar]") ;
				}
				copy("eforo_imagenes/avatares/defecto.$extensiones[$num]","eforo_imagenes/avatares/$_POST[u].$extensiones[$num]") ;
				unlink("eforo_imagenes/avatares/defecto.$extensiones[$num]") ;
				$avatar = ",avatar='$extensiones[$num]'" ;
			}
			if($_POST[borrar]) {
				$con = mysql_query("select id,avatar from $tabla_usuarios where id='$_POST[u]'") ;
				$datos = mysql_fetch_assoc($con) ;
				unlink("eforo_imagenes/avatares/$_POST[u].$datos[avatar]") ;
				mysql_query("update $tabla_usuarios set avatar='' where id='$_POST[u]'") ;
				mysql_free_result($con) ;
			}
			if($_POST[u_contrasena]) {
				$contrasena = contrasena($_POST[u_contrasena]) ;
				setcookie('ucontrasena',$contrasena,time()+1296000) ;
				$contrasena = ",contrasena='$contrasena'" ;
			}
			$nick = quitar($_POST[u_nick],1) ;
			$con = mysql_query("select count(id) from $tabla_usuarios where nick='$nick' limit 1") ;
			if(mysql_result($con,0,0) && $_COOKIE[e_nick] != $nick) {
				aviso('Error',"<p>El nick <b>$nick</b> ya existe.<p><a href=\"javascript:history.back()\" class=\"eforo\">» Regresar</a>") ;
				exit ;
			}
			else {
				setcookie('unick',$nick,time()+1296000) ;
			}
			mysql_free_result($con) ;
			$email = quitar($_POST[u_email],1) ;
			$pais = quitar($_POST[u_pais]) ;
			$edad = quitar($_POST[u_edad]) ;
			$sexo = quitar($_POST[u_sexo]) ;
			$descripcion = quitar($_POST[u_descripcion]) ;
			$web = quitar($_POST[u_web]) ;
			$firma = quitar($_POST[u_firma]) ;
			$gmt = quitar($_POST[u_gmt]) ;
			mysql_query("update $tabla_usuarios set nick='$nick',email='$email',pais='$pais',edad='$edad',sexo='$sexo',descripcion='$descripcion',web='$web',firma='$firma',gmt='$gmt'$avatar$contrasena where id='$usuario[id]'") ;
		}
		mysql_close($conectar) ;
		aviso('Perfil editado',"<p>Tu perfil ha sido editado.<p><a href=\"./$u[0]usuario$u[1]$u[2]que$u[4]perfil$u[3]u$u[4]$_POST[u]$u[5]\" class=\"eforo\">» Regresar al perfil</a><p><a href=\"./$u[0]foro$u[1]$u[5]\" class=\"eforo\">» Regresar al foro</a>") ;
		break ;
	case 'registrar' :
		if($_POST[enviar]) {
			$nick = quitar($_POST[u_nick]) ;
			$email = quitar($_POST[u_email]) ;
			$con = mysql_query("select id from $tabla_usuarios where nick='$nick' or email='$email'") ;
			if(mysql_num_rows($con) != 0) {
				echo '<p>Este usuario ya existe en la base de datos o ya hay un usuario con este email. Haz click <a href="javascript:history.back()">aquí</a> para regresar.' ;
			}
			else {
				$contrasena = contrasena($_POST[u_contrasena]) ;
				mysql_query("insert into $tabla_usuarios (fecha,nick,contrasena,email,sexo,ip,rango,conectado) values ('$fecha','$nick','$contrasena','$email','$sexo','$_SERVER[REMOTE_ADDR]','1','$fecha')") ;
				$aviso_titulo = 'Bienvenid@ '.$nick ;
				$aviso_mensaje =
				'<p>Ya eres miembro de la web, ahora podrás enviar scripts y enlaces, participar en el foro, así como tener tu propio perfil de usuari@
				y muchas cosas más. Espero que te la pases bien por aquí y que participes mucho.
				<p>Webmaster
				<p><a href="$conf[url_foro]/noticias" class="eforo">» Ir a la página principal</a>
				' ;
				aviso($aviso_titulo,$aviso_mensaje) ;
			}
		}
		break ;
	case 'contrasena' :
		if(!$_COOKIE[urecuperacion]) {
			// * Generador de contraseñas
			$longitud = 8 ;
			$caracteres = "abcdefghijklmnopqrstuvwxyz0123456789" ;
			$contrasena = substr(str_shuffle($caracteres),0,$longitud-1) ;
			$email = quitar($_POST[u_email]) ;
			$con = mysql_query("select id,nick from $tabla_usuarios where email='$email' limit 1") ;
			if(mysql_num_rows($con)) {
				$datos = mysql_fetch_assoc($con) ;
				$mensaje =
				"<style>
				body { font-family: verdana ; font-size: 10pt }
				a { color: #000000 ; font-weight: bold ; text-decoration: none }
				</style>
				<body>
				<p>Estos son tus datos de registro:
				<p>Nick: <b>$datos[nick]</b><br>Contraseña: <b>$contrasena</b>
				<p>Debido a que la contraseña se guarda encriptada no se pudo recuperar, por eso se te ha generado una nueva,
				puedes cambiarla en cualquier momento en tu perfil. Para entrar al foro haz clic en la siguiente dirección:
				<a href=\"$conf[url_foro]/$u[0]foro$u[1]$u[5]\" target=\"_blank\">$conf[url_foro]/$u[0]foro$u[1]$u[5]</a>.
				</body>
				" ;
				mail($email,"$conf[foro_titulo] · Recuperación de contraseña",$mensaje,"from: $conf[admin_email]\ncontent-type: text/html") ;
				$contrasena = md5(md5($contrasena)) ;
				mysql_query("update $tabla_usuarios set contrasena='$contrasena' where id='$datos[id]'") ;
				aviso('Datos enviados',"<p>Los datos han sido enviados al email indicado.<p><a href=\"$u[0]foro$u[1]$u[5]\" class=\"eforo\">» Regresar al foro</a>") ;
			}
			else {
				aviso('Error','<p>Este email no existe en la base de datos.<p><a href="javascript:history.back()" class="eforo">» Regresar</a>') ;
			}
			mysql_free_result($con) ;
			setcookie('urecuperacion','1',time()+900) ;
		}
		else {
			aviso('Error','<p>Sólo puedes solicitar tus datos cada 15 minutos.<p><a href="javascript:history.back()" class="eforo">» Regresar</a>') ;
		}
}
?>
<br><br>
<center>
<span style="font-size: 7pt">
<a href="http://www.electros.net" target="_blank" class="eforo">eForo v3.0</a> © 2003-2005 Bajo licencia GNU General Public License<br>
<?
// * Obtener tiempo de carga del foro (la función tiempo() se encuentra en foroconfig.php)
$tiempo_b = tiempo() ;
$tiempo = round($tiempo_b - $tiempo_a,4) ;
echo 'Tiempo de carga del servidor: <b>'.$tiempo.'</b>' ;
?>
</span>
</center>
<?
ob_end_flush() ;
?>
