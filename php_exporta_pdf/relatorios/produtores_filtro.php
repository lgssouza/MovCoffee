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
	$filtros = "where 1 = 1";
	
	if(isset($_GET["id_prod"]))
	{
		if(!empty($id_prod) && $id_prod != "undefined" && $id_prod != "nenhum")
		{
			$filtros = $filtros . " AND id_prod  = '" . $id_prod . "'";
		}
	}	
	
	/** FIM FILTROS **/
	
	/** Consultas **/
	$sql 			= "SELECT * FROM tb_produtor $filtros
					   ORDER BY prod_nome";
	$rsprodutores	= $mysqli->query($sql);
	
	$sql2 = "SELECT c.prop_nome,c.prop_ie, c.prop_areatotal, c.prop_areatotalcafe, 
	c.prop_previsaosacas, b.mov_pp_percentual, b.mov_pp_sacasreal, b.mov_pp_areareal 
	FROM tb_produtor a INNER JOIN mov_prop_prod b on a.id_prod = b.fk_id_prod 
	INNER JOIN tb_propriedade c on c.id_prop = b.fk_id_prop	 
	$filtros ORDER BY prod_nome, prop_nome";
	
	$rspropriedades	= $mysqli->query($sql2);
	
	/** Ínicia Instancia Classe FPDF **/
	$pdf = new FPDF();
	
	/** Adiciona uma Nova Pagina no Relatório **/
	$pdf->AddPage();
	
	/** Ínicio Cabeçalho **/
	$pdf->Image('../../assets/admin/layout2/img/logo_apas.png' , 10 ,8, 30 , 13,'PNG');
	
	$pdf->Ln(10);	
	$pdf->SetFont('Arial', 'B', 11);									  
	$pdf->Cell(190,   5, utf8_decode('DADOS DE PRODUTOR'), 0, 1, "C");
	$pdf->Ln(5);	
	
	/** Grid Produtores Cabeçalho **/	
	$pdf->SetFillColor(190, 190, 190);
	$pdf->Ln(5);
	
	$pdf->Line(10, 30, 200, 30);
		
	/** Grid Produtores Corpo **/
	$backgroundtable = true;
	$pdf->SetFont('Arial', 'B', 9);
	
	if($rsprodutores)
	{
		$produtores = array();

		while($produtor = $rsprodutores->fetch_object())
		{
			array_push($produtores, $produtor);
		}
		
		$rsprodutores->close();
		
		$propriedades = array();

		while($propriedade = $rspropriedades->fetch_object())
		{
			array_push($propriedades, $propriedade);
		}
		
		$rspropriedades->close();
		
		
		
		
		$pdf->SetFillColor(190, 190, 190);
		
		for($i=0;$i<count($produtores);$i++)
		{
			$pdf->Cell( 50,   5, utf8_decode('Produtor')	, 0, 0, 'L', false);							
			$pdf->Cell(	30,   5, utf8_decode('CPF')			, 0, 0, 'C', false);
			$pdf->Cell(	30,   5, utf8_decode('Telefone')		, 0, 0, 'C', false);	
			$pdf->Cell(	30,   5, utf8_decode('E-mail')		, 0, 1, 'L', false);
			
			$pdf->SetFont('Arial', '', 9);	
			$pdf->Cell( 50,   5, utf8_decode($produtores[$i]->prod_nome)	, 0, 0, 'L', false);
			$pdf->Cell( 30,   5, utf8_decode($produtores[$i]->prod_cpf)		, 0, 0, 'C', false);
			$pdf->Cell( 30,   5, utf8_decode($produtores[$i]->prod_telefone)		, 0, 0, 'C', false);
			$pdf->Cell( 30,   5, utf8_decode($produtores[$i]->prod_email)		, 0, 1, 'L', false);
			
			$pdf->SetFont('Arial', 'B', 9);
			$pdf->Cell(	75,   5, utf8_decode('Rua')		, 0, 0, 'L', false);
			$pdf->Cell(	10,   5, utf8_decode('Nº')		, 0, 0, 'L', false);
			$pdf->Cell(	30,   5, utf8_decode('Bairro')		, 0, 0, 'L', false);
			$pdf->Cell(	35,   5, utf8_decode('Cidade')		, 0, 0, 'L', false);
			$pdf->Cell(	30,   5, utf8_decode('UF')		, 0, 0, 'C', false);
			$pdf->Cell(	20,   5, utf8_decode('CEP')		, 0, 1, 'L', false);
			
			$pdf->SetFont('Arial', '', 9);
			$pdf->Cell( 75,   5, utf8_decode($produtores[$i]->prod_rua)	, 0, 0, 'L', false);
			$pdf->Cell( 10,   5, utf8_decode($produtores[$i]->prod_numero)		, 0, 0, 'L', false);
			$pdf->Cell( 30,   5, utf8_decode($produtores[$i]->prod_bairro)		, 0, 0, 'L', false);
			$pdf->Cell( 35,   5, utf8_decode($produtores[$i]->prod_cidade)		, 0, 0, 'L', false);
			$pdf->Cell( 30,   5, utf8_decode($produtores[$i]->prod_estado)	, 0, 0, 'C', false);
			$pdf->Cell( 20,   5, utf8_decode($produtores[$i]->prod_cep)		, 0, 1, 'L', false);
			
			$pdf->SetFont('Arial', 'B', 9);
			$pdf->Cell(	35,   5, utf8_decode('Banco')		, 0, 0, 'L', false);	
			$pdf->Cell(	30,   5, utf8_decode('Agência')		, 0, 0, 'C', false);	
			$pdf->Cell(	30,   5, utf8_decode('Conta')		, 0, 0, 'C', false);
			$pdf->Cell(	30,   5, utf8_decode('Descrição Conta')		, 0, 1, 'L', false);
			
			$pdf->SetFont('Arial', '', 9);
			$pdf->Cell( 35,   5, utf8_decode($produtores[$i]->prod_banco)	, 0, 0, 'L', false);
			$pdf->Cell( 30,   5, utf8_decode($produtores[$i]->prod_agencia)	, 0, 0, 'C', false);
			$pdf->Cell( 30,   5, utf8_decode($produtores[$i]->prod_conta)	, 0, 0, 'C', false);
			$pdf->Cell( 30,   5, utf8_decode($produtores[$i]->prod_conta_descricao)	, 0, 0, 'L', false);
	
		}

		$pdf->Ln(10);	
		$pdf->SetFont('Arial', 'B', 11);									  
		$pdf->Cell(190,   5, utf8_decode('PROPRIEDADES DO PRODUTOR'), 0, 1, "C");
		$pdf->Ln(5);	

		for($i=0;$i<count($propriedades);$i++)
		{
			$pdf->SetFont('Arial', 'B', 9);
			$pdf->Cell( 80,   5, utf8_decode('Propriedade')	, 0, 0, 'L', false);							
			$pdf->Cell(	30,   5, utf8_decode('IE')			, 0, 0, 'C', false);
			$pdf->Cell(	15,   5, utf8_decode('Percentual')		, 0, 1, 'C', false);			
			
			
			$pdf->SetFont('Arial', '', 9);	
			$pdf->Cell( 80,   5, utf8_decode($propriedades[$i]->prop_nome)	, 0, 0, 'L', false);
			$pdf->Cell( 30,   5, utf8_decode($propriedades[$i]->prop_ie)		, 0, 0, 'C', false);
			$pdf->Cell( 15,   5, utf8_decode($propriedades[$i]->mov_pp_percentual)		, 0, 1, 'C', false);
			
			$pdf->SetFont('Arial', 'B', 9);
			$pdf->Cell(	50,   5, utf8_decode('Área Total de Café')			, 0, 0, 'L', false);	
			$pdf->Cell(	50,   5, utf8_decode('Previsão Total de Sacas')			, 0, 0, 'C', false);			
			$pdf->Cell(	50,   5, utf8_decode('Área Real de Café')			, 0, 0, 'C', false);
			$pdf->Cell(	50,   5, utf8_decode('Previsão Real de Sacas')			, 0, 1, 'C', false);
			
			$pdf->SetFont('Arial', '', 9);	
			$pdf->Cell( 50,   5, utf8_decode($propriedades[$i]->prop_areatotalcafe)	, 0, 0, 'L', false);
			$pdf->Cell( 50,   5, utf8_decode($propriedades[$i]->prop_previsaosacas)		, 0, 0, 'C', false);
			$pdf->Cell( 50,   5, utf8_decode($propriedades[$i]->mov_pp_areareal)		, 0, 0, 'C', false);
			$pdf->Cell( 50,   5, utf8_decode($propriedades[$i]->mov_pp_sacasreal)		, 0, 1, 'C', false);			
			$pdf->Ln(5);	
		}
		
		//$pdf->SetFont('Arial', 'B', 9);
		//$pdf->SetFillColor(190, 190, 190);
		//$pdf->Cell(190,   5, utf8_decode('Total de Produtores: '.count($produtores)), 1, 0, 'L', true);
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