<?php 

	set_time_limit(0);
	date_default_timezone_set('America/Sao_Paulo');
		
	include_once('../../verificaLogin.php');
	include_once('../../verificapermissao.php');

	//if(!verificapermissao("Funcionários",$permissoes))
	//{
	//	header("Location: home.php");  
	//	exit;
	//}	
	
	include_once('../../connect.php');
	
	global $mysqli;
	
	if (!$mysqli->set_charset("utf8")) 
	{
		printf("Error loading character set utf8: %s\n", $mysqli->error);
	}
	
	global $mysqli;
	
	if(isset($_GET['ano']))
	{
		$ano = $_GET['ano'];		
	}
	else 
	{
		exit;
	}
	
	//buscando saldo inicial das duas contas
	$sql_saldo_p = "select a.*, b.conta_premio, b.conta_outros 
	from mov_contas a 
	inner join tb_conta b on a.fk_id_conta = b.id_conta 
	WHERE b.conta_premio = 1 
	and a.mov_contas_op = 'E' 
	and year(a.mov_contas_data) = $ano 
	order by a.mov_contas_data asc, a.id_mov_contas 
	limit 0,1";
	
	$sql_saldo_o = "select a.*, b.conta_premio, b.conta_outros 
	from mov_contas a 
	inner join tb_conta b on a.fk_id_conta = b.id_conta 
	WHERE b.conta_outros = 1 
	and a.mov_contas_op = 'E' 
	and year(a.mov_contas_data) = $ano 
	order by a.mov_contas_data asc, a.id_mov_contas 
	limit 0,1";
	
	$sql_totais ="SELECT 
	round(sum(a.grup_premio_orcado),2) as premio_orcado, 
	round(sum(a.grup_premio_despesas),2) as premio_despesas, 
	round(sum(a.grup_premio_saldo),2) as premio_saldo, 
	round(sum(a.grup_outros_orcado),2) as outros_orcado,  
	round(sum(a.grup_outros_despesas),2) as outros_despesas,  
	round(sum(a.grup_outros_saldo),2) as outros_saldo, 
	round(sum(a.grup_total_orcado),2) as total_orcado, 
	round(sum(a.grup_total_despesas),2) as total_despesas, 
	round(sum(a.grup_total_saldo),2) as total_saldo 
	FROM tb_pdcj_grupo_rel_valores a where a.grup_ano = $ano";
	
	$rs_saldo_p = $mysqli->query($sql_saldo_p);
	$rs_saldo_o = $mysqli->query($sql_saldo_o);
	$rs_totais = $mysqli->query($sql_totais);
	
	
	$saldoinicial_p = 0;
	$saldoinicial_o = 0;
	$linha_conta_despesas	= 0;
	$linha_conta_saldo		= 0;
	
	if($rs_saldo_p)
	{
		$saldo_p				= $rs_saldo_p->fetch_object();
		$saldoinicial_p			= $saldo_p->mov_contas_saldo - $saldo_p->mov_contas_valor;
		$rs_saldo_p->close();
	}
	else 
	{
		$saldoinicial_p		= 0;
	}
	
	if($rs_saldo_o)
	{
		$saldo_o				= $rs_saldo_o->fetch_object();
		$saldoinicial_o			= $saldo_o->mov_contas_saldo - $saldo_o->mov_contas_valor;
		$rs_saldo_o->close();
	}
	else 
	{
		$saldoinicial_o		= 0;
	}
	
	if($rs_totais)
	{
		$totais					= $rs_totais->fetch_object();
		
		$premio_orcado			= $totais->premio_orcado;
		$premio_despesas		= $totais->premio_despesas;
		$premio_saldo			= $totais->premio_saldo;
		$outros_orcado			= $totais->outros_orcado;
		$outros_despesas		= $totais->outros_despesas;
		$outros_saldo			= $totais->outros_saldo;
		$total_orcado			= $totais->total_orcado;
		$total_despesas			= $totais->total_despesas;
		$total_saldo			= $totais->total_saldo;
		
		$rs_totais->close();
	}
	else 
	{
		$premio_orcado			= 0;
		$premio_despesas		= 0;
		$premio_saldo			= 0;
		$outros_orcado			= 0;
		$outros_despesas		= 0;
		$outros_saldo			= 0;
		$total_orcado			= 0;
		$total_despesas			= 0;
		$total_saldo			= 0;
	}
	
	$total_perc1 = 0;
	$total_perc2 = 0;
	$total_perc3 = 0;
	
	$sql_p = "select * from tb_pdcj_recursos a
	WHERE year(a.recursos_data) = $ano 
	order by a.recursos_data asc";
	
	$rs_p = $mysqli->query($sql_p);
	
	$sql = "SELECT * 
			FROM tb_pdcj_subgrupo_rel_valores A
			INNER JOIN tb_pdcj_subgrupo_rel B ON A.fk_id_subg = B.id_subgrupo
			INNER JOIN tb_pdcj_grupo_rel C ON B.fk_id_grupo = C.id_grupo
			INNER JOIN tb_pdcj_grupo_rel_valores D ON  C.id_grupo = d.fk_id_grupo AND D.grup_ano = A.subg_ano	
			LEFT JOIN tb_pdcj_item_rel E ON E.fk_id_subg = A.id_subgrupo_valores
			WHERE A.subg_ano = $ano
			ORDER BY C.id_grupo ASC, B.id_subgrupo ASC, E.item_data ASC";
	
	$rs = $mysqli->query($sql);

	if($rs)
	{
		$regs1 = array();
        
		while($reg1 = $rs->fetch_object())
		{
			array_push($regs1, $reg1);
		}
		
		$rs->close();
		
		if(count($regs1) > 0)
		{			
			// Incluimos a classe PHPExcel
			include_once('..\Classes\PHPExcel.php');
			
			// Instanciamos a classe
			$objPHPExcel = new PHPExcel();
			
			// Podemos configurar diferentes larguras para as colunas como padrão
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(50);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(28);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(28);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(28);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
						
			
			// Defini linha inicial
			$linha = 0;
						
			//CABEÇALHO
			seta_cor_cedula($objPHPExcel,'A'.($linha+1).':D'.($linha+1),'9BC2E6');						
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, ($linha+1), "PLANO DE DESENVOLVIMENTO DO COMÉRCIO JUSTO:  SEÇÃO A  PLANEJAMENTO");
			$objPHPExcel->getActiveSheet()->mergeCells('A'.($linha+1).':D'.($linha+1));
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+1).':D'.($linha+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			
			
			$linha=$linha+2;		
						
			$objPHPExcel->getActiveSheet()->getRowDimension($linha+1)->setRowHeight(19.5);
			seta_cor_cedula($objPHPExcel,'A'.($linha+1).':D'.($linha+1),'A6A6A6');
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+1))->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+1))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+1))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);	
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+1))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		
			$objPHPExcel->getActiveSheet()->getStyle('B'.($linha+1))->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle('B'.($linha+1))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle('B'.($linha+1))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle('B'.($linha+1))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);					
		
			$objPHPExcel->getActiveSheet()->getStyle('C'.($linha+1).':D'.($linha+1))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+1).':D'.($linha+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+1).':D'.($linha+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+1).':D'.($linha+1))->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+1).':D'.($linha+1))->getFont()->setBold(true);
				
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("A".($linha+1), "Período de Vigência do Plano: ")
						->setCellValue("B".($linha+1), "Período")
		            	->setCellValue("C".($linha+1), '01.01.'.$ano.' à 31.12.'.$ano)
		            	->setCellValue("D".($linha+1), "");
			
			seta_cor_cedula($objPHPExcel,'A'.($linha+2).':D'.($linha+2),'A6A6A6');
			$objPHPExcel->getActiveSheet()->getStyle('B'.($linha+2).':D'.($linha+2))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('B'.($linha+2).':D'.($linha+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("A".($linha+2), "RECURSOS FINANCEIROS")
						->setCellValue("B".($linha+2), "PRÊMIO")
		            	->setCellValue("C".($linha+2), "OUTROS")
		            	->setCellValue("D".($linha+2), "");
						
			$linha+=1;
		            	
						
			if($rs_p)
			{
				$regs_p = array();
		        
				while($reg_p = $rs_p->fetch_object())
				{
					array_push($regs_p, $reg_p);
				}
				
				$rs_p->close();
				
				$first		 			= false;
				$zebra		 			= false;
				$first_line   			= $linha+2;
				$linha 					= $linha+3;
				$vartotal				= 0;
				$vartotalrecursos		= 0;
				$varsubtotal_p			= 0;
				$varsubtotal_o			= 0;
				$totalprogressbar 		= count($regs1)+count($regs_p);
				$registroprogressbar	= 0;
									
				for($y=0;$y<count($regs_p);$y++)
				{
					//Alimenta Progresso AJAX
					$registroprogressbar = $registroprogressbar + 1;
					echo '|' . round(($registroprogressbar / $totalprogressbar) * 100);
					usleep(1);
				    ob_flush();
				    flush();
									
					$varid = $regs_p[$y]->id_mov_contas;
					
					$objPHPExcel->getActiveSheet()->getRowDimension($linha)->setRowHeight(15);
															
					$objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':D'.$linha)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$first_line.':D'.$first_line)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					
					$objPHPExcel->getActiveSheet()->getStyle('C'.$linha.':D'.$linha)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
															
					if($first==false)
					{
							
						$objPHPExcel->getActiveSheet()->getStyle('A'.($first_line).':D'.($first_line))->getAlignment()->setWrapText(true);
						seta_cor_cedula($objPHPExcel,'A'.($first_line).':D'.($first_line),'FFFF00');
						$objPHPExcel->getActiveSheet()->getStyle('B'.$first_line.':B'.$first_line)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle('B'.$first_line.':B'.$first_line)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle("B".($first_line))->getNumberFormat()->setFormatCode("R$ #,##0.00");
						
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, ($first_line), "SALDO INICIAL - PRÊMIO (01/01/".$ano.')');
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, ($first_line), $saldoinicial_p);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($first_line), "");
						
						
						$objPHPExcel->getActiveSheet()->getStyle('A'.($first_line+1).':D'.($first_line+1))->getAlignment()->setWrapText(true);
						seta_cor_cedula($objPHPExcel,'A'.($first_line+1).':D'.($first_line+1),'92D050');
						$objPHPExcel->getActiveSheet()->getStyle('A'.($first_line+1).':D'.($first_line+1))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('C'.($first_line+1).':C'.($first_line+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle('C'.($first_line+1).':C'.($first_line+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle("C".($first_line+1))->getNumberFormat()->setFormatCode("R$ #,##0.00");
						
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, ($first_line+1), "SALDO INICIAL - OUTROS RECURSOS (01/01/".$ano.')');
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, ($first_line+1), "");
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($first_line+1), $saldoinicial_o);
						
						$vartotal = $saldoinicial_o + $saldoinicial_p;
							
						$first=true;
						
						$objPHPExcel->getActiveSheet()->getStyle('A'.($first_line+2).':D'.($first_line+2))->getAlignment()->setWrapText(true);
						seta_cor_cedula($objPHPExcel,'A'.($first_line+2).':D'.($first_line+2),'A6A6A6');
						$objPHPExcel->getActiveSheet()->getStyle('A'.($first_line+2).':D'.($first_line+2))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('D'.($first_line+2).':D'.($first_line+2))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle('D'.($first_line+2).':D'.($first_line+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle("D".($first_line+2))->getNumberFormat()->setFormatCode("R$ #,##0.00");						
						
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("A".($first_line+2), "TOTAL DE SALDO INICIAL")
						->setCellValue("B".($first_line+2), "")
		            	->setCellValue("C".($first_line+2), "")
		            	->setCellValue("D".($first_line+2), $vartotal);
						
						$objPHPExcel->getActiveSheet()->getStyle('A'.($first_line+3).':D'.($first_line+3))->getAlignment()->setWrapText(true);
						seta_cor_cedula($objPHPExcel,'A'.($first_line+3).':D'.($first_line+3),'A6A6A6');
						$objPHPExcel->getActiveSheet()->getStyle('A'.($first_line+3).':D'.($first_line+3))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
												
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("A".($first_line+3), "")
						->setCellValue("B".($first_line+3), "")
		            	->setCellValue("C".($first_line+3), "")
		            	->setCellValue("D".($first_line+3), "ESTIMATIVA DE ENTRADA DE RECURSOS");
						
						$linha+=3;
						
					}
					if($zebra==false)
					{
						seta_cor_cedula($objPHPExcel,'A'.($linha).':D'.($linha),'FFFF00');
						$zebra=true;
					}
					else 
					{
						seta_cor_cedula($objPHPExcel,'A'.($linha).':D'.($linha),'92D050');
						$zebra=false;
						
					}
					$objPHPExcel->getActiveSheet()->getStyle('A'.($linha).':D'.($linha))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('B'.($linha).':D'.($linha))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('B'.($linha).':D'.($linha))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle("D".($linha))->getNumberFormat()->setFormatCode("R$ #,##0.00");
					
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, ($linha), $regs_p[$y]->recursos_descricao);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, ($linha), $regs_p[$y]->recursos_valor);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($linha), $regs_p[$y]->recursos_qtd);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, ($linha), $regs_p[$y]->recursos_valor * $regs_p[$y]->recursos_qtd);
					
					$vartotalrecursos = $vartotalrecursos + ($regs_p[$y]->recursos_valor * $regs_p[$y]->recursos_qtd);
					
					$linha = $linha + 1;
				}
				
				$objPHPExcel->getActiveSheet()->getRowDimension($linha)->setRowHeight(36);
				$objPHPExcel->getActiveSheet()->getStyle('A'.($linha).':D'.($linha))->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle('B'.($linha).':D'.($linha))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('B'.($linha).':D'.($linha))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A'.($linha).':D'.($linha))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				seta_cor_cedula($objPHPExcel,'A'.($linha).':D'.($linha),'A6A6A6');
				$objPHPExcel->getActiveSheet()->getStyle("D".($linha))->getNumberFormat()->setFormatCode("R$ #,##0.00");
				
				//totais
				$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("A".($linha), "TOTAL DE RECURSOS ESTIMADOS (R$)")
						->setCellValue("B".($linha), "")
		            	->setCellValue("C".($linha), "")
		            	->setCellValue("D".($linha), $vartotalrecursos);
						
				$objPHPExcel->getActiveSheet()->getRowDimension($linha+1)->setRowHeight(36);
				$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+1).':D'.($linha+1))->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle('B'.($linha+1).':D'.($linha+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('B'.($linha+1).':D'.($linha+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+1).':D'.($linha+1))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				seta_cor_cedula($objPHPExcel,'A'.($linha+1).':D'.($linha+1),'A6A6A6');
				$objPHPExcel->getActiveSheet()->getStyle("D".($linha+1))->getNumberFormat()->setFormatCode("R$ #,##0.00");
						
				$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("A".($linha+1), "SOMA SALDO INICIAL + ESTIMADO (TOTAL)")
						->setCellValue("B".($linha+1), "")
		            	->setCellValue("C".($linha+1), "")
		            	->setCellValue("D".($linha+1), $vartotalrecursos + $vartotal);
				
				$linha+=1;
					
			}		

			$linha 				= $linha +1;
			$linha_conta_saldo 	= $linha;
			
			// Podemos configurar diferentes alturas para as linhas como padrão
			$objPHPExcel->getActiveSheet()->getRowDimension($linha+1)->setRowHeight(60);
			$objPHPExcel->getActiveSheet()->getRowDimension($linha+2)->setRowHeight(15.75);
			$objPHPExcel->getActiveSheet()->getRowDimension($linha+3)->setRowHeight(90);
			$objPHPExcel->getActiveSheet()->getRowDimension($linha+4)->setRowHeight(15);
								
			// Alinhamento horizontal e vertical linha 1
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+1).':J'.($linha+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+1).':J'.($linha+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			// Alinhamento horizontal e vertical linha 3
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+3).':J'.($linha+3))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('C'.($linha+3).':J'.($linha+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							
			// Alinhamento horizontal e vertical linha 4
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+4).':J'.($linha+4))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			
			//Bordas Linhas 1,2,3 e 4
			for($cell='A';$cell<='J';$cell++)
			{												
				// Bordas Linha 1
				$objPHPExcel->getActiveSheet()->getStyle($cell.($linha+1))->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				// Bordas Linha 2
				$objPHPExcel->getActiveSheet()->getStyle($cell.($linha+2))->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				// Bordas Linha 3
				$objPHPExcel->getActiveSheet()->getStyle($cell.($linha+3))->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);				
				// Bordas Linha 4
				$objPHPExcel->getActiveSheet()->getStyle($cell.($linha+4))->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				
			}

			// Fonte estilo linha 1
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+1).':J'.($linha+1))->getFont()->setBold(true);
			
			// Fonte estilo linha 3
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+3))->getFont()->setItalic(true);
			$objPHPExcel->getActiveSheet()->getStyle('C'.($linha+3).':J'.($linha+3))->getFont()->setItalic(true);
			
			// Fonte estilo linha 4
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+4).':J'.($linha+4))->getFont()->setBold(true);
													
			// Quebra de texto linha 1
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+1).':J'.($linha+1))->getAlignment()->setWrapText(true);
			
			// Quebra de texto linha 3
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+3).':J'.($linha+3))->getAlignment()->setWrapText(true);
				
			// Cores Linha 1, 2 e 3
			seta_cor_cedula($objPHPExcel,'A'.($linha+1).':J'.($linha+1),'9BC2E6');
			seta_cor_cedula($objPHPExcel,'A'.($linha+2).':J'.($linha+2),'9BC2E6');
			seta_cor_cedula($objPHPExcel,'A'.($linha+3).':J'.($linha+3),'00B0F0');
			
			// Cores Linha 4 
			seta_cor_cedula($objPHPExcel,'A'.($linha+4).':J'.($linha+4),'757171');
							
			// Dados Linha 1
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("A".($linha+1), "PLANEJAMENTO")
						->setCellValue("B".($linha+1), "OBJETIVO")
		            	->setCellValue("C".($linha+1), "CRONOGRAMA")
		            	->setCellValue("D".($linha+1), "RESPONSABILIDADES")
		            	->setCellValue("E".($linha+1), "RECURSOS")
		           		->setCellValue("F".($linha+1), "")
						->setCellValue("G".($linha+1), "")
						->setCellValue("H".($linha+1), "")
						->setCellValue("I".($linha+1), "")
						->setCellValue("J".($linha+1), "");
						
						$objPHPExcel->getActiveSheet()->mergeCells('E'.($linha+1).':G'.($linha+1));
    					$objPHPExcel->getActiveSheet()->getStyle('E'.($linha+1).':G'.($linha+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				
				
			// Dados Linha 3
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, ($linha+3), "Ação: o que você deseja fazer");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, ($linha+3), "O que você deseja alcançar com a ação escolhida?");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($linha+3), "Até quando você deseja terminar a ação?");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, ($linha+3), "Quem é o responsável pela realização da ação?");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, ($linha+3), "Quanto dinheiro do Prêmio você deseja gastar nessa ação e por que?");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, ($linha+3), "Quanto dinheiro além do Prêmio de Comércio Justo você deseja gastar nessa ação e por que? ");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, ($linha+3), "Quanto dinheiro no total você deseja gastar nessa ação e por que?");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, ($linha+3), "% DO PREMIO");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, ($linha+3), "% DE OUTROS RECURSOS");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, ($linha+3), "% TOTAL DE RECURSOS");
						
			//Loop dos Dados
			$grupo_atual 			= "";
			$sub_grupo_atual 		= "";
			$itens_subgrupo_atual	= "";
			$linha			    	= $linha + 4;
											
			for($y=0;$y<count($regs1);$y++)
			{
				//Alimenta Progresso AJAX
				$registroprogressbar = $registroprogressbar + 1;
				echo '|' . round(($registroprogressbar / $totalprogressbar) * 100);
				usleep(1);
			    ob_flush();
			    flush();
				
				if(($grupo_atual != $regs1[$y]->id_grupo) && $regs1[$y]->id_grupo != null)
				{
					$grupo_atual = $regs1[$y]->id_grupo;
					
					//altura do grupo
					$objPHPExcel->getActiveSheet()->getRowDimension($linha)->setRowHeight(60);
							
					// Alinhamento horizontal e vertical do Grupo
					$objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':J'.$linha)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
					$objPHPExcel->getActiveSheet()->getStyle('C'.$linha.':J'.$linha)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					
					// Bordas Grupo	
					for($cell='A';$cell<='J';$cell++)
					{
						$objPHPExcel->getActiveSheet()->getStyle($cell.$linha)->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					}
					
					//quebra de texto coluna A
					for($cell='A';$cell<='J';$cell++)
					{
						$objPHPExcel->getActiveSheet()->getStyle('A'.($linha).':A'.($linha))->getAlignment()->setWrapText(true);
					}
					
							
					// Cores Linha Grupo
					seta_cor_cedula($objPHPExcel,'A'.$linha.':J'.$linha,'5B9BD5');
					
					for($cell='E';$cell<='G';$cell++)
					{
						$objPHPExcel->getActiveSheet()->getStyle('A'.($linha).':D'.($linha))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					}

					//formato moeda
					$objPHPExcel->getActiveSheet()->getStyle('E'.$linha.':G'.$linha)->getNumberFormat()->setFormatCode("R$ #,##0.00");
					
					// Dados dos Grupos
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $linha, $regs1[$y]->id_grupo.'. '.$regs1[$y]->grup_descricao);					
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $linha, $regs1[$y]->grup_premio_orcado);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $linha, $regs1[$y]->grup_outros_orcado);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $linha, $regs1[$y]->grup_total_orcado);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $linha, round($regs1[$y]->grup_premio_orcado*100/$premio_orcado,2));
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $linha, round($regs1[$y]->grup_outros_orcado*100/$outros_orcado),2);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $linha, round($regs1[$y]->grup_total_orcado*100/$total_orcado),2);
					
					$total_perc1+=$regs1[$y]->grup_premio_orcado*100/$premio_orcado;
					$total_perc2+=$regs1[$y]->grup_outros_orcado*100/$outros_orcado;
					$total_perc3+=$regs1[$y]->grup_total_orcado*100/$total_orcado;				
					
					// Fonte e Estilo Grupo
					$objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':B'.$linha)->getFont()->setBold(true);
					
					$linha += 1;
				}
			
				if(($sub_grupo_atual != $regs1[$y]->id_subgrupo) && $regs1[$y]->id_subgrupo != null)
				{
					$sub_grupo_atual = $regs1[$y]->id_subgrupo;
					
					// Largura do SubGrupo
					$objPHPExcel->getActiveSheet()->getRowDimension($linha)->setRowHeight(15.75);
					
					// Alinhamento horizontal e vertical do Grupo
					$objPHPExcel->getActiveSheet()->getStyle('C'.$linha.':J'.$linha)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					
												
					// Bordas SubGrupo
					for($cell='A';$cell<='J';$cell++)
					{
						$objPHPExcel->getActiveSheet()->getStyle($cell.$linha)->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					}
					
					//quebra de texto coluna A
					for($cell='A';$cell<='J';$cell++)
					{
						$objPHPExcel->getActiveSheet()->getStyle('A'.($linha).':A'.($linha))->getAlignment()->setWrapText(true);
					}
					
					// Cores Linha SubGrupo 
					esquema_cor_padrao($objPHPExcel,($linha));
					
					//formato moeda
					$objPHPExcel->getActiveSheet()->getStyle('E'.$linha.':G'.$linha)->getNumberFormat()->setFormatCode("R$ #,##0.00");
					
					// Dados dos SubGrupo
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $linha, $regs1[$y]->subg_descricao);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $linha, $regs1[$y]->subg_premio_orcado);					
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $linha, $regs1[$y]->subg_outros_orcado);					
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $linha, $regs1[$y]->subg_total_orcado);
					
					
					
					// Fonte e Estilo SubGrupo
					$objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':B'.$linha)->getFont()->setBold(true);
									
					$linha += 1;
				}
							
			}


			//TOTAIS GERAL

			// Largura do Grupo
			$objPHPExcel->getActiveSheet()->getRowDimension($linha)->setRowHeight(30.75);
			
			//definindo negrito nas fontes
			$objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':J'.$linha)->getFont()->setBold(true);
			
			// Alinhamento horizontal e vertical do Grupo			
			$objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':J'.$linha)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			// Bordas Grupo	
			for($cell='A';$cell<='J';$cell++)
			{
				$objPHPExcel->getActiveSheet()->getStyle($cell.$linha)->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);										
			}
					
			// Cores Linha Grupo
			seta_cor_cedula($objPHPExcel,'A'.$linha.':J'.$linha,'0070C0');
			
			//formato moeda
			$objPHPExcel->getActiveSheet()->getStyle('E'.$linha.':G'.$linha)->getNumberFormat()->setFormatCode("R$ #,##0.00");
					
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("A".($linha), "TOTAL")						
		            	->setCellValue("E".($linha), $premio_orcado)		            	
		            	->setCellValue("F".($linha), $outros_orcado)		            	
		            	->setCellValue("G".($linha), $total_orcado)
						->setCellValue("H".($linha), round($total_perc1,2))
						->setCellValue("I".($linha), round($total_perc2,2))
						->setCellValue("J".($linha), round($total_perc3,2));
						
			
			$linha = $linha + 3;
						
			// Zoom da Pagina
			$objPHPExcel->getActiveSheet()->getSheetView()->setZoomScale(80);
			
			// Podemos renomear o nome das planilha atual, lembrando que um único arquivo pode ter várias planilhas
			$objPHPExcel->getActiveSheet()->setTitle('Planejamento');
			
			// Cabeçalho do arquivo para ele baixar
			//header('Content-Type: application/vnd.ms-excel'.$ano);
			//header('Content-Disposition: attachment;filename="planilha_'.$ano.'.xls"');
			//header('Cache-Control: no-cache');
			
			// Acessamos o 'Writer' para poder salvar o arquivo
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			
			// Salva diretamente no output, poderíamos mudar arqui para um nome de arquivo em um diretório ,caso não quisessemos jogar na tela
			//$objWriter->save('php://output');
			$objWriter->save('planilha-pdcj-planejamento-'.$ano.'.xls');
			
			//Fecha Progresso AJAX
			ob_end_flush();
		}
		else
		{
			echo 'Nenhum registro a ser exportado';
			return false;
		}
	}
	else
	{
		echo 'Nenhum registro a ser exportado';
		return false;
	}

	//Seta esquema de cores padrao dados completo
	function esquema_cor_padrao($objPHPExcel,$linha)
	{
		
		seta_cor_cedula($objPHPExcel,'A'.$linha.':D'.$linha,'BDD7EE');
		seta_cor_cedula($objPHPExcel,'E'.$linha.':E'.$linha,'FFFF00');
		seta_cor_cedula($objPHPExcel,'F'.$linha.':F'.$linha,'00B050');
		seta_cor_cedula($objPHPExcel,'G'.$linha.':J'.$linha,'BDD7EE');		
	}
				
	function seta_cor_cedula($objPHPExcel,$coluna,$cor)
	{
		$objPHPExcel->getActiveSheet()->getStyle($coluna)->applyFromArray(
	        array('fill' => array(
	                'type' => PHPExcel_Style_Fill::FILL_SOLID,
	                'color' => array('rgb' => $cor)
	            ),
	        )
		);
	}
	
	exit;
	
?>
