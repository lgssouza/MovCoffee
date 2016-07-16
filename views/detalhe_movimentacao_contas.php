<?php
	
	date_default_timezone_set('America/Sao_Paulo');
	
	//include_once('verificaLogin.php');
	//include_once('verificapermissao.php');
	
	//if(!verificapermissao("Categorias de Funcionário",$permissoes))
	//{
	//	header("Location: home.php");  
	//	exit;
	//}	
	
	include_once('connect.php');
	
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
		}
	}
	
	if(isset($_GET["data2"]))
	{
		if(!empty($_GET["data2"]) && $_GET["data2"] != "undefined" && $_GET["data2"] != "nenhum")
		{
			$filtros = $filtros . " AND mov_contas_data  <= '" . implode("-",array_reverse(explode("/",($_GET["data2"])))) . "' ";			
		}
	}
	
	/** FIM FILTROS **/
	
	$pagina = 1; 
	
	if(isset($_GET['pagina']))
	{
		if(!empty($_GET['pagina']) && $_GET['pagina'] != "undefined")
		{
			$pagina = $_GET['pagina'];
		}
	}
	
	$registros = 10;

	if(isset($_GET['registros']))
	{
		if(!empty($_GET['registros']) && $_GET['registros'] != "undefined")
		{
			$registros = $_GET['registros'];
		}
	}
	
    $inicial 	= ($pagina-1) * $registros;
    $rs = $mysqli->query('SELECT COUNT(*) FROM mov_contas '.$filtros);
		
	if($rs)
	{		
		$totalregistros 	= $rs->fetch_row();
		$totalregistros 	= $totalregistros[0];
		$divisaoregistros 	= $totalregistros / 5;
		$partefracionada 	= $divisaoregistros-floor($divisaoregistros);
		$parteinteira 		= floor($divisaoregistros);

		if($partefracionada > 0.0)
		{
			$divisaoregistros	= $parteinteira * 5 + 5;
		}
		else
	    {
			$divisaoregistros	= $parteinteira * 5;
		}
		
		$totalpaginas 		= $divisaoregistros / $registros;
		$partefracionadapag = $totalpaginas-floor($totalpaginas);
		$parteinteirapag	= floor($totalpaginas);
		
		if($partefracionadapag > 0.0)
		{
			$totalpaginas	= $parteinteirapag + 1;
		}
		else
	    {
			$totalpaginas	= $parteinteirapag;
		}		
	}
	
	if($inicial<0)
	{
		$inicial = 0;
		$pagina	 = 1;
	}
			
	$sql 	= "SELECT * FROM mov_contas $filtros order by mov_contas_data desc, id_mov_contas DESC    
			   limit $inicial, $registros";
	
	$rs 	= $mysqli->query($sql);
	
	$sql2 	= "SELECT conta_saldo FROM tb_conta_saldo WHERE fk_id_conta = ".$id_conta;
	$rs2 	= $mysqli->query($sql2);
	
	$sql3 	= "SELECT * FROM tb_conta WHERE id_conta = ".$id_conta;
	$rs3 	= $mysqli->query($sql3);
		
	if($rs)
	{
		$contas = array();
        
		while($conta = $rs->fetch_object())
		{
			array_push($contas, $conta);
		}
		
		$rs->close();
	}
	
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
	
	
	
?>

