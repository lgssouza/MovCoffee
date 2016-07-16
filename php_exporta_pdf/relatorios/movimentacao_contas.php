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
	
	if(isset($_GET['id_conta']))
	{
		$id_conta = $_GET['id_conta'];				
				
		if(empty($id_conta) && $id_conta == "undefined")		 			
		{					
			echo "houve um erro!";
			return false;							
		}
	}
	
	/** INICIO FILTROS **/
	$filtros = "where fk_id_conta = $id_conta";
	$filtroperiodode='';
	$filtroperiodoate='';
	
	if(isset($_GET["descricao"]))
	{
		if(!empty($_GET["descricao"]) && $_GET["descricao"] != "undefined" && $_GET["descricao"] != "nenhum")
		{
			$filtros = $filtros . " AND mov_contas_descricao  = '" . $_GET["descricao"] . "'";
		}
	}

	if(isset($_GET["data"]))
	{
		if(!empty($_GET["data"]) && $_GET["data"] != "undefined" && $_GET["data"] != "nenhum")
		{
			$filtros = $filtros . " AND mov_contas_data  >= '" . implode("-",array_reverse(explode("/",($_GET["data"])))) . "' ";
			$filtroperiodode = $_GET["data"];	
		}
	}
	
	if(isset($_GET["data2"]))
	{
		if(!empty($_GET["data2"]) && $_GET["data2"] != "undefined" && $_GET["data2"] != "nenhum")
		{
			$filtros = $filtros . " AND mov_contas_data  <= '" . implode("-",array_reverse(explode("/",($_GET["data2"])))) . "' ";
			$filtroperiodoate = $_GET["data2"];		
		}
	}
	
	/** FIM FILTROS **/
	
	/** Consultas **/
	$sql 	= "SELECT * FROM mov_contas $filtros order by mov_contas_data asc, id_mov_contas asc";
	
	$rs		= $mysqli->query($sql);
	
	$rs 	= $mysqli->query($sql);
	
	$sql2 	= "SELECT conta_saldo FROM tb_conta_saldo WHERE fk_id_conta = ".$id_conta;
	$rs2 	= $mysqli->query($sql2);
	
	$sql3 	= "SELECT * FROM tb_conta WHERE id_conta = ".$id_conta;
	$rs3 	= $mysqli->query($sql3);
	
	if($rs2)
	{		        
		$conta2 = $rs2->fetch_object();
		$rs2->close();
	}
	
	if($rs3)
	{		        
		$conta3 = $rs3->fetch_object();
		$rs3->close();
	}
	
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
	
	$pdf->Ln(15);
	$pdf->SetFont('Arial', '', 9);
	
	$varfiltro=10;
	
	if(!empty($filtroperiodode) && !empty($filtroperiodoate))
	{
		$pdf->Cell(190,   5, utf8_decode('Período: '.$filtroperiodode.' até '.$filtroperiodoate)			, 0, 1, 'L');
		$pdf->Ln(5);
		$varfiltro=1;
	}
	else if(!empty($filtroperiodode) && empty($filtroperiodoate))
	{
		$pdf->Cell(190,   5, utf8_decode('Período maior igual à: '.$filtroperiodode)					, 0, 1, 'L');
		$pdf->Ln(5);
		$varfiltro=1;
	}
	else if(empty($filtroperiodode) && !empty($filtroperiodoate))
	{
		$pdf->Cell(190,   5, utf8_decode('Período menor igual à: '.$filtroperiodoate)					, 0, 1, 'L');
		$pdf->Ln(5);
		$varfiltro=10;
	}
	
	$pdf->Ln($varfiltro);	
	$pdf->SetFont('Arial', 'B', 11);									  
	$pdf->Cell(190,   5, utf8_decode('RESUMO DE MOVIMENTAÇÃO BANCÁRIA - ' .$conta3->conta_banco .' ('.$conta3->conta_descricao.')'), 0, 1, "C");
	$pdf->Ln(5);	
	
	if(isset($conta2->conta_saldo))
	{
		if($conta2->conta_saldo!=0)
		{
			$saldoatual = 'R$ '.number_format($conta2->conta_saldo, 2, ',', '.');	
		}
		else
		{
			$saldoatual =  'R$ '.number_format(0, 2, ',', '.');
		}
	}
	else
	{
		$saldoatual =  'R$ '.number_format(0, 2, ',', '.');
	}
	
	
	
	/** Grid Produtores Cabeçalho **/
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->SetFillColor(190, 190, 190);
	
	//saldo atual
	$pdf->SetTextColor(190,0,0); #Seta a cor da fonte padrão RGB
	$pdf->Cell(162,   5, utf8_decode($saldoatual), 0, 1, "R");
	
	$pdf->SetTextColor(0, 0, 0); #Seta a cor da fonte padrão RGB
	
	//grid cabeçalho
	$pdf->Cell( 50,   5, utf8_decode('Descrição'), 0, 0, 'L', false);							
	$pdf->Cell(	30,   5, utf8_decode('Valor')		, 0, 0, 'L', false);	
	$pdf->Cell(	30,   5, utf8_decode('Tipo')	, 0, 0, 'C', false);	
	$pdf->Cell(	30,   5, utf8_decode('Data')	, 0, 0, 'C', false);	
	$pdf->Cell(	30,   5, utf8_decode('Saldo')	, 0, 0, 'L', false);
											
	$pdf->Ln(5);
	
	$pdf->Line(10, 30, 180, 30);
	
	/** Grid Corpo **/
	$backgroundtable = true;
	$pdf->SetFont('Arial', '', 9);
	
	if($rs)
	{
		$regs = array();

		while($reg = $rs->fetch_object())⁠⁠⁠⁠09:17⁠⁠⁠⁠⁠⁠⁠⁠⁠09:17⁠⁠⁠⁠⁠
		{
			array_push($regs, $reg);
		}
		
		$rs->close();
		
		$pdf->SetFillColor(190, 190, 190);
		
		for($i=0;$i<count($regs);$i++)
		{
			$valor = 0;
			if($regs[$i]->mov_contas_valor!=NULL)
			{
				$valor = 'R$ '.number_format($regs[$i]->mov_contas_valor, 2, ',', '.');
			}
			else
			{
				$valor = 'R$ '.number_format(0, 2, ',', '.');
				
			}

						
			$data = date('d/m/Y', strtotime($regs[$i]->mov_contas_data));
			
					
			$pdf->Cell( 50,   5, utf8_decode($regs[$i]->mov_contas_descricao)	, 0, 0, 'L', $backgroundtable);
			$pdf->Cell( 30,   5, utf8_decode($valor) , 0, 0, 'L', $backgroundtable);
			$pdf->Cell( 30,   5, utf8_decode($regs[$i]->mov_contas_op)	, 0, 0, 'C', $backgroundtable);
			$pdf->Cell( 30,   5, utf8_decode($data)	, 0, 0, 'C', $backgroundtable);
			$pdf->Cell( 30,   5, utf8_decode('R$ ' .number_format($regs[$i]->mov_contas_saldo, 2, ',', '.'))	, 0, 1, 'L', $backgroundtable);
			
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