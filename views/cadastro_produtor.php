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
	
	if(isset($_GET['id_produtor']))
	{
		$id_produtor = $_GET['id_produtor'];
	 	
		if(!empty($id_produtor) && $id_produtor != "undefined")
		{
			$status = "Alteração";
			
			$sql 	= "SELECT tb_produtor.*
					   FROM `tb_produtor`
					   WHERE id_prod = " . $id_produtor;
		}
	}
	
	$id_produtor			= '';
	$nome_produtor			= '';
	$documento_produtor		= '';
	$rua_produtor			= '';
	$bairro_produtor		= '';
	$numero_produtor		= '';
	$cidade_produtor		= '';
	$uf_produtor			= '';
	$telefone_produtor		= '';
	$email_produtor			= '';
	$cep_produtor			= '';
	$prod_banco				= '';
  	$prod_agencia			= '';
  	$prod_conta				= '';
  	$prod_conta_descricao	= '';
	
	if(!empty($sql))
	{
		$rs = $mysqli->query($sql);
		
		if($rs)
		{
			$produtor				= $rs->fetch_object();
			$id_produtor 			= $produtor->id_prod;
			$nome_produtor			= $produtor->prod_nome;
			$documento_produtor		= $produtor->prod_cpf;
			$rua_produtor			= $produtor->prod_rua;
			$bairro_produtor		= $produtor->prod_bairro;
			$numero_produtor		= $produtor->prod_numero;
			$cidade_produtor		= $produtor->prod_cidade;
			$uf_produtor			= $produtor->prod_estado;
			$telefone_produtor		= $produtor->prod_telefone;
			$email_produtor			= $produtor->prod_email;
			$cep_produtor			= $produtor->prod_cep;
			$prod_banco				= $produtor->prod_banco;
		  	$prod_agencia			= $produtor->prod_agencia;
		  	$prod_conta				= $produtor->prod_conta;
		  	$prod_conta_descricao	= $produtor->prod_conta_descricao;
			
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
					Cadastros
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="consulta_produtor.php">Produtores</a>
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="cadastro_produtor.php?id_produtor=<?php echo $id_produtor; ?>"><?php echo $status; ?></a>
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
								<i class="fa fa-users"></i>
								<?php echo $status; ?> de Produtor
							</div>
							
							<div class="tools">
								<?php if($status == "Alteração") echo '<a href="javascript:;" class="collapse"></a>'; ?>
							</div>
						</div>
						
						<div class="portlet-body form">
											
							<!-- BEGIN FORM -->							
							<div class="form-body">
								<h3 class="form-section">Informações do Produtor</h3>
								
								<div class="row">
									<div class="col-md-6">														
										<div class="form-group">
											<label class="control-label">Nome</label>
											
											<div class="input-group">
												<input type="hidden" class="form-control" id="txtidprodutor" value="<?php echo $id_produtor; ?>">
												
												<span class="input-group-addon">
													<span class="fa fa-user"></span> 
												</span>
												
												<input type="text" class="form-control" id="txtnomeprodutor"  placeholder="Digite o nome do produtor" value="<?php echo $nome_produtor; ?>">
											</div>
										</div>
									</div>
									
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">CPF</label>
																						
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-credit-card"></span> 
												</span>
												
												<input type="text" class="form-control cpf" id="txtdocumentoprodutor" placeholder="Digite o CPF do produtor" value="<?php echo $documento_produtor; ?>">
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
												
												<input type="text" class="form-control" id="txtemailprodutor" placeholder="Digite o email do produtor" value="<?php echo $email_produtor; ?>">
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
												
												<input type="text" class="form-control tel" id="txttelefoneprodutor" placeholder="Digite o telefone do produtor" value="<?php echo $telefone_produtor; ?>">
											</div>
										</div>
									</div>
								</div>
								
								<h3 class="form-section">Endereço do Produtor</h3>
								
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">Av/Rua</label>
																						
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-road"></span> 
												</span>
												
												<input type="text" class="form-control" id="txtenderecoprodutor" placeholder="Digite o endereço do produtor" value="<?php echo $rua_produtor; ?>">
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
												
												<input type="text" class="form-control" id="txtnumeroprodutor" placeholder="Digite o número residencial do produtor" value="<?php echo $numero_produtor; ?>">
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
												
												<input type="text" class="form-control" id="txtbairroprodutor" placeholder="Digite o bairro do produtor" value="<?php echo $bairro_produtor; ?>">
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
												
												<input type="text" class="form-control" id="txtcidadeprodutor" placeholder="Digite a cidade do produtor" value="<?php echo $cidade_produtor; ?>">
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
												
												<select class="form-control" id="txtufprodutor">
													<option value="" default>Escolha a UF do produtor</option>
													
													<?php
													
														if ($uf_produtor == 'AC') 
														{
															echo "<option value='AC' selected>AC</option>";
														}
														else 
														{
															echo "<option value='AC'>AC</option>";
														}
														
														if ($uf_produtor == 'AL') 
														{
															echo "<option value='AL' selected>AL</option>";
														}
														else 
														{
															echo "<option value='AL'>AL</option>";
														}
														
														if ($uf_produtor == 'AP') 
														{
															echo "<option value='AP' selected>AP</option>";
														}
														else 
														{
															echo "<option value='AP'>AC</option>";
														}
														
														if ($uf_produtor == 'AM') 
														{
															echo "<option value='AM' selected>AM</option>";
														}
														else 
														{
															echo "<option value='AM'>AM</option>";
														}
														
														if ($uf_produtor == 'BA') 
														{
															echo "<option value='BA' selected>BA</option>";
														}
														else 
														{
															echo "<option value='BA'>BA</option>";
														}
														
														if ($uf_produtor == 'CE') 
														{
															echo "<option value='CE' selected>CE</option>";
														}
														else 
														{
															echo "<option value='CE'>CE</option>";
														}
														
														if ($uf_produtor == 'DF') 
														{
															echo "<option value='DF' selected>DF</option>";
														}
														else 
														{
															echo "<option value='DF'>DF</option>";
														}
														
														if ($uf_produtor == 'ES') 
														{
															echo "<option value='ES' selected>ES</option>";
														}
														else 
														{
															echo "<option value='ES'>ES</option>";
														}
														
														if ($uf_produtor == 'Go') 
														{
															echo "<option value='Go' selected>Go</option>";
														}
														else 
														{
															echo "<option value='Go'>Go</option>";
														}
														
														if ($uf_produtor == 'MA') 
														{
															echo "<option value='MA' selected>MA</option>";
														}
														else 
														{
															echo "<option value='MA'>MA</option>";
														}
														
														if ($uf_produtor == 'MT') 
														{
															echo "<option value='MT' selected>MT</option>";
														}
														else 
														{
															echo "<option value='MT'>MT</option>";
														}
														
														if ($uf_produtor == 'MS') 
														{
															echo "<option value='MS' selected>MS</option>";
														}
														else 
														{
															echo "<option value='MS'>MS</option>";
														}
														
														if ($uf_produtor == 'MG') 
														{
															echo "<option value='MG' selected>MG</option>";
														}
														else 
														{
															echo "<option value='MG'>MG</option>";
														}
														
														if ($uf_produtor == 'PA') 
														{
															echo "<option value='PA' selected>PA</option>";
														}
														else 
														{
															echo "<option value='PA'>PA</option>";
														}
														
														if ($uf_produtor == 'PB') 
														{
															echo "<option value='PB' selected>PB</option>";
														}
														else 
														{
															echo "<option value='PB'>PB</option>";
														}
														
														if ($uf_produtor == 'PR') 
														{
															echo "<option value='PR' selected>PR</option>";
														}
														else 
														{
															echo "<option value='PR'>PR</option>";
														}
														
														if ($uf_produtor == 'PE') 
														{
															echo "<option value='PE' selected>PE</option>";
														}
														else 
														{
															echo "<option value='PE'>PE</option>";
														}
														
														if ($uf_produtor == 'PI') 
														{
															echo "<option value='PI' selected>PI</option>";
														}
														else 
														{
															echo "<option value='PI'>PI</option>";
														}
														
														if ($uf_produtor == 'RJ') 
														{
															echo "<option value='RJ' selected>RJ</option>";
														}
														else 
														{
															echo "<option value='RJ'>RJ</option>";
														}
														
														if ($uf_produtor == 'RN') 
														{
															echo "<option value='RN' selected>RN</option>";
														}
														else 
														{
															echo "<option value='RN'>RN</option>";
														}
														
														if ($uf_produtor == 'RS') 
														{
															echo "<option value='RS' selected>RS</option>";
														}
														else 
														{
															echo "<option value='RS'>RS</option>";
														}
														
														if ($uf_produtor == 'RO') 
														{
															echo "<option value='RO' selected>RO</option>";
														}
														else 
														{
															echo "<option value='RO'>RO</option>";
														}
														
														if ($uf_produtor == 'RR') 
														{
															echo "<option value='RR' selected>RR</option>";
														}
														else 
														{
															echo "<option value='RR'>RR</option>";
														}
														
														if ($uf_produtor == 'SC') 
														{
															echo "<option value='SC' selected>SC</option>";
														}
														else 
														{
															echo "<option value='SC'>SC</option>";
														}
														
														if ($uf_produtor == 'SP') 
														{
															echo "<option value='SP' selected>SP</option>";
														}
														else 
														{
															echo "<option value='SP'>SP</option>";
														}
														
														if ($uf_produtor == 'SE') 
														{
															echo "<option value='SE' selected>SE</option>";
														}
														else 
														{
															echo "<option value='SE'>SE</option>";
														}
														
														if ($uf_produtor == 'TO') 
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
												
												<input type="text" class="form-control cep" id="txtcepprodutor" placeholder="Digite o cep do produtor" value="<?php echo $cep_produtor; ?>">
											</div>
										</div>
									</div>
								</div>
								
								<h3 class="form-section">Conta Bancária do Produtor</h3>
									
								<div class="row">																	
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">Banco</label>	
											
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-university"></span>													 
												</span>
												<input type="text" class="form-control" id="txtprodbanco" placeholder="Digite o banco da conta" value="<?php echo $prod_banco; ?>">											
											</div>
										</div>
									</div>	
									
									<div class="col-md-3">
										<div class="form-group">
											<label class="control-label">Agência</label>
											
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-credit-card-alt"></span>													 
												</span>
												<input type="text" class="form-control" id="txtprodagencia" placeholder="Digite a agência da conta" value="<?php echo $prod_agencia; ?>">
											</div>
										</div>
									</div>			
									
									<div class="col-md-3">
										<div class="form-group">
											<label class="control-label">Conta</label>
											
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-credit-card"></span>													 
												</span>
												<input type="text" class="form-control" id="txtprodconta" placeholder="Digite o número da conta" value="<?php echo $prod_conta; ?>">
											</div>
										</div>
									</div>	
								</div>
								
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label class="control-label">Descrição</label>
											
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-list"></span>													 
												</span>
												<input type="text" class="form-control" id="txtproddescricaoconta" placeholder="Digite a descrição da conta" value="<?php echo $prod_conta_descricao; ?>">
											</div>
										</div>
									</div>									
								</div>
							</div>
								
							<div class="form-actions right">
								<button type="submit" class="btn blue" id="grava_produtor">
									<i class="fa fa-floppy-o"></i>
									&nbsp;Salvar
								</button>
								
								<button type="button" class="btn red-thunderbird" id="limpa_produtor">
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
		
		<?php 
			
			if($status == "Alteração")
			{
				$paginapp 	= 1; 
	
				if(isset($_GET['paginapp']))
				{
					if(!empty($_GET['paginapp']) && $_GET['paginapp'] != "undefined")
					{
						$paginapp = $_GET['paginapp'];
					}
				}
				
				$registrospp 	= 5;
			
				if(isset($_GET['registrospp']))
				{
					if(!empty($_GET['registrospp']) && $_GET['registrospp'] != "undefined")
					{
						$registrospp = $_GET['registrospp'];
					}
				}
				
			    $inicialpp = ($paginapp-1) * $registrospp;
				$rspp      = $mysqli->query('SELECT COUNT(*) 
											 FROM mov_prop_prod a
											 INNER JOIN tb_propriedade b ON a.fk_id_prop = b.id_prop
											 WHERE a.fk_id_prod = ' . $id_produtor);							   
				
				if($rspp)
				{
					$totalregistrospp 	= $rspp->fetch_row();
					$totalregistrospp 	= $totalregistrospp[0];
					$divisaoregistrospp = $totalregistrospp / 5;
					$partefracionadapp 	= $divisaoregistrospp-floor($divisaoregistrospp);
					$parteinteirapp		= floor($divisaoregistrospp);
					
					if($partefracionadapp > 0.0)
					{
						$divisaoregistrospp	= $parteinteirapp * 5 + 5;
					}
					else
				    {
						$divisaoregistrospp	= $parteinteirapp * 5;
					}
					
					$totalpaginaspp		  = $divisaoregistrospp / $registrospp;
					$partefracionadapagpp = $totalpaginaspp-floor($totalpaginaspp);
					$parteinteirapagpp    = floor($totalpaginaspp);
					
					if($partefracionadapagpp > 0.0)
					{
						$totalpaginaspp	= $parteinteirapagpp + 1;
					}
					else
				    {
						$totalpaginaspp	= $parteinteirapagpp;
					}		
				}
				
				if($inicialpp<0)
				{
					$inicialpp 	= 0;
					$paginapp	= 1;
				}
				
				$sql 	= "SELECT a.*, b.prop_nome
						   FROM mov_prop_prod a
						   INNER JOIN tb_propriedade b ON a.fk_id_prop = b.id_prop
						   WHERE a.fk_id_prod = " . $id_produtor .
						  " limit $inicialpp, $registrospp";				
						
				$rs     = $mysqli->query($sql);
	
				if($rs)
				{
					$propriedadesprod = array();
			        
					while($propriedadeprod = $rs->fetch_object())
					{
						array_push($propriedadesprod, $propriedadeprod);
					}
					
					$rs->close();
				}
				
		?>
		
		<div class="row">
			<div class="col-md-12">
				<div class="tab-pane" id="tab_1">
					<div class="portlet box green-jungle">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-fort-awesome"></i>
								Propriedades do Produtor
							</div>
							
							<div class="actions">
								<a class="btn green-jungle" onclick="cadastropropriedadeprodutor()">
									<i class="fa fa-plus"></i> 
									Adicionar 
								</a>
							</div>
						</div>
						
						<div class="portlet-body">
							<div class="table-responsive">
								<table class="table table-striped table-hover" id="tbl_propriedades_produtor">
									<thead>
										<tr>
											<th style="display: none">Código</th>
							          		<th>Nome</th>
							          		<th>Percentual</th>
							          		<th>Área Real</th>
							          		<th>Sacas Real</th>
							          								          		
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
											  		
							      			for($i=0;$i<count($propriedadesprod);$i++)
											{
										        echo	'<tr>
											          		<td style="display: none">'.$propriedadesprod[$i]->id_mov_pp.'</td>
											          		<td>'.$propriedadesprod[$i]->prop_nome.'</td>
															<td>'.number_format($propriedadesprod[$i]->mov_pp_percentual, 2, ',', '.').'</td>
															<td>'.number_format($propriedadesprod[$i]->mov_pp_areareal, 2, ',', '.').'</td>
															<td>'.number_format($propriedadesprod[$i]->mov_pp_sacasreal, 2, ',', '.').'</td>';
											          												          		
															//if(verificapermissaoedicao("Funcionários",$permissoes))
															//{
																echo '<td align="center">
																	  	  <a> 
																		  	  <span class="glyphicon glyphicon-pencil" id="'.$propriedadesprod[$i]->id_mov_pp.'" style="color:green;width:100%;height:100%"></span>
																		  </a>
																	  </td>';
															//}
															
															//if(verificapermissaoexclusao("Funcionários",$permissoes))
															//{
																echo '	<td align="center">
																			<a>
																				<span class="glyphicon glyphicon-trash" id="'.$propriedadesprod[$i]->id_mov_pp.'" style="color:red;width:100%;height:100%"></span>
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
													$paginapp,					 /** Pagina atual **/
													$registrospp,				 /** Quantidade de registros por paginação  **/
													$totalpaginaspp,			 /** Total de paginas **/
													$totalregistrospp,			 /** Total de registros **/
													'cadastroprodutor', 		 /** Nome da função javascrip arquivo 'funcoes.js' **/
													'propriedades'				 /** Descrição que aparecera na paginação **/
												  );
								
							?>
						</div>
					</div>
				</div>
			</div>
		</div>	
				
		<div id="modal-prop-prod" class="modal " tabindex="-1" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">
							<div id="titulomodalpp">
								<i class="fa fa-fort-awesome"></i>&nbsp;
								Propriedade do Produtor
							</div>
						</h4>
					</div>
					
					<!-- BEGIN MENSAGENS-->
					<div class="caixa_mensagens_modal"></div>	
					<!-- END MENSAGENS-->
					
					<div class="modal-body">
						<div class="scroller" style="height:300px" data-always-visible="1" data-rail-visible="1">
							<div class="row">										
								<div class="col-md-12">
									<div class="form-group">	
										<label class="control-label">Propriedade</label>
										
										<div class="input-group">
											<span class="input-group-addon">
												<span class="fa fa-fort-awesome"></span>													 
											</span>
											
											<input type="hidden" class="form-control" id="txtidmovpp" value="">
											<input type="hidden" class="form-control maskarea" id="cmbpropriedadeatual" placeholder="" value="<?php //echo $areatotalcafe; ?>">
											
											<select class="form-control select2me" id="cmbpropriedade">	
												<option selected value="">Selecione á propriedade...</option>	
																				
												<?php 
												
													$sqlprop 	= "SELECT * FROM `tb_propriedade`";	
													$rsprop 	= $mysqli->query($sqlprop);	
											
													if($rsprop)
													{
														$regsprop = array();
										        
														while($regprop = $rsprop->fetch_object())
														{
															array_push($regsprop, $regprop);
															
														}
												
														$rsprop->close();
														
														for($i=0;$i<count($regsprop);$i++)
														{
																echo "<option value=".$regsprop[$i]->id_prop.">".$regsprop[$i]->prop_nome."</option>";
														}
													}
																								
												?>
											</select>
											
											<span class="input-group-addon" style="background-color: #26C281">
												<a type="button" onclick="cadastropropriedade()" style="color: #ffffff">
													<i class="fa fa-plus"></i>		
												</a>														 
											</span>
										</div>
									</div>				
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label">Percentual</label>
										
										<div class="input-group">
											<span class="input-group-addon">
												<span class="fa fa-percent"></span> 
											</span>
											
											<input type="hidden" class="form-control maskarea" id="txtpercentualppatual" placeholder="Digite o percentual" value="<?php //echo $areatotal; ?>">
											<input type="text" class="form-control maskarea" id="txtpercentualpp" placeholder="Digite o percentual" value="<?php //echo $areatotal; ?>">
										</div>
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label ">Área Real</label>
										
										<div class="input-group">
											<span class="input-group-addon">
												<span class="fa fa-area-chart"></span> 
											</span>
											
											<input type="hidden" class="form-control maskarea" id="txtareacafep" placeholder="" value="<?php //echo $areatotalcafe; ?>">
											<input type="text" class="form-control maskarea" id="txtarearealpp" readonly placeholder="Tamanho da área real" value="<?php //echo $areatotalcafe; ?>">
										</div>
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label">Sacas Real</label>
																					
										<div class="input-group">
											<span class="input-group-addon">
												<span class="fa fa-cubes"></span> 
											</span>
											
											<input type="hidden" class="form-control maskarea" id="txtprevisaosacasp" placeholder="" value="<?php //echo $areatotalcafe; ?>">
											<input type="text" class="form-control maskarea" id="txtsacasrealpp" readonly placeholder="Quantidade de sacas real" value="<?php //echo $previsaosacas; ?>">
										</div>											
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="modal-footer">
						<button type="submit" class="btn blue" id="grava_propriedade_produtor">
							<i class="fa fa-floppy-o"></i>
							&nbsp;Salvar
						</button>
						
						<button type="button" class="btn red-thunderbird" id="limpa_propriedade_produtor">
							<i class="fa fa-remove"></i>
							&nbsp;Limpar
						</button>
					</div>
				</div>
			</div>
		</div>
		
		<?php 
			
			}
				
		?>
		
		<!-- END PAGE CONTENT-->
		
	</div>
</div>
<!-- END CONTENT -->

<?php

	$mysqli->close();
	
?>