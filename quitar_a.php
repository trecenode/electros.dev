<?
function quitar_a($archivo) {
	$caracteres = array('<','>','&','"',"'",'/',"\\",'?','*',':','|') ;
	foreach($caracteres as $a) {
		$archivo = str_replace($a,'',$archivo) ;
	}
	if(!$archivo) exit('<p>Escribe un nombre de archivo v�lido (est�n prohibidos los caract�res: <b>'.implode(' ',$caracteres).')</b>') ;
	return $archivo ;
}
?>