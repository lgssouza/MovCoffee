<?php
	
	date_default_timezone_set('America/Sao_Paulo');
	
	include_once('verificaLogin.php');
	include_once('verificapermissao.php');
	
	//if(!verificapermissao("fornecedors",$permissoes))
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
	
	$sql 			= "";
	
	$status = "Inclusão";
	
	if(isset($_GET['id_fornecedor']))
	{
		$id_fornec = $_GET['id_fornecedor'];
	 	
		if(!empty($id_fornec) && $id_fornec != "undefined")
		{
			$status = "Alteração";
			
			$sql 	= "SELECT tb_fornecedor.*					  
					   FROM `tb_fornecedor`
					   WHERE id_fornec = " . $id_fornec;
		}
	}
	
	$id_fornec			= '';	
	$nome_fornec		= '';	
	$documento_fornec	= '';
	$rua_fornec		= '';
	$bairro_fornec		= '';
	$numero_fornec		= '';
	$cidade_fornec		= '';
	$uf_fornec			= '';
	$datanasc_fornec	= '';
	$telefone_fornec	= '';
	$email_fornec		= '';
	$cep_fornec		= '';
	
	if(!empty($sql))
	{
		$rs 	= $mysqli->query($sql);
		
		if($rs)
		{
			$fornec			= $rs->fetch_object();
			$id_fornec 		= $fornec->id_fornec;			
			$nome_fornec		= $fornec->fornec_nome;			
			$documento_fornec	= $fornec->fornec_cnpj;
			$rua_fornec		= $fornec->fornec_rua;
			$bairro_fornec		= $fornec->fornec_bairro;
			$numero_fornec		= $fornec->fornec_numero;
			$cidade_fornec		= $fornec->fornec_cidade;
			$uf_fornec			= $fornec->fornec_estado;			
			$telefone_fornec	= $fornec->fornec_telefone;
			$email_fornec		= $fornec->fornec_email;
			$cep_fornec		= $fornec->fornec_cep;
			
			$rs->close();
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
					Cadastros
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="consulta_fornecedores.php">Fornecedores</a>
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="cadastro_fornecedores.php?id_fornec=<?php echo $id_fornec; ?>"><?php echo $status; ?></a>
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
								<i class="fa fa-user"></i>
								<?php echo $status; ?> de Fornecedor
							</div>
							
							<div class="tools">
								<!-- <a href="javascript:;" class="collapse"></a>-->
							</div>
						</div>
						
						<div class="portlet-body form">
											
							<!-- BEGIN FORM -->							
							<div class="form-body">
								<h3 class="form-section">Informações do Fornecedor</h3>
								
								<div class="row">
									<div class="col-md-6">														
										<div class="form-group">
											<label class="control-label">Nome</label>
											
											<div class="input-group">
												<input type="hidden" class="form-control" id="txtidfornecedor" value="<?php echo $id_fornec; ?>">
												
												<span class="input-group-addon">
													<span class="fa fa-user"></span> 
												</span>
												
												<input type="text" class="form-control" id="txtnomefornecedor" placeholder="Digite o nome do fornecedor" value="<?php echo $nome_fornec; ?>">
											</div>
										</div>
									</div>
									
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">CNPJ</label>
																						
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-credit-card"></span> 
												</span>
												
												<input type="text" class="form-control cnpj" id="txtdocumentofornecedor" placeholder="Digite o cnpj do fornecedor" value="<?php echo $documento_fornec; ?>">
											</div>											
										</div>
									</div>
								</div>
								
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">Email</label>
											
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-envelope"></span> 
												</span>
												
												<input type="text" class="form-control" id="txtemailfornecedor"  placeholder="Digite o email do fornecedor" value="<?php echo $email_fornec; ?>">
											</div>
										</div>
									</div>
									
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label ">Telefone</label>
											
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-phone"></span> 
												</span>
												
												<input type="text" class="form-control tel" id="txttelefonefornecedor" placeholder="Digite o telefone do fornecedor" value="<?php echo $telefone_fornec; ?>">
											</div>
										</div>
									</div>
								</div>
																								
								<h3 class="form-section">Endereço do Fornecedor</h3>
								
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">Av/Rua</label>
																						
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-road"></span> 
												</span>
												
												<input type="text" class="form-control" id="txtenderecofornecedor" placeholder="Digite logradouro do fornecedor" value="<?php echo $rua_fornec; ?>">
											</div>											
										</div>
									</div>
									
									<div class="col-md-6">										
										<div class="form-group">
											<label class="control-label">Número</label>
																						
											<div class="input-group">
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-sd-video"></span> 
												</span>
												
												<input type="text" class="form-control" id="txtnumerofornecedor" placeholder="Digite número residencial do fornecedor" value="<?php echo $numero_fornec; ?>">
											</div>
										</div>
									</div>
								</div>
								
								<div class="row">	
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">Bairro</label>
																						
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-tree"></span> 
												</span>
												
												<input type="text" class="form-control" id="txtbairrofornecedor" placeholder="Digite o bairro do fornecedor" value="<?php echo $bairro_fornec; ?>">
											</div>											
										</div>
									</div>
									
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">Cidade</label>
																						
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-university"></span> 
												</span>
												
												<input type="text" class="form-control" id="txtcidadefornecedor" placeholder="Digite a cidade do fornecedor" value="<?php echo $cidade_fornec; ?>">
											</div>
										</div>
									</div>
								</div>
								
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">UF</label>
																						
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-flag"></span> 
												</span>
												
												<select class="form-control" id="txtuffornecedor">
													<option value="" default>Escolha a UF do fornecedor</option>
													
													<?php
													
														if ($uf_fornec == 'AC') 
														{
															echo "<option value='AC' selected>AC</option>";
														}
														else 
														{
															echo "<option value='AC'>AC</option>";
														}
														
														if ($uf_fornec == 'AL') 
														{
															echo "<option value='AL' selected>AL</option>";
														}
														else 
														{
															echo "<option value='AL'>AL</option>";
														}
														
														if ($uf_fornec == 'AP') 
														{
															echo "<option value='AP' selected>AP</option>";
														}
														else 
														{
															echo "<option value='AP'>AC</option>";
														}
														
														if ($uf_fornec == 'AM') 
														{
															echo "<option value='AM' selected>AM</option>";
														}
														else 
														{
															echo "<option value='AM'>AM</option>";
														}
														
														if ($uf_fornec == 'BA') 
														{
															echo "<option value='BA' selected>BA</option>";
														}
														else 
														{
															echo "<option value='BA'>BA</option>";
														}
														
														if ($uf_fornec == 'CE') 
														{
															echo "<option value='CE' selected>CE</option>";
														}
														else 
														{
															echo "<option value='CE'>CE</option>";
														}
														
														if ($uf_fornec == 'DF') 
														{
															echo "<option value='DF' selected>DF</option>";
														}
														else 
														{
															echo "<option value='DF'>DF</option>";
														}
														
														if ($uf_fornec == 'ES') 
														{
															echo "<option value='ES' selected>ES</option>";
														}
														else 
														{
															echo "<option value='ES'>ES</option>";
														}
														
														if ($uf_fornec == 'Go') 
														{
															echo "<option value='Go' selected>Go</option>";
														}
														else 
														{
															echo "<option value='Go'>Go</option>";
														}
														
														if ($uf_fornec == 'MA') 
														{
															echo "<option value='MA' selected>MA</option>";
														}
														else 
														{
															echo "<option value='MA'>MA</option>";
														}
														
														if ($uf_fornec == 'MT') 
														{
															echo "<option value='MT' selected>MT</option>";
														}
														else 
														{
															echo "<option value='MT'>MT</option>";
														}
														
														if ($uf_fornec == 'MS') 
														{
															echo "<option value='MS' selected>MS</option>";
														}
														else 
														{
															echo "<option value='MS'>MS</option>";
														}
														
														if ($uf_fornec == 'MG') 
														{
															echo "<option value='MG' selected>MG</option>";
														}
														else 
														{
															echo "<option value='MG'>MG</option>";
														}
														
														if ($uf_fornec == 'PA') 
														{
															echo "<option value='PA' selected>PA</option>";
														}
														else 
														{
															echo "<option value='PA'>PA</option>";
														}
														
														if ($uf_fornec == 'PB') 
														{
															echo "<option value='PB' selected>PB</option>";
														}
														else 
														{
															echo "<option value='PB'>PB</option>";
														}
														
														if ($uf_fornec == 'PR') 
														{
															echo "<option value='PR' selected>PR</option>";
														}
														else 
														{
															echo "<option value='PR'>PR</option>";
														}
														
														if ($uf_fornec == 'PE') 
														{
															echo "<option value='PE' selected>PE</option>";
														}
														else 
														{
															echo "<option value='PE'>PE</option>";
														}
														
														if ($uf_fornec == 'PI') 
														{
															echo "<option value='PI' selected>PI</option>";
														}
														else 
														{
															echo "<option value='PI'>PI</option>";
														}
														
														if ($uf_fornec == 'RJ') 
														{
															echo "<option value='RJ' selected>RJ</option>";
														}
														else 
														{
															echo "<option value='RJ'>RJ</option>";
														}
														
														if ($uf_fornec == 'RN') 
														{
															echo "<option value='RN' selected>RN</option>";
														}
														else 
														{
															echo "<option value='RN'>RN</option>";
														}
														
														if ($uf_fornec == 'RS') 
														{
															echo "<option value='RS' selected>RS</option>";
														}
														else 
														{
															echo "<option value='RS'>RS</option>";
														}
														
														if ($uf_fornec == 'RO') 
														{
															echo "<option value='RO' selected>RO</option>";
														}
														else 
														{
															echo "<option value='RO'>RO</option>";
														}
														
														if ($uf_fornec == 'RR') 
														{
															echo "<option value='RR' selected>RR</option>";
														}
														else 
														{
															echo "<option value='RR'>RR</option>";
														}
														
														if ($uf_fornec == 'SC') 
														{
															echo "<option value='SC' selected>SC</option>";
														}
														else 
														{
															echo "<option value='SC'>SC</option>";
														}
														
														if ($uf_fornec == 'SP') 
														{
															echo "<option value='SP' selected>SP</option>";
														}
														else 
														{
															echo "<option value='SP'>SP</option>";
														}
														
														if ($uf_fornec == 'SE') 
														{
															echo "<option value='SE' selected>SE</option>";
														}
														else 
														{
															echo "<option value='SE'>SE</option>";
														}
														
														if ($uf_fornec == 'TO') 
														{
															echo "<option value='TO' selected>TO</option>";
														}
														else 
														{
															echo "<option value='TO'>TO</option>";
														}	
													
													?>																										
												</select>
											</div>											
										</div>
									</div>
									
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">CEP</label>
																						
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-globe"></span> 
												</span>
												
												<input type="text" class="form-control cep" id="txtcepfornecedor" placeholder="Digite o cep do fornecedor" value="<?php echo $cep_fornec; ?>">
											</div>
										</div>
									</div>
								</div>
							</div>
								
							<div class="form-actions right">
								<button type="submit" class="btn blue" id="grava_fornecedores">
									<i class="fa fa-floppy-o"></i>
									&nbsp;Salvar
								</button>
								
								<button type="button" class="btn red-thunderbird" id="limpa_fornecedores">
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