<?
// * Código especial
function codigo($texto) {
	$caracteres = array(
	'&lt;'   => '<',
	'&gt;'   => '>',
	'&quot;' => '"',
	'&amp;'  => '&'
	) ;
	foreach($caracteres as $a => $b) {
		$texto = str_replace($a,$b,$texto) ;
	}
	if(strstr($texto,'[codigo]')) {
		@ini_set('highlight.bg','') ; # Fondo
		@ini_set('highlight.comment','#757575') ; # Comentarios
		@ini_set('highlight.default','#0075cc') ; # Texto por defecto
		@ini_set('highlight.html','#aa7500') ; # Código HTML
		@ini_set('highlight.keyword','#008000') ; # Caractéres y funciones de PHP
		@ini_set('highlight.string','#0000ff') ; # Cadenas de texto
		preg_match_all('/\[codigo\](.+)\[\/codigo\]/sU',$texto,$texto_extraido) ;
		for($i = 0 ; $i < count($texto_extraido[0]) ; $i++) {
			$texto_codigo = $texto_extraido[1][$i] ;
			$texto = str_replace($texto_extraido[0][$i],'<div class="codigo"><div style="margin: 3px">'.str_replace("\n",'',highlight_string(trim($texto_codigo),1)).'</div></div>',$texto) ;
			$texto = str_replace("\r<br />",'<br>',$texto) ;
		}
	}
	$texto = preg_replace('/\[t1\](.+)\[\/t1\]/','<span style="font-size: 10pt ; font-weight: bold ; color: #c00000">\1</span>',$texto) ;
	$texto = str_replace("\n",'<br>',$texto) ;
	return $texto ;
}
?>