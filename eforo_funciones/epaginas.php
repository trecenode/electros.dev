<?
/*
*************************************************
*** ePaginas v1.0
*** Creado por: Electros <webmaster@electros.net>
*** Sitio web: www.electros.net
*** Licencia: GNU General Public License
*************************************************

ePaginas - Clase para ePaginas resultados MySQL
Copyright © 2005-2006 Daniel Osorio "Electros"

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
*/

class ePaginas {
	function ePaginas($a,$b) {
		$this->consulta = $a ;
		# Obtener el total de resultados
		$con = mysql_query(eregi_replace('select (.+) from','select count(*) from',$this->consulta)) ;
		$this->total_res = mysql_result($con,0,0) ;
		# Resultados a mostrar por página
		$this->resultados = $b ;
		# Total de páginas
		$this->total_pag = ceil($this->total_res/$this->resultados) ;
	}
	# Si las siguientes variables no fueron modificadas se aplican sus valores por defecto
	function variables() {
		if(empty($this->u)) $this->u = array('','/','/','') ; # <-- Sintaxis de URL (para su uso con mod_rewrite)
		if(empty($this->p)) $this->p = 'p' ; # <-- Variable de página
		if(empty($this->e)) $this->e = array('<a href="','">','</a>') ; # <-- Formato de enlace
		if(empty($this->m)) $this->m = 9 ; # <-- Máximo de páginas a mostrar (número impar)
	}
	# Procesar la consulta SQL
	function consultar() {
		$this->variables() ; # <-- Comprobar variables
		if(empty($_GET[$this->p]) || !ereg('^[0-9]+$',$_GET[$this->p])) $_GET[$this->p] = 1 ;
		elseif($this->total_pag > 0 && $_GET[$this->p] > $this->total_pag) $_GET[$this->p] = $this->total_pag ;
		$desde = ($_GET[$this->p] - 1) * $this->resultados ;
		return mysql_query($this->consulta." limit $desde,$this->resultados") ;
	}
	# Obtener los datos de la URL
	function url_datos() {
		$url = '' ;
		foreach($_GET as $variable => $valor) {
			# Se ignora la variable de página para evitar que se repita
			if($variable != $this->p) {
			# Descomenta el siguiente código si deseas usar mod_rewrite de la forma que se
			# indica en el archivo leeme.html:
				$url .= ($variable == 'id') ? $valor.$this->u[1] : $variable.$this->u[2].urlencode($valor).$this->u[1] ;
			}
		}
		return $url ;
	}
	function paginar() {
		$paginas = array() ;
		# Debes descomentar también el siguiente código para usar mod_rewrite según el archivo leeme.html:
		$_SERVER['PHP_SELF'] = str_replace('index.php','',$_SERVER['PHP_SELF']) ;
		$url = $this->e[0].$_SERVER['PHP_SELF'].$this->u[0].$this->url_datos().$this->p.$this->u[2] ;
		# Si se está después de la primera página se muestra la flecha de retroceder y el enlace a la primera página
		$pag_anterior = $_GET[$this->p] - 1 ;
		if($pag_anterior >= 1) {
			$paginas[] = $url.'1'.$this->u[3].$this->e[1].'Primera'.$this->e[2] ;
			$paginas[] = $url.$pag_anterior.$this->u[3].$this->e[1].'«'.$this->e[2] ;
		}
		# Se muestran los enlaces hacia las demás páginas
		$pag_desde = $_GET[$this->p] - ($this->m - 1) / 2 ;
		if($pag_desde < 1) $pag_desde = 1 ;
		$pag_hasta = $_GET[$this->p] + ($this->m - 1) / 2 ;
		if($pag_hasta > $this->total_pag) $pag_hasta = $this->total_pag ;
		for($a = $pag_desde ; $a <= $pag_hasta ; $a++) {
			# Si se visita una página se le quita el enlace
			$paginas[] = ($a != $_GET[$this->p]) ? $url.$a.$this->u[3].$this->e[1].$a.$this->e[2] : $a ;
		}
		# Si se está antes de la última página se muestra la flecha de avanzar y el enlace a la última página
		$pag_siguiente = $_GET[$this->p] + 1 ;
		if($pag_siguiente <= $this->total_pag) {
			$paginas[] = $url.$pag_siguiente.$this->u[3].$this->e[1].'»'.$this->e[2] ;
			$paginas[] = $url.$this->total_pag.$this->u[3].$this->e[1].'Ultima'.$this->e[2] ;
		}
		$paginas =
'<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td>Resultados: <b>'.$this->total_res.'</b> Páginas: <b>'.$this->total_pag.'</b></td>
<td><div align="right">'.implode(', ',$paginas).'</div></td>
</tr>
</table>' ;
		return $paginas ;
	}
}
?>
