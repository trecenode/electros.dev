<?
/*
************************************************
*** eForo v3.0
*** Creado por: Electros <electros@electros.net>
*** Sitio web: www.electros.net
*** Licencia: GNU General Public License
************************************************

--- Página: foroescribirpro.php ---

eForo - Una comunidad para que tus visitantes se comuniquen y se sientan parte de tu web
Copyright © 2003-2005 Daniel Osorio "Electros"

Este programa es software libre, puedes redistribuirlo y/o modificarlo bajo los términos
de la GNU General Public License publicados por la Free Software Foundation; desde la
versión 2 de la licencia, o (si lo deseas) cualquiera más reciente.
*/

require('foroconfig.php') ;

switch($_POST[que]) {
	case 1 :
		$permiso = 'p_nuevo' ;
		break ;
	case 2 :
		$permiso = 'p_responder' ;
		break ;
	case 3 :
		$permiso = 'p_editar' ;
}

require('eforo_funciones/aviso.php') ;
require('eforo_funciones/sesion.php') ;

# * Comprobar permiso de usuario
if(!$es_moderador) {
	permiso($permiso) ;
}

# * El mensaje se guardará dependiendo de lo que se haya elegido (escribir, responder o editar el mensaje)
if($_POST[enviar]) {
	# --> Función para adjuntar archivos a los mensajes
	if($_FILES[m_archivo][name]) {
		# --> Se comprueba el tamaño del archivo adjunto
		$tamano_max = @ini_get('upload_max_filesize') ? str_replace('M','',ini_get('upload_max_filesize')) * 1024 : 2048 ;
		if($tamano_max < $conf[adjunto_tamano]) {
			$conf[adjunto_tamano] = $tamano_max ;
		}
		if(!$_FILES[m_archivo][size] || $_FILES[m_archivo][size] > ($conf[adjunto_tamano] * 1024)) {
			aviso('Error al subir el archivo','<p>El archivo debe ser menor de <b>'.$conf[adjunto_tamano].' KB</b>.<p><a href="javascript:history.back()" class="eforo">» Regresar</a>') ;
			exit ;
		}
		# --> Se comprueba si la extensión está permitida
		preg_match('/(.+)\.(\w+)/i',$_FILES[m_archivo][name],$parte) ;
		$nombre_archivo = $parte[1] ;
		$extension_archivo = strtolower($parte[2]) ;
		if(!in_array($extension_archivo,$conf[adjunto_ext])) {
			aviso('Error al subir el archivo','<p>La extensión <b>'.$extension_archivo.'</b> no está permitida.<p><a href="javascript:history.back()" class="eforo">» Regresar</a>') ;
			exit ;
		}
		# --> Se comprueba el número de caractéres en el nombre de archivo
		if(strlen($nombre_archivo) > $conf[adjunto_nombre]) {
			aviso('Error al subir el archivo','<p>El nombre de archivo debe ser menor de <b>'.$conf[adjunto_nombre].'</b> caractéres.<p><a href="javascript:history.back()" class="eforo">» Regresar</a>') ;
			exit ;
		}
		# --> Se guarda el nombre real del archivo en la base de datos
		mysql_query("insert into eforo_adjuntos (archivo) values ('{$_FILES[m_archivo][name]}')") ;
		# --> El archivo se guardará con el número del último registro en la base de datos
		$id_adjunto = mysql_insert_id() ;
		move_uploaded_file($_FILES[m_archivo][tmp_name],'eforo_adjuntos/'.$id_adjunto.'.dat') ;
	}
	require('eforo_funciones/quitar.php') ;
	if(!$_POST[m_caretos]) {
		$_POST[m_caretos] = 0 ;
	}
	if(!$_POST[m_codigo]) {
		$_POST[m_codigo] = 0 ;
	}
	if(!$_POST[m_url]) {
		$_POST[m_url] = 0 ;
	}
	if(!$_POST[m_firma]) {
		$_POST[m_firma] = 0 ;
	}
	if(!$_POST[m_notificacion]) {
		$_POST[m_notificacion] = 0 ;
	}
	else {
		if($_GET[tema] != $_GET[mensaje]) {
			$_POST[m_notificacion] = 0 ;
		}
	}
	if(!$_POST[m_importante]) {
		$_POST[m_importante] = 0 ;
	}
	else {
		$con = mysql_query("select p_importante from eforo_foros where id='$_GET[foro]'") ;
		$datos = mysql_fetch_assoc($con) ;
		if($usuario[rango] < $datos[p_importante] && !$es_moderador) {
			$_POST[m_importante] = 0 ;
		}
		mysql_free_result($con) ;
	}
	switch($_POST[que]) {
		# --> Escribir un nuevo tema
		case 1 :
		$_POST[m_tema] = quitar($_POST[m_tema]) ;
		$_POST[m_mensaje] = quitar($_POST[m_mensaje]) ;
		mysql_query("insert into eforo_mensajes (id_foro,fecha,id_usuario,tema,mensaje,o_caretos,o_codigo,o_url,o_firma,o_importante,o_notificacion,fecha_ultimo) values ('$_GET[foro]','$fecha','$usuario[id]','$_POST[m_tema]','$_POST[m_mensaje]','$_POST[m_caretos]','$_POST[m_codigo]','$_POST[m_url]','$_POST[m_firma]','$_POST[m_importante]','$_POST[m_notificacion]','$fecha')") ;
		$id_ultimo = mysql_insert_id() ;
		mysql_query("update eforo_mensajes set id_tema='$id_ultimo' where id='$id_ultimo'") ;
		mysql_query("update eforo_foros set num_temas=num_temas+1,num_mensajes=num_mensajes+1 where id='$_GET[foro]'") ;
		if($_COOKIE[e_nick]) {
			mysql_query("update $tabla_usuarios set mensajes=mensajes+1 where id='$usuario[id]'") ;
		}
		aviso('Confirmación',"<p>Tu mensaje ha sido publicado.<p><a href=\"$u[0]foromensajes$u[1]$u[2]foro$u[4]$_GET[foro]$u[3]tema$u[4]$id_ultimo$u[5]\" class=\"eforo\">» Ir al mensaje</a>\n<p><a href=\"$u[0]forotemas$u[1]$u[2]foro$u[4]$_GET[foro]$u[5]\" class=\"eforo\">» Regresar al foro</a>") ;
		break ;
		# --> Responder al tema
		case 2 :
		$_POST[m_mensaje] = quitar($_POST[m_mensaje]) ;
		mysql_query("insert into eforo_mensajes (id_foro,id_tema,fecha,id_usuario,mensaje,o_caretos,o_codigo,o_url,o_firma,o_importante) values ('$_GET[foro]','$_GET[tema]','$fecha','$usuario[id]','$_POST[m_mensaje]','$_POST[m_caretos]','$_POST[m_codigo]','$_POST[m_url]','$_POST[m_firma]','$_POST[m_importante]')") ;
		$id_ultimo = mysql_insert_id() ;
		mysql_query("update eforo_mensajes set num_respuestas=num_respuestas+1,fecha_ultimo='$fecha' where id='$_GET[tema]'") ;
		mysql_query("update eforo_foros set num_mensajes=num_mensajes+1 where id='$_GET[foro]'") ;
		if($_COOKIE[e_nick]) {
			mysql_query("update $tabla_usuarios set mensajes=mensajes+1 where id='$usuario[id]'") ;
		}
		aviso('Confirmación',"<p>Tu mensaje ha sido publicado.<p><a href=\"$u[0]foromensajes$u[1]$u[2]foro$u[4]$_GET[foro]$u[3]tema$u[4]$_GET[tema]$u[3]p$u[4]$_GET[p]$u[5]#$id_ultimo\" class=\"eforo\">» Ir al mensaje</a>\n<p><a href=\"$u[0]forotemas$u[1]$u[2]foro$u[4]$_GET[foro]$u[5]\" class=\"eforo\">» Regresar al foro</a>") ;
		break ;
		# Editar el mensaje
		case 3 :
		if($es_tema) {
			$_POST[m_tema] = quitar($_POST[m_tema]) ;
		}
		$_POST[m_mensaje] = quitar($_POST[m_mensaje]) ;
		$id_ultimo = $_GET[mensaje] ;
		mysql_query("update eforo_mensajes set tema='$_POST[m_tema]',mensaje='$_POST[m_mensaje]',o_caretos='$_POST[m_caretos]',o_codigo='$_POST[m_codigo]',o_url='$_POST[m_url]',o_firma='$_POST[m_firma]',o_importante='$_POST[m_importante]',o_notificacion='$_POST[m_notificacion]',fecha_editado='$fecha' where id='$_GET[mensaje]'") ;
		aviso('Confirmación',"Tu mensaje ha sido editado.<p><a href=\"$u[0]foromensajes$u[1]$u[2]foro$u[4]$_GET[foro]$u[3]tema$u[4]$_GET[tema]$u[3]p$u[4]$_GET[p]$u[5]#$_GET[mensaje]\" class=\"eforo\">» Ir al mensaje</a><p><a href=\"$u[0]forotemas$u[1]$u[2]foro$u[4]$_GET[foro]\" class=\"eforo\">» Regresar al foro</a>") ;
	}
	if($_POST[m_notificacion]) {
		mysql_query("update eforo_mensajes set o_notificacion_email='1' where id='$id_ultimo'") ;
	}
	if($_FILES[m_archivo]) {
		mysql_query("update eforo_adjuntos set id_mensaje='$id_ultimo' where id='$id_adjunto'") ;
	}
	# * Notificación por email
	if($conf[notificacion_email] && $_GET[foro] && $_GET[tema]) {
		$con = mysql_query("select id_usuario,tema,o_notificacion_email from eforo_mensajes where id='$_GET[tema]'") ;
		$datos = mysql_fetch_assoc($con) ;
		if($datos[o_notificacion_email] && $datos[id_usuario] != $usuario[id]) {
			$con2 = mysql_query("select nick,email from $tabla_usuarios where id='$datos[id_usuario]'") ;
			$datos2 = mysql_fetch_assoc($con2) ;
			$mensaje =
			"<style>
			body { font-family: verdana ; font-size: 10pt }
			a { color: #000000 ; font-weight: bold ; text-decoration: none }
			</style>
			<body>
			<p>Saludos <b>$datos2[nick]</b>
			<p>Han respondido a tu mensaje <b>$datos[tema]</b>
			<p>Puedes visitarlo en la siguiente dirección:
			<p><a href=\"$conf[url_foro]/$u[0]foromensajes$u[1]$u[2]foro$u[4]$_GET[foro]$u[3]tema$u[4]$_GET[tema]$u[5]#$id_ultimo\" target=\"_blank\">$conf[url_foro]/$u[0]foromensajes$u[1]$u[2]foro$u[4]$_GET[foro]$u[3]tema$u[4]$_GET[tema]$u[5]#$id_ultimo</a>
			<p>No recibirás más notificaciones hasta que visites tu mensaje. Para desactivar esta opción edita
			tu mensaje y desactiva la casilla <b>Notificar por email cuando haya respuestas</b>.
			</body>
			" ;
			mail($datos2[email],"Saludos $datos2[nick] han respondido a tu mensaje",$mensaje,"from: $conf[admin_email]\ncontent-type: text/html") ;
			mysql_free_result($con2) ;
			# Se desactivan la notificaciones hasta que el usuario revise su mensaje
			mysql_query("update eforo_mensajes set o_notificacion_email='0' where id='$_GET[tema]'") ;
		}
		mysql_free_result($con) ;
		
	}
}
?>
<br><br>
<center>
<span style="font-size: 7pt">
<a href="http://www.electros.net" target="_blank" class="eforo">eForo v3.0</a> © 2003-2005 Bajo licencia GNU General Public License<br>
<?
# * Obtener tiempo de carga del foro (la función tiempo() se encuentra en foroconfig.php)
$tiempo_b = tiempo() ;
$tiempo = round($tiempo_b - $tiempo_a,4) ;
echo 'Tiempo de carga del servidor: <b>'.$tiempo.'</b>' ;
?>
</span>
</center>
