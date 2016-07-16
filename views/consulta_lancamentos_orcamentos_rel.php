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
	
	if(isset($_GET["grupo"]))
	{
		if(!empty($_GET["grupo"]) && $_GET["grupo"] != "undefined" && $_GET["grupo"] != "nenhum")
		{
			$filtros = $filtros . " AND a.fk_id_grupo  =  ".$_GET["grupo"]. "";			
		}
	}
	
	if(isset($_GET["subgrupo"]))
	{
		if(!empty($_GET["subgrupo"]) && $_GET["subgrupo"] != "undefined" && $_GET["subgrupo"] != "nenhum")
		{
			$filtros = $filtros . " AND c.fk_id_subg  =  ".$_GET["subgrupo"]. "";			
		}
	}
	
	if(isset($_GET["ano"]))
	{
		if(!empty($_GET["ano"]) && $_GET["ano"] != "undefined" && $_GET["ano"] != "nenhum")
		{
						
		}
	}
	
	$filtros = $filtros . " order by c.subg_ano desc, b.grup_descricao asc, a.subg_descricao asc";
	
	
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
    $rs        = $mysqli->query('SELECT count(*) FROM tb_pdcj_subgrupo_rel a inner join tb_pdcj_grupo_rel b on a.fk_id_grupo = b.id_grupo inner join tb_pdcj_subgrupo_rel_valores c on a.id_subgrupo = c.fk_id_subg '.$filtros);
	
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
	
	$sql 	= "SELECT a.subg_descricao, b.id_grupo, b.grup_descricao, c.id_subgrupo_valores, 
				c.subg_premio_orcado, c.subg_outros_orcado, c.subg_ano 
				FROM tb_pdcj_subgrupo_rel a 
				inner join tb_pdcj_grupo_rel b on a.fk_id_grupo = b.id_grupo 
				inner join tb_pdcj_subgrupo_rel_valores c on a.id_subgrupo = c.fk_id_subg $filtros   
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
					<a href="consulta_lancamentos_orcamentos_rel.php">Orçamentos</a>
				</li>
			</ul>
			
			<div class="page-toolbar">
				<div class="btn-toolbar">			
					<div class="btn-group pull-right">	
						<button style="width: 110px" type="button" class="btn btn-fit-height default green-jungle" id="btn_filtro_orcamentos">
							<i class="fa fa-search"></i>
							&nbsp;Pesquisar 						
						</button>
					</div>							
					<div class="btn-group pull-right">
						<button style="width: 110px" type="button" class="btn btn-fit-height green-jungle" onclick="cadastrolancamentosorcamentosrel()">
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
							<i class="fa fa-opera"></i>Consulta de Orçamentos
						</div>
						
						<div class="tools">
							<!-- <a href="javascript:;" class="collapse"></a>-->
						</div>
					</div>
					
					<div class="portlet-body">
						<div class="table-responsive">
							<table class="table table-striped table-hover" id="tbl_lancamentos_orcamentos_rel">
								<thead>
									<tr>
										<th style="display:none;">Código</th>
		          						<th style="width: 150px">Grupo</th>
		          						<th>Sub-Grupo</th>
		          						<th style="width: 130px">Prêmio</th>
		          						<th style="width: 130px">Outros</th>
		          						<th>Ano</th>
		          								      
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
											if($regs[$i]->subg_premio_orcado==null){
												$premio_orcado = 'R$ '.number_format(0, 2, ',', '.');
											}
											else
											{
												$premio_orcado = 'R$ '.number_format($regs[$i]->subg_premio_orcado, 2, ',', '.');
												$premio_orcado 	= str_replace('.','',$premio_orcado);
											}
											
											if($regs[$i]->subg_outros_orcado==null){
												$outros_orcado = 'R$ '.number_format(0, 2, ',', '.');
												
											}
											else
											{
												$outros_orcado = 'R$ '.number_format($regs[$i]->subg_outros_orcado, 2, ',', '.');
												$outros_orcado 	= str_replace('.','',$outros_orcado);
											}
											
											
									        echo	'<tr>
										          		<td style="display:none;">'.$regs[$i]->id_subgrupo_valores.'</td>
										          		<td style="width: 150px">'.$regs[$i]->grup_descricao.'</td>
										          		<td>'.$regs[$i]->subg_descricao.'</td>										          		
										          		<td style="width: 130px">'.$premio_orcado.'</td>
										          		<td style="width: 130px">'.$outros_orcado.'</td>
										          		<td>'.$regs[$i]->subg_ano.'</td>';
										          		
														//if(verificapermissaoedicao("Categorias de Funcionário",$permissoes))
														//{
															echo '	<td align="center">
																		<a>
																			<span class="glyphicon glyphicon-pencil" id="'.$regs[$i]->id_subgrupo_valores.'" style="color:green;width:100%;height:100%"></span>
																		</a>
																	</td>';
														//}
														//
														//if(verificapermissaoexclusao("Categorias de Funcionário",$permissoes))
														//{
															echo '	<td align="center">
																		<a>
																			<span class="glyphicon glyphicon-trash" id="'.$regs[$i]->id_subgrupo_valores.'" style="color:red;width:100%;height:100%"></span>
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
												'consultalancamentosorcamentosrel', /** Nome da função javascrip arquivo 'funcoes.js' **/
												'orçamentos'		 /** Descrição que aparecera na paginação **/
											  );
							
						?>
					
					</div>
				</div>
				<!-- END SAMPLE TABLE PORTLET-->
				
			</div>
		</div>
		<!-- END PAGE CONTENT-->
		<!-- INICIO FIM MODAL DE CONSULTA -->
		<div id="modal_filtros_orcamentos" class="modal " tabindex="-1" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">
							<div id="titulomodalpp">
								<i class="fa fa-filter"></i>&nbsp;
								Filtros para Consulta de Orçamentos (PDCJ)
							</div>
						</h4>
					</div>
					
					<div class="modal-body">
						<div class="scroller" style="height:300px" data-always-visible="1" data-rail-visible="1">
							<div class="row">										
								<div class="col-md-12">
									<div class="form-group">	
										<label class="control-label">Grupo</label>
										<select class="form-control select2me" id="cmbfiltrogrupos">	
											<option selected value="nenhum">Selecione um grupo...</option>	
																			
											<?php 
											
												$sql 			= "SELECT * FROM `tb_pdcj_grupo_rel`";
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
															echo "<option value=".$regs[$i]->id_grupo.">".$regs[$i]->grup_descricao."</option>";
													}
												}
																							
											?>
										</select>										
									</div>				
								</div>
							</div>
							<br />
							<div class="row">										
								<div class="col-md-12">
									<div class="form-group">	
										<label class="control-label">Sub-Grupo</label>
										<select class="form-control select2me" id="cmbfiltrosubgrupos">	
											<option selected value="nenhum">Selecione um grupo...</option>	
																			
											<?php 
											
												$sql 			= "SELECT * FROM `tb_pdcj_subgrupo_rel`";
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
															echo "<option value=".$regs[$i]->id_subgrupo.">".$regs[$i]->subg_descricao."</option>";
													}
												}
																							
											?>
										</select>
									</div>				
								</div>
							</div>
							<br />	
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label">Ano PDCJ</label>											
										<div class="input-group date date-pickerano">
											<span class="input-group-addon">
												<span class="fa fa-calendar"></span>													 
											</span>
											<input type="text" class="form-control" id="txtano" placeholder="Digite o Ano" value="<?php echo $ano; ?>">
										</div>
									</div>
								</div>
							</div>	
						</div>
					</div>
					
					<div class="modal-footer">
						<button type="submit" class="btn blue" id="btn_pesquisa_orcamentos">
							<i class="fa fa-search"></i>
							&nbsp;Pesquisar
						</button>
						
						<button type="button" class="btn red-thunderbird" id="btn_limpar_pesquisa_orcamentos">
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
