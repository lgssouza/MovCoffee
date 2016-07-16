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
		
	if(isset($_GET['id_conta_pagar']))
	{
		$id_conta_pagar = $_GET['id_conta_pagar'];				
				
		if(!empty($id_conta_pagar) && $id_conta_pagar != "undefined")		 			
		{
					
			$status = "Alteração";			
			$sql 	= "SELECT * FROM `tb_conta_pagar` WHERE id_conta_pagar = " . $id_conta_pagar;			
			
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
		}
	}
	
	$id_conta_pagar		= '';
	$fk_id_fornec		= '';
	$descricao 			= '';
	$documento 			= '';
	$valor 				= '';
	$data_abertura 		= '';
	$data_vencimento 	= '';
	$data_baixa 		= '';
	$forma_pagamento 	= '';
	$conta_destino 		= '';
	$datahj = date('d/m/Y');
	
	if(isset($contas))
	{
		if(isset($contas[0]->id_conta_pagar))
		{
			$id_conta_pagar = $contas[0]->id_conta_pagar;
		}
				
		if(isset($contas[0]->descricao))
		{
			$descricao = $contas[0]->descricao;
		}		
		
		if(isset($contas[0]->fk_id_fornec))
		{
			$fk_id_fornec = $contas[0]->fk_id_fornec;
		}
		
		if(isset($contas[0]->documento))
		{
			$documento = $contas[0]->documento;
		}
		
		if(isset($contas[0]->valor))
		{
			$valor = $contas[0]->valor;
		}
		
		if(isset($contas[0]->data_abertura))
		{
			$data_abertura = $contas[0]->data_abertura;
		}
		
		if(isset($contas[0]->data_vencimento))
		{
			$data_vencimento = $contas[0]->data_vencimento;
		}
		
		if(isset($contas[0]->data_baixa))
		{
			$data_baixa = $contas[0]->data_baixa;
		}
		
		if(isset($contas[0]->forma_pagamento))
		{
			$forma_pagamento = $contas[0]->forma_pagamento;
		}
		
		if(isset($contas[0]->conta_destino))
		{
			$conta_destino = $contas[0]->conta_destino;
		}
		
		if($valor!=0)
		{
			$valor = number_format($valor, 2, ',', '.');	
			$valor 	= str_replace('.','',$valor);
		}
		else
		{
			$valor = number_format(0, 2, ',', '.');
		}
		
		if($data_abertura!='')
		{	
			$data_abertura =  date('d/m/Y', strtotime($data_abertura));
		}
		
		if($data_vencimento!='')
		{	
			$data_vencimento =  date('d/m/Y', strtotime($data_vencimento));
		}

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
					Financeiro
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="consulta_conta_pagar.php">Contas a Pagar</a>
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="cadastro_conta_pagar.php?id_conta_pagar=<?php echo $id_conta_pagar; ?>"><?php echo $status; ?></a>
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
								<i class="fa fa-money"></i>
								<?php echo $status; ?> de Conta a Pagar
							</div>
							
							<div class="tools">
								<!-- <a href="javascript:;" class="collapse"></a>-->
							</div>
						</div>
						
						<div class="portlet-body form">
											
							<!-- BEGIN FORM -->
							<div class="form-body">
								<h3 class="form-section">Informações da Conta a Pagar</h3>
									
								<div class="row">																		
										<div class="col-md-8">
											<div class="form-group">
												<label class="control-label">Fornecedor</label>
																							
												<div class="input-group">
													<input type="hidden" class="form-control" id="txtidcontapagar" value="<?php echo $id_conta_pagar; ?>">
													
													<span class="input-group-addon">
														<span class="fa fa-users"></span> 
													</span>
													
													<select class="form-control select2me" id="cmbfornecedorpagar" data-param="<?php echo $fk_id_fornec; ?>" tabindex="1">
														<option value="" default>Selecione um fornecedor...</option>
															
														<?php
															$sql 			= "SELECT * FROM `tb_fornecedor` order by fornec_nome asc";
															$rsfornec 	= $mysqli->query($sql);
															$sql 			= "";
															
															if($rsfornec)
															{																													
																$fornecs = array();
	    
																while($fornec = $rsfornec->fetch_object())
																{
																	array_push($fornecs, $fornec);
																}
																
																$rsfornec->close();
																
																for($i=0;$i<count($fornecs);$i++)
																{																
																	if($fk_id_fornec == $fornecs[$i]->id_fornec)
																	{
																		echo '"<option value="'.$fornecs[$i]->id_fornec.'" selected>'.$fornecs[$i]->fornec_nome.'</option>"';
																	}
																	else
																	{
																		echo '"<option value="'.$fornecs[$i]->id_fornec.'">'.$fornecs[$i]->fornec_nome.'</option>"';
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
												<div class="form-group">
													<label class="control-label">Documento</label>
													<div class="input-group">
														<span class="input-group-addon">
															<span class="fa fa-list"></span>													 
														</span>
														<input type="text" class="form-control" id="txtdocumentopagar" placeholder="Digite a documento da conta a pagar" value="<?php echo $documento; ?>">
													</div>
												</div>
											</div>
										</div>
									</div>
								
								<div class="row">									
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label">Valor</label>
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-list"></span>													 
												</span>
												<input type="text" class="form-control maskdinheiro" id="txtvalorpagar" placeholder="Digite o valor da conta a pagar" value="<?php echo $valor; ?>">
											</div>
										</div>
									</div>
									<div class="col-md-4">		
										<div class="form-group">
											<label class="control-label">Data Abertura</label>
											
											<div class="input-group date date-pickerbr">
												<span class="input-group-btn">
													<button class="btn default date-set" type="button">
														<i class="fa fa-calendar"></i>
													</button>
												</span>
												
												<input type="text" size="16" class="form-control" id="txtdataaberturapagar" placeholder="Digite a data do lançamento" value="<?php if($status=='Inclusão'){echo $datahj;}else{echo $data_abertura;} ?>">
												
											</div>
										</div>
									</div>	
									<div class="col-md-4">		
										<div class="form-group">
											<label class="control-label">Data Vencimento</label>
											
											<div class="input-group date date-pickerbr">
												<span class="input-group-btn">
													<button class="btn default date-set" type="button">
														<i class="fa fa-calendar"></i>
													</button>
												</span>
												
												<input type="text" size="16" class="form-control" id="txtdatavencimentopagar" placeholder="Digite a data do vencimento" value="<?php echo $data_vencimento; ?>">
												
											</div>
										</div>
									</div>	
											
								</div>
								
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<div class="form-group">
												<label class="control-label">Descrição</label>
												<div class="input-group">
													<span class="input-group-addon">
														<span class="fa fa-list"></span>													 
													</span>
													<input type="text" class="form-control" id="txtdescricaopagar" placeholder="Digite a descrição da conta" value="<?php echo $descricao; ?>">
												</div>
											</div>
										</div>
									</div>
								</div>
																								
								<div id="alertcontas">
								</div>								
							</div>
							
							</br>
							
							<div class="form-actions right">
								<button type="submit" class="btn blue" id="grava_contaspagar" <?php if($data_baixa!=null){echo "style=\"display:none\"";}  ?> >
									<i class="fa fa-floppy-o"></i>
									&nbsp;Salvar
								</button>
								
								<button type="button" class="btn red-thunderbird" id="limpa_contaspagar">
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