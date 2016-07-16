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
	$sql 			= "SELECT * FROM tb_conta";
	$rs	= $mysqli->query($sql);
	$sql 	 		= "";	 				
	
	/** Ínicia Instancia Classe FPDF **/
	$pdf = new FPDF();
	
	/** Adiciona uma Nova Pagina no Relatório **/
	$pdf->AddPage();
	
	/** Ínicio Cabeçalho **/
	$pdf->Image('../../assets/admin/layout2/img/logo_apas.png' , 10 ,8, 30 , 13,'PNG');
	
	$pdf->Ln(10);	
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(190,   5, utf8_decode('RELAÇÃO DE CONTAS'), 0, 1, "C");
	$pdf->Ln(5);	
	
	/** Grid Produtores Cabeçalho **/
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->SetFillColor(190, 190, 190);
	
	$pdf->Cell( 50,   5, utf8_decode('Banco')	, 0, 0, 'L', false);							
	$pdf->Cell(	30,   5, utf8_decode('Agência')			, 0, 0, 'C', false);	
	$pdf->Cell(	30,   5, utf8_decode('Conta')		, 0, 0, 'C', false);	
	$pdf->Cell(	50,   5, utf8_decode('Descrição')		, 0, 0, 'L', false);	
	$pdf->Cell(	30,   5, utf8_decode('Tipo')		, 0, 1, 'C', false);
														
	$pdf->Ln(5);
	
	$pdf->Line(10, 30, 200, 30);
		
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
			$pdf->Cell( 50,   5, utf8_decode($regs[$i]->conta_banco)	, 0, 0, 'L', $backgroundtable);
			$pdf->Cell( 30,   5, utf8_decode($regs[$i]->conta_agencia)		, 0, 0, 'C', $backgroundtable);
			$pdf->Cell( 30,   5, utf8_decode($regs[$i]->conta_numero)	, 0, 0, 'C', $backgroundtable);
			$pdf->Cell( 50,   5, utf8_decode($regs[$i]->conta_descricao)	, 0, 0, 'L', $backgroundtable);
			
			$vartipo='';
			if($regs[$i]->conta_premio==1)
			{
				$vartipo='Prêmio';
			}
			else if($regs[$i]->conta_outros==1)
			{
				$vartipo='Outros';
			}
			else
			{
				$vartipo='Padrão';	
			}
			
			$pdf->Cell( 30,   5, utf8_decode($vartipo)	, 0, 1, 'C', $backgroundtable);
			
			if($backgroundtable)
			{
				$backgroundtable = false;
			}
			else
			{
				$backgroundtable = true;
			}
		}
		
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