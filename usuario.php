<?
/*
************************************************
*** eForo v3.0
*** Creado por: Electros <electros@electros.net>
*** Sitio web: www.electros.net
*** Licencia: GNU General Public License
************************************************

--- Página: usuario.php ---

eForo - Una comunidad para que tus visitantes se comuniquen y se sientan parte de tu web
Copyright © 2003-2005 Daniel Osorio "Electros"

Este programa es software libre, puedes redistribuirlo y/o modificarlo bajo los términos
de la GNU General Public License publicados por la Free Software Foundation; desde la
versión 2 de la licencia, o (si lo deseas) cualquiera más reciente.
*/

require('foroconfig.php') ;
?>
<table width="100%" border="0" cellpadding="5" cellspacing="0" align="center" class="eforo_tabla_principal">
<tr>
<td class="eforo_tabla_titulo"><div align="center" class="eforo_titulo_1">Usuario</div></td>
</tr>
<tr>
<td class="eforo_tabla_defecto">
<?
require('eforo_funciones/quitar.php') ;
// * Esta página se comportará dependiendo de lo que se haya elegido (registrar, entrar, editar y salir)
switch($_GET[que]) {
	// --> Iniciar sesión como usuario registrado
	case 'entrar' :
?>
<script type="text/javascript">
function revisar_usuario() {
	document.u_formulario.u_nick.value = document.u_formulario.u_nick.value.replace(/^\s*|\s*$/g,'') ;
	document.u_formulario.u_contrasena.value = document.u_formulario.u_contrasena.value.replace(/^\s*|\s*$/g,'') ;
	if(document.u_formulario.u_nick.value.length == 0) {
		alert('Debes escribir un nick') ;
		return false ;
	}
	if(document.u_formulario.u_contrasena.value.length == 0) {
		alert('Debes escribir una contraseña') ;
		return false ;
	}
}
</script>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td valign="top">
<form name="u_formulario" method="post" action="usuariopro.php?que=entrar" onsubmit="return revisar_usuario()">
<b>Nick:</b>
<input type="text" name="u_nick" size="10" maxlength="20" class="eforo_formulario">
<b>Contraseña:</b>
<input type="password" name="u_contrasena" size="10" maxlength="10" class="eforo_formulario">
<input type="submit" name="enviar" value="Entrar" class="eforo_formulario">
</td></form>
<td valign="top"><div align="right"><a href="<?="$u[0]usuario$u[1]$u[2]que$u[4]registrar$u[5]"?>" class="eforo">Nuevo usuario</a> | <a href="<?="$u[0]usuario$u[1]$u[2]que$u[4]contrasena$u[5]"?>" class="eforo">Perdí mi contraseña</a></div></td>
</tr>
</table>
<?
	break ;
	// --> Registrar nuevo usuario
	case 'registrar' :
?>
<script>
function revisar() {
	document.u_formulario.u_nick.value = document.u_formulario.u_nick.value.replace(/^\s*|\s*$/g,'') ;
	document.u_formulario.u_contrasena.value = document.u_formulario.u_contrasena.value.replace(/^\s*|\s*$/g,'') ;
	document.u_formulario.u_email.value = document.u_formulario.u_email.value.replace(/^\s*|\s*$/g,'') ;
	if(document.u_formulario.u_nick.value.length < 3) {
		alert('El nick debe contener por lo mínimo 3 caractéres.') ;
		return false ;
		}
	if(document.u_formulario.u_contrasena.value.length < 8) {
		alert('La contraseña debe contener por lo mínimo 8 caractéres.') ;
		return false ;
	}
	if(document.u_formulario.u_contrasena.value != document.u_formulario.u_contrasena2.value) {
		alert('Las contraseñas no son correctas.') ;
		return false ;
	} 
	if(!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(document.u_formulario.u_email.value)) {
		alert('Debes poner un email válido.') ;
		return false ;
	}
}
</script>
<p><a href="javascript:history.back()" class="eforo">» Regresar</a>
<p><b>Nuev@ usuari@</b>
<p>Como usuari@ registrad@ de la web podrás hacer lo siguiente:
<p>
<ul>
<li>Subir scripts a la web.
<li>Enviar enlaces de otros sitios.
<li>Participar en el foro pudiendo editar y borrar tus mensajes.
<li>Tener tu propio perfil de usuari@.
<li>Y muchas cosas más.
</ul>
<p>
<form name="u_formulario" method="post" action="usuariopro.php?que=registrar" onsubmit="return revisar()">
<b>Nick:</b><br>
<input type="text" name="u_nick" maxlength="20" class="eforo_formulario"><br>
<b>Contraseña:</b><br>
<input type="password" name="u_contrasena" maxlength="10" class="eforo_formulario"><br>
<b>Confirmar contraseña:</b><br>
<input type="password" name="u_contrasena2" maxlength="10" class="eforo_formulario"><br>
<b>Email:</b><br>
<input type="text" name="u_email" maxlength="40" class="eforo_formulario"><br>
<b>Sexo:</b><br>
<select name="u_sexo" class="eforo_formulario">
<option value="0">Masculino
<option value="1">Femenino
</select><br><br>
<input type="submit" name="enviar" value="Registrar Nuevo Usuario" class="eforo_formulario">
</form>
<?
	break ;
	// --> Recuperar los datos del usuario en caso de extravío
	case 'contrasena' :
?>
<script>
function revisar() {
	if(!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(document.u_formulario.u_email.value)) {
		alert('Debes poner un email válido.') ;
		return false ;
	}
}
</script>
<p><a href="javascript:history.back()" class="eforo">» Regresar</a>
<p><b>Recuperar contraseña</b>
<p>Para recuperar tus datos debes escribir el email
con el que te registraste. Debido a que la contraseña se guarda encriptada no hay forma de recuperarla por lo que se te enviara una
nueva, más tarde puedes cambiarla desde tu perfil.
<p>
<form name="u_formulario" method="post" action="usuariopro.php?que=contrasena" onsubmit="return revisar()">
<input type="text" name="u_email" size="25" maxlength="40" class="eforo_formulario"><br><br>
<input type="submit" value="Recuperar Contraseña" class="eforo_formulario">
</form>
<?
	break ;
	// --> Editar los datos del perfil del usuario
	case 'perfil' :
		require('eforo_funciones/sesion.php') ;
		if($_GET[u] != $usuario[id] && !$es_administrador) {
			echo '<center><b>No puedes editar el perfil</b></center>' ;
			exit ;
		}
		$con = mysql_query("select * from $tabla_usuarios where id='$_GET[u]'") ;
		$datos = mysql_fetch_assoc($con) ;
?>
<p><a href="noticias" class="eforo">» Regresar</a>
<p><b>Editar perfil</b>
<p>En esta sección puedes editar tu perfil, el avatar no debe pesar más de <b><?=$conf[avatar_tamano]?> KB</b> y debe medir entre
<b><?=$conf[avatar_largo]?></b> x <b><?=$conf[avatar_ancho]?></b> pixeles.
<script>
function revisar() {
	if(document.u_formulario.u_nick.value == '') {
		alert('Debes escribir un nick.') ;
		return false ;
	}
	if(document.u_formulario.u_contrasena.value.length > 0 && document.u_formulario.u_contrasena.value.length < 8) {
		alert('La contraseña debe contener por lo mínimo 8 caractéres.') ;
		return false ;
	}
	if(document.u_formulario.u_contrasena.value != document.u_formulario.u_contrasena2.value) {
		alert('Las contraseñas no son correctas.') ;
		return false ;
	}
	if(!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(document.u_formulario.u_email.value)) {
		alert('Debes poner un email válido.') ;
		return false ;
	}
	if(document.u_formulario.u_descripcion.value.length > 255) {
		alert('La descripción supera los 255 caractéres.') ;
		return false ;
	}
}
</script>
<form name="u_formulario" method="post" action="usuariopro.php?que=perfil" enctype="multipart/form-data" onsubmit="return revisar()">
<input type="hidden" name="u" value="<?=$_GET[u]?>">
<b>* Nick:</b><br>
<input type="text" name="u_nick" maxlength="20" value="<?=$datos[nick]?>" class="eforo_formulario"><br>
<b>Contraseña nueva:</b><br>
<input type="password" name="u_contrasena" maxlength="20" class="eforo_formulario"><br>
<b>Repite la contraseña:</b><br>
<input type="password" name="u_contrasena2" maxlength="20" class="eforo_formulario"><br>
<b>* Email:</b><br>
<input type="text" name="u_email" maxlength="40" value="<?=$datos[email]?>" class="eforo_formulario"><br>
<b>País:</b><br>
<input type="text" name="u_pais" maxlength="20" value="<?=$datos[pais]?>" class="eforo_formulario"><br>
<b>Edad:</b><br>
<input type="text" name="u_edad" maxlength="2" size="3" value="<?=$datos[edad] ? $datos[edad] : ''?>" class="eforo_formulario"><br>
<b>Sexo:</b><br>
<select name="u_sexo" class="eforo_formulario">
<option value="0">Masculino
<option value="1"<?=$datos[sexo] ? ' selected' : ''?>>Femenino
</select><br>
<b>Descripción:</b><br>
<textarea name="u_descripcion" cols="30" rows="5" class="eforo_formulario"><?=$datos[descripcion]?></textarea><br>
<b>Web:</b><br>
<input type="text" name="u_web" maxlength="100" size="30" value="<?=$datos[web]?>" class="eforo_formulario"><br>
<b>Firma:</b><br>
<textarea name="u_firma" cols="30" rows="5" class="eforo_formulario"><?=$datos[firma]?></textarea><br>
<b>Zona horaria GMT:</b><br>
<select name="u_gmt" class="eforo_formulario">
<option value="-12"<?=$datos[gmt] == -12 ? ' selected' : ''?>>(GMT-12) Eniwetok, Kwajalein
<option value="-11"<?=$datos[gmt] == -11 ? ' selected' : ''?>>(GMT-11) Midway, Samoa
<option value="-10"<?=$datos[gmt] == -10 ? ' selected' : ''?>>(GMT-10) Hawaii
<option value="-9"<?=$datos[gmt] == -9 ? ' selected' : ''?>>(GMT-9) Alaska
<option value="-8"<?=$datos[gmt] == -8 ? ' selected' : ''?>>(GMT-8) Tijuana, Pacific Time (USA)
<option value="-7"<?=$datos[gmt] == -7 ? ' selected' : ''?>>(GMT-7) Arizona, Mountain Time (USA)
<option value="-6"<?=$datos[gmt] == -6 ? ' selected' : ''?>>(GMT-6) Mexico (D.F.), Central Time (USA)
<option value="-5"<?=$datos[gmt] == -5 ? ' selected' : ''?>>(GMT-5) New York, Eastern Time (USA)
<option value="-4"<?=$datos[gmt] == -4 ? ' selected' : ''?>>(GMT-4) Caracas, Atlantic Time (Canada)
<option value="-3"<?=$datos[gmt] == -3 ? ' selected' : ''?>>(GMT-3) Brasilia, Buenos Aires
<option value="-2"<?=$datos[gmt] == -2 ? ' selected' : ''?>>(GMT-2) Mid-Atlantic
<option value="-1"<?=$datos[gmt] == -1 ? ' selected' : ''?>>(GMT-1) Azores, Cabo Verde
<option value="0"<?=$datos[gmt] == 0 ? ' selected' : ''?>>(GMT 0) Dublin, Lisboa, Londres
<option value="1"<?=$datos[gmt] == 1 ? ' selected' : ''?>>(GMT+1) Amsterdam, Madrid, Paris
<option value="2"<?=$datos[gmt] == 2 ? ' selected' : ''?>>(GMT+2) Atenas, Estambul, Israel
<option value="3"<?=$datos[gmt] == 3 ? ' selected' : ''?>>(GMT+3) Bagdad, Kuwait, Riyad, Moscu
<option value="4"<?=$datos[gmt] == 4 ? ' selected' : ''?>>(GMT+4) Abu Dhabi, Baku, Tbilisi
<option value="5"<?=$datos[gmt] == 5 ? ' selected' : ''?>>(GMT+5) Islamabad, Karachi
<option value="6"<?=$datos[gmt] == 6 ? ' selected' : ''?>>(GMT+6) Almaty, Dhaka, Colombo
<option value="7"<?=$datos[gmt] == 7 ? ' selected' : ''?>>(GMT+7) Bangkok, Hanoi, Yakarta
<option value="8"<?=$datos[gmt] == 8 ? ' selected' : ''?>>(GMT+8) Beijing, Hong Kong, Singapur
<option value="9"<?=$datos[gmt] == 9 ? ' selected' : ''?>>(GMT+9) Tokyo, Seul
<option value="10"<?=$datos[gmt] == 10 ? ' selected' : ''?>>(GMT+10) Melbourne, Sydney
<option value="11"<?=$datos[gmt] == 11 ? ' selected' : ''?>>(GMT+11) Solomon, Nueva Caledonia
<option value="12"<?=$datos[gmt] == 12 ? ' selected' : ''?>>(GMT+12) Auckland, Wellington, Fiji
</select><br>
<b>Avatar:</b><br>
<input type="file" name="u_archivo" class="eforo_formulario"><br>
<? if($datos[avatar]) { ?>
<b>Avatar actual:</b><br>
<img src="eforo_imagenes/avatares/<?=$datos[id]?>.<?=$datos[avatar]?>"><br>
<input type="checkbox" name="borrar" value="1" id="borrar"><label for="borrar">Borrar el avatar</label><br>
<? } ?>
<br>
<input type="submit" name="enviar" value="Editar Perfil" class="eforo_formulario">
</form>
<?
		mysql_free_result($con) ;
}
?>
</td>
</tr>
</table>
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
