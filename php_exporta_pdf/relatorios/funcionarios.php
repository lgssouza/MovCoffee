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
	$sql 			= "SELECT * FROM tb_funcionario
					   ORDER BY func_nome";
	$rsprodutores	= $mysqli->query($sql);
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
	$pdf->Cell(190,   5, utf8_decode('RELAÇÃO DE FUNCIONÁRIOS'), 0, 1, "C");
	$pdf->Ln(5);	
	
	/** Grid Produtores Cabeçalho **/
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->SetFillColor(190, 190, 190);
													
	$pdf->Ln(5);
	
	$pdf->Line(10, 30, 190, 30);
		
	/** Grid Produtores Corpo **/
	
	if($rsprodutores)
	{
		$funcionarios = array();

		while($funcionario = $rsprodutores->fetch_object())
		{
			array_push($funcionarios, $funcionario);
		}
		
		$rsprodutores->close();
		
		$pdf->SetFillColor(190, 190, 190);
		
		for($i=0;$i<count($funcionarios);$i++)
		{
			$pdf->SetFont('Arial', 'B', 9);
			$pdf->Cell( 50,   5, utf8_decode('Funcionário')	, 0, 0, 'L', false);							
			$pdf->Cell(	30,   5, utf8_decode('CPF')			, 0, 0, 'C', false);	
			$pdf->Cell(	35,   5, utf8_decode('Data Nasc.')		, 0, 0, 'C', false);	
			$pdf->Cell(	30,   5, utf8_decode('Telefone')		, 0, 0, 'C', false);	
			$pdf->Cell(	30,   5, utf8_decode('E-mail')		, 0, 1, 'L', false);	
			
			$pdf->SetFont('Arial', '', 9);
			$pdf->Cell( 50,   5, utf8_decode($funcionarios[$i]->func_nome)	, 0, 0, 'L', false);
			$pdf->Cell( 30,   5, utf8_decode($funcionarios[$i]->func_cpf)		, 0, 0, 'C', false);
			$pdf->Cell( 35,   5, utf8_decode($funcionarios[$i]->func_nasc)	, 0, 0, 'C', false);
			$pdf->Cell( 30,   5, utf8_decode($funcionarios[$i]->func_telefone)	, 0, 0, 'C', false);
			$pdf->Cell( 30,   5, utf8_decode($funcionarios[$i]->func_email)	, 0, 1, 'L', false);
			
			$pdf->SetFont('Arial', 'B', 9);
			$pdf->Cell(	50,   5, utf8_decode('Rua')	, 0, 0, 'L', false);
			$pdf->Cell(	20,   5, utf8_decode('Número')	, 0, 0, 'L', false);
			$pdf->Cell(	50,   5, utf8_decode('Bairro')	, 0, 0, 'L', false);
			$pdf->Cell(	50,   5, utf8_decode('Cidade')	, 0, 1, 'L', false);
	
			$pdf->SetFont('Arial', '', 9);
			$pdf->Cell( 50,   5, utf8_decode($funcionarios[$i]->func_rua)	, 0, 0, 'L', false);
			$pdf->Cell( 20,   5, utf8_decode($funcionarios[$i]->func_numero)	, 0, 0, 'L', false);
			$pdf->Cell( 50,   5, utf8_decode($funcionarios[$i]->func_bairro)	, 0, 0, 'L', false);
			$pdf->Cell( 50,   5, utf8_decode($funcionarios[$i]->func_cidade)	, 0, 1, 'L', false);
			$pdf->Ln(3);			
		
			
		}
		
		//$pdf->SetFont('Arial', 'B', 9);
		//$pdf->SetFillColor(190, 190, 190);
		//$pdf->Cell(190,   5, utf8_decode('Total de Produtores: '.count($funcionarios)), 1, 0, 'L', true);
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