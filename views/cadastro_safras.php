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
	
	$status = "Inclusão";
		
	if(isset($_GET['id_safra']))
	{
		$id_safra = $_GET['id_safra'];				
				
		if(!empty($id_safra) && $id_safra != "undefined")		 			
		{
					
			$status = "Alteração";			
			$sql 	= "SELECT * FROM `tb_safra` WHERE id_safra = " . $id_safra;			
			
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
		}
	}
	
	$id_safra			= '';
	$fk_id_prod			= '';
	$fk_id_prop			= '';
	$talhao 			= '';
	$variedade			= '';
	$espaco1			= '';
	$espaco2			= '';
	$area				= '';
	$anoplantio			= '';
	$nplantas			= '';
	$safra				= '';
	$previsao			= '';
	$producao			= '';
	
	
	if(isset($regs))
	{
		if(isset($regs[0]->id_safra))
		{
			$id_safra = $regs[0]->id_safra;
		}
		
		if(isset($regs[0]->fk_id_produtor))
		{
			$fk_id_prod = $regs[0]->fk_id_produtor;
		}
		
		if(isset($regs[0]->fk_id_prop))
		{
			$fk_id_prop = $regs[0]->fk_id_prop;
		}			
		
		if(isset($regs[0]->safra_talhao))
		{
			$talhao = $regs[0]->safra_talhao;
		}
		
		if(isset($regs[0]->safra_variedade))
		{
			$variedade = $regs[0]->safra_variedade;
		}
		
		if(isset($regs[0]->safra_esp1))
		{
			$espaco1 = $regs[0]->safra_esp1;
		}
		
		if(isset($regs[0]->safra_esp2))
		{
			$espaco2 = $regs[0]->safra_esp2;
		}
		
		if(isset($regs[0]->safra_area))
		{
			$area = $regs[0]->safra_area;
		}
		
		if(isset($regs[0]->safra_anoplantio))
		{
			$anoplantio = $regs[0]->safra_anoplantio;
		}
		
		if(isset($regs[0]->safra_numeroplantas))
		{
			$nplantas = $regs[0]->safra_numeroplantas;
		}
		
		if(isset($regs[0]->safra_safra))
		{
			$safra = $regs[0]->safra_safra;
		}
		
		if(isset($regs[0]->safra_previsao))
		{
			$previsao = $regs[0]->safra_previsao;
		}
		
		if(isset($regs[0]->safra_producao))
		{
			$producao = $regs[0]->safra_producao;
		}
		
	}

	if($espaco1!=0)
	{	
		$espaco1 = str_replace('.',',',$espaco1);	
	}
	else
	{
		$espaco1 = str_replace('.',',',0);
	}
	
	if($espaco2!=0)
	{
		$espaco2 = str_replace('.',',',$espaco2);	
	}
	else
	{
		$espaco2 = str_replace('.',',',0);
	}	
	
	if($area!=0)
	{
		$area = str_replace('.',',',$area);	
	}
	else
	{
		$area = str_replace('.',',',0);
	}	
	
		
		
?>