<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
	<div class="page-content">
		
		<!-- BEGIN MENSAGENS-->
		<?php
			if(isset($_GET['msg']))
			{
				if(!empty($_GET['msg']))
				{
					if($_GET['tipo_msg'] == "sucesso")
					{
		?>
		
						<div class="alert alert-success">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
							<strong>Sucesso!</strong> 
							<?php echo $_GET['msg'];?>
						</div>
		
		<?php
					}
					else if($_GET['tipo_msg'] == "erro")
					{
		?>
		
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
							<strong>Erro!</strong> 
							<?php echo $_GET['msg'];?>
						</div>
		
		<?php
					}
				}
			}
		?>
		<!-- END MENSAGENS-->
		
		<!-- BEGIN PAGE HEADER-->
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="inicio.php">Início</a>
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					Financeiro
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="consulta_movimentacao_contas.php">Saldo Contas</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="detalhe_movimentacao_contas.php?id_conta=<?php echo $id_conta ?>">Movimentação</a>
				</li>
			</ul>
			
			<div class="btn-toolbar">
				<div class="btn-group pull-right">
					<button style="width: 110px" type="button" class="btn btn-fit-height default green-jungle" id="btn_relatorio_movimentacao_contas">
						<i class="fa fa-print"></i>
						&nbsp;Relatório 						
					</button>
				</div>
				
				<div class="btn-group pull-right">	
					<button style="width: 110px" type="button" class="btn btn-fit-height default green-jungle" id="btn_filtro_movimentacao_contas">
						<i class="fa fa-search"></i>
						&nbsp;Pesquisar 						
					</button>
				</div>
				
				<div class="btn-group pull-right">
					<button style="width: 110px" type="button" class="btn btn-fit-height default green-jungle" id="btn_novomovimento" value="<?php echo $id_conta ?>" onclick="cadastromovimentacao()">
						<i class="fa fa-plus"></i>							
						&nbsp;Novo 						
					</button>				
				</div>
			</div>
		</div>
		<!-- END PAGE HEADER-->
		
		<!-- BEGIN PAGE CONTENT-->
		<div class="row">
			<div class="col-md-12">
				
				<!-- BEGIN SAMPLE TABLE PORTLET-->
				<div class="portlet box green-jungle">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-credit-card"></i>Movimentação - <?php echo $conta3->conta_banco .' ('.$conta3->conta_descricao.')' ?> 
						</div>
						
						<div class="tools">							
							<?php 
								if(isset($conta2->conta_saldo))
								{
									if($conta2->conta_saldo!=0)
									{
										echo 'Saldo Atual: R$ '.number_format($conta2->conta_saldo, 2, ',', '.');	
									}
									else
									{
										echo 'Saldo Atual: R$ '.number_format(0, 2, ',', '.');
									}
								}
								else
								{
									echo 'Saldo Atual: R$ '.number_format(0, 2, ',', '.');
								}
							?>
							<!-- <a href="javascript:;" class="collapse"></a>-->
						</div>
					</div>
					
					<div class="portlet-body">
						<div class="table-responsive">
							<table class="table table-striped table-hover" id="tbl_detalhes_contas">
								<thead>
									<tr>
										<th style="display:none;">id_conta</th>		
										<th style="display:none;">id_trans</th>
										<th style="display:none;">id_pdcj</th>																		
										<th style="display:none;">id_mov_conta</th>
		          						<th>Descrição</th>
		          						<th>Op</th>
		          						<th>Valor</th>
		          						<th>Saldo Anterior</th>
		          						<th>Data</th>
		          						
		          						<?php
						          			//if(verificapermissaoedicao("Categorias de Funcionário",$permissoes))
						          			//{
						          				echo '<th style="text-align: center">Editar</th>';
											//}
										?>
						          		
						          		<?php
						          			//if(verificapermissaoexclusao("Categorias de Funcionário",$permissoes))
						          			//{
						          				echo '<th style="text-align: center">Excluir</th>';
											//}
										?>
																	
									</tr>
								</thead>
								
								<tbody>
									
									<?php										      		
										
							  			for($i=0;$i<count($contas);$i++)
										{
											$valor = 0;
											if($contas[$i]->mov_contas_valor!=NULL)
											{
												$valor = 'R$ '.number_format($contas[$i]->mov_contas_valor, 2, ',', '.');
											}
											else
											{
												$valor = 'R$ '.number_format(0, 2, ',', '.');
												
											}
																						
											if($contas[$i]->mov_contas_op == 'E')
											{
												$saldoant = $contas[$i]->mov_contas_saldo - $contas[$i]->mov_contas_valor;
											}
											else 
											{
												$saldoant = $contas[$i]->mov_contas_saldo + $contas[$i]->mov_contas_valor;
											}
											
											$data = date('d/m/Y', strtotime($contas[$i]->mov_contas_data));	
													
									        echo	'<tr>
									        			<td style="display:none;">'.$contas[$i]->fk_id_conta.'</td>
									        			<td style="display:none;">'.$contas[$i]->mov_contas_transferencia.'</td>
									        			<td style="display:none;">'.$contas[$i]->mov_contas_pdcj.'</td>
									        			<td style="display:none;">'.$contas[$i]->id_mov_contas.'</td>									        			
										          		<td>'.$contas[$i]->mov_contas_descricao.'</td>
										          		<td>'.$contas[$i]->mov_contas_op.'</td>
										          		<td>'.$valor.'</td>
										          		<td>R$ '.number_format($saldoant, 2, ',', '.').'</td>
										          		<td>'.$data.'</td>';
										          		
										          		
														//if(verificapermissaoedicao("Categorias de Funcionário",$permissoes))
														//{
															echo '<td align="center"><a><span class="glyphicon glyphicon-pencil" id="'.$id_conta.'" idmov="'.$contas[$i]->id_mov_contas.'" id_trans="'.$contas[$i]->mov_contas_transferencia.'" id_pdcj="'.$contas[$i]->mov_contas_pdcj.'" style="color:green;width:100%;height:100%"></span></a></td>';
														//}
														//
														//if(verificapermissaoexclusao("Categorias de Funcionário",$permissoes))
														//{
															echo '<td align="center"><a><span class="glyphicon glyphicon-trash" id="'.$contas[$i]->id_mov_contas.'" idconta="'.$id_conta.'" id_pdcj="'.$contas[$i]->mov_contas_pdcj.'" style="color:red;width:100%;height:100%"></span></a></td>';
														//}
							
											echo	'</tr>';
										}
										
							    	?>
							    	
								</tbody>
							</table>
						</div>
					
						<?php
							
							/** Inclui Função Paginação **/
							include_once('Funcoes/paginacao2.php');
							
							/** Imprime Paginação **/
							echo paginacao_php(
												$pagina,					 /** Pagina atual **/
												$registros,					 /** Quantidade de registros por paginação  **/
												$totalpaginas,				 /** Total de paginas **/
												$totalregistros,			 /** Total de registros **/
												'detalhemovimentacaocontas', /** Nome da função javascrip arquivo 'funcoes.js' **/
												'movimentos',				 /** Descrição que aparecera na paginação **/
												$id_conta
											  );
							
						?>
					
					</div>
				</div>
				<!-- END SAMPLE TABLE PORTLET-->
				
			</div>
		</div>
		<!-- END PAGE CONTENT-->
		<!-- INICIO FIM MODAL DE CONSULTA -->
		<div id="modal_filtros_movimentacao_contas" class="modal " tabindex="-1" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">
							<div id="titulomodalpp">
								<i class="fa fa-filter"></i>&nbsp;
								Filtros para Consulta de Movimentação de Contas
							</div>
						</h4>
					</div>
					
					<div class="modal-body">
						<div class="scroller" style="height:300px" data-always-visible="1" data-rail-visible="1">
							<div class="row">										
								<div class="col-md-12">
									<input type="hidden" class="form-control" id="txtfiltroidconta" value="<?php echo $id_conta; ?>">		
									<label class="control-label">Descrição</label>											  
									<div class="input-group">
										<span class="input-group-addon">
											<span class="fa fa-list"></span>													 
										</span>
										<input type="text" class="form-control" id="txtfiltrodescricao" placeholder="Digite a descrição da movimentação">
									</div>			
								</div>
							</div>
							<br />
							<div class="row">										
								<div class="col-md-4">		
									<div class="form-group">
										<label class="control-label">Data Inicial</label>
										
										<div class="input-group date date-pickerbr">
											<span class="input-group-btn">
												<button class="btn default date-set" type="button">
													<i class="fa fa-calendar"></i>
												</button>
											</span>
											
											<input type="text" size="16" class="form-control" id="txtfiltrodata" placeholder="Digite a data">
											
										</div>
									</div>
								</div>	
								<div class="col-md-4">		
									<div class="form-group">
										<label class="control-label">Data Final</label>
										
										<div class="input-group date date-pickerbr">
											<span class="input-group-btn">
												<button class="btn default date-set" type="button">
													<i class="fa fa-calendar"></i>
												</button>
											</span>
											
											<input type="text" size="16" class="form-control" id="txtfiltrodata2" placeholder="Digite a data">
											
										</div>
									</div>
								</div>	
							</div>		
						</div>
					</div>
					
					<div class="modal-footer">
						<button type="submit" class="btn blue" id="btn_pesquisa_movimentacao_contas">
							<i class="fa fa-search"></i>
							&nbsp;Pesquisar
						</button>
						
						<button type="button" class="btn red-thunderbird" id="btn_limpar_pesquisa_movimentacao_contas">
							<i class="fa fa-remove"></i>
							&nbsp;Limpar
						</button>
					</div>	
				</div>
			</div>
		</div>
		<!-- FIM MODAL DE CONSULTA-->	
		<!-- INICIO MODAL DE RELATÓRIO -->
		<div id="modal_relatorio_movimentacao_contas" class="modal " tabindex="-1" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">
							<div id="titulomodalpp">
								<i class="fa fa-filter"></i>&nbsp;
								Filtros para Relatório de Movimentação de Contas
							</div>
						</h4>
					</div>
					
					<div class="modal-body">
						<div class="scroller" style="height:300px" data-always-visible="1" data-rail-visible="1">
							<div class="row">										
								<div class="col-md-12">
									<input type="hidden" class="form-control" id="txtfiltrorelidconta" value="<?php echo $id_conta; ?>">		
									<label class="control-label">Descrição</label>											  
									<div class="input-group">
										<span class="input-group-addon">
											<span class="fa fa-list"></span>													 
										</span>
										<input type="text" class="form-control" id="txtfiltroreldescricao" placeholder="Digite a descrição da movimentação">
									</div>			
								</div>
							</div>
							<br />
							<div class="row">										
								<div class="col-md-4">		
									<div class="form-group">
										<label class="control-label">Data Inicial</label>
										
										<div class="input-group date date-pickerbr">
											<span class="input-group-btn">
												<button class="btn default date-set" type="button">
													<i class="fa fa-calendar"></i>
												</button>
											</span>
											
											<input type="text" size="16" class="form-control" id="txtfiltroreldata" placeholder="Digite a data">
											
										</div>
									</div>
								</div>	
								<div class="col-md-4">		
									<div class="form-group">
										<label class="control-label">Data Final</label>
										
										<div class="input-group date date-pickerbr">
											<span class="input-group-btn">
												<button class="btn default date-set" type="button">
													<i class="fa fa-calendar"></i>
												</button>
											</span>
											
											<input type="text" size="16" class="form-control" id="txtfiltroreldata2" placeholder="Digite a data">
											
										</div>
									</div>
								</div>	
							</div>		
						</div>
					</div>
					
					<div class="modal-footer">
						<button type="submit" class="btn blue" id="btn_relatorio_filtro_movimentacao_contas">
							<i class="fa fa-print"></i>
							&nbsp;Imprimir
						</button>
						
						<button type="button" class="btn red-thunderbird" id="btn_limpar_relatorio_movimentacao_contas">
							<i class="fa fa-remove"></i>
							&nbsp;Limpar
						</button>
					</div>	
				</div>
			</div>
		</div>
		<!-- FIM MODAL DE RELATORIO-->
	</div>
</div>
<!-- END CONTENT -->

<?php

	$mysqli->close();
	
?>