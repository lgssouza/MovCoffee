<?php
	
	date_default_timezone_set('America/Sao_Paulo');
	
	include_once('verificaLogin.php');
	include_once('verificapermissao.php');
	
	//if(!verificapermissao("Funcionários",$permissoes))
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
	
	if(isset($_GET["produtor"]))
	{
		if(!empty($_GET["produtor"]) && $_GET["produtor"] != "undefined" && $_GET["produtor"] != "nenhum")
		{
			$filtros = $filtros . " AND id_prod = " . $_GET["produtor"] . " ";
		}
	}
	
	$filtros = $filtros . " order by prod_nome";
	
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
    $rs        	= $mysqli->query('SELECT COUNT(*) FROM tb_produtor'.$filtros);
	
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
	 
	$sql 	= "SELECT * FROM tb_produtor $filtros
			   limit $inicial, $registros";
	
	$rs     = $mysqli->query($sql);
	
	if($rs)
	{
		$produtores = array();
        
		while($produtor = $rs->fetch_object())
		{
			array_push($produtores, $produtor);
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
					Cadastros
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="consulta_produtor.php">Produtores</a>
				</li>
			</ul>

			
			<div class="page-toolbar">
				<div class="btn-toolbar">
					<div class="btn-group pull-right">
						<button style="width: 110px" type="button" class="btn btn-fit-height default green-jungle" id="relatorio_produtor">
							<i class="fa fa-print"></i>
							&nbsp;Relatório 						
						</button>
					</div>
					
					<div class="btn-group pull-right">	
						<button style="width: 110px" type="button" class="btn btn-fit-height default green-jungle" id="btn_filtros_produtor">
							<i class="fa fa-search"></i>
							&nbsp;Pesquisar 						
						</button>
					</div>
					
					<div class="btn-group pull-right">
						<button style="width: 110px" type="button" class="btn btn-fit-height default green-jungle" onclick="cadastroprodutor()">
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
							<i class="fa fa-users"></i>Consulta de Produtores
						</div>
						
						<div class="tools">
							<!-- <a href="javascript:;" class="collapse"></a>-->
						</div>
					</div>
					
					<div class="portlet-body">
						<div class="table-responsive">
							<table class="table table-striped table-hover" id="tbl_produtor">
								<thead>
									<tr>
										<th style="display: none">Código</th>
						          		<th>Nome</th>
						          								          		
						          		<?php
						          			//if(verificapermissaoedicao("Funcionários",$permissoes))
						          			//{
						          				echo '<th style="text-align: center">Editar</th>';
											//}
										?>
						          		
						          		<?php
						          			//if(verificapermissaoexclusao("Funcionários",$permissoes))
						          			//{
						          				echo '<th style="text-align: center">Excluir</th>';
											//}
										?>																	
									</tr>
								</thead>
								
								<tbody>									
									<?php
										   		
						      			for($i=0;$i<count($produtores);$i++)
										{
									        echo	'<tr>
										          		<td style="display: none">'.$produtores[$i]->id_prod.'</td>
										          		<td>'.$produtores[$i]->prod_nome.'</td>';
										          												          		
														//if(verificapermissaoedicao("Funcionários",$permissoes))
														//{
															echo '	<td align="center">
																		<a>
																			<span class="glyphicon glyphicon-pencil" id="'.$produtores[$i]->id_prod.'" style="color:green;width:100%;height:100%"></span>
																		</a>
																	</td>';
														//}
														
														//if(verificapermissaoexclusao("Funcionários",$permissoes))
														//{
															echo '	<td align="center">
																		<a>
																			<span class="glyphicon glyphicon-trash" id="'.$produtores[$i]->id_prod.'" style="color:red;width:100%;height:100%"></span>
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
												'consultaprodutor',			 /** Nome da função javascrip arquivo 'funcoes.js' **/
												'produtores'				 /** Descrição que aparecera na paginação **/
											  );
							
						?>
						
					</div>
				</div>
				<!-- END SAMPLE TABLE PORTLET-->
				
			</div>
		</div>
		<!-- END PAGE CONTENT-->
		<!-- INICIO FIM MODAL DE CONSULTA -->
		<div id="modal_filtros_produtores" class="modal " tabindex="-1" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">
							<div id="titulomodalpp">
								<i class="fa fa-filter"></i>&nbsp;
								Filtros para Consulta de Produtores
							</div>
						</h4>
					</div>
					
					<div class="modal-body">
						<div class="scroller" style="height:300px" data-always-visible="1" data-rail-visible="1">
							<div class="row">										
								<div class="col-md-12">
									<div class="form-group">	
										<label class="control-label">Produtores</label>
										
										<div class="input-group">
											<span class="input-group-addon">
												<span class="fa fa-users"></span>													 
											</span>
											
											<select class="form-control select2me" id="cmbfiltroprodutores">	
												<option selected value="nenhum">Selecione um produtor...</option>	
																				
												<?php 
												
													$sql 			= "SELECT * FROM `tb_produtor`";
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
																echo "<option value=".$regs[$i]->id_prod.">".$regs[$i]->prod_nome."</option>";
														}
													}
																								
												?>
											</select>
										</div>
									</div>				
								</div>
							</div>		
						</div>
					</div>
					
					<div class="modal-footer">
						<button type="submit" class="btn blue" id="btn_pesquisa_produtor">
							<i class="fa fa-search"></i>
							&nbsp;Pesquisar
						</button>
						
						<button type="button" class="btn red-thunderbird" id="btn_limpar_pesquisa_produtor">
							<i class="fa fa-remove"></i>
							&nbsp;Limpar
						</button>
					</div>	
				</div>
			</div>
		</div>
		<!-- FIM MODAL DE CONSULTA-->
		<!-- INICIO MODAL DE RELATÓRIO -->
		<div id="modal_relatorio_produtor" class="modal " tabindex="-1" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">
							<div id="titulomodalpp">
								<i class="fa fa-filter"></i>&nbsp;
								Filtros para Relatório de Produtor
							</div>
						</h4>
					</div>
					
					<div class="modal-body">
						<div class="scroller" style="height:300px" data-always-visible="1" data-rail-visible="1">							
							<div class="row">										
								<div class="col-md-12">
									<div class="form-group">	
										<label class="control-label">Produtor</label>										
										<div class="input-group">
											<span class="input-group-addon">
												<span class="fa fa-users"></span>													 
											</span>
											
											<select class="form-control select2me" id="cmbrelfiltroprodutores">	
												<option selected value="nenhum">Selecione um produtor...</option>	
																				
												<?php 
												
													$sql 			= "SELECT * FROM `tb_produtor` order by prod_nome";
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
																echo "<option value=".$regs[$i]->id_prod.">".$regs[$i]->prod_nome."</option>";
														}
													}
																								
												?>
											</select>
										</div>
									</div>				
								</div>
							</div>		
						</div>
					</div>
					
					<div class="modal-footer">
						<button type="submit" class="btn blue" id="btn_relatorio_filtro_produtor">
							<i class="fa fa-print"></i>
							&nbsp;Imprimir
						</button>
						
						<button type="button" class="btn red-thunderbird" id="btn_limpar_relatorio_produtor">
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