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
	
	$sql	= "";
	$status = "Inclusão";
	
	if(isset($_GET['id_entrada_cafe']))
	{
		$id_entrada_cafe = $_GET['id_entrada_cafe'];
	 	
		if(!empty($id_entrada_cafe) && $id_entrada_cafe != "undefined")
		{
			$status = "Alteração";
			
			$sql 	= "SELECT a.id, a.umidade, a.bebida,
					   date_format(a.data,'%d/%m/%Y') AS data, 
					   b.fk_id_prod, b.fk_id_prop, a.observacao,
			   		   a.lote_apas, a.lote_coperativa, a.quantidade
					   FROM mov_razao_produtos a
					   INNER JOIN mov_prop_prod b ON a.fk_mov_pp = b.id_mov_pp
					   INNER JOIN tb_produtor c ON b.fk_id_prod = c.id_prod
					   INNER JOIN tb_propriedade d ON b.fk_id_prop = d.id_prop
					   WHERE a.fk_produto = 1 AND a.tipo_movimentacao = 'E'
					   AND a.id = " . $id_entrada_cafe;
		}
	}
	
	$id						= '';
	$data					= '';
	$fk_id_prod				= '';
	$fk_id_prop				= '';
	$lote_apas				= '';
	$lote_coperativa		= '';
	$quantidade				= '';
	$umidade				= '';
	$bebida					= '';
	$observacao				= '';
	$datahj 				= date('d/m/Y');
	
	if(!empty($sql))
	{
		$rs = $mysqli->query($sql);
		
		if($rs)
		{
			$entradacafe		= $rs->fetch_object();
			$id 				= $entradacafe->id;
			$data 				= $entradacafe->data;
			$fk_id_prod			= $entradacafe->fk_id_prod;
			$fk_id_prop			= $entradacafe->fk_id_prop;
			$lote_apas			= $entradacafe->lote_apas;
			$lote_coperativa	= $entradacafe->lote_coperativa;
			$quantidade			= $entradacafe->quantidade;
			$umidade			= $entradacafe->umidade;
			$bebida				= $entradacafe->bebida;
			$observacao			= $entradacafe->observacao;
			
			$rs->close();
		}
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
					Movimentação de Café
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="consulta_entrada_cafe.php">Entradas de Café</a>
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="cadastro_entrada_cafe.php?id_entrada_cafe=<?php echo $id_entrada_cafe; ?>"><?php echo $status; ?></a>
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
								<i class="fa fa-cart-plus"></i>
								<?php echo $status; ?> de Entrada de Café
							</div>
							
							<div class="tools">
								<?php if($status == "Alteração") echo '<a href="javascript:;" class="collapse"></a>'; ?>
							</div>
						</div>
						
						<div class="portlet-body form">
											
							<!-- BEGIN FORM -->							
							<div class="form-body">
								<h3 class="form-section">Informações da Movimentação</h3>
								
								<div class="row">																		
									<div class="col-md-8">
										<div class="form-group">
											<label class="control-label">Produtor</label>
																						
											<div class="input-group">
												<input type="hidden" class="form-control" id="txtidentcafe" value="<?php echo $id; ?>">
												
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
									<div class="col-md-4">		
										<div class="form-group">
											<label class="control-label">Data</label>
											
											<div class="input-group date date-pickerbr">
												<span class="input-group-btn">
													<button class="btn default date-set" type="button">
														<i class="fa fa-calendar"></i>
													</button>
												</span>
												
												<input type="text" size="16" class="form-control" id="txtdataentcafe" placeholder="Informe a data do lançamento" value="<?php if($status=='Inclusão'){echo $datahj;}else{echo $data;} ?>">
											</div>
										</div>
									</div>	
									
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label ">Lote Apas</label>
											
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-tree"></span> 
												</span>
												
												<input type="text" class="form-control" id="txtloteapasentcafe" placeholder="Informe o lote da APAS" value="<?php echo $lote_apas; ?>">
											</div>
										</div>
									</div>
									
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label ">Lote Outros</label>
											
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-tree"></span> 
												</span>
												
												<input type="text" class="form-control" id="txtlotecoperativaentcafe" placeholder="informe o lote Outros" value="<?php echo $lote_coperativa; ?>">
											</div>
										</div>
									</div>
								</div>	
								
								<div class="row">								
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label ">Quantidade</label>
											
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-cubes"></span> 
												</span>
												
												<input type="text" class="form-control maskarea" id="txtquantidadeentcafe" placeholder="Informe a quantidade da entrada de café" value="<?php echo $quantidade; ?>">
											</div>
										</div>
									</div>
									
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label ">Umidade</label>
											
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-tint"></span> 
												</span>
												
												<input type="text" class="form-control maskarea" id="txtumidadeentcafe" placeholder="Informe a umidade do café" value="<?php echo $umidade; ?>">
											</div>
										</div>
									</div>
									
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label ">Bebida</label>
											
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-coffee"></span> 
												</span>
												
												<input type="text" class="form-control maskarea" id="txtbebidaentcafe" placeholder="Informe a bebida do café" value="<?php echo $bebida; ?>">
											</div>
										</div>
									</div>
								</div>
								
								<div class="row">								
									<div class="col-md-12">
										<div class="form-group">
											<label class="control-label ">Observação</label>
											
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-list"></span> 
												</span>

												<textarea class="form-control" id="txtobsevacaoentcafe" style="resize:vertical;" rows="5" placeholder="Campo para observação na entrada de café..."><?php echo $observacao; ?></textarea>
											</div>
										</div>
									</div>
								</div>
														
								<h3 class="form-section">Peneiras</h3>
								
								<div class="row">
									<div class="col-md-12">
										<?php
											
											$sql 			= "SELECT * FROM `tb_peneira` 
															   ORDER BY ID";
											$rspeneiras		= $mysqli->query($sql);
											$sql 			= "";
															
											if($rspeneiras)
											{
												$peneiras = array();
    
												while($peneira = $rspeneiras->fetch_object())
												{
													array_push($peneiras, $peneira);
												}
												
												$rspeneiras->close();
												
												for($i=0;$i<count($peneiras);$i++)
												{
													$percentual_sacas	= "";
													$sacas				= "";
													
													if(!empty($id))
													{
														$sql 				= "SELECT percentual_sacas, sacas
																			   FROM `mov_razao_produtos_peneira` A 
																  		       INNER JOIN tb_peneira B ON A.fk_peneira = B.id
																  		       WHERE fk_razao_produtos = $id
																  		       AND fk_peneira = ".$peneiras[$i]->id;
																			   
														$rspeneirasatual 	= $mysqli->query($sql);										
														$mov_prod_peneira 	= $rspeneirasatual->fetch_object();
														$percentual_sacas	= $mov_prod_peneira->percentual_sacas;
														$sacas				= $mov_prod_peneira->sacas;
													}
													
													if($i==0)
													{
														echo 	'<div class="row">								
																	<div class="col-md-4">
																		<div class="form-group">
																			<label class="control-label">Peneira</label>
																			
																			<div class="input-group">
																				<input type="hidden" class="form-control" id="txtidpeneiraentcafe'.$i.'" readonly="true" value="'.$peneiras[$i]->id.'">
																			
																				<span class="input-group-addon">
																					<span class="fa fa-filter"></span> 
																				</span>
																				
																				<input type="text" class="form-control" id="txtpeneiraentcafe'.$i.'" readonly="true" value="'.$peneiras[$i]->descricao.'">
																			</div>
																		</div>
																	</div>
																	
																	<div class="col-md-4">
																		<div class="form-group">
																			<label class="control-label">Percentual</label>
																			
																			<div class="input-group">
																				<span class="input-group-addon">
																					<span class="fa fa-percent"></span> 
																				</span>
																				
																				<input type="text" class="form-control maskarea percentualpeneiraentcafe" id="txtpercentualpeneiraentcafe'.$i.'" placeholder="Informe o percentual da peneira" value="'.$percentual_sacas.'">
																			</div>
																		</div>
																	</div>
																	
																	<div class="col-md-4">
																		<div class="form-group">
																			<label class="control-label">Sacas</label>
																			
																			<div class="input-group">
																				<span class="input-group-addon">
																					<span class="fa fa-cubes"></span> 
																				</span>
																				
																				<input type="text" class="form-control maskarea" id="txtsacaspeneiraentcafe'.$i.'" readonly="true" placeholder="Sacas referente ao percentual" value="'.$sacas.'">
																			</div>
																		</div>
																	</div>
																</div>';	
													}
													else												
													{
														echo 	'<div class="row">								
																	<div class="col-md-4">
																		<div class="form-group">
																			<div class="input-group">
																				<input type="hidden" class="form-control" id="txtidpeneiraentcafe'.$i.'" readonly="true" value="'.$peneiras[$i]->id.'">
																				
																				<span class="input-group-addon">
																					<span class="fa fa-filter"></span> 
																				</span>
																				
																				<input type="text" class="form-control" id="txtpeneiraentcafe'.$i.'" readonly="true" value="'.$peneiras[$i]->descricao.'">
																			</div>
																		</div>
																	</div>
																	
																	<div class="col-md-4">
																		<div class="form-group">
																			<div class="input-group">
																				<span class="input-group-addon">
																					<span class="fa fa-percent"></span> 
																				</span>
																				
																				<input type="text" class="form-control maskarea percentualpeneiraentcafe" id="txtpercentualpeneiraentcafe'.$i.'" placeholder="Informe o percentual da peneira" value="'.$percentual_sacas.'">
																			</div>
																		</div>
																	</div>
																	
																	<div class="col-md-4">
																		<div class="form-group">
																			<div class="input-group">
																				<span class="input-group-addon">
																					<span class="fa fa-cubes"></span> 
																				</span>
																				
																				<input type="text" class="form-control maskarea" id="txtsacaspeneiraentcafe'.$i.'" readonly="true" placeholder="Sacas referente ao percentual" value="'.$sacas.'">
																			</div>
																		</div>
																	</div>
																</div>';
													}
												}														
											}
										?>
									</div>
								</div>
							</div>
								
							<div class="form-actions right">
								<button type="submit" class="btn blue" id="grava_entrada_cafe">
									<i class="fa fa-floppy-o"></i>
									&nbsp;Salvar
								</button>
								
								<button type="button" class="btn red-thunderbird" id="limpa_entrada_cafe">
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