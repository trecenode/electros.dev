<?
function fecha($a) {
	$fecha_actual = $GLOBALS['fecha'] - $a ;
	switch (true) {
		case $fecha_actual > 0 && $fecha_actual < 3600 :
			$minutos = round($fecha_actual / 60) ;
			return ($minutos == 0 || $minutos == 1) ? 'Hace 1 minuto' : "Hace $minutos minutos" ;
			break ;
		default :
			if(!empty($_COOKIE['e_id'])) {
				$con = mysql_query("select gmt from usuarios where id='{$_COOKIE['e_id']}'") ;
				$datos = mysql_fetch_assoc($con) ;
				$u_gmt = $datos['gmt'] + date('I') ;
				mysql_free_result($con) ;
			}
			else {
				$u_gmt = 0 ;
			}
			$gmt = $a + 3600 * $u_gmt ;
			$meses = array('','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic') ;
			return date('j',$gmt).' '.$meses[date('n',$gmt)].' '.date('Y',$gmt).' '.date('h:i A',$gmt) ;
	}
}
?>