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
	
	if(isset($_GET['id_venda_cafe']))
	{
		$id_venda_cafe = $_GET['id_venda_cafe'];
	 	
		if(!empty($id_venda_cafe) && $id_venda_cafe != "undefined")
		{
			$status = "Alteração";
			
			$sql 	= "SELECT a.id id_venda_cafe, a.bebida, f.percentual_sacas, f.id fk_peneira,
					   date_format(a.data,'%d/%m/%Y') AS data, 
					   b.fk_id_prod, b.fk_id_prop, a.observacao,
			   		   a.lote_apas, a.lote_coperativa, a.quantidade, e.*
					   FROM mov_razao_produtos a					  
					   INNER JOIN mov_prop_prod b ON a.fk_mov_pp = b.id_mov_pp
					   INNER JOIN tb_produtor c ON b.fk_id_prod = c.id_prod
					   INNER JOIN tb_propriedade d ON b.fk_id_prop = d.id_prop
					   INNER JOIN mov_venda e ON e.fk_razao_produtos = a.id
					   INNER JOIN mov_razao_produtos_peneira f on f.fk_razao_produtos = a.id
					   WHERE a.fk_produto = 1 AND a.tipo_movimentacao = 'S'
					   AND a.id = " . $id_venda_cafe;
		}
	}
	
	$id_venda_cafe			= '';
	$data					= '';
	$fk_id_prod				= '';
	$fk_id_prop				= '';
	$lote_apas				= '';
	$lote_coperativa		= '';
	$quantidade				= '';
	$tipocafe				= '';
	$bebida					= '';
	$observacao				= '';
	$comprador				= '';
	$fk_peneira				= '';
	$percentual_peneira		= '';
	$fairtrade				= '';
	$sacas_prontas			= '';
	$sacas_fundo			= '';
	$valor_saca_preparada	= '';
	$valor_saca_fundo		= '';
	$valor_total			= '';
	$datahj 				= date('d/m/Y');
	
	if(!empty($sql))
	{
		$rs = $mysqli->query($sql);
		
		if($rs)
		{
			$vendacafe				= $rs->fetch_object();
			$id_venda_cafe			= $vendacafe->id_venda_cafe;
			$data					= $vendacafe->data;
			$fk_id_prod				= $vendacafe->fk_id_prod;
			$fk_id_prop				= $vendacafe->fk_id_prop;
			$lote_apas				= $vendacafe->lote_apas;
			$lote_coperativa		= $vendacafe->lote_coperativa;
			$quantidade				= $vendacafe->quantidade;
			$tipocafe				= $vendacafe->tipo_cafe;
			$bebida					= $vendacafe->bebida;
			$observacao				= $vendacafe->observacao;
			$comprador				= $vendacafe->comprador;
			$fk_peneira				= $vendacafe->fk_peneira;
			$percentual_peneira		= $vendacafe->percentual_sacas;
			$fairtrade				= $vendacafe->fairtrade;
			$sacas_prontas			= $vendacafe->sacas_prontas;
			$sacas_fundo			= $vendacafe->sacas_fundo;
			$valor_saca_preparada	= $vendacafe->valor_saca_preparada;
			$valor_saca_fundo		= $vendacafe->valor_saca_fundo;
			$valor_total			= $vendacafe->valor_total;	
			
			if($fairtrade == 1)
			{
				$fairtrade = "checked";
			}
			
			$rs->close();
			
			$sql 	= "SELECT b.id
					   FROM mov_razao_produtos a					  
					   INNER JOIN mov_razao_produtos_peneira b on b.fk_razao_produtos = a.id
					   WHERE a.lote_apas = '$lote_apas'";
					   
			$rs 			= $mysqli->query($sql);
			$peneiraentrada	= $rs->fetch_object();
			$fk_peneira		= $peneiraentrada->id;	
			
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
					<a href="consulta_venda_cafe.php">Venda de Café</a>
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="cadastro_venda_cafe.php?id_venda_cafe=<?php echo $id_venda_cafe; ?>"><?php echo $status; ?></a>
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
								<i class="fa fa-cart-arrow-down" style="transform: rotateY(180deg);"></i>
								<?php echo $status; ?> de Venda de Café
							</div>
							
							<div class="tools">
								<?php if($status == "Alteração") echo '<a href="javascript:;" class="collapse"></a>'; ?>
							</div>
						</div>
						
						<div class="portlet-body form">
											
							<!-- BEGIN FORM -->							
							<div class="form-body">
								<h3 class="form-section">
									Informações da Movimentação				
								</h3>
								
								<div class="row">																		
									<div class="col-md-8">
										<div class="form-group">
											<label class="control-label">Produtor</label>
																						
											<div class="input-group">
												<input type="hidden" class="form-control" id="txtidvendacafe" value="<?php echo $id_venda_cafe; ?>">
												
												<span class="input-group-addon">
													<span class="fa fa-users"></span> 
												</span>
												
												<select class="form-control select2me" id="cmbprodutorvenda" data-param="<?php echo $fk_id_prop; ?>" tabindex="1">
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
												
												<select class="form-control select2me" id="cmbpropriedadevenda" data-param="<?php echo $lote_apas; ?>" tabindex="1">
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
												
												<input type="text" size="16" class="form-control" id="txtdatavendacafe" placeholder="Informe a data da venda" value="<?php if($status=='Inclusão'){echo $datahj;}else{echo $data;} ?>">
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
																								
												<select class="form-control select2me" id="cmbloteapasvendacafe" data-param="<?php echo $fk_peneira; ?>" tabindex="1">
													<option value="" default>Selecione um lote APAS...</option>
												</select>
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
												
												<select class="form-control select2me" id="cmblotecoperativavendacafe" data-param="<?php echo $fk_peneira; ?>" tabindex="1">
													<option value="" default>Selecione um lote Outros...</option>
												</select>
											</div>
										</div>
									</div>
								</div>	
								
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label ">Comprador</label>
											
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-male"></span> 
												</span>
												
												<input type="text" class="form-control" id="txtcompradorvendacafe" placeholder="Informe o comprador" value="<?php echo $comprador; ?>">
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
												
												<input type="text" class="form-control maskarea" readonly="true" id="txtbebidavendacafe" placeholder="Informe a bebida do café" value="<?php echo $bebida; ?>">
											</div>
										</div>
									</div>	
									
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label ">Fair Trade</label>
											
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-renren"></span> 
												</span>
												
												<input type="checkbox" class="make-switch" id="fairtrade" <?php echo $fairtrade; ?> data-on-text="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sim&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" data-off-text="Não">
											</div>
										</div>
									</div>
									<!--	
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label ">Tipo Café</label>
											
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-tag"></span> 
												</span>
												
												<input type="text" class="form-control maskarea" id="txttipovendacafe" placeholder="Informe o tipo do café" value="<?php echo $tipocafe; ?>">
											</div>
										</div>
									</div>
									-->
								</div>
								
								<div class="row">	
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label ">Quantidade</label>
											
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-cubes"></span> 
												</span>
												
												<input type="text" class="form-control maskarea" id="txtquantidadevendacafe" readonly="true" placeholder="Quantidade de Sacas" value="<?php echo $quantidade; ?>">
											</div>
										</div>
									</div>
															
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label">Peneira</label>
																						
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-filter"></span> 
												</span>
												
												<select class="form-control select2me" id="cmbpeneiravenda" tabindex="1">
													<option value="" default>Selecione uma peneira...</option>
												</select>
											</div>											
										</div>
									</div>
									
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label">Percentual Peneira</label>
											
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-percent"></span> 
												</span>
												
												<input type="text" class="form-control maskarea" readonly="true" id="txtpercentualpeneiravendacafe" placeholder="informe o percentual da peneira" value="<?php echo $percentual_peneira; ?>">
											</div>
										</div>
									</div>
								</div>
							
								<div class="row">				
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label ">Sacas Prontas</label>
											
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-cubes"></span> 
												</span>
												
												<input type="text" class="form-control maskarea" readonly="true" id="txtsacasprontasvendacafe" placeholder="Sacas prontas" value="<?php echo $sacas_prontas; ?>">
											</div>
										</div>
									</div>
									
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label ">Sacas Fundo</label>
											
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-cubes"></span> 
												</span>
												
												<input type="text" class="form-control maskarea" readonly="true" id="txtsacasfundovendacafe" placeholder="Sacas de fundo" value="<?php echo $sacas_fundo; ?>">
											</div>
										</div>
									</div>	
								</div>
							
								<div class="row">			
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label">Valor Saca Preparada</label>
											
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-dollar"></span> 
												</span>
												
												<input type="text" class="form-control maskdinheiro" id="txtvalorsacaspreparavendacafe" placeholder="Informe o valor da saca preparada" value="<?php echo $valor_saca_preparada; ?>">
											</div>
										</div>
									</div>
									
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label">Valor Saca Fundo</label>
											
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-dollar"></span> 
												</span>
												
												<input type="text" class="form-control maskdinheiro" id="txtvalorsacasfundovendacafe" placeholder="Informe o valor da saca de fundo" value="<?php echo $valor_saca_fundo; ?>">
											</div>
										</div>
									</div>
									
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label">Valor Total</label>
											
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-dollar"></span> 
												</span>
												
												<input type="text" class="form-control maskdinheiro" readonly="true" id="txtvalortotalvendacafe" placeholder="Valor total" value="<?php echo $valor_total; ?>">
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

												<textarea class="form-control" id="txtobsevacaovendacafe" style="resize:vertical;" rows="5" placeholder="Campo para observação na venda de café..."><?php echo $observacao; ?></textarea>
											</div>
										</div>
									</div>
								</div>
							</div>
								
							<div class="form-actions right">
								<button type="submit" class="btn blue" id="grava_venda_cafe">
									<i class="fa fa-floppy-o"></i>
									&nbsp;Salvar
								</button>
								
								<button type="button" class="btn red-thunderbird" id="limpa_venda_cafe">
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