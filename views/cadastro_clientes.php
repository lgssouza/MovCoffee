<?php
	
	date_default_timezone_set('America/Sao_Paulo');
	
	include_once('verificaLogin.php');
	include_once('verificapermissao.php');
	
	//if(!verificapermissao("clientes",$permissoes))
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
	
	if(isset($_GET['id_cliente']))
	{
		$id_cliente = $_GET['id_cliente'];
	 	
		if(!empty($id_cliente) && $id_cliente != "undefined")
		{
			$status = "Alteração";
			
			$sql 	= "SELECT tb_cliente.*,
					   date_format(tb_cliente.cli_nasc,'%d/%m/%Y') AS dt_nasc 	
					   FROM `tb_cliente`
					   WHERE id_cliente = " . $id_cliente;
		}
	}
	
	$id_cliente			= '';	
	$nome_cliente		= '';	
	$documento_cliente	= '';
	$rua_cliente		= '';
	$bairro_cliente		= '';
	$numero_cliente		= '';
	$cidade_cliente		= '';
	$uf_cliente			= '';
	$datanasc_cliente	= '';
	$telefone_cliente	= '';
	$email_cliente		= '';
	$cep_cliente		= '';
	
	if(!empty($sql))
	{
		$rs 	= $mysqli->query($sql);
		
		if($rs)
		{
			$cliente			= $rs->fetch_object();
			$id_cliente 		= $cliente->id_cliente;			
			$nome_cliente		= $cliente->cli_nome;			
			$documento_cliente	= $cliente->cli_cpf;
			$rua_cliente		= $cliente->cli_rua;
			$bairro_cliente		= $cliente->cli_bairro;
			$numero_cliente		= $cliente->cli_numero;
			$cidade_cliente		= $cliente->cli_cidade;
			$uf_cliente			= $cliente->cli_estado;
			$datanasc_cliente	= $cliente->dt_nasc;
			$telefone_cliente	= $cliente->cli_telefone;
			$email_cliente		= $cliente->cli_email;
			$cep_cliente		= $cliente->cli_cep;
			
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
					<a href="consulta_clientes.php">Clientes</a>
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="cadastro_clientes.php?id_cliente=<?php echo $id_cliente; ?>"><?php echo $status; ?></a>
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
								<?php echo $status; ?> de Cliente
							</div>
							
							<div class="tools">
								<!-- <a href="javascript:;" class="collapse"></a>-->
							</div>
						</div>
						
						<div class="portlet-body form">
											
							<!-- BEGIN FORM -->							
							<div class="form-body">
								<h3 class="form-section">Informações do Cliente</h3>
								
								<div class="row">
									<div class="col-md-6">														
										<div class="form-group">
											<label class="control-label">Nome</label>
											
											<div class="input-group">
												<input type="hidden" class="form-control" id="txtidcliente" value="<?php echo $id_cliente; ?>">
												
												<span class="input-group-addon">
													<span class="fa fa-user"></span> 
												</span>
												
												<input type="text" class="form-control" id="txtnomecliente" placeholder="Digite o nome do cliente" value="<?php echo $nome_cliente; ?>">
											</div>
										</div>
									</div>
									
									<div class="col-md-3">
										<div class="form-group">
											<label class="control-label">CPF</label>
																						
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-credit-card"></span> 
												</span>
												
												<input type="text" class="form-control cpf" id="txtdocumentocliente" placeholder="Digite o CPF do cliente" value="<?php echo $documento_cliente; ?>">
											</div>											
										</div>
									</div>
									
									<div class="col-md-3">		
										<div class="form-group">
											<label class="control-label">Data de Nascimento</label>
											
											<div class="input-group date date-pickerbr">
												<span class="input-group-btn">
													<button class="btn default date-set" type="button">
														<i class="fa fa-calendar"></i>
													</button>
												</span>
												
												<input type="text" size="16" class="form-control" id="txtdtnasccliente" placeholder="Digite a data de nascimento do cliente" value="<?php echo $datanasc_cliente; ?>">
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
												
												<input type="text" class="form-control" id="txtemailcliente"  placeholder="Digite o email do cliente" value="<?php echo $email_cliente; ?>">
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
												
												<input type="text" class="form-control tel" id="txttelefonecliente" placeholder="Digite o telefone do cliente" value="<?php echo $telefone_cliente; ?>">
											</div>
										</div>
									</div>
								</div>
																								
								<h3 class="form-section">Endereço do cliente</h3>
								
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">Av/Rua</label>
																						
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-road"></span> 
												</span>
												
												<input type="text" class="form-control" id="txtenderecocliente" placeholder="Digite logradouro do cliente" value="<?php echo $rua_cliente; ?>">
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
												
												<input type="text" class="form-control" id="txtnumerocliente" placeholder="Digite número residencial do cliente" value="<?php echo $numero_cliente; ?>">
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
												
												<input type="text" class="form-control" id="txtbairrocliente" placeholder="Digite o bairro do cliente" value="<?php echo $bairro_cliente; ?>">
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
												
												<input type="text" class="form-control" id="txtcidadecliente" placeholder="Digite a cidade do cliente" value="<?php echo $cidade_cliente; ?>">
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
												
												<select class="form-control" id="txtufcliente">
													<option value="" default>Escolha a UF do cliente</option>
													
													<?php
													
														if ($uf_cliente == 'AC') 
														{
															echo "<option value='AC' selected>AC</option>";
														}
														else 
														{
															echo "<option value='AC'>AC</option>";
														}
														
														if ($uf_cliente == 'AL') 
														{
															echo "<option value='AL' selected>AL</option>";
														}
														else 
														{
															echo "<option value='AL'>AL</option>";
														}
														
														if ($uf_cliente == 'AP') 
														{
															echo "<option value='AP' selected>AP</option>";
														}
														else 
														{
															echo "<option value='AP'>AC</option>";
														}
														
														if ($uf_cliente == 'AM') 
														{
															echo "<option value='AM' selected>AM</option>";
														}
														else 
														{
															echo "<option value='AM'>AM</option>";
														}
														
														if ($uf_cliente == 'BA') 
														{
															echo "<option value='BA' selected>BA</option>";
														}
														else 
														{
															echo "<option value='BA'>BA</option>";
														}
														
														if ($uf_cliente == 'CE') 
														{
															echo "<option value='CE' selected>CE</option>";
														}
														else 
														{
															echo "<option value='CE'>CE</option>";
														}
														
														if ($uf_cliente == 'DF') 
														{
															echo "<option value='DF' selected>DF</option>";
														}
														else 
														{
															echo "<option value='DF'>DF</option>";
														}
														
														if ($uf_cliente == 'ES') 
														{
															echo "<option value='ES' selected>ES</option>";
														}
														else 
														{
															echo "<option value='ES'>ES</option>";
														}
														
														if ($uf_cliente == 'Go') 
														{
															echo "<option value='Go' selected>Go</option>";
														}
														else 
														{
															echo "<option value='Go'>Go</option>";
														}
														
														if ($uf_cliente == 'MA') 
														{
															echo "<option value='MA' selected>MA</option>";
														}
														else 
														{
															echo "<option value='MA'>MA</option>";
														}
														
														if ($uf_cliente == 'MT') 
														{
															echo "<option value='MT' selected>MT</option>";
														}
														else 
														{
															echo "<option value='MT'>MT</option>";
														}
														
														if ($uf_cliente == 'MS') 
														{
															echo "<option value='MS' selected>MS</option>";
														}
														else 
														{
															echo "<option value='MS'>MS</option>";
														}
														
														if ($uf_cliente == 'MG') 
														{
															echo "<option value='MG' selected>MG</option>";
														}
														else 
														{
															echo "<option value='MG'>MG</option>";
														}
														
														if ($uf_cliente == 'PA') 
														{
															echo "<option value='PA' selected>PA</option>";
														}
														else 
														{
															echo "<option value='PA'>PA</option>";
														}
														
														if ($uf_cliente == 'PB') 
														{
															echo "<option value='PB' selected>PB</option>";
														}
														else 
														{
															echo "<option value='PB'>PB</option>";
														}
														
														if ($uf_cliente == 'PR') 
														{
															echo "<option value='PR' selected>PR</option>";
														}
														else 
														{
															echo "<option value='PR'>PR</option>";
														}
														
														if ($uf_cliente == 'PE') 
														{
															echo "<option value='PE' selected>PE</option>";
														}
														else 
														{
															echo "<option value='PE'>PE</option>";
														}
														
														if ($uf_cliente == 'PI') 
														{
															echo "<option value='PI' selected>PI</option>";
														}
														else 
														{
															echo "<option value='PI'>PI</option>";
														}
														
														if ($uf_cliente == 'RJ') 
														{
															echo "<option value='RJ' selected>RJ</option>";
														}
														else 
														{
															echo "<option value='RJ'>RJ</option>";
														}
														
														if ($uf_cliente == 'RN') 
														{
															echo "<option value='RN' selected>RN</option>";
														}
														else 
														{
															echo "<option value='RN'>RN</option>";
														}
														
														if ($uf_cliente == 'RS') 
														{
															echo "<option value='RS' selected>RS</option>";
														}
														else 
														{
															echo "<option value='RS'>RS</option>";
														}
														
														if ($uf_cliente == 'RO') 
														{
															echo "<option value='RO' selected>RO</option>";
														}
														else 
														{
															echo "<option value='RO'>RO</option>";
														}
														
														if ($uf_cliente == 'RR') 
														{
															echo "<option value='RR' selected>RR</option>";
														}
														else 
														{
															echo "<option value='RR'>RR</option>";
														}
														
														if ($uf_cliente == 'SC') 
														{
															echo "<option value='SC' selected>SC</option>";
														}
														else 
														{
															echo "<option value='SC'>SC</option>";
														}
														
														if ($uf_cliente == 'SP') 
														{
															echo "<option value='SP' selected>SP</option>";
														}
														else 
														{
															echo "<option value='SP'>SP</option>";
														}
														
														if ($uf_cliente == 'SE') 
														{
															echo "<option value='SE' selected>SE</option>";
														}
														else 
														{
															echo "<option value='SE'>SE</option>";
														}
														
														if ($uf_cliente == 'TO') 
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
												
												<input type="text" class="form-control cep" id="txtcepcliente" placeholder="Digite o cep do cliente" value="<?php echo $cep_cliente; ?>">
											</div>
										</div>
									</div>
								</div>
							</div>
								
							<div class="form-actions right">
								<button type="submit" class="btn blue" id="grava_cliente">
									<i class="fa fa-floppy-o"></i>
									&nbsp;Salvar
								</button>
								
								<button type="button" class="btn red-thunderbird" id="limpa_cliente">
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