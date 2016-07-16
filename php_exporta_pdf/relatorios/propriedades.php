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
	$sql 			= "select * from tb_propriedade
					   ORDER BY prop_nome";
	$rs	= $mysqli->query($sql);
	$sql 	 		= "";	 				
	
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
	$pdf->Cell(190,   5, utf8_decode('RELAÇÃO DE PROPRIEDADES'), 0, 1, "C");
	$pdf->Ln(5);	
	
	/** Grid Produtores Cabeçalho **/
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->SetFillColor(190, 190, 190);
	
	$pdf->Cell( 50,   5, utf8_decode('Propriedade')	, 0, 0, 'L', false);							
	$pdf->Cell(	30,   5, utf8_decode('IE')			, 0, 0, 'C', false);	
	$pdf->Cell(	35,   5, utf8_decode('Área Total')		, 0, 0, 'C', false);	
	$pdf->Cell(	30,   5, utf8_decode('Área Total Café')		, 0, 0, 'C', false);	
	$pdf->Cell(	30,   5, utf8_decode('Previsao Sacas')		, 0, 0, 'C', false);
													
	$pdf->Ln(5);
	
	$pdf->Line(10, 30, 185, 30);
		
	/** Grid Produtores Corpo **/
	$backgroundtable = true;
	$pdf->SetFont('Arial', '', 9);
	
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
			$pdf->Cell( 50,   5, utf8_decode($regs[$i]->prop_nome)	, 0, 0, 'L', $backgroundtable);
			$pdf->Cell( 30,   5, utf8_decode($regs[$i]->prop_ie), 0, 0, 'C', $backgroundtable);
			$pdf->Cell( 35,   5, utf8_decode(number_format($regs[$i]->prop_areatotal, 2, ',','.'))	, 0, 0, 'C', $backgroundtable);
			$pdf->Cell( 30,   5, utf8_decode(number_format($regs[$i]->prop_areatotalcafe, 2, ',','.'))	, 0, 0, 'C', $backgroundtable);
			$pdf->Cell( 30,   5, utf8_decode(number_format($regs[$i]->prop_previsaosacas, 2, ',','.'))	, 0, 1, 'C', $backgroundtable);
			
			if($backgroundtable)
			{
				$backgroundtable = false;
			}
			else
			{
				$backgroundtable = true;
			}
		}
		
		//$pdf->SetFont('Arial', 'B', 9);
		//$pdf->SetFillColor(190, 190, 190);
		//$pdf->Cell(190,   5, utf8_decode('Total de Produtores: '.count($regs)), 1, 0, 'L', true);
	} 
	
	$pdf->Output('reporte.pdf','I');
	
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