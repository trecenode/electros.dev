<?
/*
************************************************
*** eForo v3.0
*** Creado por: Electros <electros@electros.net>
*** Sitio web: www.electros.net
*** Licencia: GNU General Public License
************************************************

--- Página: eforo_funciones/epaginas.php ---

eForo - Una comunidad para tus visitantes
Copyright © 2003-2004 Daniel Osorio "Electros"

Este programa es software libre, puedes redistribuirlo y/o modificarlo bajo los términos
de la GNU General Public License publicados por la Free Software Foundation; desde la
versión 2 de la licencia, o (si lo deseas) cualquiera más reciente.
*/

# * ePaginas
# Clase para ePaginas los resultados de una consulta MySQL
class ePaginas {
	function ePaginas($a) {
		# * Si deseas utilizar el Mod_Rewrite de Apache puedes modificar las siguientes variables
		# Ejemplo: "index.php?seccion=pagina" podría ser "index.php/seccion/pagina.html".
		# Para esto caso necesitaremos las variables:
		# $this->u[1] = '/' ;
		# $this->u[2] = '/' ;
		# $this->u[3] = '/' ;
		# $this->u[4] = '.html' ;
		# Si no deseas utilizar esta opción deja los valores tal como están.
		$this->u[1] = '' ;
		$this->u[2] = '/' ;
		$this->u[3] = '/' ;
		$this->u[4] = '' ;
		$this->codigo = $a ;
		$con = mysql_query(eregi_replace('select (.+) from','select count(*) from',$this->codigo)) ;
		$this->total_res = mysql_result($con,0,0) ;
	}
	# Obtener el total de resultados
	function mostrar($a) {
		$this->mostrar = $a ;
	}
	# Procesar el código SQL
	function consultar() {
		$this->total_pag = ceil($this->total_res/$this->mostrar) ;
		switch(true) {
			case $_GET[p] < 1 :
				$_GET[p] = 1 ;
				break ;
			case $_GET[p] > $this->total_pag :
				$_GET[p] = $this->total_pag ;
		}
		$desde = ereg('[0-9]+',$_GET[p]) ? ($_GET[p] - 1) * $this->mostrar : 0 ;
		$con = mysql_query($this->codigo." limit $desde,$this->mostrar") ;
		return $con ;
	}
	# Crear la URL evitando repetir varias veces la variable de página (ej. index.php?id=noticias&n=1&pag=1)
	function url() {
		foreach ($_GET as $variable => $valor) {
			if ($variable != 'p') {
				if($variable == 'id') {
					$url .= $valor.'/' ;
				}
				else {
					$valor = urlencode($valor) ;
					$url .= "$variable{$this->u[3]}$valor{$this->u[2]}" ;
				}
			}
		}
		return $url ;
	}
	function paginar() {
		# Para los que usan enlaces tipo www.pagina.com/?seccion=descargas
		$pagina = $_SERVER['PHP_SELF'] ;
		if(strstr($pagina,'index.php')) {
			$pagina = str_replace('index.php','',$pagina) ;
		}
		$max_paginas = 8 ;
		$url = $this->url() ;
		$pag_anterior = $_GET[p] - 1 ;
		if($pag_anterior >= 1) {
			$paginas[] = "<a href=\"$pagina{$this->u[1]}$url"."p{$this->u[3]}1{$this->u[4]}\" class=\"eforo\">Primera</a>" ;
			$paginas[] = "<a href=\"$pagina{$this->u[1]}$url"."p{$this->u[3]}$pag_anterior{$this->u[4]}\" class=\"eforo\">«</a>" ;
		}
		$this->total_pag_mostrar = $this->total_pag > $max_paginas ? $max_paginas : $this->total_pag ;
		$pag_desde = ($_GET[p]-$max_paginas/2) ;
		if($pag_desde < 1) {
			$pag_desde = 1 ;
		}
		$pag_hasta = ($_GET[p]+$max_paginas/2) ;
		if($pag_hasta > $this->total_pag) {
			$pag_hasta = $this->total_pag ;
		}
		for($a = $pag_desde ; $a <= $pag_hasta ; $a++) {
			$paginas[] = ($a != $_GET[p]) ? "<a href=\"$pagina{$this->u[1]}$url"."p{$this->u[3]}$a{$this->u[4]}\" class=\"eforo\">$a</a>" : $a ;
		}
		$pag_siguiente = $_GET[p] + 1 ;
		if($pag_siguiente <= $this->total_pag) {
			$paginas[] = "<a href=\"$pagina{$this->u[1]}$url"."p{$this->u[3]}$pag_siguiente{$this->u[4]}\" class=\"eforo\">»</a>" ;
			$paginas[] = "<a href=\"$pagina{$this->u[1]}$url"."p{$this->u[3]}$this->total_pag{$this->u[4]}\" class=\"eforo\">Ultima</a>" ;
		}
		$paginas =
		'<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
		<td>Total: <b>'.$this->total_res.'</b> Páginas: <b>'.$this->total_pag.'</b></td>
		<td><div align="right">'.@implode(', ',$paginas).'</div></td>
		</tr>
		</table>
		' ;
		echo $paginas ;
	}
}
?>
