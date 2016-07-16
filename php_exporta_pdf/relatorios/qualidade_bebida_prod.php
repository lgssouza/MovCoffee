<?php

	date_default_timezone_set('America/Sao_Paulo');
	
	include_once('../../verificaLogin.php');
	require_once('../fpdf.php');
	require_once('../../connect.php');
	require_once('../phplot/phplot.php');
		
	$filtros = "where a.tipo_movimentacao = 'E'";
	
	if(isset($_GET["produtor"]))
	{
		if(!empty($_GET["produtor"]) && $_GET["produtor"] != "undefined" && $_GET["produtor"] != "nenhum")
		{
			$filtros = $filtros . " AND b.fk_id_prod = '" . $_GET["produtor"] . "'";
		}
	}
		
	/** Ínicia Instancia Classe FPDF **/
	$pdf = new FPDF();
	
	/** Adiciona uma Nova Pagina no Relatório **/
	$pdf->addPage();
	
	/** Ínicio Cabeçalho **/
	$pdf->Image('../../assets/admin/layout2/img/logo_apas.png' , 10 ,8, 30 , 13,'PNG');
	
	$sql 		= "SELECT a.bebida, sum(a.quantidade) as qtd , c.prod_nome FROM mov_razao_produtos a inner join mov_prop_prod b on a.fk_mov_pp = b.id_mov_pp inner join tb_produtor c on b.fk_id_prod= c.id_prod $filtros group by a.bebida";
	
	$rs		= $mysqli->query($sql);
	if($rs)
	{
		$data 		= array();
		$arr 		= array();
		$produtor	='';
		
		while($reg = $rs->fetch_object())
		{
			$produtor = $reg->prod_nome;			
			array_push($data, $reg->bebida);			
			$new_arr = array('', $reg->qtd);			
			$arr[] = $new_arr;
		}
		
		$rs->close();
	}
	
	$pdf->Ln(10);	
	$pdf->SetFont('Arial', 'B', 11);		
	$pdf->Cell(190,   5, utf8_decode('RESUMO BEBIDA POR PRODUTOR - '.$produtor), 0, 1, "C");
	
	$pdf->Ln(5);
	
	if (!empty($arr)) 
	{
    
		/* INICIO GRAFICO */
		$plot = new PHPlot(600,600);
		$plot->SetImageBorderType('plain');
		$plot->SetDataType('text-data-single');
		$plot->SetFileFormat("png");
		$plot->SetDataValues($arr);
		$plot->SetPlotType('pie');
		$plot->SetTitle('Qualidade Bebida do Produtor');
		$colors = array('red', 'green', 'blue', 'yellow', 'cyan','gray','white','gold','violet','orange','tan','navy','pink','grey','aquamarine1','azure1','beige','blue','brown','cyan','gold','gray','green','grey','ivory','lavender','magenta','maroon','navy','orange','orchid','peru','pink','plum','purple','red','salmon','snow','tan','violet','wheat','white','yellow');
		$plot->SetDataColors($colors);
		$plot->SetLegend($data);
		$plot->SetShading(0);
		$plot->SetLabelScalePosition(0.2);
		//ignora a saida para o browser e permite a saida em arquivo
		$plot->SetIsInline(true);
		$plot->SetOutputFile('grafico_bebiba_prod.png');
		$plot->DrawGraph();
		/* FIM GRAFICO */
	
		$pdf->Image('grafico_bebiba_prod.png',30,40,140);
		
	} 
	else 
	{
	    $pdf->Cell(190,   5, utf8_decode('Não existe nenhum lançamento para este produtor'), 0, 1, "C");
	}	
	
	$pdf->Output('reportes.pdf','I');
	
	
?>