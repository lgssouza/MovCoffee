
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
	
	/** INICIO FILTROS **/
	$filtros = " WHERE 1 = 1 ";
	
	if(isset($_GET["datainicial"]))
	{
		if(!empty($_GET["datainicial"]) && $_GET["datainicial"] != "undefined" && $_GET["datainicial"] != "nenhum")
		{
			$filtros = $filtros . " AND recursos_data  >= '" . implode("-",array_reverse(explode("/",($_GET["datainicial"])))) . "' ";			
		}
	}
	
	if(isset($_GET["datafinal"]))
	{
		if(!empty($_GET["datafinal"]) && $_GET["datafinal"] != "undefined" && $_GET["datafinal"] != "nenhum")
		{
			$filtros = $filtros . " AND recursos_data  <= '" . implode("-",array_reverse(explode("/",($_GET["datafinal"])))) . "' ";			
		}
	}
	
	if(isset($_GET["descricao"]))
	{
		if(!empty($_GET["descricao"]) && $_GET["descricao"] != "undefined" && $_GET["descricao"] != "nenhum")
		{
			$filtros = $filtros . " AND recursos_descricao  = '" . $_GET["descricao"] . "'";
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
	
    $inicial   = ($pagina-1) * $registros;
    $rs        = $mysqli->query('select count(*) from tb_pdcj_recursos a '.$filtros.' order by a.recursos_data desc');
	
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
	
	$sql 	= "select * from tb_pdcj_recursos a $filtros order by a.recursos_data desc   
			   	limit $inicial, $registros";
				
	$rs 	= $mysqli->query($sql);
	
	if($rs)
	{
		$regs = array();
        
		while($reg = $rs->fetch_object())
		{
			array_push($regs, $reg);
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
					<a href="consulta_lancamentos_recursos_plan.php">Recursos</a>
				</li>
			</ul>
			
			<div class="page-toolbar">
				<div class="btn-toolbar">
					<div class="btn-group pull-right">	
						<button style="width: 110px" type="button" class="btn btn-fit-height default green-jungle" id="btn_filtro_recursos">
							<i class="fa fa-search"></i>
							&nbsp;Pesquisar 						
						</button>
					</div>
					
					<div class="btn-group pull-right">
						<button style="width: 110px" type="button" class="btn btn-fit-height green-jungle" onclick="cadastrolancamentosrecursosplan()">
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
							<i class="fa fa-opera"></i>Consulta de Recursos
						</div>
						
						<div class="tools">
							<!-- <a href="javascript:;" class="collapse"></a>-->
						</div>
					</div>
					
					<div class="portlet-body">
						<div class="table-responsive">
							<table class="table table-striped table-hover" id="tbl_lancamentos_recursos_plan">
								<thead>
									<tr>
										<th style="display:none;">Código</th>
		          						<th>Descrição</th>
		          						<th>Valor</th>
		          						<th>Quant.</th>
		          						<th>Total</th>
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
										      		
							  			for($i=0;$i<count($regs);$i++)
										{
											$total=0;
											if($regs[$i]->recursos_valor!=0 && $regs[$i]->recursos_valor!=0)
											{			
												$total = $regs[$i]->recursos_valor * $regs[$i]->recursos_qtd;												
												$total = 'R$ '.number_format($total, 2, ',', '.');
												$valor = 'R$ '.number_format($regs[$i]->recursos_valor, 2, ',', '.');
											}
											else
											{
												$total = 'R$ '.number_format(0, 2, ',', '.');
											}
											
											$data = date('d/m/Y', strtotime($regs[$i]->recursos_data));	
											
											echo	'<tr>
										          		<td style="display:none;">'.$regs[$i]->id_recursos.'</td>
										          		<td>'.$regs[$i]->recursos_descricao.'</td>
										          		<td>'.$valor.'</td>
										          		<td>'.$regs[$i]->recursos_qtd.'</td>
										          		<td>'.$total.'</td>										          		
										          		<td>'.$data.'</td>';
										          		
														//if(verificapermissaoedicao("Categorias de Funcionário",$permissoes))
														//{
															echo '	<td align="center">
																		<a>
																			<span class="glyphicon glyphicon-pencil" id="'.$regs[$i]->id_recursos.'" style="color:green;width:100%;height:100%"></span>
																		</a>
																	</td>';
														//}
														//
														//if(verificapermissaoexclusao("Categorias de Funcionário",$permissoes))
														//{
															echo '	<td align="center">
																		<a>
																			<span class="glyphicon glyphicon-trash" id="'.$regs[$i]->id_recursos.'" style="color:red;width:100%;height:100%"></span>
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
												'consultalancamentosrecursosplan', /** Nome da função javascrip arquivo 'funcoes.js' **/
												'recursos'		 /** Descrição que aparecera na paginação **/
											  );
							
						?>
					
					</div>
				</div>
				<!-- END SAMPLE TABLE PORTLET-->
				
			</div>
		</div>
		<!-- END PAGE CONTENT-->
		<!-- INICIO FIM MODAL DE CONSULTA -->
		<div id="modal_filtros_recursos" class="modal " tabindex="-1" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">
							<div id="titulomodalpp">
								<i class="fa fa-filter"></i>&nbsp;
								Filtros para Consulta de Recursos (PDCJ)
							</div>
						</h4>
					</div>
					
					<div class="modal-body">
						<div class="scroller" style="height:300px" data-always-visible="1" data-rail-visible="1">
							<div class="row">										
								<div class="col-md-12">											
									<label class="control-label">Descrição</label>											  
									<div class="input-group">
										<span class="input-group-addon">
											<span class="fa fa-list"></span>													 
										</span>
										<input type="text" class="form-control" id="txtfiltrodescricao" placeholder="Digite a descrição do recurso">
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
											
											<input type="text" size="16" class="form-control" id="txtfiltrodatainicial" placeholder="Digite a data">
											
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
											
											<input type="text" size="16" class="form-control" id="txtfiltrodatafinal" placeholder="Digite a data">
											
										</div>
									</div>
								</div>	
							</div>		
						</div>
					</div>
					
					<div class="modal-footer">
						<button type="submit" class="btn blue" id="btn_pesquisa_recursos">
							<i class="fa fa-search"></i>
							&nbsp;Pesquisar
						</button>
						
						<button type="button" class="btn red-thunderbird" id="btn_limpar_pesquisa_recursos">
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
