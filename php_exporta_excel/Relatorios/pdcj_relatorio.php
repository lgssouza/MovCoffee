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
		
	/*padrão para definição da procedure 
	 * 1º Param = Ano do PDCJ - Sempre irá buscar saldo final do ano anterior.
	 * 2º Param = Conta Premio Sim-1 , Não-0
	 * 3º Param = Conta Outros Sim-1 , Não-0
	 * 4º Param = Conta Corrente Sim-1 , Não-0
	 * 5º Param = Conta Aplicação Sim-1 , Não-0
	 * Lembrando que pela regra de negócio nunca temos:
	 * uma conta premio e outros ao mesmo tempo.
	 * uma conta aplicacao e corrente ao mesmo tempo.
	 * 
	 * a procedure irá jogar o valor numa tabela temporaria então devemos 
	 * buscar o valor antes de executar ela novamente.
	 * 
	 * */
	
	//Conta Premio Corrente
	$sp = $mysqli->query("call SP_BuscaSaldoContaPeriodo($ano,1,0,1,0)");
	
	$rs_saldo_tmp = $mysqli->query("SELECT * FROM tmp_saldo_conta");
	if($rs_saldo_tmp)
	{
		$saldo_tmp				= $rs_saldo_tmp->fetch_object();
		$saldoinicial_p_c		= $saldo_tmp->tmp_saldo;
		$rs_saldo_tmp->close();
	}
	else 
	{
		$saldoinicial_p_c		= 0;
	}
	
	//Conta Premio Aplicacao
	$sp = $mysqli->query("call SP_BuscaSaldoContaPeriodo($ano,1,0,0,1)");
	
	$rs_saldo_tmp = $mysqli->query("SELECT * FROM tmp_saldo_conta");
	if($rs_saldo_tmp)
	{
		$saldo_tmp				= $rs_saldo_tmp->fetch_object();
		$saldoinicial_p_a		= $saldo_tmp->tmp_saldo;
		$rs_saldo_tmp->close();
	}
	else 
	{
		$saldoinicial_p_a		= 0;
	}
	
	//Conta Outros Corrente
	$sp = $mysqli->query("call SP_BuscaSaldoContaPeriodo($ano,0,1,1,0)");
	
	$rs_saldo_tmp = $mysqli->query("SELECT * FROM tmp_saldo_conta");
	if($rs_saldo_tmp)
	{
		$saldo_tmp				= $rs_saldo_tmp->fetch_object();
		$saldoinicial_o_c		= $saldo_tmp->tmp_saldo;
		$rs_saldo_tmp->close();
	}
	else 
	{
		$saldoinicial_o_c		= 0;
	}
	
	//Conta Outros Aplicacao
	$sp = $mysqli->query("call SP_BuscaSaldoContaPeriodo($ano,0,1,0,1)");
	
	$rs_saldo_tmp = $mysqli->query("SELECT * FROM tmp_saldo_conta");
	if($rs_saldo_tmp)
	{
		$saldo_tmp				= $rs_saldo_tmp->fetch_object();
		$saldoinicial_o_a		= $saldo_tmp->tmp_saldo;
		$rs_saldo_tmp->close();
	}
	else 
	{
		$saldoinicial_o_a		= 0;
	}
	
	
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
	
	$rs_totais = $mysqli->query($sql_totais);
	
	
	$linha_conta_despesas	= 0;
	$linha_conta_saldo		= 0;
	
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
	
	$sql_p = "select a.*, b.conta_premio, b.conta_outros 
	from mov_contas a 
	inner join tb_conta b on a.fk_id_conta = b.id_conta 
	WHERE (b.conta_premio = 1 OR b.conta_outros = 1) 
	and b.conta_corrente = 1 
	and a.mov_contas_op = 'E' 
	and a.mov_contas_transferencia = 0
	and year(a.mov_contas_data) = $ano 
	order by a.mov_contas_data asc, a.id_mov_contas";
	
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
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(11);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(44.43);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(19.14);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(14.71);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15.86);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(14.43);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(22.14);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(22.14);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(22.14);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(19.86);
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(19.86);
			$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(24.14);
			$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(55.43);
			
			// Defini linha inicial
			$linha = 0;
			
			//CABEÇALHO
			seta_cor_cedula($objPHPExcel,'A'.($linha+1).':M'.($linha+1),'9BC2E6');						
			$objPHPExcel->getActiveSheet()->getStyle('C'.($linha+1).':C'.($linha+1))->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('C'.($linha+1).':C'.($linha+1))->getFont()->setSize(16);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($linha+1), "PLANO DE DESENVOLVIMENTO DO COMÉRCIO JUSTO:  SEÇÃO B RELATÓRIO");
			$objPHPExcel->getActiveSheet()->mergeCells('C'.($linha+1).':M'.($linha+1));
			$objPHPExcel->getActiveSheet()->getStyle('C'.($linha+1).':M'.($linha+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			
			
			seta_cor_cedula($objPHPExcel,'A'.($linha+2).':M'.($linha+2),'9BC2E6');						
			$objPHPExcel->getActiveSheet()->getStyle('C'.($linha+2).':C'.($linha+2))->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($linha+2), "Período de Vigência do Plano: 01. 04.$ano a 31.12.$ano"."                  "."Organização: APAS");
			$objPHPExcel->getActiveSheet()->mergeCells('C'.($linha+2).':M'.($linha+2));
			$objPHPExcel->getActiveSheet()->getStyle('C'.($linha+2).':M'.($linha+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			
			
			$linha=$linha+3;
						
			$objPHPExcel->getActiveSheet()->getRowDimension($linha+1)->setRowHeight(45.75);
			seta_cor_cedula($objPHPExcel,'A'.($linha+1).':E'.($linha+1),'FFFF00');
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+1))->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+1))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+1))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);	
		
			$objPHPExcel->getActiveSheet()->getStyle('B'.($linha+1))->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
			$objPHPExcel->getActiveSheet()->getStyle('B'.($linha+1))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
			$objPHPExcel->getActiveSheet()->getStyle('B'.($linha+1))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);					
		
			$objPHPExcel->getActiveSheet()->getStyle('C'.($linha+1).':E'.($linha+1))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
		
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+1).':E'.($linha+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+1).':E'.($linha+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+1).':E'.($linha+1))->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+1).':E'.($linha+1))->getFont()->setBold(true);
		
			$objPHPExcel->getActiveSheet()->getStyle('C'.($linha+1).':E'.($linha+1))->getFont()->getColor()->setRGB('FF0000');
				
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("A".($linha+1), "")
						->setCellValue("B".($linha+1), "DISCRIMINAÇÃO DE ENTRADAS DE RECURSOS")
		            	->setCellValue("C".($linha+1), "PRÊMIO")
		            	->setCellValue("D".($linha+1), "OUTROS RECURSOS")
		            	->setCellValue("E".($linha+1), "TOTAL DE RECURSOS RECEBIDOS");
						
			if($rs_p)
			{
				$regs_p = array();
		        
				while($reg_p = $rs_p->fetch_object())
				{
					array_push($regs_p, $reg_p);
				}
				
				$rs_p->close();
				
				$first		 			= false;
				$first_line   			= $linha+2;
				$linha 					= $linha+3;
				$vartotal				= 0;
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
					
					//formato moeda
					$objPHPExcel->getActiveSheet()->getStyle('C'.$linha.':E'.$linha)->getNumberFormat()->setFormatCode("R$ #,##0.00");
					$objPHPExcel->getActiveSheet()->getStyle('C'.$first_line.':E'.$first_line)->getNumberFormat()->setFormatCode("R$ #,##0.00");
															
					$objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':E'.$linha)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$first_line.':E'.$first_line)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					
					$objPHPExcel->getActiveSheet()->getStyle('C'.$linha.':E'.$linha)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('C'.$linha.':E'.$linha)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);					
						
					if($first==false)
					{
						
					
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, ($first_line), '01.01.'.$ano);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, ($first_line), "Saldo Inicial");
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($first_line), $saldoinicial_p_c + $saldoinicial_p_a);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, ($first_line), $saldoinicial_o_c + $saldoinicial_o_a);						
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, ($first_line), $saldoinicial_p_c + $saldoinicial_p_a + $saldoinicial_o_c + $saldoinicial_o_a);
						
						$vartotal = $saldoinicial_p_c + $saldoinicial_p_a + $saldoinicial_o_c + $saldoinicial_o_a;
						$varsubtotal_p	= $varsubtotal_p + $saldoinicial_p_c + $saldoinicial_p_a;
						$varsubtotal_o	= $varsubtotal_o + $saldoinicial_o_c + $saldoinicial_o_a;
							
						$first=true;
					}
							
					$item_data = date('d.m.Y', strtotime($regs_p[$y]->mov_contas_data));
					
					if($regs_p[$y]->conta_premio==1)
					{
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, ($linha), $item_data);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, ($linha), $regs_p[$y]->mov_contas_descricao);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($linha), $regs_p[$y]->mov_contas_valor);
						
						$vartotal = $vartotal + $regs_p[$y]->mov_contas_valor;		
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, ($linha), $vartotal);
						
						$varsubtotal_p	= $varsubtotal_p + $regs_p[$y]->mov_contas_valor;
					}
					else 
					{
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, ($linha), $item_data);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, ($linha), $regs_p[$y]->mov_contas_descricao);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, ($linha), $regs_p[$y]->mov_contas_valor);
						
						$vartotal = $vartotal + $regs_p[$y]->mov_contas_valor;
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, ($linha), $vartotal);
						
						$varsubtotal_o	= $varsubtotal_o + $regs_p[$y]->mov_contas_valor;
					}
						
					$linha = $linha + 1;
				}
				
				//total 	
				$objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':E'.$linha)->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':E'.$linha)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					
				$objPHPExcel->getActiveSheet()->getStyle('B'.$linha)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$objPHPExcel->getActiveSheet()->getStyle('C'.$linha.':E'.$linha)->getNumberFormat()->setFormatCode("R$ #,##0.00");
									
				$objPHPExcel->getActiveSheet()->getRowDimension($linha)->setRowHeight(22.5);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, ($linha), 'Total');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($linha), $varsubtotal_p);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, ($linha), $varsubtotal_o);				
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, ($linha), $varsubtotal_p + $varsubtotal_o);
				
				//total despesas e saldo
				$linha = $linha +1;
				$linha_conta_despesas = $linha;
				
				$objPHPExcel->getActiveSheet()->getStyle('A'.($linha).':E'.($linha))->getFont()->setBold(true);					
				$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+1).':E'.($linha+1))->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getStyle('A'.($linha).':E'.($linha))->getFont()->setSize(16);
				$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+1).':E'.($linha+1))->getFont()->setSize(16);
				
				$objPHPExcel->getActiveSheet()->getStyle('D'.($linha))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$objPHPExcel->getActiveSheet()->getStyle('D'.($linha+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					
				$objPHPExcel->getActiveSheet()->getStyle('D'.($linha))->getFont()->getColor()->setRGB('FF0000');
				$objPHPExcel->getActiveSheet()->getStyle('D'.($linha+1))->getFont()->getColor()->setRGB('FF0000');
				
				$objPHPExcel->getActiveSheet()->getStyle('D'.($linha))->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
				$objPHPExcel->getActiveSheet()->getStyle('D'.($linha))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
				$objPHPExcel->getActiveSheet()->getStyle('D'.($linha))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
				$objPHPExcel->getActiveSheet()->getStyle('D'.($linha+1))->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
				$objPHPExcel->getActiveSheet()->getStyle('D'.($linha+1))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
				$objPHPExcel->getActiveSheet()->getStyle('D'.($linha+1))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
		
				$objPHPExcel->getActiveSheet()->getStyle('E'.($linha))->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
				$objPHPExcel->getActiveSheet()->getStyle('E'.($linha))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
				$objPHPExcel->getActiveSheet()->getStyle('E'.($linha))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
				$objPHPExcel->getActiveSheet()->getStyle('E'.($linha+1))->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
				$objPHPExcel->getActiveSheet()->getStyle('E'.($linha+1))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
				$objPHPExcel->getActiveSheet()->getStyle('E'.($linha+1))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
		
				$objPHPExcel->getActiveSheet()->getStyle('A'.($linha).':C'.($linha))->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
				$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+1).':C'.($linha+1))->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);	
		
				$objPHPExcel->getActiveSheet()->getRowDimension($linha)->setRowHeight(45.75);
				seta_cor_cedula($objPHPExcel,'A'.($linha).':E'.($linha),'FFFF00');
				
				$objPHPExcel->getActiveSheet()->getStyle('C'.$linha)->getFont()->getColor()->setRGB('FF0000');
				$objPHPExcel->getActiveSheet()->getStyle('C'.$linha.':C'.$linha)->getNumberFormat()->setFormatCode("R$ #,##0.00");
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, ($linha), 'DESPESAS TOTAL');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($linha), $total_despesas);
				
				seta_cor_cedula($objPHPExcel,'H'.($linha).':I'.($linha),'FFFF00');
				$objPHPExcel->getActiveSheet()->getStyle('I'.$linha)->getFont()->getColor()->setRGB('FF0000');
				$objPHPExcel->getActiveSheet()->getStyle('I'.$linha.':I'.$linha)->getNumberFormat()->setFormatCode("R$ #,##0.00");
				$objPHPExcel->getActiveSheet()->getStyle('H'.($linha).':H'.($linha+1))->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle('H'.($linha).':I'.($linha+1))->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getStyle('H'.$linha.':I'.$linha)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, ($linha), 'SALDO PRÊMIO BANCO DO BRASIL');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, ($linha), $varsubtotal_p - $premio_despesas);
				
				
				$linha = $linha +1;
				
				$objPHPExcel->getActiveSheet()->getRowDimension($linha)->setRowHeight(45.75);
				seta_cor_cedula($objPHPExcel,'A'.($linha).':E'.($linha),'FFFF00');
				
				$objPHPExcel->getActiveSheet()->getStyle('C'.$linha)->getFont()->getColor()->setRGB('FF0000');
				$objPHPExcel->getActiveSheet()->getStyle('C'.$linha.':C'.$linha)->getNumberFormat()->setFormatCode("R$ #,##0.00");
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, ($linha), 'SALDO FINAL');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($linha), ($varsubtotal_p + $varsubtotal_o) - $total_despesas);
				
				seta_cor_cedula($objPHPExcel,'H'.($linha).':I'.($linha),'FFFF00');
				$objPHPExcel->getActiveSheet()->getStyle('I'.$linha)->getFont()->getColor()->setRGB('FF0000');
				$objPHPExcel->getActiveSheet()->getStyle('I'.$linha.':I'.$linha)->getNumberFormat()->setFormatCode("R$ #,##0.00");
				$objPHPExcel->getActiveSheet()->getStyle('H'.($linha).':H'.($linha+1))->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle('H'.($linha).':I'.($linha+1))->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getStyle('H'.$linha.':I'.$linha)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, ($linha), 'SALDO OUTROS RECURSOS- SICOOB');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, ($linha), $varsubtotal_o - $outros_despesas);
				
				
			}		
	
			$linha 				= $linha +1;
			$linha_conta_saldo 	= $linha;
			
			// Podemos configurar diferentes alturas para as linhas como padrão
			$objPHPExcel->getActiveSheet()->getRowDimension($linha+1)->setRowHeight(60);
			$objPHPExcel->getActiveSheet()->getRowDimension($linha+2)->setRowHeight(15.75);
			$objPHPExcel->getActiveSheet()->getRowDimension($linha+3)->setRowHeight(90);
			$objPHPExcel->getActiveSheet()->getRowDimension($linha+4)->setRowHeight(15);
								
			// Alinhamento horizontal e vertical linha 1
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+1).':M'.($linha+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+1).':M'.($linha+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			// Alinhamento horizontal e vertical linha 3
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+3).':M'.($linha+3))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('C'.($linha+3).':M'.($linha+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							
			// Alinhamento horizontal e vertical linha 4
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+4).':M'.($linha+4))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			
			//Bordas Linhas 1,2,3 e 4
			for($cell='A';$cell<='M';$cell++)
			{												
				// Bordas Linha 1
				$objPHPExcel->getActiveSheet()->getStyle($cell.($linha+1))->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
				
				if($cell != 'B')
				{
					$objPHPExcel->getActiveSheet()->getStyle($cell.($linha+1))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
				}
				
				if($cell != 'A')
				{
					$objPHPExcel->getActiveSheet()->getStyle($cell.($linha+1))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
				}
				
				// Bordas Linha 2
				$objPHPExcel->getActiveSheet()->getStyle($cell.($linha+2))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
				
				if($cell != 'B')
				{
					$objPHPExcel->getActiveSheet()->getStyle($cell.($linha+2))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
				}
				
				if($cell != 'A')
				{
					$objPHPExcel->getActiveSheet()->getStyle($cell.($linha+2))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
				}
				
				// Bordas Linha 3
				$objPHPExcel->getActiveSheet()->getStyle($cell.($linha+3))->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
				$objPHPExcel->getActiveSheet()->getStyle($cell.($linha+3))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
				
				if($cell != 'B')
				{
					$objPHPExcel->getActiveSheet()->getStyle($cell.($linha+3))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
				}
				
				if($cell != 'A')
				{
					$objPHPExcel->getActiveSheet()->getStyle($cell.($linha+3))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
				}
				
				// Bordas Linha 4
				$objPHPExcel->getActiveSheet()->getStyle($cell.($linha+4))->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
				$objPHPExcel->getActiveSheet()->getStyle($cell.($linha+4))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
				$objPHPExcel->getActiveSheet()->getStyle($cell.($linha+4))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
			}

			// Fonte estilo linha 1
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+1).':M'.($linha+1))->getFont()->setBold(true);
			
			// Fonte estilo linha 3
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+3))->getFont()->setItalic(true);
			$objPHPExcel->getActiveSheet()->getStyle('C'.($linha+3).':M'.($linha+3))->getFont()->setItalic(true);
			
			// Fonte estilo linha 4
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+4).':M'.($linha+4))->getFont()->setBold(true);
													
			// Quebra de texto linha 1
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+1).':M'.($linha+1))->getAlignment()->setWrapText(true);
			
			// Quebra de texto linha 3
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha+3).':M'.($linha+3))->getAlignment()->setWrapText(true);
				
			// Cores Linha 1, 2 e 3
			esquema_cor_padrao($objPHPExcel,($linha+1));
			esquema_cor_padrao($objPHPExcel,($linha+2));
			esquema_cor_padrao($objPHPExcel,($linha+3));
			
			// Cores Linha 4 
			seta_cor_cedula($objPHPExcel,'A'.($linha+4).':M'.($linha+4),'757171');
							
			// Dados Linha 1
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("A".($linha+1), "")
						->setCellValue("B".($linha+1), "")
		            	->setCellValue("C".($linha+1), "VALOR ORÇADO DO PRÊMIO")
		            	->setCellValue("D".($linha+1), "DESPESAS DO PREMIO")
		            	->setCellValue("E".($linha+1), "SALDO DO VALOR ORÇADO DO PRÊMIO")
		           		->setCellValue("F".($linha+1), "VALOR ORÇADO OUTROS RECURSOS")
						->setCellValue("G".($linha+1), "DESPESAS OUTROS RECURSOS")
						->setCellValue("H".($linha+1), "SALDO DO VALOR ORÇADO DOS OUTROS RECURSOS")
						->setCellValue("I".($linha+1), "TOTAL DE DINHEIRO VOCÊ  ORÇOU PARA ESSAS AÇÕES")
						->setCellValue("J".($linha+1), "TOTAL DAS DESPESAS PRÊMIO + OUTROS RECURSOS")
						->setCellValue("K".($linha+1), "SALDO TOTAL PRÊMIO + OUTROS RECURSOS")
						->setCellValue("L".($linha+1), "RESPONSÁVEL PELA AÇÃO")
						->setCellValue("M".($linha+1), "AUTO-AVALIAÇÃO");			
				
			// Dados Linha 3
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, ($linha+3), "Ação: o que você fez?");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($linha+3), "Quanto dinheiro do Prêmio você orçou para essa ação?");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, ($linha+3), "Quanto dinheiro do Prêmio você gastou nesta ação ?");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, ($linha+3), "Qual é o valor que ainda não foi usado do valor orçado do dinheiro do Prêmio ?");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, ($linha+3), "Quanto dinheiro de outros recursosvocê orçou para essa ação?");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, ($linha+3), "Quanto dinheiro de outros recursos você gastou nesta ação ?");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, ($linha+3), "Qual é o valor que ainda não foi usado do valor orçado dos outros recursos ?");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, ($linha+3), "");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, ($linha+3), "Quanto dinheiro no total você gastou nesta ação? ");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, ($linha+3), "Qual é o valor total que ainda não foi usado nestas ações ?");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, ($linha+3), "Quem é foi o responsável pela realização da ação ?");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, ($linha+3), "A ação foi realizada? Quando? Como? O objetivo foi realizado? Serão necessárias novas ações? Justificativa, caso a ação não tenha sido realizada");
			
			// Dados Linha 4
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, ($linha+4), "DATA");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, ($linha+4), "DESPESAS PRÊMIO FT:");
			
			//Loop dos Dados
			$grupo_atual 			= "";
			$sub_grupo_atual 		= "";
			$itens_subgrupo_atual	= "";
			$linha			    	= $linha + 5;
											
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
					
					// Largura do Grupo
					$objPHPExcel->getActiveSheet()->getRowDimension($linha)->setRowHeight(30.75);
							
					// Alinhamento horizontal e vertical do Grupo
					$objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':M'.$linha)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
					$objPHPExcel->getActiveSheet()->getStyle('C'.$linha.':M'.$linha)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					
					// Bordas Grupo	
					for($cell='A';$cell<='M';$cell++)
					{
						$objPHPExcel->getActiveSheet()->getStyle($cell.$linha)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);	
						$objPHPExcel->getActiveSheet()->getStyle($cell.$linha)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);	
						$objPHPExcel->getActiveSheet()->getStyle($cell.$linha)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);						
					}
							
					// Cores Linha Grupo
					seta_cor_cedula($objPHPExcel,'A'.$linha.':M'.$linha,'757171');
					
					$objPHPExcel->getActiveSheet()->getStyle('C'.$linha.':K'.$linha)->getNumberFormat()->setFormatCode("R$ #,##0.00");
					
					// Dados dos Grupos
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $linha, $regs1[$y]->id_grupo.'. '.$regs1[$y]->grup_descricao);					
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $linha, $regs1[$y]->grup_premio_orcado);					
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $linha, $regs1[$y]->grup_premio_despesas);					
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $linha, $regs1[$y]->grup_premio_saldo);					
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $linha, $regs1[$y]->grup_outros_orcado);					
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $linha, $regs1[$y]->grup_outros_despesas);					
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $linha, $regs1[$y]->grup_outros_saldo);					
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $linha, $regs1[$y]->grup_total_orcado);					
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $linha, $regs1[$y]->grup_total_despesas);					
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $linha, $regs1[$y]->grup_total_saldo);					
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $linha, $regs1[$y]->grup_responsavel);					
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $linha, $regs1[$y]->grup_avaliacao);
			
					// Fonte e Estilo Grupo
					$objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':D'.$linha)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$linha.':G'.$linha)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('I'.$linha)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('D'.$linha)->getFont()->getColor()->setRGB('FF0000');
					$objPHPExcel->getActiveSheet()->getStyle('G'.$linha)->getFont()->getColor()->setRGB('FF0000');
					
					$linha += 1;
				}
			
				if(($sub_grupo_atual != $regs1[$y]->id_subgrupo) && $regs1[$y]->id_subgrupo != null)
				{
					$sub_grupo_atual = $regs1[$y]->id_subgrupo;
					
					// Largura do SubGrupo
					$objPHPExcel->getActiveSheet()->getRowDimension($linha)->setRowHeight(15.75);
					
					// Alinhamento horizontal e vertical do Grupo
					$objPHPExcel->getActiveSheet()->getStyle('C'.$linha.':M'.$linha)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
												
					// Bordas SubGrupo
					$objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':M'.$linha)->getBorders()->getHorizontal()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':M'.$linha)->getBorders()->getVertical()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					
					// Cores Linha SubGrupo 
					seta_cor_cedula($objPHPExcel,'A'.$linha.':M'.$linha,'AEAAAA');
					$objPHPExcel->getActiveSheet()->getStyle('C'.$linha.':K'.$linha)->getNumberFormat()->setFormatCode("R$ #,##0.00");
					
					// Dados dos SubGrupo
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $linha, $regs1[$y]->subg_descricao);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $linha, $regs1[$y]->subg_premio_orcado);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $linha, $regs1[$y]->subg_premio_despesas);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $linha, $regs1[$y]->subg_premio_saldo);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $linha, $regs1[$y]->subg_outros_orcado);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $linha, $regs1[$y]->subg_outros_despesas);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $linha, $regs1[$y]->subg_outros_saldo);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $linha, $regs1[$y]->subg_total_orcado);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $linha, $regs1[$y]->subg_total_despesas);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $linha, $regs1[$y]->subg_total_saldo);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $linha, $regs1[$y]->subg_responsavel);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $linha, $regs1[$y]->subg_avaliacao);								
					
					// Fonte e Estilo SubGrupo
					$objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':B'.$linha)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('D'.$linha)->getFont()->getColor()->setRGB('FF0000');
					$objPHPExcel->getActiveSheet()->getStyle('G'.$linha)->getFont()->getColor()->setRGB('FF0000');
									
					$linha += 1;
				}
								
				if(($itens_subgrupo_atual != $regs1[$y]->id_item) &&  $regs1[$y]->id_item != null)
				{
					$itens_subgrupo_atual = $regs1[$y]->id_item;
					
					// Largura do Item
					$objPHPExcel->getActiveSheet()->getRowDimension($linha)->setRowHeight(15.75);
					$objPHPExcel->getActiveSheet()->getStyle('C'.$linha.':M'.$linha)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
												
					// Bordas SubGrupo
					if(isset($regs1[($y+1)]))
					{
						if($grupo_atual == $regs1[($y+1)]->id_grupo)
						{
							$objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':M'.$linha)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						}
						else
						{
							$objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':M'.$linha)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':M'.$linha)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);	
						}
					}
					else
					{
						$objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':M'.$linha)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					}
						
					// Cores Linha SubGrupo 
					esquema_cor_padrao($objPHPExcel,$linha);
																		
					// Dados dos SubGrupo
					$item_data = date('d.m.Y', strtotime($regs1[$y]->item_data));
					$objPHPExcel->getActiveSheet()->getStyle('C'.$linha.':K'.$linha)->getNumberFormat()->setFormatCode("R$ #,##0.00");
													
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $linha, $item_data);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $linha, $regs1[$y]->item_descricao);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $linha, "");
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $linha, $regs1[$y]->item_valor_premio);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $linha, "");
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $linha, "");
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $linha, $regs1[$y]->item_valor_outros);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $linha, "");
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $linha, "");
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $linha, "");
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $linha, "");
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $linha, $regs1[$y]->item_responsavel);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $linha, "");								
												
					// Fonte e Estilo SubGrupo
					$objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':M'.$linha)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('D'.$linha)->getFont()->getColor()->setRGB('FF0000');
					$objPHPExcel->getActiveSheet()->getStyle('G'.$linha)->getFont()->getColor()->setRGB('FF0000');
														
					$linha += 1;
				}
				elseif($regs1[$y]->id_item == null)
				{
					// Largura do Item
					$objPHPExcel->getActiveSheet()->getRowDimension($linha)->setRowHeight(15.75);
					$objPHPExcel->getActiveSheet()->getStyle('C'.$linha.':M'.$linha)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
												
					// Bordas SubGrupo
					if(isset($regs1[($y+1)]))
					{
						if($grupo_atual == $regs1[($y+1)]->id_grupo)
						{
							$objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':M'.$linha)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						}
						else
						{
							$objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':M'.$linha)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':M'.$linha)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);	
						}
					}
					else
					{
						$objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':M'.$linha)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					}
						
					// Cores Linha SubGrupo 
					esquema_cor_padrao($objPHPExcel,$linha);
												
					// Fonte e Estilo SubGrupo
					$objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':M'.$linha)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('D'.$linha)->getFont()->getColor()->setRGB('FF0000');
					$objPHPExcel->getActiveSheet()->getStyle('G'.$linha)->getFont()->getColor()->setRGB('FF0000');
														
					$linha += 1;
				}
			}


			//TOTAIS GERAL

			// Largura do Grupo
			$objPHPExcel->getActiveSheet()->getRowDimension($linha)->setRowHeight(30.75);
			
			//definindo negrito nas fontes
			$objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':M'.$linha)->getFont()->setBold(true);
			
			// Alinhamento horizontal e vertical do Grupo			
			$objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':B'.$linha)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			
			// Bordas Grupo	
			for($cell='A';$cell<='M';$cell++)
			{
				$objPHPExcel->getActiveSheet()->getStyle($cell.$linha)->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);										
			}
					
			// Cores Linha Grupo
			seta_cor_cedula($objPHPExcel,'A'.$linha.':M'.$linha,'757171');
			$objPHPExcel->getActiveSheet()->getStyle('C'.$linha.':K'.$linha)->getNumberFormat()->setFormatCode("R$ #,##0.00");
					
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("A".($linha), "")
						->setCellValue("B".($linha), "TOTAL")
		            	->setCellValue("C".($linha), $premio_orcado)
		            	->setCellValue("D".($linha), $premio_despesas)
		            	->setCellValue("E".($linha), $premio_saldo)
		            	->setCellValue("F".($linha), $outros_orcado)
		            	->setCellValue("G".($linha), $outros_despesas)
		            	->setCellValue("H".($linha), $outros_saldo)
		            	->setCellValue("I".($linha), $total_orcado)
		            	->setCellValue("J".($linha), $total_despesas)
		            	->setCellValue("K".($linha), $total_saldo)
						->setCellValue("L".($linha), "")
						->setCellValue("M".($linha), "");
						
			
			$linha = $linha + 3;
			
			//resumo da previsão de uso - percentuais	
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha).':F'.($linha))->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
			
			$objPHPExcel->getActiveSheet()->mergeCells('A'.($linha).':B'.($linha));
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha).':B'.($linha))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->mergeCells('C'.($linha).':D'.($linha));
			$objPHPExcel->getActiveSheet()->getStyle('C'.($linha).':D'.($linha))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->mergeCells('E'.($linha).':F'.($linha));
			$objPHPExcel->getActiveSheet()->getStyle('E'.($linha).':F'.($linha))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, ($linha), 'RESUMO DAS PREVISÕES');			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, ($linha), '(%) USO DOS RECURSOS');
			
			$linha = $linha + 1;
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha).':F'.($linha))->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
			seta_cor_cedula($objPHPExcel,'A'.$linha.':F'.$linha,'FFFF00');
			
			$objPHPExcel->getActiveSheet()->mergeCells('A'.($linha).':B'.($linha));
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha).':B'.($linha))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->mergeCells('C'.($linha).':D'.($linha));
			$objPHPExcel->getActiveSheet()->getStyle('C'.($linha).':D'.($linha))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->mergeCells('E'.($linha).':F'.($linha));
			$objPHPExcel->getActiveSheet()->getStyle('E'.($linha).':F'.($linha))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$linha.':C'.$linha)->getNumberFormat()->setFormatCode("R$ #,##0.00");
			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, ($linha), 'PREVISÃO DO USO DO PRÊMIO');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($linha), $premio_orcado);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, ($linha), round($premio_despesas*100/$premio_orcado),2);
			
			$linha = $linha + 1;
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha).':F'.($linha))->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
			seta_cor_cedula($objPHPExcel,'A'.$linha.':F'.$linha,'00B050');
			
			$objPHPExcel->getActiveSheet()->mergeCells('A'.($linha).':B'.($linha));
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha).':B'.($linha))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->mergeCells('C'.($linha).':D'.($linha));
			$objPHPExcel->getActiveSheet()->getStyle('C'.($linha).':D'.($linha))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->mergeCells('E'.($linha).':F'.($linha));
			$objPHPExcel->getActiveSheet()->getStyle('E'.($linha).':F'.($linha))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$linha.':C'.$linha)->getNumberFormat()->setFormatCode("R$ #,##0.00");
			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, ($linha), 'PREVISÃO DO USO DE OUTROS RECURSOS');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($linha), $outros_orcado);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, ($linha), round($outros_despesas*100/$outros_orcado,2));
			
			$linha = $linha + 1;
			
			seta_cor_cedula($objPHPExcel,'A'.$linha.':F'.$linha,'F4B084');
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha).':F'.($linha))->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
			
			$objPHPExcel->getActiveSheet()->mergeCells('A'.($linha).':B'.($linha));
			$objPHPExcel->getActiveSheet()->getStyle('A'.($linha).':B'.($linha))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->mergeCells('C'.($linha).':D'.($linha));
			$objPHPExcel->getActiveSheet()->getStyle('C'.($linha).':D'.($linha))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->mergeCells('E'.($linha).':F'.($linha));
			$objPHPExcel->getActiveSheet()->getStyle('E'.($linha).':F'.($linha))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$linha.':C'.$linha)->getNumberFormat()->setFormatCode("R$ #,##0.00");
			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, ($linha), 'PREVISÃO DO USO DO TOTAL DE RECURSOS');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($linha), $total_orcado);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, ($linha), round($total_despesas*100/$total_orcado,2));
						
			// Zoom da Pagina
			$objPHPExcel->getActiveSheet()->getSheetView()->setZoomScale(80);
			
			// Podemos renomear o nome das planilha atual, lembrando que um único arquivo pode ter várias planilhas
			$objPHPExcel->getActiveSheet()->setTitle('Relatório');
			
			// Cabeçalho do arquivo para ele baixar
			//header('Content-Type: application/vnd.ms-excel'.$ano);
			//header('Content-Disposition: attachment;filename="planilha_'.$ano.'.xls"');
			//header('Cache-Control: no-cache');
			
			// Acessamos o 'Writer' para poder salvar o arquivo
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			
			// Salva diretamente no output, poderíamos mudar arqui para um nome de arquivo em um diretório ,caso não quisessemos jogar na tela
			//$objWriter->save('php://output');
			$objWriter->save('planilha-pdcj-relatorio-'.$ano.'.xls');
			
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
		/*
		seta_cor_cedula($objPHPExcel,'A'.$linha,'9BC2E6');
		seta_cor_cedula($objPHPExcel,'B'.$linha,'9BC2E6');
		seta_cor_cedula($objPHPExcel,'C'.$linha,'FFFF00');
		seta_cor_cedula($objPHPExcel,'D'.$linha,'FFFF00');
		seta_cor_cedula($objPHPExcel,'E'.$linha,'FFFF00');
		seta_cor_cedula($objPHPExcel,'F'.$linha,'00B050');
		seta_cor_cedula($objPHPExcel,'G'.$linha,'00B050');
		seta_cor_cedula($objPHPExcel,'H'.$linha,'00B050');
		seta_cor_cedula($objPHPExcel,'I'.$linha,'F4B084');
		seta_cor_cedula($objPHPExcel,'J'.$linha,'F4B084');
		seta_cor_cedula($objPHPExcel,'K'.$linha,'F4B084');
		seta_cor_cedula($objPHPExcel,'L'.$linha,'FFD966');
		seta_cor_cedula($objPHPExcel,'M'.$linha,'FFD966'); 
		*/
		
		seta_cor_cedula($objPHPExcel,'A'.$linha.':B'.$linha,'9BC2E6');
		seta_cor_cedula($objPHPExcel,'C'.$linha.':E'.$linha,'FFFF00');
		seta_cor_cedula($objPHPExcel,'F'.$linha.':H'.$linha,'00B050');
		seta_cor_cedula($objPHPExcel,'I'.$linha.':K'.$linha,'F4B084');
		seta_cor_cedula($objPHPExcel,'L'.$linha.':M'.$linha,'FFD966');
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