<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
	<div class="page-content">
		
		<!-- BEGIN MENSAGENS-->
		<div class="caixa_mensagens"></div>	
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
					<a href="consulta_safras.php">Safras</a>
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="cadastro_contas.php?id_safra=<?php echo $id_safra; ?>"><?php echo $status; ?></a>
				</li>
			</ul>
		</div>
		<!-- END PAGE HEADER-->
		
		<!-- BEGIN PAGE CONTENT-->
		<div class="row">
			<div class="col-md-12">
				<div class="tab-pane" id="tab_1">
					<div class="portlet box green-jungle">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-leaf"></i>
								<?php echo $status; ?> de Safra
							</div>
							
							<div class="tools">
								<!-- <a href="javascript:;" class="collapse"></a>-->
							</div>
						</div>
						
						<div class="portlet-body form">
											
							<!-- BEGIN FORM -->
							<div class="form-body">
								<h3 class="form-section">Informações da Safra</h3>
									
									<div class="row">																		
										<div class="col-md-8">
											<div class="form-group">
												<label class="control-label">Produtor</label>
																							
												<div class="input-group">
													<input type="hidden" class="form-control" id="txtidsafra" value="<?php echo $id_safra; ?>">
													
													<span class="input-group-addon">
														<span class="fa fa-users"></span> 
													</span>
													
													<select class="form-control select2me" id="cmbprodutor" data-param="<?php echo $fk_id_prop; ?>" tabindex="1">
														<option value="" default>Selecione um produtor...</option>
															
														<?php
															$sql 			= "SELECT * FROM `tb_produtor` order by prod_nome asc";
															$rsprodutores 	= $mysqli->query($sql);
															$sql 			= "";
															
															if($rsprodutores)
															{																													
																$produtores = array();
	    
																while($produtor = $rsprodutores->fetch_object())
																{
																	array_push($produtores, $produtor);
																}
																
																$rsprodutores->close();
																
																for($i=0;$i<count($produtores);$i++)
																{																
																	if($fk_id_prod == $produtores[$i]->id_prod)
																	{
																		echo '"<option value="'.$produtores[$i]->id_prod.'" selected>'.$produtores[$i]->prod_nome.'</option>"';
																	}
																	else
																	{
																		echo '"<option value="'.$produtores[$i]->id_prod.'">'.$produtores[$i]->prod_nome.'</option>"';
																	}
																}
															}
															
														?>
													</select>
												</div>											
											</div>
										</div>
																							
										<div class="col-md-4">
											<div class="form-group">
												<label class="control-label">Propriedade</label>
																							
												<div class="input-group">
													<span class="input-group-addon">
														<span class="fa fa-fort-awesome"></span> 
													</span>
													
													<select class="form-control select2me" id="cmbpropriedade" tabindex="1">
														<option value="" default>Selecione uma propriedade...</option>
													</select>
												</div>
											</div>
										</div>
									</div>
								
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">Talhão</label>
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-crop"></span>													 
												</span>
												<input type="text" class="form-control" id="txttalhao" placeholder="Digite o talhão da safra" value="<?php echo $talhao; ?>">
											</div>
										</div>
									</div>
									
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">Variedade</label>
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-coffee"></span>													 
												</span>
												<input type="text" class="form-control" id="txtvariedade" placeholder="Digite a variedade da safra" value="<?php echo $variedade; ?>">
											</div>
										</div>
									</div>
									
								</div>
								
								<div class="row">
									
									<div class="col-md-3">
										<div class="form-group">
											<label class="control-label">Espaçamento 1</label>
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-area-chart"></span>													 
												</span>
												<input type="text" class="form-control maskarea" id="txtesp1" placeholder="Esp. 1" value="<?php echo $espaco1; ?>">
											</div>
										</div>
									</div>
									
									<div class="col-md-3">	
										<div class="form-group">									
											<label class="control-label">Espaçamento 2</label>										
											<div class="input-group">											
												<span class="input-group-addon">
													<span class="fa fa-area-chart"></span>													 
												</span>
												<input type="text" class="form-control maskarea" id="txtesp2" placeholder="Esp. 2" value="<?php echo $espaco2; ?>">
											</div>
										</div>
									</div>		
									
									<div class="col-md-3">
										<div class="form-group">
											<label class="control-label">Área (ha)</label>
											<div class="input-group">
												
												<span class="input-group-addon">
													<span class="fa fa-area-chart"></span>													 
												</span>
												<input type="text" class="form-control" id="txtarea" placeholder="Total Área" value="<?php echo $area; ?>" readonly="readonly">
											</div>
										</div>
									</div>		
									
									<div class="col-md-3">
										<div class="form-group">
											<label class="control-label">Qtd Plantas</label>
											<div class="input-group">
												
												<span class="input-group-addon">
													<span class="fa fa-pagelines"></span>													 
												</span>
												<input type="number" class="form-control" id="txtqtdplantas" placeholder="Digite a quantidade de plantas" value="<?php echo $nplantas; ?>">
											</div>
										</div>
									</div>															
								</div>
								
								<div class="row">
									
									<div class="col-md-3">
										<div class="form-group">
											<label class="control-label">Ano Plantio</label>
											
											<div class="input-group date date-pickerano">
												<span class="input-group-addon">
													<span class="fa fa-calendar"></span>													 
												</span>
												
												<input type="text" class="form-control" id="txtanoplantio" placeholder="Ano Plantio" value="<?php echo $anoplantio; ?>">
											</div>
										</div>
									</div>		
									
									<div class="col-md-3">
										<div class="form-group">
											<label class="control-label">Safra</label>
											
											<div class="input-group date date-pickerano">
												<span class="input-group-addon">
													<span class="fa fa-calendar"></span>													 
												</span>
												
												<input type="text" class="form-control" id="txtsafra" placeholder="Digite a Safra" value="<?php echo $safra; ?>">
											</div>
										</div>
									</div>
									
									<div class="col-md-3">
										<div class="form-group">
											<label class="control-label">Previsão</label>
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-question-circle"></span>													 
												</span>
												<input type="number" class="form-control" id="txtprevisao" placeholder="Digite a Previsão" value="<?php echo $previsao; ?>">
											</div>
										</div>
									</div>
									
									<div class="col-md-3">
										<div class="form-group">
											<label class="control-label">Produção</label>
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-product-hunt"></span>													 
												</span>
												<input type="number" class="form-control" id="txtproducao" placeholder="Digite a Produção" value="<?php echo $producao; ?>">
											</div>
										</div>
									</div>
									
								</div>
																								
								<div id="alertcontas">
								<div>								
							</div>
							
							</br>
							
							<div class="form-actions right">
								<button type="submit" class="btn blue" id="grava_safras">
									<i class="fa fa-floppy-o"></i>
									&nbsp;Salvar
								</button>
								
								<button type="button" class="btn red-thunderbird" id="limpa_safras">
									<i class="fa fa-remove"></i>
									&nbsp;Limpar
								</button>
							</div>							
							<!-- END FORM -->
							
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- END PAGE CONTENT-->
		
	</div>
</div>
<!-- END CONTENT -->

<?php

	$mysqli->close();
	
?>