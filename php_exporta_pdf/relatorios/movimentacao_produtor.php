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
	
	if(isset($_GET['id_prod']))
	{
		$id_prod = $_GET['id_prod'];				
				
		if(empty($id_prod) && $id_prod == "undefined")		 			
		{					
			echo "houve um erro!";
			return false;							
		}
	}
	
	/** INICIO FILTROS **/
	$filtros = "where b.fk_id_prod = $id_prod";
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
	$sql 	= "SELECT * FROM mov_razao_produtos a 
	inner join mov_prop_prod b on a.fk_mov_pp = b.id_mov_pp 
	inner join tb_produtor c on b.fk_id_prod= c.id_prod 
	left join mov_venda d on d.fk_razao_produtos = a.id $filtros order by a.lote_apas asc";
	 
	$rs		= $mysqli->query($sql);
	
	$sql2 	= "SELECT prod_nome FROM tb_produtor WHERE id_prod = ".$id_prod;
	$rs2	= $mysqli->query($sql2);
	
	if($rs2)
	{		        
		$prod2 = $rs2->fetch_object();
		$rs2->close();
	}
	else 
	{
		echo "Nenhum produtor selecionado.";
		return false;
			
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
	$pdf->Cell(190,   5, utf8_decode('RESUMO DE MOVIMENTAÇÃO PRODUTOR'), 0, 1, "C");
	$pdf->Cell(195,   5, utf8_decode($prod2->prod_nome), 0, 1, "C");
	
	$pdf->Ln(5);	
	
	
	/** Grid Produtores Cabeçalho **/
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->SetFillColor(190, 190, 190);
	
	//grid cabeçalho
	$pdf->Cell( 30,   5, utf8_decode('Data'), 0, 0, 'L', false);							
	$pdf->Cell(	30,   5, utf8_decode('Lote APAS')		, 0, 0, 'C', false);
	$pdf->Cell(	30,   5, utf8_decode('Lote OUTROS')		, 0, 0, 'C', false);
	$pdf->Cell(	20,   5, utf8_decode('Bebida')	, 0, 0, 'C', false);		
	$pdf->Cell(	20,   5, utf8_decode('Tipo')	, 0, 0, 'C', false);	
	$pdf->Cell(	30,   5, utf8_decode('Qtd')	, 0, 0, 'C', false);	
	$pdf->Cell(	30,   5, utf8_decode('Saldo')	, 0, 0, 'L', false);
											
	$pdf->Ln(5);
	
	$pdf->Line(10, 30, 180, 30);
	
	/** Grid Corpo **/
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
			
			$data = date('d/m/Y', strtotime($regs[$i]->data));
			
					
			$pdf->Cell( 30,   5, utf8_decode($data)	, 0, 0, 'L', $backgroundtable);
			$pdf->Cell( 30,   5, utf8_decode($regs[$i]->lote_apas) , 0, 0, 'C', $backgroundtable);
			$pdf->Cell( 30,   5, utf8_decode($regs[$i]->lote_coperativa) , 0, 0, 'C', $backgroundtable);
			$pdf->Cell( 20,   5, utf8_decode($regs[$i]->bebida)	, 0, 0, 'C', $backgroundtable);
			$pdf->Cell( 20,   5, utf8_decode($regs[$i]->tipo_movimentacao)	, 0, 0, 'C', $backgroundtable);
			$pdf->Cell( 30,   5, utf8_decode(number_format($regs[$i]->quantidade,2,',','.')) , 0, 0, 'C', $backgroundtable);
			$pdf->Cell( 30,   5, utf8_decode(number_format($regs[$i]->saldo_produtor_propriedade, 2, ',', '.'))	, 0, 1, 'L', $backgroundtable);
			
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