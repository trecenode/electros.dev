<?
function quitar_a($archivo) {
	$caracteres = array('<','>','&','"',"'",'/',"\\",'?','*',':','|') ;
	foreach($caracteres as $a) {
		$archivo = str_replace($a,'',$archivo) ;
	}
	if(!$archivo) exit('<p>Escribe un nombre de archivo válido (están prohibidos los caractéres: <b>'.implode(' ',$caracteres).')</b>') ;
	return $archivo ;
}
?>