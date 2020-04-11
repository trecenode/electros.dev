<?
function quitar($a,$b=0) {
	$a = trim($a) ;
	if(get_magic_quotes_gpc()) $a = stripslashes($a) ;
	$a = str_replace(chr(160),'',$a) ;
	$a = htmlspecialchars($a) ;
	if($b) {
		$a = mysql_real_escape_string($a) ;
	}
	return $a ;
}
?>