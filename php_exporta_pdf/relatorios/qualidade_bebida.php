<?php

	date_default_timezone_set('America/Sao_Paulo');
	
	include_once('../../verificaLogin.php');
	require_once('../fpdf.php');
	require_once('../../connect.php');
	require_once('../phplot/phplot.php');
		
	/** Ínicia Instancia Classe FPDF **/
	$pdf = new FPDF();
	
	/** Adiciona uma Nova Pagina no Relatório **/
	$pdf->addPage();
	
	/** Ínicio Cabeçalho **/
	$pdf->Image('../../assets/admin/layout2/img/logo_apas.png' , 10 ,8, 30 , 13,'PNG');
	
	$sql 		= "SELECT bebida, sum(quantidade) as qtd FROM `mov_razao_produtos` where tipo_movimentacao = 'E' group by bebida";
	
	$rs		= $mysqli->query($sql);
	if($rs)
	{
		$data = array();
		$arr = array();
			
		while($reg = $rs->fetch_object())
		{
			array_push($data, $reg->bebida);
			$new_arr = array('', $reg->qtd);
			$arr[] = $new_arr;
		}
		
		$rs->close();
	}
	else 
	{
		echo "nada";
		return false;	
	}
	
	$pdf->Ln(10);	
	$pdf->SetFont('Arial', 'B', 11);		
	$pdf->Cell(190,   5, utf8_decode('RESUMO BEBIDA'), 0, 1, "C");
	$pdf->Ln(5);	
	
	/* INICIO GRAFICO */
	$plot = new PHPlot(600,600);
	$plot->SetImageBorderType('plain');
	$plot->SetDataType('text-data-single');
	$plot->SetFileFormat("png");
	$plot->SetDataValues($arr);
	$plot->SetPlotType('pie');
	$plot->SetTitle('Qualidade Bebida APAS');
	$colors = array('red', 'green', 'blue', 'yellow', 'cyan','gray','white','gold','violet','orange','tan','navy','pink','grey','aquamarine1','azure1','beige','blue','brown','cyan','gold','gray','green','grey','ivory','lavender','magenta','maroon','navy','orange','orchid','peru','pink','plum','purple','red','salmon','snow','tan','violet','wheat','white','yellow');
	$plot->SetDataColors($colors);
	$plot->SetLegend($data);
	$plot->SetShading(0);
	$plot->SetLabelScalePosition(0.2);
	//ignora a saida para o browser e permite a saida em arquivo
	$plot->SetIsInline(true);
	$plot->SetOutputFile('grafico_bebiba.png');
	$plot->DrawGraph();
	/* FIM GRAFICO */
	
	$pdf->Image('grafico_bebiba.png',30,40,140);
	
	$pdf->Output('reportes.pdf','I');
	
	
?>