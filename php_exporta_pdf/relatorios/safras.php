<?php

	date_default_timezone_set('America/Sao_Paulo');
	
	include_once('../../verificaLogin.php');
	require_once('../fpdf.php');
	require_once('../../connect.php');
	
	global $mysqli;
	
	if (!$mysqli->set_charset("utf8")) 
	{
		printf("Error loading character set utf8: %s\n", $mysqli->error);
	}
	
	/** Consultas **/
	$sql 	= "select safra_safra from tb_safra 
	           GROUP by safra_safra order by safra_safra desc";
	
	$rs		= $mysqli->query($sql);
	
	/** Ínicia Instancia Classe FPDF **/
	$pdf = new FPDF();
	
	/** Adiciona uma Nova Pagina no Relatório **/
	$pdf->AddPage();
	
	/** Ínicio Cabeçalho **/
	$pdf->Image('../../assets/admin/layout2/img/logo_apas.png' , 10 ,8, 30 , 13,'PNG');
	/*
	$pdf->SetFont('Arial', '', 9);
	$pdf->Cell(190,   5, utf8_decode('Rua: Oswaldo Henrique Valadão, 225')		  	, 0, 0, 'C');
	$pdf->Cell(  0,   5, utf8_decode('Data: '.date('d/m/Y'))              		  	, 0, 1, 'R');
	$pdf->Cell(190,   5, utf8_decode('Jardim Simões - Varginha/MG Cep: 37064-084')	, 0, 1, 'C');
	$pdf->Cell(190,   5, utf8_decode('Telefone: (35) 3214-4203')				  	, 0, 1, 'C');
      
	/** Ínicio Conteudo **/
	/*
	$pdf->Ln(5);
	$pdf->SetFont('Arial', '', 9);
	
	if(!empty($filtroperiodode) && !empty($filtroperiodoate))
	{
		$pdf->Cell(190,   5, utf8_decode('Período: '.$filtroperiodode.' até '.$filtroperiodoate)			, 0, 1, 'L');
		$pdf->Ln(5);
	}
	else if(!empty($filtroperiodode) && empty($filtroperiodoate))
	{
		$pdf->Cell(190,   5, utf8_decode('Período maior igual à: '.$filtroperiodode)					, 0, 1, 'L');
		$pdf->Ln(5);
	}
	else if(empty($filtroperiodode) && !empty($filtroperiodoate))
	{
		$pdf->Cell(190,   5, utf8_decode('Período menor igual à: '.$filtroperiodoate)					, 0, 1, 'L');
		$pdf->Ln(5);
	}
	*/
	$pdf->Ln(10);	
	$pdf->SetFont('Arial', 'B', 11);									  
	$pdf->Cell(190,   5, utf8_decode('RESUMO DE SAFRA'), 0, 1, "C");
	$pdf->Ln(5);	
	
	/** Grid Produtores Cabeçalho **/
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->SetFillColor(190, 190, 190);
	
	$pdf->Cell( 70,   5, utf8_decode('Produtor'), 0, 0, 'L', false);							
	$pdf->Cell(	30,   5, utf8_decode('Área(ha)')		, 0, 0, 'C', false);	
	$pdf->Cell(	30,   5, utf8_decode('Previsto')	, 0, 0, 'C', false);	
	$pdf->Cell(	30,   5, utf8_decode('Colhido')	, 0, 0, 'C', false);	
	$pdf->Cell(	30,   5, utf8_decode('Média por ha')	, 0, 0, 'C', false);
											
	$pdf->Ln(5);
	
	$pdf->Line(10, 30, 200, 30);
	
	/** Grid registros Corpo **/
	$backgroundtable = true;
	$pdf->SetFont('Arial', '', 9);
	
	/**  totalizadores para final do relatório **/
	$totarea_geral=0;
	$totprevisao_geral=0;
	$totproducao_geral=0;
	$totmedia_geral=0;
	
	if($rs)
	{
		$regs = array();

		while($reg = $rs->fetch_object())
		{
			array_push($regs, $reg);
		}
		
		$rs->close();
		
		$pdf->SetFillColor(190, 190, 190);
		
		for($i=0;$i<count($regs);$i++)
		{
			$totarea=0;
			$totprevisao=0;
			$totproducao=0;
			$totmedia=0;
			if($i>0){
				$pdf->Cell( 190,   5, '', 0, 1, 'L', false);	
			}
						
			$pdf->Cell( 190,   5, utf8_decode($regs[$i]->safra_safra)	, 0, 1, 'L', true);
			$sql2		= "select a.*, b.prod_nome, c.prop_nome from tb_safra a inner join tb_produtor b on a.fk_id_produtor = b.id_prod inner join tb_propriedade c on a.fk_id_prop = c.id_prop where a.safra_safra = ".$regs[$i]->safra_safra;
			
			$rs2		= $mysqli->query($sql2);
			if($rs2)
			{
				$regs2 = array();
		
				while($reg2 = $rs2->fetch_object())
				{
					array_push($regs2, $reg2);
				}
				
				$rs2->close();
				for($b=0;$b<count($regs2);$b++)
				{
					$pdf->Cell( 70,   5, utf8_decode($regs2[$b]->prod_nome.' ('. $regs2[$b]->prop_nome.')')	, 0, 0, 'L', false);
					$pdf->Cell( 30,   5, utf8_decode(number_format($regs2[$b]->safra_area,2,',','.')) , 0, 0, 'C', false);
					$pdf->Cell( 30,   5, utf8_decode($regs2[$b]->safra_previsao)	, 0, 0, 'C', false);
					$pdf->Cell( 30,   5, utf8_decode($regs2[$b]->safra_producao)	, 0, 0, 'C', false);
					$pdf->Cell( 30,   5, utf8_decode(number_format($regs2[$b]->safra_producao/$regs2[$b]->safra_area, 2, ',', '.'))	, 0, 1, 'C', false);
					
					/** totalizando **/
					$totarea = $totarea + $regs2[$b]->safra_area;
					$totprevisao = $totprevisao + $regs2[$b]->safra_previsao;
					$totproducao = $totproducao + $regs2[$b]->safra_producao;
										
					$totarea_geral= $totarea_geral + $totarea;
					$totprevisao_geral = $totprevisao_geral + $totprevisao;
					$totproducao_geral = $totproducao_geral + $totproducao;
					
					
				}
				$pdf->Cell( 70,   5, utf8_decode('Total Safra')	, 0, 0, 'L', true);
				$pdf->Cell( 30,   5, utf8_decode(number_format($totarea,2,',','.')) , 0, 0, 'C', true);
				$pdf->Cell( 30,   5, utf8_decode($totprevisao)	, 0, 0, 'C', true);
				$pdf->Cell( 30,   5, utf8_decode($totproducao)	, 0, 0, 'C', true);
				$pdf->Cell( 30,   5, utf8_decode(number_format($totproducao/$totarea, 2, ',', '.'))	, 0, 1, 'C', true);
			}
		}
		
		//$pdf->SetFont('Arial', 'B', 9);
		//$pdf->SetFillColor(190, 190, 190);
		$pdf->Cell( 170,   5, '', 0, 1, 'L', false);
		$pdf->Cell( 70,   5, utf8_decode('Total') , 0, 0, 'L', true);
		$pdf->Cell( 30,   5, utf8_decode(number_format($totarea_geral,2,',','.')) , 0, 0, 'C', true);
		$pdf->Cell( 30,   5, utf8_decode($totprevisao_geral) , 0, 0, 'C', true);
		$pdf->Cell( 30,   5, utf8_decode($totproducao_geral) , 0, 0, 'C', true);
		if($totarea_geral>0)
		{
			$pdf->Cell( 30,   5, utf8_decode(number_format($totproducao_geral/$totarea_geral, 2, ',', '.')) , 0, 1, 'C', true);
		}
		else 
		{
			$pdf->Cell( 30,   5, utf8_decode(number_format(0, 2, ',', '.')) , 0, 1, 'C', true);	
		}
		
	} 
	
	$pdf->Output('reportes.pdf','I');
	
	function hex2rgb($hex) {
	   $hex = str_replace("#", "", $hex);
	
	   if(strlen($hex) == 3) 
	   {
	      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
	      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
	      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	   } 
	   else 
	   {
	      $r = hexdec(substr($hex,0,2));
	      $g = hexdec(substr($hex,2,2));
	      $b = hexdec(substr($hex,4,2));
	   }
	  
	   $rgb = array($r, $g, $b);
	   
	   //return implode(",", $rgb); // returns the rgb values separated by commas
	   return $rgb; 				// returns an array with the rgb values
	}
	
?>