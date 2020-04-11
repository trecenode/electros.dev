<?
require 'codigo.php' ;
?>
<table width="100%" border="0" cellpadding="3" cellspacing="0" class="tabla_t1">
<tr>
<td height="20">&nbsp;<img src="imagenes/flecha.gif" border="0" width="11" height="11" alt="Scripts" align="top"> <b>Scripts</b></td>
</tr>
<tr class="tabla_t2">
<td>
<div style="margin: 10px">
<p><a href="scripts">� Regresar a Scripts</a>
<p><b>Como crear el config.php</b>
<p>En la mayor�a de los scripts es necesario una base de datos MySQL y por eso se requieren
los datos de conexi�n como son el servidor, el usuario, la contrase�a y la base de datos a
usar, para evitar tener que insertar estos datos en todos los scripts, hemos decidido que
todo script en la web deber� usar un archivo �nico que hemos llamado <b>config.php</b>, aqu�
pondr�s tus datos de conexi�n, entonces cada vez que se requiera la base de datos MySQL el
mismo script llamar� a <b>config.php</b> mediante la funci�n
<a href="http://www.php.net/manual/function.require.php" target="_blank">require()</a>
por lo que ya no tendremos que preocuparnos por modificar estos datos por cada nuevo script,
para crear el <b>archivo config.php</b> mete el siguiente c�digo en un editor de texto como
el Bloc de notas de Windows:
<p><?=codigo('[codigo]'.file_get_contents('ayuda_config.txt').'[/codigo]')?>
<p>Tambi�n puedes descargar el archivo desde aqu� (clic derecho y luego guardar destino...):
<p><a href="ayuda_config.txt">Descargar config.php</a>
</div>
</td>
</tr>
</table>