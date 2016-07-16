<?php
	
	date_default_timezone_set('America/Sao_Paulo');
	
	include_once('verificaLogin.php');
	include_once('verificapermissao.php');
	
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
	
	/** INICIO FILTROS **/
	$filtros = " WHERE 1 = 1 ";
	
	if(isset($_GET["descricao"]))
	{
		if(!empty($_GET["descricao"]) && $_GET["descricao"] != "undefined" && $_GET["descricao"] != "nenhum")
		{
			$filtros = $filtros . " AND id_conta_pagar = " . $_GET["descricao"] . " ";
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
    $rs        	= $mysqli->query('SELECT count(*) FROM tb_conta_pagar a inner join tb_fornecedor b on a.fk_id_fornec = b.id_fornec'.$filtros);
	
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
		
	$sql 	= "	SELECT a.id_conta_pagar, a.descricao, a.valor, date_format(a.data_abertura,'%d/%m/%Y') AS dataabertura, 
				date_format(a.data_vencimento,'%d/%m/%Y') AS datavencimento, b.fornec_nome as nome_fornec,
				date_format(a.data_baixa,'%d/%m/%Y') AS databaixa
				FROM tb_conta_pagar a inner join tb_fornecedor b on a.fk_id_fornec = b.id_fornec $filtros
			   	limit $inicial, $registros";
	$rs 	= $mysqli->query($sql);
	
	if($rs)
	{
		$contas = array();
        
		while($conta = $rs->fetch_object())
		{
			array_push($contas, $conta);
		}
		
		$rs->close();
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
					<a href="consulta_conta_pagar.php">Contas a Pagar</a>
				</li>
			</ul>
			
			<div class="page-toolbar">
				<div class="btn-toolbar">
					<div class="btn-group pull-right">
						<button style="width: 110px" type="button" class="btn btn-fit-height default green-jungle" id="relatorio_contas_pagar">
							<i class="fa fa-print"></i>
							&nbsp;Relatório 						
						</button>
					</div>
					
					<div class="btn-group pull-right">	
						<button style="width: 110px" type="button" class="btn btn-fit-height default green-jungle" id="btn_filtros_conta">
							<i class="fa fa-search"></i>
							&nbsp;Pesquisar 						
						</button>
					</div>
					
					<div class="btn-group pull-right">
						<button style="width: 110px" type="button" class="btn btn-fit-height default green-jungle" onclick="cadastrocontaspagar()">
							<i class="fa fa-plus"></i>							
							&nbsp;Novo 						
						</button>				
					</div>
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
							<i class="fa fa-money"></i>Consulta de Contas a Pagar
						</div>
						
						<div class="tools">
							<!-- <a href="javascript:;" class="collapse"></a>-->
						</div>
					</div>
					
					<div class="portlet-body">
						<div class="table-responsive">
							<table class="table table-striped table-hover" id="tbl_contaspagar">
								<thead>
									<tr>
										<th style="display:none;">Código</th>														
		          						<th>Fornecedor</th>
		          						<th>Descrição</th>
		          						<th>Valor</th>
		          						<th>Data Lanc.</th>
		          						<th>Data Vencto.</th>
		          						<th>Data Baixa</th>
		          						
		          						
		          						<?php
						          			//if(verificapermissaoedicao("Categorias de Funcionário",$permissoes))
						          			//{
						          				echo '<th style="text-align: center">Baixar</th>';
											//}
										?>
										
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
											$valor = 'R$ '.number_format($contas[$i]->valor, 2, ',', '.');
											$valor 	= str_replace('.','',$valor);
												
									        echo	'<tr>
										          		<td style="display:none;">'.$contas[$i]->id_conta_pagar.'</td>
										          		<td>'.$contas[$i]->nome_fornec.'</td>
										          		<td>'.$contas[$i]->descricao.'</td>										          		
										          		<td>'.$valor.'</td>
										          		<td>'.$contas[$i]->dataabertura.'</td>
										          		<td>'.$contas[$i]->datavencimento.'</td>
										          		<td>'.$contas[$i]->databaixa.'</td>';
										          		
														if($contas[$i]->databaixa==null)
														{
															echo '	<td align="center">
																		<a>
																			<span class="glyphicon glyphicon-check" id="'.$contas[$i]->id_conta_pagar.'" databaixa="'.$contas[$i]->databaixa.'" style="color:green;width:100%;height:100%"></span>
																		</a>
																	</td>';
														}
														else
														{
															echo '	<td align="center">
																		<a>
																			<span class="glyphicon glyphicon-check" id="'.$contas[$i]->id_conta_pagar.'" databaixa="'.$contas[$i]->databaixa.'" style="color:red;width:100%;height:100%"></span>
																		</a>
																	</td>';
														}
														//if(verificapermissaoedicao("Categorias de Funcionário",$permissoes))
														//{
															echo '	<td align="center">
																		<a>
																			<span class="glyphicon glyphicon-pencil" id="'.$contas[$i]->id_conta_pagar.'" style="color:green;width:100%;height:100%"></span>
																		</a>
																	</td>';
														//}
														//
														//if(verificapermissaoexclusao("Categorias de Funcionário",$permissoes))
														//{
															echo '	<td align="center">
																		<a>
																			<span class="glyphicon glyphicon-trash" id="'.$contas[$i]->id_conta_pagar.'" style="color:red;width:100%;height:100%"></span>
																		</a>
																	</td>';
														//}
														
															
							
											echo	'</tr>';
										}
										
							    	?>
							    	
								</tbody>
							</table>
						</div>
					
						<?php
							
							/** Inclui Função Paginação **/
							include_once('Funcoes/paginacao.php');
							
							/** Imprime Paginação **/
							echo paginacao_php(
												$pagina,					 /** Pagina atual **/
												$registros,					 /** Quantidade de registros por paginação  **/
												$totalpaginas,				 /** Total de paginas **/
												$totalregistros,			 /** Total de registros **/
												'consultacontaspagar', 			 /** Nome da função javascrip arquivo 'funcoes.js' **/
												'contas a pagar'					 /** Descrição que aparecera na paginação **/
											  );
							
						?>
					
					</div>
				</div>
				<!-- END SAMPLE TABLE PORTLET-->
				
			</div>
		</div>
		<!-- END PAGE CONTENT-->
		<!-- INICIO FIM MODAL DE BAIXA -->
		<div id="modal_baixa_contas_pagar" class="modal " tabindex="-1" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">
							<div id="titulomodalpp">
								<i class="fa fa-filter"></i>&nbsp;
								Realizar Baixa de Conta a Pagar
							</div>
						</h4>
					</div>
					
					<div class="modal-body">
						<div class="scroller" style="height:300px" data-always-visible="1" data-rail-visible="1">
							<div class="row">										
								<div class="col-md-6">
									<div class="form-group">	
										<label class="control-label">Conta Baixa</label>
										
										<div class="input-group">
											<span class="input-group-addon">
												<span class="fa fa-credit-card"></span>													 
											</span>
											
											<input type="hidden" class="form-control" id="txtidcontapagarbaixa" name="txtidcontapagarbaixa">
												
											<select class="form-control select2me" id="cmbconta_conta_pagar">	
												<option selected value="nenhum">Selecione uma conta...</option>	
																				
												<?php 
												
													$sql 			= "SELECT * FROM `tb_conta`";
													$rsfiltro	= $mysqli->query($sql);
													$sql 			= "";
										
													if($rsfiltro)
													{
														$regs = array();
										        
														while($reg = $rsfiltro->fetch_object())
														{
															array_push($regs, $reg);
															
														}
												
														$rsfiltro->close();
														
														for($i=0;$i<count($regs);$i++)
														{
																echo "<option value=".$regs[$i]->id_conta.">".$regs[$i]->conta_banco." - ".$regs[$i]->conta_descricao."</option>";
														}
													}
																								
												?>
											</select>
										</div>
									</div>				
								</div>
							</div>		
							<div class="row">
								<div class="col-md-6">
									<label class="control-label">Data de Baixa</label>
									
									<div class="input-group date date-pickerbr">
										<span class="input-group-btn">
											<button class="btn default date-set" type="button">
												<i class="fa fa-calendar"></i>
											</button>
										</span>
										
										<input type="text" size="16" class="form-control" id="txtdatabaixa" placeholder="Digite a data de baixa">
										
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="modal-footer">
						<button type="submit" class="btn blue" id="btn_baixa_conta_pagar">
							<i class="fa fa-search"></i>
							&nbsp;Baixa
						</button>
						
						<button type="button" class="btn red-thunderbird" id="btn_limpar_baixa_conta_pagar">
							<i class="fa fa-remove"></i>
							&nbsp;Limpar
						</button>
					</div>	
				</div>
			</div>
		</div>
		<!-- FIM MODAL DE BAIXA-->
		<!-- INICIO FIM MODAL DE CONSULTA -->
		<div id="modal_filtros_contas_pagar" class="modal " tabindex="-1" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">
							<div id="titulomodalpp">
								<i class="fa fa-filter"></i>&nbsp;
								Filtros para Consulta de Contas a Pagar
							</div>
						</h4>
					</div>
					
					<div class="modal-body">
						<div class="scroller" style="height:300px" data-always-visible="1" data-rail-visible="1">
							<div class="row">										
								
							</div>		
						</div>
					</div>
					
					<div class="modal-footer">
						<button type="submit" class="btn blue" id="btn_pesquisa_contas_pagar">
							<i class="fa fa-search"></i>
							&nbsp;Pesquisar
						</button>
						
						<button type="button" class="btn red-thunderbird" id="btn_limpar_pesquisa_contas_pagar">
							<i class="fa fa-remove"></i>
							&nbsp;Limpar
						</button>
					</div>	
				</div>
			</div>
		</div>
		<!-- FIM MODAL DE CONSULTA-->
	</div>
</div>
<!-- END CONTENT -->

<?php

	$mysqli->close();
	
?>