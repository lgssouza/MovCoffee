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
	
	if(isset($_GET['id_subgrupo']))
	{
		$id_subg = $_GET['id_subgrupo'];				
				
		if(!empty($id_subg) && $id_subg != "undefined")		 			
		{
			
			/** INICIO FILTROS **/
			$filtros = "WHERE a.fk_id_subg = $id_subg";
			
			
			if(isset($_GET["descricao"]))
			{
				if(!empty($_GET["descricao"]) && $_GET["descricao"] != "undefined" && $_GET["descricao"] != "nenhum")
				{
					$filtros = $filtros . " AND item_descricao  = '" . $_GET["descricao"] . "'";
				}
			}
			
			if(isset($_GET["data"]))
			{
				if(!empty($_GET["data"]) && $_GET["data"] != "undefined" && $_GET["data"] != "nenhum")
				{
					$filtros = $filtros . " AND item_data  >= '" . implode("-",array_reverse(explode("/",($_GET["data"])))) . "' ";			
				}
			}
			
			if(isset($_GET["data2"]))
			{
				if(!empty($_GET["data2"]) && $_GET["data2"] != "undefined" && $_GET["data2"] != "nenhum")
				{
					$filtros = $filtros . " AND item_data  <= '" . implode("-",array_reverse(explode("/",($_GET["data2"])))) . "' ";			
				}
			}
			
			$filtros = $filtros . " order by a.item_data desc";
			
			/** FIM FILTROS **/
						
			$rs = $mysqli->query('SELECT count(*) FROM tb_pdcj_item_rel a inner join tb_pdcj_subgrupo_rel_valores b on a.fk_id_subg = b.id_subgrupo_valores inner join tb_pdcj_subgrupo_rel c on b.fk_id_subg = c.id_subgrupo '.$filtros );
			
			$sql_descricao = "select b.subg_descricao from tb_pdcj_subgrupo_rel_valores a inner join tb_pdcj_subgrupo_rel b on a.fk_id_subg = b.id_subgrupo where id_subgrupo_valores = $id_subg limit 0,1";
			$rs_descricao 	= $mysqli->query($sql_descricao);
			if($rs_descricao)
			{
				$registro_desc	= $rs_descricao->fetch_object();
				$descricao 		= $registro_desc->subg_descricao;				
				$rs_descricao->close();	
			}
		}
	}	
	
    $inicial = ($pagina-1) * $registros;
	
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
	
	$sql 	= "SELECT a.*, c.subg_descricao FROM tb_pdcj_item_rel a inner join tb_pdcj_subgrupo_rel_valores b on a.fk_id_subg = b.id_subgrupo_valores inner join tb_pdcj_subgrupo_rel c on b.fk_id_subg = c.id_subgrupo $filtros 
			   limit $inicial, $registros";
	$rs 	= $mysqli->query($sql);
			
	if($rs)
	{
		$itens = array();
        
		while($item = $rs->fetch_object())
		{
			array_push($itens, $item);
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
					PDCJ
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					Lançamentos
					<i class="fa fa-angle-right"></i>
				</li>
								
				<li>
					<a href="consulta_lancamentos_gastos_rel.php">Gastos</a>
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="detalhe_lancamentos_gastos_rel.php?id_subgrupo=<?php echo $id_subg; ?>">Detalhes</a>
				</li>				
			</ul>
			
			<div class="btn-toolbar">
				<div class="btn-group pull-right">	
					<button style="width: 110px" type="button" class="btn btn-fit-height default green-jungle" id="btn_filtro_movimentacao_gastos">
						<i class="fa fa-search"></i>
						&nbsp;Pesquisar 						
					</button>
				</div>
				
				<div class="btn-group pull-right">
					<button style="width: 110px" type="button" class="btn btn-fit-height default green-jungle" id="btn_novoitemgasto" value="<?php echo $id_subg ?>" onclick="cadastrolancamentosgastosrel()">
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
							<i class="fa fa-dollar"></i>Lançamentos dos Gastos - <?php echo $descricao?>
						</div>
						
						<div class="tools">						
							
							<!-- <a href="javascript:;" class="collapse"></a>-->
						</div>
					</div>
					
					<div class="portlet-body">
						<div class="table-responsive">
							<table class="table table-striped table-hover" id="tbl_detalhes_gastos_rel">
								<thead>
									<tr>
										<th style="display:none;">fk_id_subg</th>
										<th style="display:none;">id_item</th>
		          						<th>Descrição</th>		          						
		          						<th>Valor - Prêmio</th>
		          						<th>Valor - Outros</th>
		          						<th>Data</th>
		          						<th>Responsável</th>
		          						
		          						
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
				
							  			for($i=0;$i<count($itens);$i++)
										{
											$valorpremio = 0;
											if($itens[$i]->item_valor_premio!=NULL)
											{
												$valorpremio = 'R$ '.number_format($itens[$i]->item_valor_premio, 2, ',', '.');
												$valorpremio 	= str_replace('.','',$valorpremio);
											}
											else
											{
												$valorpremio = 'R$ '.number_format(0, 2, ',', '.');
												
											}
											
											$valoroutros = 0;
											if($itens[$i]->item_valor_outros!=NULL)
											{
												$valoroutros = 'R$ '.number_format($itens[$i]->item_valor_outros, 2, ',', '.');
												$valoroutros 	= str_replace('.','',$valoroutros);
											}
											else
											{
												$valoroutros = 'R$ '.number_format(0, 2, ',', '.');
												
											}
											
											$data = date('d/m/Y', strtotime($itens[$i]->item_data));
													
									        echo	'<tr>
									        			<td style="display:none;">'.$itens[$i]->fk_id_subg.'</td>
									        			<td style="display:none;">'.$itens[$i]->id_item.'</td>
										          		<td>'.$itens[$i]->item_descricao.'</td>										          		
										          		<td>'.$valorpremio.'</td>
										          		<td>'.$valoroutros.'</td>
										          		<td>'.$data.'</td>												          		
										          		<td>'.$itens[$i]->item_responsavel.'</td>';
										          		
										          		
														//if(verificapermissaoedicao("Categorias de Funcionário",$permissoes))
														//{
															echo '<td align="center"><a><span class="glyphicon glyphicon-pencil" iditem="'.$itens[$i]->id_item.'" idsubgrupo="'.$id_subg.'" style="color:green;width:100%;height:100%"></span></a></td>';
														//}
														//
														//if(verificapermissaoexclusao("Categorias de Funcionário",$permissoes))
														//{
															echo '<td align="center"><a><span class="glyphicon glyphicon-trash" iditem="'.$itens[$i]->id_item.'" idsubgrupo="'.$id_subg.'"  style="color:red;width:100%;height:100%"></span></a></td>';
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
												'detalheslancamentosgastosrel', /** Nome da função javascrip arquivo 'funcoes.js' **/
												'itens gastos',				 /** Descrição que aparecera na paginação **/
												$id_subg
											  );
							
						?>
					
					</div>
				</div>
				<!-- END SAMPLE TABLE PORTLET-->
				
			</div>
		</div>
		<!-- END PAGE CONTENT-->
		<!-- INICIO FIM MODAL DE CONSULTA -->
		<div id="modal_filtros_movimentacao_gastos" class="modal " tabindex="-1" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">
							<div id="titulomodalpp">
								<i class="fa fa-filter"></i>&nbsp;
								Filtros para Consulta de Movimentação de Gastos
							</div>
						</h4>
					</div>
					
					<div class="modal-body">
						<div class="scroller" style="height:300px" data-always-visible="1" data-rail-visible="1">
							<div class="row">										
								<div class="col-md-12">
									<input type="hidden" class="form-control" id="txtfiltroidsubg" value="<?php echo $id_subg; ?>">		
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
						<button type="submit" class="btn blue" id="btn_pesquisa_movimentacao_gastos">
							<i class="fa fa-search"></i>
							&nbsp;Pesquisar
						</button>
						
						<button type="button" class="btn red-thunderbird" id="btn_limpar_pesquisa_movimentacao_gastos">
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