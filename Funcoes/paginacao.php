<?php

	function paginacao_php($pagina,$registros,$totalpaginas,$totalregistros,$linkconsulta,$descricaoconsulta,$idregistro=0)
	{
		$html = '<div align="center">
					<nav>
						<ul class="pagination">';
		
		$html .= '<li class="btn-group dropup">
				
					<a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
						'.$registros.'
						<span class="caret"></span>
					</a>
					
					<ul class="dropdown-menu">
						<li>
							<a onclick="'.$linkconsulta.'('.$pagina.', 5)">5</a>
						</li>
						
						<li>
							<a onclick="'.$linkconsulta.'('.$pagina.', 10)">10</a>
						</li>
						
						<li>
							<a onclick="'.$linkconsulta.'('.$pagina.', 15)">15</a>
						</li>
						
						<li>
							<a onclick="'.$linkconsulta.'('.$pagina.', 20)">20</a>
						</li>
						
						<li>
							<a onclick="'.$linkconsulta.'('.$pagina.', 25)">25</a>
						</li>
						
						<li>
							<a onclick="'.$linkconsulta.'('.$pagina.', 30)">30</a>
						</li>
					</ul>
					
				 </li>';
		
		
		if($pagina <= 1)
		{
			$html .= '<li class="disabled">
						  <a aria-label="Previous">
							  <span aria-hidden="true">&laquo;</span>
						  </a>
					  </li>
					  
					  <li class="disabled">
						  <a aria-label="Previous">
							  <span aria-hidden="true">&lsaquo;</span>
						  </a>
					  </li>';
		}
		else 
		{					
			$html .= '<li>
						  <a onclick="'.$linkconsulta.'('.(0).','.$registros.')" aria-label="Previous">
							  <span aria-hidden="true">&laquo;</span>
						  </a>
					  </li>
					  
					  <li>
						  <a onclick="'.$linkconsulta.'('.($pagina-1).','.$registros.')" aria-label="Previous">
							  <span aria-hidden="true">&lsaquo;</span>
						  </a>
					  </li>';
		}
		
		if(isset($totalpaginas))
		{		
			for ($y=1;$y<=$totalpaginas;$y++) 
			{
				if($y == 1 && $pagina > 1)
				{
					if($pagina > ($totalpaginas-6))
					{
						$linkpagina = $totalpaginas-8;
					}
					elseif(($pagina-3) >= 1)
					{
						$linkpagina = $pagina-3;
					}													
					else
					{
						$linkpagina = 1;
					}
					
					$html .= "<li><a onclick='".$linkconsulta."(".$linkpagina.",".$registros.")'>...</a></li>";
				}
				elseif (($y < ($pagina+3) && !($y < $pagina)) || ((($y+6) > $totalpaginas) && ($pagina+6) > $totalpaginas))
				{
					if($y == $pagina)
					{
						$html .= "<li class='active'><a onclick='".$linkconsulta."(".$y.",".$registros.")'>".$y."</a></li>";
					}
					else
					{
						$html .= "<li><a onclick='".$linkconsulta."(".$y.",".$registros.")'>".$y."</a></li>";
					}
				}												
				elseif($y == ($pagina+3) && !($pagina > ($totalpaginas-6))) 
				{
					$html .= "<li><a onclick='".$linkconsulta."(".$y.",".$registros.")'>...</a></li>";
				}
				elseif($y > ($totalpaginas-3)) 
				{
					$html .= "<li><a onclick='".$linkconsulta."(".$y.",".$registros.")'>".$y."</a></li>";
				} 
			}
		}										
		
		if($pagina == ($y-1) || ($y-1) <= 0)
		{
			$html .= '<li class="disabled">
						  <a aria-label="Next">
							  <span aria-hidden="true">&rsaquo;</span>
						  </a>
					  </li>
					 
					  <li class="disabled">
						  <a aria-label="Next">
							  <span aria-hidden="true">&raquo;</span>
						  </a>
					  </li>';
		}
		else
		{
			$html .= '<li>
						  <a onclick="'.$linkconsulta.'('.($pagina+1).','.$registros.')" aria-label="Next">
							  <span aria-hidden="true">&rsaquo;</span>
						  </a>
					  </li>
					  
					  <li>
						  <a onclick="'.$linkconsulta.'('.($y-1).','.$registros.')" aria-label="Next">
							  <span aria-hidden="true">&raquo;</span>
						  </a>
					  </li>';
		}
		
		if($pagina == 1)
		{
			$registrosatual = $pagina;
		}
		elseif($totalregistros > 0)
		{
			$registrosatual = ((($pagina-1)*$registros)+1);
		}
		
		if($totalregistros == 0)
		{
			$registrosatual = 0;
		}
		
		if(($pagina*$registros) <= $totalregistros)
		{
			$html .= '<li>
						  <a>
							  Listando de '.$registrosatual.' 
							  à '.($pagina*$registros).' 
							  de '.$totalregistros.' '.$descricaoconsulta.'
						  </a> 
					 </li>';
		}
		else 
		{
			$html .= '<li>
						  <a>
							  Listando de '.$registrosatual.' 
							  à '.$totalregistros.' 
							  de '.$totalregistros.' '.$descricaoconsulta.'
						  </a> 
					  </li>';
		}
		
		$html .=		'</ul>
					</nav>
				</div>';
		
		return $html;
	}
	
?>
								