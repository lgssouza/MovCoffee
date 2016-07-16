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
	
	$sql 			= "SELECT * FROM `tb_categoria`";
	$rscategorias 	= $mysqli->query($sql);
	$sql 			= "";
	
	$status = "Inclusão";
	
	if(isset($_GET['id_funcionario']))
	{
		$id_funcionario = $_GET['id_funcionario'];
	 	
		if(!empty($id_funcionario) && $id_funcionario != "undefined")
		{
			$status = "Alteração";
			
			$sql 	= "SELECT tb_funcionario.*,
					   date_format(tb_funcionario.func_nasc,'%d/%m/%Y') AS dt_nasc 	
					   FROM `tb_funcionario`
					   WHERE id_func = " . $id_funcionario;
		}
	}
	
	$id_funcionario			= '';
	$fk_categoria			= '';
	$nome_funcionario		= '';
	$usuario_funcionario	= '';
	$senha_funcionario		= '';
	$documento_funcionario	= '';
	$rua_funcionario		= '';
	$bairro_funcionario		= '';
	$numero_funcionario		= '';
	$cidade_funcionario		= '';
	$uf_funcionario			= '';
	$datanasc_funcionario	= '';
	$telefone_funcionario	= '';
	$email_funcionario		= '';
	$cep_funcionario		= '';
	
	if(!empty($sql))
	{
		$rs 	= $mysqli->query($sql);
		
		if($rs)
		{
			$funcionario			= $rs->fetch_object();
			$id_funcionario 		= $funcionario->id_func;
			$fk_categoria			= $funcionario->fk_categoria;
			$nome_funcionario		= $funcionario->func_nome;
			$usuario_funcionario	= $funcionario->func_usuario;
			$senha_funcionario		= $funcionario->func_senha;
			$documento_funcionario	= $funcionario->func_cpf;
			$rua_funcionario		= $funcionario->func_rua;
			$bairro_funcionario		= $funcionario->func_bairro;
			$numero_funcionario		= $funcionario->func_numero;
			$cidade_funcionario		= $funcionario->func_cidade;
			$uf_funcionario			= $funcionario->func_estado;
			$datanasc_funcionario	= $funcionario->dt_nasc;
			$telefone_funcionario	= $funcionario->func_telefone;
			$email_funcionario		= $funcionario->func_email;
			$cep_funcionario		= $funcionario->func_cep;
			
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
					<a href="consulta_funcionario.php">Funcionários</a>
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="cadastro_funcionario.php?id_funcionario=<?php echo $id_funcionario; ?>"><?php echo $status; ?></a>
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
								<?php echo $status; ?> de Funcionário
							</div>
							
							<div class="tools">
								<!-- <a href="javascript:;" class="collapse"></a>-->
							</div>
						</div>
						
						<div class="portlet-body form">
											
							<!-- BEGIN FORM -->							
							<div class="form-body">
								<h3 class="form-section">Informações do Funcionário</h3>
								
								<div class="row">
									<div class="col-md-6">														
										<div class="form-group">
											<label class="control-label">Nome</label>
											
											<div class="input-group">
												<input type="hidden" class="form-control" id="txtidfuncionario" value="<?php echo $id_funcionario; ?>">
												
												<span class="input-group-addon">
													<span class="fa fa-user"></span> 
												</span>
												
												<input type="text" class="form-control" id="txtnomefuncionario" placeholder="Digite o nome do funcionário" value="<?php echo $nome_funcionario; ?>">
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
												
												<input type="text" class="form-control cpf" id="txtdocumentofuncionario" placeholder="Digite o CPF do funcionário" value="<?php echo $documento_funcionario; ?>">
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
												
												<input type="text" size="16" class="form-control" id="txtdtnascfuncionario" placeholder="Digite a data de nascimento do funcionário" value="<?php echo $datanasc_funcionario; ?>">
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
												
												<input type="text" class="form-control" id="txtemailfuncionario"  placeholder="Digite o email do funcionário" value="<?php echo $email_funcionario; ?>">
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
												
												<input type="text" class="form-control tel" id="txttelefonefuncionario" placeholder="Digite o telefone do funcionário" value="<?php echo $telefone_funcionario; ?>">
											</div>
										</div>
									</div>
								</div>
								
								<h3 class="form-section">Informações de Usuário do Funcionário</h3>
								
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">Usuário</label>
																						
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-user"></span> 
												</span>
												
												<input type="text" class="form-control" id="txtusuariofuncionario" placeholder="Digite o nome de usuário do funcionário" value="<?php echo $usuario_funcionario; ?>">
											</div>											
										</div>
									</div>
									
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">Categoria</label>
																						
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-tag"></span> 
												</span>
												
												<select class="form-control select2me" id="txtcategoriafuncionario" tabindex="1">
													<option value="" default>Selecione a categoria de usuário do funcionário</option>
													
													<?php
														
														if($rscategorias)
														{																													
															$categorias = array();
    
															while($categoria = $rscategorias->fetch_object())
															{
																array_push($categorias, $categoria);
															}
															
															$rscategorias->close();
															
															for($i=0;$i<count($categorias);$i++)
															{																
																if($fk_categoria == $categorias[$i]->id_cat)
																{
																	echo '"<option value="'.$categorias[$i]->id_cat.'" selected>'.$categorias[$i]->cat_descricao.'</option>"';
																}
																else
																{
																	echo '"<option value="'.$categorias[$i]->id_cat.'">'.$categorias[$i]->cat_descricao.'</option>"';
																}
															}
														}
														
													?>
												</select>
											</div>											
										</div>
									</div>
								</div>
									
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">Senha</label>
																						
											<div class="input-group">
												<input type="hidden" class="form-control" id="txtsenhaantfuncionario" placeholder="Digite o senha de usuário do funcionário" value="<?php echo $senha_funcionario; ?>">
												
												<span class="input-group-addon">
													<span class="fa fa-lock"></span> 
												</span>
												
												<input type="password" class="form-control" id="txtsenhafuncionario" placeholder="Digite o senha de usuário do funcionário" value="<?php echo $senha_funcionario; ?>">
											</div>
										</div>
									</div>
									
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">Confirma Senha</label>
																						
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-check"></span> 
												</span>
												
												<input type="password" class="form-control" id="txtconfirmafuncionario" placeholder="Digite a senha de confimação de usuário do funcionário" value="<?php echo $senha_funcionario; ?>">
											</div>											
										</div>
									</div>
								</div>
								
								<h3 class="form-section">Endereço do Funcionário</h3>
								
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">Av/Rua</label>
																						
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-road"></span> 
												</span>
												
												<input type="text" class="form-control" id="txtenderecofuncionario" placeholder="Digite logradouro do funcionário" value="<?php echo $rua_funcionario; ?>">
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
												
												<input type="text" class="form-control" id="txtnumerofuncionario" placeholder="Digite número residencial do funcionário" value="<?php echo $numero_funcionario; ?>">
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
												
												<input type="text" class="form-control" id="txtbairrofuncionario" placeholder="Digite o bairro do funcionário" value="<?php echo $bairro_funcionario; ?>">
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
												
												<input type="text" class="form-control" id="txtcidadefuncionario" placeholder="Digite a cidade do funcionário" value="<?php echo $cidade_funcionario; ?>">
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
												
												<select class="form-control" id="txtuffuncionario">
													<option value="" default>Escolha a UF do funcionário</option>
													
													<?php
													
														if ($uf_funcionario == 'AC') 
														{
															echo "<option value='AC' selected>AC</option>";
														}
														else 
														{
															echo "<option value='AC'>AC</option>";
														}
														
														if ($uf_funcionario == 'AL') 
														{
															echo "<option value='AL' selected>AL</option>";
														}
														else 
														{
															echo "<option value='AL'>AL</option>";
														}
														
														if ($uf_funcionario == 'AP') 
														{
															echo "<option value='AP' selected>AP</option>";
														}
														else 
														{
															echo "<option value='AP'>AC</option>";
														}
														
														if ($uf_funcionario == 'AM') 
														{
															echo "<option value='AM' selected>AM</option>";
														}
														else 
														{
															echo "<option value='AM'>AM</option>";
														}
														
														if ($uf_funcionario == 'BA') 
														{
															echo "<option value='BA' selected>BA</option>";
														}
														else 
														{
															echo "<option value='BA'>BA</option>";
														}
														
														if ($uf_funcionario == 'CE') 
														{
															echo "<option value='CE' selected>CE</option>";
														}
														else 
														{
															echo "<option value='CE'>CE</option>";
														}
														
														if ($uf_funcionario == 'DF') 
														{
															echo "<option value='DF' selected>DF</option>";
														}
														else 
														{
															echo "<option value='DF'>DF</option>";
														}
														
														if ($uf_funcionario == 'ES') 
														{
															echo "<option value='ES' selected>ES</option>";
														}
														else 
														{
															echo "<option value='ES'>ES</option>";
														}
														
														if ($uf_funcionario == 'Go') 
														{
															echo "<option value='Go' selected>Go</option>";
														}
														else 
														{
															echo "<option value='Go'>Go</option>";
														}
														
														if ($uf_funcionario == 'MA') 
														{
															echo "<option value='MA' selected>MA</option>";
														}
														else 
														{
															echo "<option value='MA'>MA</option>";
														}
														
														if ($uf_funcionario == 'MT') 
														{
															echo "<option value='MT' selected>MT</option>";
														}
														else 
														{
															echo "<option value='MT'>MT</option>";
														}
														
														if ($uf_funcionario == 'MS') 
														{
															echo "<option value='MS' selected>MS</option>";
														}
														else 
														{
															echo "<option value='MS'>MS</option>";
														}
														
														if ($uf_funcionario == 'MG') 
														{
															echo "<option value='MG' selected>MG</option>";
														}
														else 
														{
															echo "<option value='MG'>MG</option>";
														}
														
														if ($uf_funcionario == 'PA') 
														{
															echo "<option value='PA' selected>PA</option>";
														}
														else 
														{
															echo "<option value='PA'>PA</option>";
														}
														
														if ($uf_funcionario == 'PB') 
														{
															echo "<option value='PB' selected>PB</option>";
														}
														else 
														{
															echo "<option value='PB'>PB</option>";
														}
														
														if ($uf_funcionario == 'PR') 
														{
															echo "<option value='PR' selected>PR</option>";
														}
														else 
														{
															echo "<option value='PR'>PR</option>";
														}
														
														if ($uf_funcionario == 'PE') 
														{
															echo "<option value='PE' selected>PE</option>";
														}
														else 
														{
															echo "<option value='PE'>PE</option>";
														}
														
														if ($uf_funcionario == 'PI') 
														{
															echo "<option value='PI' selected>PI</option>";
														}
														else 
														{
															echo "<option value='PI'>PI</option>";
														}
														
														if ($uf_funcionario == 'RJ') 
														{
															echo "<option value='RJ' selected>RJ</option>";
														}
														else 
														{
															echo "<option value='RJ'>RJ</option>";
														}
														
														if ($uf_funcionario == 'RN') 
														{
															echo "<option value='RN' selected>RN</option>";
														}
														else 
														{
															echo "<option value='RN'>RN</option>";
														}
														
														if ($uf_funcionario == 'RS') 
														{
															echo "<option value='RS' selected>RS</option>";
														}
														else 
														{
															echo "<option value='RS'>RS</option>";
														}
														
														if ($uf_funcionario == 'RO') 
														{
															echo "<option value='RO' selected>RO</option>";
														}
														else 
														{
															echo "<option value='RO'>RO</option>";
														}
														
														if ($uf_funcionario == 'RR') 
														{
															echo "<option value='RR' selected>RR</option>";
														}
														else 
														{
															echo "<option value='RR'>RR</option>";
														}
														
														if ($uf_funcionario == 'SC') 
														{
															echo "<option value='SC' selected>SC</option>";
														}
														else 
														{
															echo "<option value='SC'>SC</option>";
														}
														
														if ($uf_funcionario == 'SP') 
														{
															echo "<option value='SP' selected>SP</option>";
														}
														else 
														{
															echo "<option value='SP'>SP</option>";
														}
														
														if ($uf_funcionario == 'SE') 
														{
															echo "<option value='SE' selected>SE</option>";
														}
														else 
														{
															echo "<option value='SE'>SE</option>";
														}
														
														if ($uf_funcionario == 'TO') 
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
												
												<input type="text" class="form-control cep" id="txtcepfuncionario" placeholder="Digite o cep do funcionário" value="<?php echo $cep_funcionario; ?>">
											</div>
										</div>
									</div>
								</div>
							</div>
								
							<div class="form-actions right">
								<button type="submit" class="btn blue" id="grava_funcionario">
									<i class="fa fa-floppy-o"></i>
									&nbsp;Salvar
								</button>
								
								<button type="button" class="btn red-thunderbird" id="limpa_funcionario">
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