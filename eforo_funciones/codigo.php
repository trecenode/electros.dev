<?
/*
************************************************
*** eForo v3.0
*** Creado por: Electros <electros@electros.net>
*** Sitio web: www.electros.net
*** Licencia: GNU General Public License
************************************************

--- Página: codigo.php ---

eForo - Una comunidad para tus visitantes
Copyright © 2003-2004 Daniel Osorio "Electros"

Este programa es software libre, puedes redistribuirlo y/o modificarlo bajo los términos
de la GNU General Public License publicados por la Free Software Foundation; desde la
versión 2 de la licencia, o (si lo deseas) cualquiera más reciente.
*/

# * Código Especial
# Este es el código especial usado en el foro, son las etiquetas [b] y [/b] para poner el texto en
# negrita, [img] y [/img] para colocar una imagen en el foro, [cod] y [/cod] que colorea todo
# lo que sea código mediante la función highlight_string() de PHP, la función que sustituye los :) por
# su respectiva imagen, la función que busca direcciones web o correos y los transforma automáticamente
# en enlaces y la función para censurar palabras.

# * Sustituye el código especial por su respectivo código HTML
if($conf[permitir_codigo]) {
	function codigo($texto) {
		# --> Colorear código
		# Colorea el texto contenido entre las etiquetas [cod] y [/cod] mediante la función highlight_string() de PHP.
		if(strstr($texto,'[cod]')) {
			# --> Modifica los colores por defecto de PHP (sin tocar el php.ini)
			# No todos los servidores permiten accesar a la función ini_set() 
			@ini_set('highlight.bg','') ; # Fondo
			@ini_set('highlight.comment','#757575') ; # Comentarios
			@ini_set('highlight.default','#0075cc') ; # Texto por defecto
			@ini_set('highlight.html','#aa7500') ; # Código HTML
			@ini_set('highlight.keyword','#008000') ; # Caractéres y funciones de PHP
			@ini_set('highlight.string','#0000ff') ; # Cadenas de texto
			$caracteres = array(
			'&lt;'   => '<',
			'&gt;'   => '>',
			'&quot;' => '"',
			'&amp;'  => '&'
			) ;
			preg_match_all('/\[cod\](.+)\[\/cod\]/sU',$texto,$texto_extraido) ;
			for($i = 0 ; $i < count($texto_extraido[0]) ; $i++) {
				$texto_codigo = $texto_extraido[1][$i] ;
				foreach($caracteres as $a => $b) {
					$texto_codigo = str_replace($a,$b,$texto_codigo) ;
				}
				$texto = str_replace($texto_extraido[0][$i],'<div class="codigo"><div style="margin: 3px">'.str_replace("\n",'',highlight_string(trim($texto_codigo),1)).'</div></div>',$texto) ;
				$texto = str_replace("\r<br />",'<br>',$texto) ;
			}
		}
		# --> Reemplaza etiquetas [nombre] por <nombre>
		$etiquetas = array(
		'[b]'    => '<b>',
		'[/b]'   => '</b>',
		'[i]'    => '<i>',
		'[/i]'   => '</i>',
		'[u]'    => '<u>',
		'[/u]'   => '</u>',
		'[img]'  => '<img src="',
		'[/img]' => '" border="0">'
		) ;
		foreach($etiquetas as $a => $b) {
			$texto = str_replace($a,$b,$texto) ;
		}
		# --> Reemplaza etiquetas usando también expresiones regulares
		$texto = preg_replace('/\[color=(#?[0-9a-z]+)\]/iU','<span style="color: \\1">',$texto) ;
		$texto = str_replace('[/color]','</span>',$texto) ;
		$texto = preg_replace('/\[url\](.+)\[\/url\]/iU','<a href="\\1" target="_blank" class="eforo">\\1</a>',$texto) ;
		$texto = preg_replace('/\[url=(.+)\]/iU','<a href="\\1" target="_blank" class="eforo">',$texto) ;
		$texto = str_replace('[/url]','</a>',$texto) ;
		$texto = preg_replace('/\[citar autor=(.+)\]/i','<div class="eforo_tabla_codigo">Escrito originalmente por: <b>\\1</b><hr width="100%" color="#505050">',$texto) ;
		$texto = str_replace('[/citar]','</div>',$texto) ;
		return $texto ;
	}
}
# --> Pone caretos en los mensajes
if($conf[permitir_caretos]) {
	function caretos($texto) {
		$caretos = array(
		':D'   => 'alegre.gif',
		':P'   => 'burla.gif',
		':(1'  => 'demonio.gif',
		':?'   => 'duda.gif',
		';)'   => 'guino.gif',
		':lol' => 'lol.gif',
		':|'   => 'neutral.gif',
		':-)'  => 'sonrisa.gif',
		':O'   => 'sorprendido.gif',
		':8'   => 'asustado.gif',
		':S'   => 'confundido.gif',
		':(2'  => 'demonio2.gif',
		':-('  => 'enojado.gif',
		':\'('  => 'llorar.gif',
		':M'   => 'moda.gif',
		':)'   => 'risa.gif',
		':R'   => 'sonrojado.gif',
		':('   => 'triste.gif'
		) ;
		foreach($caretos as $a => $b) {
			$texto = str_replace($a,'<img src="eforo_imagenes/caretos/'.$b.'" width="15" height="15" align="absmiddle">',$texto) ;
		}
		return $texto ;
	}
}
# --> Transforma URLs en enlaces
if($conf[transformar_url]) {
	function url($texto) {
		$texto = preg_replace('/(?<!<a href=")(?<!<img src=")(http|ftp)s?:\/\/[^,\s]+/i','<a href="\\0" target="_blank" class="eforo">\\0</a>',$texto) ;
		$texto = preg_replace('/([\w_.-]+)@([\w.-]+\.\w{2,4})/i',"<script>proteger_email('\\1','\\2')</script>",$texto) ;
		return $texto ;
	}
}
# --> Censura palabras
if($conf[censurar_palabras]) {
	function censurar($texto) {
		$palabras = array(
		'insulto1' => '*****',
		'insulto2' => '*****',
		'insulto3' => '*****'
		) ;
		foreach($palabras as $a => $b) {
			$texto = str_replace($a,$b,$texto) ;
		}
		return $texto ;
	}
}
?>
