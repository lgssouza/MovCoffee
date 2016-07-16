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
		
	if(isset($_GET['id_conta']))
	{
		$id_conta = $_GET['id_conta'];				
				
		if(!empty($id_conta) && $id_conta != "undefined")		 			
		{
					
			$status = "Alteração";			
			$sql 	= "SELECT * FROM `tb_conta` WHERE id_conta = " . $id_conta;			
			
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
	
	$id_conta			= '';
	$banco 				= '';
	$agencia 			= '';
	$numero 			= '';
	$descricao_conta 	= '';
	$tipo				= '';
	$tipo1				= '';
	$tipo2				= '';
	$tipo3				= '';
	$tipo4				= '';
	$tipo5				= '';
	$mostrar			= 'none';
	
	if(isset($contas))
	{
		if(isset($contas[0]->id_conta))
		{
			$id_conta = $contas[0]->id_conta;
		}
		
		if(isset($contas[0]->conta_banco))
		{
			$banco = $contas[0]->conta_banco;
		}
		
		if(isset($contas[0]->conta_agencia))
		{
			$agencia = $contas[0]->conta_agencia;
		}
		
		if(isset($contas[0]->conta_numero))
		{
			$numero = $contas[0]->conta_numero;
		}
		
		if(isset($contas[0]->conta_descricao))
		{
			$descricao_conta = $contas[0]->conta_descricao;
		}
		
		if(isset($contas[0]->conta_premio) && isset($contas[0]->conta_outros))
		{				
			if($contas[0]->conta_premio==1)
			{
				$tipo1 = '';
				$tipo2 = 'checked';
				$tipo3 = '';
				
			}
			elseif($contas[0]->conta_outros==1)
			{
				$tipo1 = '';
				$tipo2 = '';
				$tipo3 = 'checked';
				
			}
			else 
			{
	
				$tipo1 = 'checked';
				$tipo2 = '';
				$tipo3 = '';
			}
		}
		if(isset($contas[0]->conta_corrente) && isset($contas[0]->conta_aplicacao))
		{				
			if($contas[0]->conta_corrente==1)
			{
				$tipo4 = 'checked';
				$tipo5 = '';
				$mostrar = 'block';
			}
			elseif($contas[0]->conta_aplicacao==1)
			{				
				$tipo4 = '';
				$tipo5 = 'checked';
				$mostrar = 'block';
			}
			else 
			{
				$tipo4 = '';
				$tipo5 = '';
				$mostrar = 'none';
			}
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
					<a href="consulta_contas.php">Contas</a>
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="cadastro_contas.php?id_conta=<?php echo $id_conta; ?>"><?php echo $status; ?></a>
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
								<?php echo $status; ?> de Conta
							</div>
							
							<div class="tools">
								<!-- <a href="javascript:;" class="collapse"></a>-->
							</div>
						</div>
						
						<div class="portlet-body form">
											
							<!-- BEGIN FORM -->
							<div class="form-body">
								<h3 class="form-section">Informações da Conta</h3>
									
								<div class="row">																	
									<div class="col-md-6">
										<div class="form-group">
											<input type="hidden" class="form-control" id="txtidconta" value="<?php echo $id_conta; ?>">
											
											<label class="control-label">Banco</label>	
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-university"></span>													 
												</span>
												<input type="text" class="form-control" id="txtbanco" placeholder="Digite o banco da conta" value="<?php echo $banco; ?>">											
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
												<input type="text" class="form-control" id="txtagencia" placeholder="Digite a agência da conta" value="<?php echo $agencia; ?>">
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
												<input type="text" class="form-control" id="txtnumero" placeholder="Digite o número da conta" value="<?php echo $numero; ?>">
											</div>
										</div>
									</div>	
								</div>
								
								<div class="row">
									<div class="col-md-8">
										<div class="form-group">
											<label class="control-label">Descrição</label>
											<div class="input-group">
												<span class="input-group-addon">
													<span class="fa fa-list"></span>													 
												</span>
												<input type="text" class="form-control" id="txtdescricaoconta" placeholder="Digite a descrição da conta" value="<?php echo $descricao_conta; ?>">
											</div>
										</div>
									</div>
									
									<div class="col-md-2">
										<label>Tipo</label>
										<div class="input-group">
											<div class="icheck-list">
												<label class="">
													<div class="iradio_square-green" style="position: relative;">
														<input <?php echo $tipo1; ?> type="radio" name="tpconta" id="tpconta" value = "1"  class="icheck" data-radio="iradio_square-green" style="position: absolute; opacity: 0;">
														<ins id="tpcontapadrao" class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins>															
													</div> 
													Padrão 
												</label>
												<label class="">
													<div class="iradio_square-green" style="position: relative;">
														<input <?php echo $tipo2; ?> type="radio" name="tpconta" id="tpconta" value = "2" class="icheck" data-radio="iradio_square-green" style="position: absolute; opacity: 0;">
														<ins id="tpcontapremio" class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins>															
													</div> 
													Prêmio 
												</label>
												<label class="">
													<div class="iradio_square-green" style="position: relative;">
														<input <?php echo $tipo3; ?> type="radio" name="tpconta" id="tpconta" value = "3" data-valor = "3" class="icheck" data-radio="iradio_square-green" style="position: absolute; opacity: 0;">
														<ins id="tpcontaoutros" class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins>														
													</div> 
													Outros 
												</label>
											</div>
										</div>
									</div>
									<div id="divconta2" class="col-md-2" style="display: <?php echo $mostrar; ?>">
										<label>Função</label>
										<div class="input-group">
											<div class="icheck-list">
												<label class="">
													<div class="iradio_square-green" style="position: relative;">
														<input <?php echo $tipo4; ?> type="radio" name="tpconta2" id="tpconta2" value = "1"  class="icheck" data-radio="iradio_square-green" style="position: absolute; opacity: 0;">
														<ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins>															
													</div> 
													Corrente 
												</label>
												<label class="">
													<div class="iradio_square-green" style="position: relative;">
														<input <?php echo $tipo5; ?> type="radio" name="tpconta2" id="tpconta2" value = "2" class="icheck" data-radio="iradio_square-green" style="position: absolute; opacity: 0;">
														<ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins>															
													</div> 
													Aplicação 
												</label>												
											</div>
										</div>
									</div>						
								</div>
																								
								<div id="alertcontas">
								</div>								
							</div>
							
							</br>
							
							<div class="form-actions right">
								<button type="submit" class="btn blue" id="grava_contas">
									<i class="fa fa-floppy-o"></i>
									&nbsp;Salvar
								</button>
								
								<button type="button" class="btn red-thunderbird" id="limpa_contas">
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