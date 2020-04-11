<?
// * Código especial
function codigo($texto) {
	if(strstr($texto,'[codigo]')) {
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
		preg_match_all('/\[codigo\](.+)\[\/codigo\]/sU',$texto,$texto_extraido) ;
		for($i = 0 ; $i < count($texto_extraido[0]) ; $i++) {
			$texto_codigo = $texto_extraido[1][$i] ;
			foreach($caracteres as $a => $b) {
				$texto_codigo = str_replace($a,$b,$texto_codigo) ;
			}
			$texto = str_replace($texto_extraido[0][$i],'<div class="codigo"><div style="margin: 3px">'.str_replace("\n",'',highlight_string(trim($texto_codigo),1)).'</div></div>',$texto) ;
			$texto = str_replace("\r<br />",'<br>',$texto) ;
		}
	}
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
	$texto = preg_replace('/\[url\](.+)\[\/url\]/iU','<a href="\\1" target="_blank" class="eforo">\\1</a>',$texto) ;
	$texto = preg_replace('/\[url=(.+)\](.+)\[\/url]/iU','<a href="\\1" target="_blank" class="eforo">\\2</a>',$texto) ;
	$texto = preg_replace('/([\w_-]+)@([\w\.-]+\.\w{2,3})/i',"<script>correo('\\1','\\2')</script>",$texto) ;
	$texto = str_replace("\n",'<br>',$texto) ;
	return $texto ;
}
?>
